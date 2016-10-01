<?php
namespace ctrl\main;
use common,
    ZPHP\Controller\IController,
    ZPHP\Protocol\Request,
    ZPHP\Core\Config as ZConfig;

class main implements IController
{
    protected $server;
    protected $params = array();

    public function _before()
    {
        session_start();
        if(empty($_SESSION['user_id'])) {
            return common\Utils::jump("login", "login", array(
                "msg"=>"需要登录"
            ));
        }
        $this->params = Request::getParams();
        return true;
    }

    public function _after()
    {
        //common\loadClass::getDao('User')->closeDb();
    }

    protected function getInteger(array $params, $key, $default = null, $abs = true, $notEmpty = false)
    {

        if (!isset($params[$key])) {
            if ($default !== null) {
                return $default;
            }
            throw new \Exception("no params {$key}", common\ERROR::PARAM_ERROR);
        }

        $integer = isset($params[$key]) ? \intval($params[$key]) : 0;

        if ($abs) {
            $integer = \abs($integer);
        }

        if ($notEmpty && empty($integer)) {
            throw new \Exception('params no empty', common\ERROR::PARAM_ERROR);
        }

        return $integer;
    }

    protected function getString($params, $key, $default = null, $notEmpty = false)
    {
        $params = (array)$params;
        if (!isset($params[$key])) {
            if (null !== $default) {
                return $default;
            }
            throw new \Exception("no params {$key}", common\ERROR::PARAM_ERROR);
        }

        $string = \trim($params[$key]);

        if (!empty($notEmpty) && empty($string)) {
            throw new \Exception('params no empty', common\ERROR::PARAM_ERROR);
        }

        return \addslashes($string);
    }
    public function main()
    {
        $token = $this->getString($this->params, 'token', '');
        $uid = $this->getString($this->params, 'uid', '');
        if(!empty($_SESSION['user_id'])) {
            return array(
                'uid'=>$uid ? $uid : $_SESSION['user_id'],
                'token'=>$token ? $token : $_SESSION['token'],
                'static_url'=>ZConfig::getField('project', 'static_url'),
                'app_host'=>ZConfig::getField('project', 'app_host'),
            );
        }
        return common\Utils::jump("login", "login", array(
            "msg"=>"需要登录"
        ));

    }

    //查找好友加,好友设置
    public function find() {
        $user_id = $this->getString($this->params, 'user_id');
        //分组列表
        $group_list = $this->groupList($user_id);
        //群列表
        $swarm_list = $this->swarmList($user_id);
        //除本用户群列表
        $swarm_list_other = $this->swarmList($user_id,false);
        //用户列表
        $user_list = $this->userList($user_id);
        return array(
            'static_url'=>ZConfig::getField('project', 'static_url'),
            'group_list'=>$group_list,
            'swarm_list' => $swarm_list,
            'user_id'   => $user_id,
            'user_list' => $user_list,
            'swarm_list_other' => $swarm_list_other
        );
    }



    public function userList($user_id) {
        $user_server =  common\loadClass::getService('User');
        return $user_server->userList($user_id);
    }


    /**
     * 用户注册显示页
     * @return array
     */
    public function reg()
    {
        return array(
            'static_url'=>ZConfig::getField('project', 'static_url'),
        );
    }



    //添加朋友
    public function addFriend() {
        $to_user_id = $this->getString($this->params,'to_user_id');
        $self_user_id = $this->getString($this->params,'user_id');
        $user_server = common\loadClass::getService('User');
        $user_info   = $user_server->getUserInfo($to_user_id);
        $group_list  = $user_server->getGroupList($self_user_id);
        return array(
            'static_url'=>ZConfig::getField('project', 'static_url'),
            'group_list' => $group_list,
            'user_info'  => $user_info,
            'self_user_id' => $self_user_id

        );
    }
    //添加朋友验证信息面
    public function checkMsgFriend() {
        $to_user_id = $this->getString($this->params,'to_user_id');
        $self_user_id = $this->getString($this->params,'user_id');
        $to_group_id  = $this->getString($this->params,'to_group_id');
        $check_msg    = $this->getString($this->params,'check_msg');
        $user_server = common\loadClass::getService('User');
        $user_info   = $user_server->getUserInfo($to_user_id);
        $group_list  = $user_server->getGroupList($self_user_id);
        return array(
            'static_url'=>ZConfig::getField('project', 'static_url'),
            'group_list' => $group_list,
            'user_info'  => $user_info,
            'check_msg'  => $check_msg,
            'to_user_id' => $to_user_id,
            'self_user_id' => $self_user_id,
            'to_group_id' => $to_group_id
        );
    }

    /**
     * 加入群管理员审查信息
     * @return array
     * @throws \Exception
     */
    public function checkMsgFriendForSwarm() {
        $to_user_id = $this->getString($this->params,'to_user_id');
        $created_user_id = $this->getString($this->params,'created_user_id');
        $to_swarm_id  = $this->getString($this->params,'to_swarm_id');
        $check_msg    = $this->getString($this->params,'check_msg');
        $user_server = common\loadClass::getService('User');
        $user_info   = $user_server->getUserInfo($to_user_id);
        return array(
            'static_url'=>ZConfig::getField('project', 'static_url'),
            'user_info'  => $user_info,
            'check_msg'  => $check_msg,
            'to_user_id' => $to_user_id,
            'to_swarm_id' => $to_swarm_id,
            'created_user_id' => $created_user_id
        );
    }
    //分组管理
    public function groupList($user_id) {
       $user_server =  common\loadClass::getService('User');
        return $user_server->getGroupList($user_id);
    }
    //群列表
    public function swarmList($user_id,$self=true) {
        $user_server =  common\loadClass::getService('User');
        return $user_server->getSwarmList($user_id,$self);
    }

}

