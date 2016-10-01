<?php
namespace service;

use common,
    entity;
use ZPHP\Protocol\Request;
use ZPHP\Conn\Factory as ZConn;
use ZPHP\Core\Config as ZConfig;

class Chat extends Base
{
    public function __construct()
    {
        $this->dao = common\loadClass::getDao('User');
    }
    private function getConn()
    {
        return ZConn::getInstance('Redis', ZConfig::get('connection'));
    }

    public function check($uid, $token)
    {
        $fd = Request::getFd();
        if(common\Utils::checkToken($uid, $token)) {
            $user_info =  common\loadClass::getService('User')->getUserInfo($uid);
            if(!empty($user_info)) {  //登录成功

                $this->user_id = $uid;
                $user_info['token'] = $token;
                $this->getConn()->add($user_info, $fd);
                //离线聊天消息推送
                $off_key = "fd_{$uid}_off";
                if($this->getConn()->exists($off_key)) {
                    $off_data = $this->getConn()->getOffData($off_key);
                    $off_data = json_decode($off_data,true);
                    if(!empty($off_data)) {
                        foreach($off_data as $item) {
                            Request::getSocket()->push($fd,json_encode($item));
                        }
                        $this->getConn()->deleteOffData($off_key);
                    }
                }
                //请求加好友离线消息推送(加群推送)
                $news_key = "fd_{$uid}_news";
                if($this->getConn()->exists($news_key)) {
                    $data = $this->getConn()->getNews($uid);
                    $data = json_decode($data,true);
                    if(!empty($data)) {
                        foreach ($data as $item) {
                            Request::getSocket()->push($fd, json_encode($item));
                        }
                        $this->getConn()->deleteNews($news_key);
                    }
                }
                return true;
            }
        }
    }

    /**
     * 下线
     */
    public function offLine($uid)
    {
        $this->getConn()->delete($uid);
    }

    /**
     * 退出
     */
    public function loginOut($uid,$msg)
    {
        $fd = Request::getFd();
        $this->getConn()->delete($uid);
        $data = array(
            'content' => $msg
        );
        Request::getSocket()->push($fd,json_encode($data));
    }

    /**
     * 关闭服务服
     * @param $fd
     */
    public function close($fd)
    {
        Request::getSocket()->close($fd);
    }

    /**
     * websocket 单聊服务器返回数据
     * @param $my_id
     * @param $user_id
     * @param $msg
     * @return mixed
     */
    public function msg($my_id,$user_id, $msg)
    {
        $toInfo = $this->getConn()->get($user_id);
        $myInfo = $this->getConn()->get($my_id);
        //如果离线
        if(false==$toInfo) {
            $data   = array(
                'id' =>  $myInfo['id'],
                'username'=> $myInfo['username'],
                'avatar'  => $myInfo['avatar'],
                'type'    => 'friend'
            );
            $data = array_merge($data,$msg);
            $this->getConn()->offLineData($data,$user_id);
            return false;
        }
        $data   = array(
            'id' =>  $myInfo['id'],
            'username'=> $myInfo['username'],
            'avatar'  => $myInfo['avatar'],
            'type'    => 'friend',
        );
        $data = array_merge($data,$msg);
        return Request::getSocket()->push($toInfo['fd'],json_encode($data));
    }
    /**
     * websocket 群聊
     * @param $my_id
     * @param $user_id
     * @param $msg
     * @return mixed
     */
    public function msgAll($my_id,$swarm_id, $msg)
    {
        $service  = common\loadClass::getService('User');
        $user_ids = $service->getUserBySwarm($my_id,$swarm_id);
        $myInfo = $this->getConn()->get($my_id);
        foreach($user_ids as $uid) {
            $toInfo = $this->getConn()->get($uid['user_id']);
            $on_key = "fd_{$uid['user_id']}_chat";
            if($this->getConn()->exists($on_key)) {
                $data   = array(
                    'id' =>  $swarm_id,
                    'username'=> $myInfo['username'],
                    'avatar'  => $myInfo['avatar'],
                    'type'    => 'group',

                );
                $data = array_merge($data,$msg);
                Request::getSocket()->push($toInfo['fd'],json_encode($data));
            }else{
                $data   = array(
                    'id' =>  $swarm_id,
                    'username'=> $myInfo['username'],
                    'avatar'  => $myInfo['avatar'],
                    'type'    => 'group',

                );
                $data = array($data,$msg);
                $this->getConn()->offLineData($data,$uid['user_id']);
            }

        }

    }
    //修改个性签名
    public function saveSign($user_id,$sign) {
        $uid = common\loadClass::getService('User')->saveSign($user_id,$sign);
        if(false!==$uid) {
            $data = array(
                'code' => 0,
                'msg'  => '修改成功!'
            );
        }else{
            $data = array(
                'code' => 1,
                'msg' => '失败!'
            );
        }
        exit(json_encode($data));
    }

    //判断用户是否在线
    public function userIsOn($user_id) {
        $key = "fd_{$user_id}_chat";
        $flag = $this->getConn()->exists($key);
        return $flag;
    }

    /**
     * 发送申请加好友信息验证
     * @param $user_id
     * @param $to_user_id
     * @param $group_id
     * @param $check_msg
     * @return mixed
     */
    public function addFriendCheckMsg($user_id,$to_user_id,$group_id,$check_msg,$msg) {
        $data =  array('user_id'=>$user_id,'group_id'=>$group_id,'check_msg'=>$check_msg,'msg_type'=>\common\chat::ADD_FRIEND_MSG_TYPE);
        $data = array_merge($data,$msg);

        if($this->getConn()->exists("fd_{$to_user_id}_chat")) {
            $toInfo = $this->getConn()->get($to_user_id);
            $fd = $toInfo['fd'];
            return Request::getSocket()->push($fd,json_encode($data));
        }else{
            $this->getConn()->setNews($data,$to_user_id);
        }

    }

    /**
     * 同意好友加友请求
     * @param $user_id
     * @param $to_user_id
     * @param $my_group_id
     * @param $to_group_id
     * @param $msg
     * @return mixed
     */
    public function agreeFriend($user_id,$to_user_id,$my_group_id,$to_group_id,$msg) {
        $Info = $this->getConn()->get($user_id);
        $to_user_info = $this->getConn()->get($to_user_id);
        $data   = array(
            'avatar' => $Info['avatar'],
            'username' => $Info['username'],
            'group_id' => $to_group_id,
            'user_id'      => $Info['id'],
            'sign'    => $Info['sign']
        );

        if($this->dao->addFriend($user_id,$to_user_id,$my_group_id,$to_group_id)) {
            $data = array_merge($data,$msg);
        }else{
            $msg = array('chat_stataus'=>-1);
            $data = array_merge($data,$msg);
        }
        return Request::getSocket()->push($to_user_info['fd'],json_encode($data));
    }

    /**
     * 同意好友加群请求
     * @param $to_user_id
     * @param $swarm_id
     * @param $msg
     * @return mixed
     */
    public function agreeFriendForSwarm($to_user_id,$swarm_id,$msg) {
        $to_user_info = $this->getConn()->get($to_user_id);
        $swarm_info = $this->dao->getSwarmInfo($swarm_id);
        $data   = array(
            'type' => 'group',
            'avatar' => $swarm_info['avatar'],
            'swarm_name' => $swarm_info['swarm_name'],
            'swarm_id'      => $swarm_info['swarm_id'],
            'msg'     => "管理员已同意您加入:{$swarm_info['swarm_name']}群",
            'user_id' => $swarm_info['user_id']
        );

        if($this->dao->addFriendForSwarm($swarm_id,$to_user_id)) {
            $data = array_merge($data,$msg);
        }else{
            $msg = array('chat_stataus'=>-1);
            $data = array_merge($data,$msg);
        }
        return Request::getSocket()->push($to_user_info['fd'],json_encode($data));
    }
    //服务器断开连接
    public function onClose() {

        echo "连接已关闭...\n";
        $this->offLine($this->user_id);

    }

} 