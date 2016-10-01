<?php
namespace service;

use common,
    entity;
use ZPHP\Protocol\Request;
use ZPHP\Conn\Factory as ZConn;
use ZPHP\Core\Config as ZConfig;

class User extends Base
{

    public function __construct()
    {
        $this->dao = common\loadClass::getDao('User');
    }

    private function getConn()
    {
        return ZConn::getInstance('Redis', ZConfig::get('connection'));
    }

    public function checkUser($username, $password)
    {
        $password = md5($password);
        $userInfo = $this->fetchAll(array(
                "username"=>"'{$username}'",
                "password"=>"'{$password}'",
            )
        );
        if(empty($userInfo)) {
            return false;
        } else {
            return $userInfo[0];
        }
    }

    public function addUser($username, $password,$remark_name,$avatar)
    {
        if($this->checkUser($username, $password)) {
            return false;
        }
        $entity = new entity\User();
        $entity->username = $username;
        $entity->password = md5($password);
        $entity->remark_name = $remark_name;
        $entity->avatar = $avatar;
        return $this->dao->addGroup($this->add($entity),common\chat::DEFAULT_GROUP_NAME);
    }

    /**
     * 用户登陆成功后数据初始化
     * @param string $user_id
     * @return mixed
     */
    public function userInit($user_id='') {
        return $this->dao->userInit($user_id);
    }

    /**
     * 获取指定群成员列表
     * @param string $swarm_id
     * @return mixed
     */
    public function getSwramUserList($swarm_id='') {
        return $this->dao->getSwramUserList($swarm_id);
    }

    /**
     * 获取用户信息
     * @param $user_id
     * @return mixed
     */
    public function getUserInfo($user_id) {
        return $this->dao->getUserInfo($user_id);
    }

    public function getUserBySwarm($my_id,$swarm_id) {
        return $this->dao->getUserBySwarm($my_id,$swarm_id);
    }

    //修改个性签名
    public function saveSign($user_id,$sign) {
        return $this->dao->saveSign($user_id,$sign);
    }

    //获取用户的所有分组
    public function getGroupList($user_id) {
        return $this->dao->getGroupList($user_id);
    }

    //获取用户的所有创建的群(或不是用户所创建的群根据$self)
    public function getSwarmList($user_id,$self=true) {
        $swarm_list = $this->dao->getSwarmList($user_id,$self);
        $userToSwarmIds = $this->dao->userToSwarm($user_id);
        if($self==false) {
            foreach($swarm_list as &$item) {
                if(in_array($item['swarm_id'],$userToSwarmIds)) {
                    $item['is_join'] = true;
                }else{
                    $item['is_join'] = false;
                }
            }
        }
        return $swarm_list;
    }


    //获取指定分组下的所有用户
    public function getFriendsByGroupId($group_id){
        $user_list = $this->dao->getFriendsByGroupId($group_id);
        foreach($user_list as &$vo){
            $flag = common\loadClass::getService('Chat')->userIsOn($vo['to_user_id']);
            if($flag) {
                $vo['on_line'] = 1;
            }else{
                $vo['on_line'] = 0;
            }

        }
        return $user_list;
    }
    //添加分组
    public function addGroup($user_id,$group_name,$msg) {
        $fd   = Request::getFd();
        $gid = $this->dao->addGroup($user_id,$group_name);
        if($gid){
            $msg['group_id'] = $gid;
            $msg['group_name'] = $group_name;
            $msg['friend_num'] = 0;
        }else{
            $msg['chat_status'] = -1;
        }
        return Request::getSocket()->push($fd,json_encode($msg));

    }

    //删除分组
    public function deleteGroup($group_id,$msg) {
        $fd = Request::getFd();
        $this->dao->deleteGroup($group_id);
        return Request::getSocket()->push($fd,json_encode($msg));
    }

    /**
     * 删除我的好友
     * @param $my_id
     * @param $user_id
     * @param $msg
     */
    public function delFriend($my_id,$user_id,$msg) {
        $fd = Request::getFd();
        $this->dao->delFriend($my_id,$user_id);

        return Request::getSocket()->push($fd,json_encode($msg));
    }

    /**
     * 获取所有用户列表除当前用户
     * @param $user_id
     * @return mixed
     */
    public function userList($user_id){
        $user_list = $this->dao->getUserList($user_id);
        foreach($user_list as &$item) {
            $flag = common\loadClass::getService('Chat')->userIsOn($item['id']);
            if($flag) {
                $item['on_line'] = 1;
            }else{
                $item['on_line'] = 0;
            }
        }
        return $user_list;
    }
    //获取指定群下的所有用户
    public function getFriendsBySwarmId($swarm_id){
        $user_list = $this->dao->getFriendsBySwarmId($swarm_id);
        foreach($user_list as &$vo){
            $flag = common\loadClass::getService('Chat')->userIsOn($vo['user_id']);
            if($flag) {
                $vo['on_line'] = 1;
            }else{
                $vo['on_line'] = 0;
            }

        }
        return $user_list;
    }
    //创建群
    public function addSwarm($user_id,$swarm_name,$avatar,$msg) {
        $fd   = Request::getFd();
        $sid = $this->dao->addSwarm($user_id,$swarm_name,$avatar);
        if($sid){
            $msg['type'] = 'group';
            $msg['swarm_name'] = $swarm_name;
            $msg['swarm_id'] = $sid;
            $msg['avatar'] = $avatar;
        }else{
            $msg['chat_status'] = -1;
        }
        return Request::getSocket()->push($fd,json_encode($msg));

    }

    //删除群
    public function deleteSwarm($swarm_id,$msg) {
        $fd = Request::getFd();
        $flag = $this->dao->deleteSwarm($swarm_id);
        if(false==$flag) {
            $msg['chat_status'] = -1;
        }
        return Request::getSocket()->push($fd,json_encode($msg));

    }
    //从群中移除成员
    public function delFriendForSwarm($swarm_id,$user_id,$my_id,$msg) {
        $flag = $this->dao->delFriendForSwarm($swarm_id,$user_id);
        $swarmInfo = $this->dao->getSwarmInfo($swarm_id);
        if(false==$flag) {
            $msg['chat_status'] = -1;
        }
        $toInfo = $this->getConn()->get($user_id);
        $msg['msg'] = "您已被群管理移除:".$swarmInfo['swarm_name']."群";
        //离线
        if(empty($toInfo)) {
            $this->getConn()->setNews($msg,$user_id);
        }else{
            return Request::getSocket()->push($toInfo['fd'],json_encode($msg));
        }
    }

    //加入群
    public function joinSwarm($swarm_id,$user_id,$to_user_id,$msg) {
        $userInfo = $this->getConn()->get($user_id);
        $toInfo = $this->getConn()->get($to_user_id);
        $swarm_info = $this->dao->getSwarmInfo($swarm_id);

        if(empty($swarm_info)) {
            $msg['chat_status'] = -1;
        }
        $msg = array_merge($msg,$swarm_info);
        $msg['user_id'] = $user_id;
        $msg['check_msg'] = $userInfo['remark_name']."请求加入群!";
        //离线
        if(empty($toInfo)) {
            $this->getConn()->setNews($msg,$to_user_id);
        }else{
            return Request::getSocket()->push($toInfo['fd'],json_encode($msg));
        }

    }

    //退群
    public function exitSwarm($swarm_id,$user_id,$msg) {
        $fd = Request::getFd();
        $this->dao->exitSwarm($swarm_id,$user_id);
        $swarm_info = $this->dao->getSwarmInfo($swarm_id);
        $data = array(
           "type" => 'group',
            "swarm_id" => $swarm_id,
            "user_id"  => $swarm_info['user_id']
        );
        $data = array_merge($data,$msg);
        return Request::getSocket()->push($fd,json_encode($data));
    }

    //切换在线状态
    public function changeOnline($user_id,$status){
        $user_ids = $this->dao->hasUserOfAllUserIds($user_id);

        $new_user_ids = array();
        foreach($user_ids as $item){
            $new_user_ids[] = $item['user_id'];
        }
        foreach($new_user_ids as $vo) {
            $flag = common\loadClass::getService('Chat')->userIsOn($vo);
            $toInfo = $this->getConn()->get($vo);
            if($flag) {
                $data = array(
                    'user_id' => $user_id,
                    'chat_status'  => common\chat::CHANGE_LINE,
                    'line'    => $status
                );
                Request::getSocket()->push($toInfo['fd'],json_encode($data));
            }else{
                $data = array(
                    'user_id' => $user_id,
                    'chat_status'  => common\chat::CHANGE_LINE,
                    'line'    => $status
                );
                $this->getConn()->setNews($data,$vo);
            }
        }

    }

} 