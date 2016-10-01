<?php

namespace dao;
use ZPHP\Db\Pdo as ZPdo;

class User extends Base {

    private $pdo;
    public function __construct()
    {
        parent::__construct('entity\\User');
    }

    /**
     * 用户初始化
     * @param int $user_id
     * @return string
     */
    public function userInit($user_id=0) {
        $this->useDb();
        $msql = "SELECT * FROM chat.ct_user WHERE id=$user_id";
        $myInfo = $this->execute($msql);

        $group_sql  = "SELECT * FROM chat.ct_user_group WHERE user_id=$user_id";
        $group_list = $this->execute($group_sql);

        $friends_sql  = "SELECT * FROM chat.ct_user_tree A LEFT JOIN chat.ct_user B ON A.to_user_id=B.id WHERE A.user_id=$user_id";
        $friends_list = $this->execute($friends_sql);

        $friends_data = array();
        foreach($group_list as $key=>$item) {
            $friends_data[$key]['groupname'] = $item['group_name'];
            $friends_data[$key]['id'] = $item['group_id'];
            $friends_data[$key]['online'] = 2;
            foreach($friends_list as $vo) {
                if($vo['group_id']==$item['group_id']) {
                    $friends_data[$key]['list'][] = array(
                        'username' => $vo['username'],
                        'id'       => $vo['id'],
                        'avatar'   => $vo['avatar'],
                        'sign'     => $vo['sign']
                    );
                }
            }
        }
        try{
            $swaram_sql = "(SELECT A.swarm_id AS id,A.swarm_name AS groupname,A.avatar FROM chat.ct_swarm AS A WHERE  A.user_id=$user_id) UNION ALL (SELECT B.swarm_id AS id,B.swarm_name AS groupname,B.avatar FROM chat.ct_swarm_user A LEFT JOIN chat.ct_swarm B ON A.swarm_id=B.swarm_id WHERE A.user_id=$user_id)";
            $group_data = $this->execute($swaram_sql);
        }catch(\Exception $e) {
            exit($e->getMessage());
        }

        $data = array(
            'code' => 0,
            'msg'  => '',
            'data' => array(
                'mine'  => array(//我的个人信息
                    'username' => $myInfo[0]['remark_name'],
                    'id'       => $myInfo[0]['id'],
                    'status'   => 'online',
                    'sign'     => $myInfo[0]['sign'],
                    'avatar'   => $myInfo[0]['avatar']
                ),
                'friend' => $friends_data, //朋友信息
                'group'  => $group_data
            )
        );
        return json_encode($data);
    }

    /**
     * 获取指定群列表成员
     * @param int $swarm_id
     * @return string
     */
    public function getSwramUserList($swarm_id=0) {
        $this->useDb();
        $swarm_user_list_sql = "(SELECT B.id,B.username,B.avatar,B.sign FROM chat.ct_swarm_user AS A INNER JOIN chat.ct_user AS B ON A.user_id=B.id WHERE A.swarm_id=$swarm_id) UNION ALL (SELECT id,username,avatar,sign FROM chat.ct_user WHERE id IN (SELECT user_id FROM chat.ct_swarm WHERE swarm_id=$swarm_id))";
        $swarm_user_list     = $this->execute($swarm_user_list_sql);

        $msql = "SELECT id,username,sign,avatar FROM chat.ct_user WHERE id IN (SELECT user_id FROM chat.ct_swarm WHERE swarm_id=$swarm_id)";
        $userInfo = $this->execute($msql);

        $data = array(
            "code" => 0,
            "msg"  => '成功',
            "data" => array(
                "owner" => $userInfo[0],
                "list"  => $swarm_user_list
            )
        );
        return json_encode($data);
    }
    /**
     * 获取用户信息
     * @param $user_id
     * @return mixed
     */
    public function getUserInfo($user_id) {
        try{
            $this->useDb();
            $msql = "SELECT * FROM chat.ct_user WHERE id=$user_id";
            $userInfo = $this->execute($msql);
            return $userInfo[0];
        }catch(\Exception $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 根据群ID获取群信息
     * @param $swarm_id
     * @return mixed
     */
    public function getSwarmInfo($swarm_id) {
        try{
            $this->useDb();
            $sql = "SELECT * FROM chat.ct_swarm WHERE swarm_id=$swarm_id";
            $swarmInfo = $this->execute($sql);
            return $swarmInfo[0];
        }catch(\Exception $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 通过群ID获取用户ID
     * @param $swarm_id
     * @return mixed
     */
    public function getUserBySwarm($my_id,$swarm_id) {
        $this->useDb();
        $sql = "(SELECT user_id FROM chat.ct_swarm_user WHERE swarm_id=$swarm_id AND user_id<>$my_id) UNION ALL (SELECT IF(user_id=$my_id,'',user_id) as user_id FROM chat.ct_swarm WHERE swarm_id=$swarm_id)";
        $userIds = $this->execute($sql);
        foreach($userIds as $key=>$vo) {
            if(empty($vo['user_id'])) {
                unset($userIds[$key]);
            }
        }
        return $userIds;
    }

    /**
     * 修改个性签名
     * @param $user_id
     * @param $sign
     * @return int|string
     */
    public function saveSign($user_id,$sign) {
        $this->useDb();
        $sql  = "UPDATE chat.ct_user SET `sign`='{$sign}' WHERE `id`='{$user_id}'";
        $rows = $this->queryBySql($sql);
        return $rows;
    }

    /**
     * 获取用户下所有分组
     * @param $user_id
     * @return array
     */
    public function getGroupList($user_id) {
        $this->useDb();
        $sql = "SELECT A.user_id,count(A.user_id) as friend_num,A.group_id,A.group_name FROM chat.ct_user_group AS A LEFT JOIN chat.ct_user_tree AS B ON A.group_id=B.group_id WHERE A.user_id={$user_id} GROUP BY A.group_id;";
        $group_list = $this->execute($sql);
        return $group_list;
    }

    /**
     * 通过分组ID获取所有用户
     * @param $group_id
     * @return array
     */
    public function getFriendsByGroupId($group_id){
        $this->useDb();
        $sql = "SELECT * FROM chat.ct_user_tree AS A LEFT JOIN chat.ct_user AS B ON A.to_user_id=B.id WHERE A.group_id=$group_id";
        $user_list = $this->execute($sql);
        return $user_list;
    }

    /**
     * 通过群ID获取所有用户
     * @param $group_id
     * @return array
     */
    public function getFriendsBySwarmId($swarm_id){
        $this->useDb();
        $sql = "SELECT * FROM chat.ct_swarm_user AS A LEFT JOIN chat.ct_user AS B ON A.user_id=B.id WHERE A.swarm_id=$swarm_id";
        $user_list = $this->execute($sql);
        return $user_list;
    }

    /**
     * 删除分组
     * @param $group_id
     * @return bool
     */
    public function deleteGroup($group_id) {
        $flag = false;
        $this->useDb();
        $user_list = $this->getFriendsByGroupId($group_id);
        if(empty($user_list)) {
            $sql = "DELETE  FROM chat.ct_user_group WHERE group_id=$group_id";
            $this->queryBySql($sql);
            $flag = true;
        }
        return $flag;
    }
    //添加分组
    public function addGroup($user_id,$group_name) {
        try{
            $this->useDb();
            $sql = "INSERT INTO chat.ct_user_group(`user_id`,`group_name`) VALUES($user_id,'{$group_name}')";
            $gid = $this->queryBySql($sql,'add');
            return $gid;
        }catch(\Exception $e) {
            exit($e->getMessage());
        }

    }
    //删除好友
    public function delFriend($my_id,$user_id) {
        try{
            $flag = true;
            $this->useDb();
            $sql = "DELETE FROM chat.ct_user_tree WHERE user_id=$my_id AND to_user_id=$user_id";
            $this->queryBySql($sql);
        }catch(\ErrorException $e) {
            exit($e->getMessage());
        }

        return $flag;
    }

    //获取所有用户
    public function getUserList($user_id) {
        $this->useDb();
        $sql = "SELECT * FROM chat.ct_user WHERE id<>$user_id AND id NOT IN(SELECT to_user_id FROM chat.ct_user_tree WHERE user_id=$user_id)";
        $user_list = $this->execute($sql);
        return $user_list;
    }

    //添加好友
    public function addFriend($user_id,$to_user_id,$my_group_id,$to_group_id) {
        $this->useDb();
        try{
            $this->useDb();
            $sql = "INSERT INTO chat.ct_user_tree(`user_id`,`to_user_id`,`group_id`) VALUES($user_id,$to_user_id,$my_group_id)";
            $this->queryBySql($sql,'add');
            $sql = "INSERT INTO chat.ct_user_tree(`user_id`,`to_user_id`,`group_id`) VALUES($to_user_id,$user_id,$to_group_id)";
            $this->queryBySql($sql,'add');
        }catch(\Exception $e) {
            exit($e->getMessage());
        }
        return true;
    }
    //获取群列表
    public function getSwarmList($user_id,$self=true) {
        $this->useDb();
        try{
            if(false!==$self) {
                $sql = "SELECT * FROM chat.ct_swarm WHERE user_id=$user_id";
            }else{
                $sql = "SELECT * FROM chat.ct_swarm WHERE user_id<>$user_id";
            }
            $swarm_list = $this->execute($sql);
            return $swarm_list;
        }catch(\Exception $e){
            exit($e->getMessage());
        }
    }
    //创建群
    public function addSwarm($user_id,$swarm_name,$avatar) {
        try{
            $this->useDb();
            $sql = "INSERT INTO chat.ct_swarm(`user_id`,`swarm_name`,`avatar`) VALUES($user_id,'{$swarm_name}','{$avatar}')";
            $sid = $this->queryBySql($sql,'add');
            return $sid;
        }catch(\Exception $e) {
            exit($e->getMessage());
        }

    }

    /**
     * 删除群
     * @param $group_id
     * @return bool
     */
    public function deleteSwarm($swarm_id) {
        $flag = false;
        $this->useDb();
        $user_list = $this->getFriendsBySwarmId($swarm_id);
        if(empty($user_list)) {
            $sql = "DELETE  FROM chat.ct_swarm WHERE swarm_id=$swarm_id";
            $this->queryBySql($sql);
            $flag = true;
        }
        return $flag;
    }

    //删除好友
    public function delFriendForSwarm($swarm_id,$user_id) {
        try{
            $flag = true;
            $this->useDb();
            $sql = "DELETE FROM chat.ct_swarm_user WHERE user_id=$user_id AND swarm_id=$swarm_id";
            $this->queryBySql($sql);
        }catch(\ErrorException $e) {
            exit($e->getMessage());
        }

        return $flag;
    }

    /**
     * 同意好友入群
     * @param $swarm_id
     * @param $user_id
     * @return int|string
     */
    public function addFriendForSwarm($swarm_id,$user_id) {
        try{
            $this->useDb();
            $sql = "INSERT INTO chat.ct_swarm_user(`user_id`,`swarm_id`) VALUES($user_id,'{$swarm_id}')";
            $sid = $this->queryBySql($sql,'add');
            return $sid;
        }catch(\Exception $e) {
            exit($e->getMessage());
        }
    }

    /**
     * 用户所加所有群的IDS
     * @param $user_id
     * @return mixed
     */
    public function userToSwarm($user_id){
        $this->useDb();
        try{
            $sql = "SELECT swarm_id FROM chat.ct_swarm_user WHERE user_id=$user_id";
            $swarm_id = $this->execute($sql);
            if(!empty($swarm_id))
                return $swarm_id[0];
            return array();
        }catch(\Exception $e){
            exit($e->getMessage());
        }
    }

    /**
     * 退群
     * @param $swarm_id
     * @param $user_id
     * @return bool
     */
    public function exitSwarm($swarm_id,$user_id) {
        try{
            $flag = true;
            $this->useDb();
            $sql = "DELETE FROM chat.ct_swarm_user WHERE user_id=$user_id AND swarm_id=$swarm_id";
            $this->queryBySql($sql);
        }catch(\ErrorException $e) {
            exit($e->getMessage());
        }

        return $flag;
    }

    public function hasUserOfAllUserIds($user_id){
        $this->useDb();
        try{
            $sql = "SELECT user_id FROM chat.ct_user_tree WHERE to_user_id=$user_id";
            $user_ids = $this->execute($sql);
            return $user_ids;
        }catch(\Exception $e){
            exit($e->getMessage());
        }
    }

} 