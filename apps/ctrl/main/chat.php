<?php
namespace ctrl\main;
use common,
    ZPHP\Core\Config as ZConfig;

class chat extends  Base
{

    /**
     * 用户登陆上线初始化数据
     */
    public function userInit() {
        $user_id  = $this->getString($this->params, 'user_id', '');
        $service  = common\loadClass::getService('User');
        $userData = $service->userInit($user_id);
        exit($userData);
    }

    /**
     * 获取群成员列表
     * @throws \Exception
     */
    public function getSwramUserList() {
        $swarm_id = $this->getString($this->params,'id','');
        $service = common\loadClass::getService('User');
        $swarmUserList = $service->getSwramUserList($swarm_id);
        exit($swarmUserList);
    }
    /**
     * 发送信息(个人)
     * @throws \Exception
     */
    public function sendMsg() {
        $user_id  = $this->getString($this->params, 'user_id', '');
        $my_id    = $this->getString($this->params, 'my_id', '');
        $msg      = $this->getString($this->params, 'msg', '');
        $data     = array(
            'chat_status' => common\chat::SINGLE_CHAT,
            'msg'         => $msg
        );
        common\loadClass::getService('Chat')->msg($my_id,$user_id, $data);
    }

    /**
     * 群聊
     * @throws \Exception
     */
    public function sendMsgSwarm() {
        $swarm_id = $this->getString($this->params, 'swarm_id', '');
        $my_id    = $this->getString($this->params, 'my_id', '');
        $msg      = $this->getString($this->params, 'msg', '');
        $data     = array(
            'chat_status' => common\chat::GROUP_CHAT,
            'msg'         => $msg
        );
        common\loadClass::getService('Chat')->msgAll($my_id,$swarm_id, $data);
    }

    /**
     * 登陆后默认触发,生成redis数据(包括离线信息)
     * @return mixed
     * @throws \Exception
     */
    public function check()
    {
        $uid = $this->getInteger($this->params, 'uid');
        $token = $this->getString($this->params, 'token');
        return common\loadClass::getService('Chat')->check($uid, $token);
    }

    /**
     * 聊天时上传图片
     */
    public function uploadImg() {
        $info = $this->upload("./uploads/");
        $data = array(
            "code" => 0,
            "msg"  => "",
            "data" => array(
                "src" => $info['url']
            )
        );
        exit(json_encode($data));
    }

    /**
     * 聊天时上传附件
     */
    public function uploadFile() {
        $info = $this->upload("./uploads/");
        $data = array(
            "code" => 0,
            "msg"  => "",
            "data" => array(
                "src"  => $info['url'],
                "name" => $info['name']
            )
        );
        exit(json_encode($data));
    }


    public function online()
    {
        common\loadClass::getService('Chat')->getOnlineList();
    }
    //离线退出
    public function loginOut()
    {
        $uid = $this->getInteger($this->params, 'uid');
        $data     = array(
            'chat_status' => common\chat::LOGIN_OUT
        );
        common\loadClass::getService('Chat')->loginOut($uid,$data);
    }

    //修改个性签名
    public function saveSign() {
        $user_id   = $this->getString($this->params, 'user_id', '');
        $sign      = $this->getString($this->params,'sign','');
        common\loadClass::getService('Chat')->saveSign($user_id,$sign);
    }

    //添加好友
    public function addFriend() {
        $data = array(
            'code' => 1,
            'msg' => '成功!'
        );
        exit(json_encode($data));
    }

    //查看分组下的所有好友
    public function getFriendsByGroupId() {
        $group_id   = $this->getString($this->params, 'group_id', '');
        $user_id      = $this->getString($this->params, 'user_id', '');
        $user_list  = common\loadClass::getService('User')->getFriendsByGroupId($group_id);
        return array(
            'static_url'=> ZConfig::getField('project', 'static_url'),
            'user_list'=> $user_list,
            'user_id'  => $user_id
        );
    }
    //添加分组
    public function addGroup() {
        $user_id = $this->getString($this->params,'user_id','');
        $group_name = $this->getString($this->params,'group_name','');
        $data  = array(
            'chat_status' => common\chat::ADD_GROUP
        );
        $data = common\loadClass::getService('User')->addGroup($user_id,$group_name,$data);
        exit(json_encode($data));
    }
    //删除分组
    public function deleteGroup() {
        $group_id   = $this->getString($this->params, 'group_id', '');
        $data     = array(
            'chat_status' => common\chat::REMOVE_GROUP,
            'group_id' => $group_id
        );
        $data = common\loadClass::getService('User')->deleteGroup($group_id,$data);
        exit(json_encode($data));
    }

    /**
     * 删除好友
     * @throws \Exception
     */
    public function delFriend() {
        $user_id  = $this->getString($this->params, 'user_id', '');
        $my_id    = $this->getString($this->params, 'my_id', '');
        $data     = array(
            'chat_status' => common\chat::REMOVE_FRIEND,
            'user_id'     => $user_id
        );
        $data = common\loadClass::getService('User')->delFriend($my_id,$user_id,$data);
        exit(json_encode($data));
    }

    /**
     * 添加好友
     * @return mixed
     * @throws \Exception
     */
    public function addFriendCheckMsg() {
        $user_id    = $this->getString($this->params,'user_id','');
        $to_user_id = $this->getString($this->params,'to_user_id');
        $group_id   = $this->getString($this->params,'group_id');
        $check_msg  = $this->getString($this->params,'check_msg');
        $data = array(
            'chat_status' => common\chat::SEND_CHECK_MSG
        );
        return common\loadClass::getService('Chat')->addFriendCheckMsg($user_id,$to_user_id,$group_id,$check_msg,$data);
    }

    /**
     * 同意好友请求
     * @return mixed
     * @throws \Exception
     */
    public function agreeFriend() {
        $my_user_id    = $this->getString($this->params,'my_user_id','');
        $to_user_id = $this->getString($this->params,'to_user_id');
        $my_group_id   = $this->getString($this->params,'my_group_id');
        $to_group_id   = $this->getString($this->params,'to_group_id');
        $data = array(
            'chat_status' => common\chat::JOIN_FRIEND
        );
        return common\loadClass::getService('Chat')->agreeFriend($my_user_id,$to_user_id,$my_group_id,$to_group_id,$data);
    }
    /**
     * 同意好友入群请求
     * @return mixed
     * @throws \Exception
     */
    public function agreeFriendForSwarm() {
        $to_user_id = $this->getString($this->params,'to_user_id');
        $to_swarm_id   = $this->getString($this->params,'to_swarm_id');
        $data = array(
            'chat_status' => common\chat::JOIN_SWARM_SUCCESS
        );
        return common\loadClass::getService('Chat')->agreeFriendForSwarm($to_user_id,$to_swarm_id,$data);
    }
    //查看群下所有用户(不包含我自己)
    public function getFriendsBySwarmId() {
        $swarm_id   = $this->getString($this->params, 'swarm_id', '');
        $user_id      = $this->getString($this->params, 'user_id', '');
        $user_list  = common\loadClass::getService('User')->getFriendsBySwarmId($swarm_id);
        return array(
            'static_url'=> ZConfig::getField('project', 'static_url'),
            'user_list'=> $user_list,
            'user_id'  => $user_id,
            'swarm_id' => $swarm_id
        );
    }
    //创建群
    public function addSwarm() {
        $user_id = $this->getString($this->params,'user_id','');
        $swarm_name = $this->getString($this->params,'swarm_name','');
        $avatar = $this->getString($this->params,'avatar','');
        $data  = array(
            'chat_status' => common\chat::CREATE_SWARM
        );
        $data = common\loadClass::getService('User')->addSwarm($user_id,$swarm_name,$avatar,$data);
        exit(json_encode($data));
    }

    //删除群
    public function deleteSwarm() {
        $swarm_id   = $this->getString($this->params, 'swarm_id', '');
        $data     = array(
            'chat_status' => common\chat::DELETE_SWARM,
            'swarm_id' => $swarm_id
        );
        $data = common\loadClass::getService('User')->deleteSwarm($swarm_id,$data);
        exit(json_encode($data));
    }

    /**
     * 从群中移除成员
     * @throws \Exception
     */
    public function delFriendForSwarm() {
        $user_id  = $this->getString($this->params, 'user_id', '');
        $my_id  = $this->getString($this->params, 'my_id', '');
        $swarm_id    = $this->getString($this->params, 'swarm_id', '');
        $data     = array(
            'chat_status' => common\chat::REMOVE_FRIEND_FOR_SWARM,
            'msg_type'    => common\chat::REMOVED_SWARM_MSG_TYPE,
            'user_id'     => $user_id,
            'swarm_id'    => $swarm_id
        );
        $data = common\loadClass::getService('User')->delFriendForSwarm($swarm_id,$user_id,$my_id,$data);
        exit(json_encode($data));
    }

    /**
     * 加入群
     */
    public function joinSwarm() {
        $user_id  = $this->getString($this->params,'user_id','');
        $swarm_id = $this->getString($this->params,'swarm_id','');
        $created_user_id = $this->getString($this->params,'created_user_id');
        $data     = array(
            'chat_status' => common\chat::JOIN_SWARM,
            'msg_type'    => common\chat::JOIN_SWARM_MSG_TYPE,
            'user_id'     => $user_id,
            'created_user_id' => $created_user_id,
            'swarm_id'    => $swarm_id
        );
        common\loadClass::getService('User')->joinSwarm($swarm_id,$user_id,$created_user_id,$data);
    }

    public function exitSwarm(){
        $user_id  = $this->getString($this->params,'user_id','');
        $swarm_id = $this->getString($this->params,'swarm_id','');
        $data     = array(
            'chat_status' => common\chat::QUIT_SWARM,
            'swarm_id'    => $swarm_id,
            'msg'         => "退出成功!"
        );
        common\loadClass::getService('User')->exitSwarm($swarm_id,$user_id,$data);
    }

    public function getUserHistoryMsg() {
        $id = $this->getString($this->params, 'id', '');
        $type = $this->getString($this->params, 'type', '');
        return array(
            'static_url'=>ZConfig::getField('project', 'static_url'),
            'id'        => $id,
            'type'      => $type
        );
    }

    public function changeOnline() {
        $user_id = $this->getString($this->params,'user_id','');
        $status  = $this->getString($this->params,'status','');
        common\loadClass::getService('User')->changeOnline($user_id,$status);
    }

}

