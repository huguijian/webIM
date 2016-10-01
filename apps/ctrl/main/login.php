<?php
namespace ctrl\main;
use common,
    ZPHP\Controller\IController,
    ZPHP\Protocol\Request,
    ZPHP\Core\Config as ZConfig;

class login implements IController
{
    protected $server;
    protected $params = array();

    public function _before()
    {
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
    public function login() {
        if(isset($_SESSION)) {
            unset($_SESSION);
        }
        return array(
            'static_url'=>ZConfig::getField('project', 'static_url'),
        );
    }

    public function doLogin() {

    }

    public function reg() {
        return array(
            'static_url'=>ZConfig::getField('project', 'static_url'),
        );
    }

    /**
     * 用户注册操作
     * @return array
     * @throws \Exception
     */
    public function register()
    {
        $username = $this->getString($this->params, 'username');
        $remark_name = $this->getString($this->params, 'remark_name');
        $avatar = $this->getString($this->params, 'avatar');
        $password = $this->getString($this->params, 'password');
        $service = common\loadClass::getService('User');
        $result = $service->addUser($username, $password,$remark_name,$avatar);
        if($result) {
            return common\Utils::jump("login", "login", array(
                "msg"=>"注册成功"
            ));
        }
        return common\Utils::showMsg("注册失败，请重试");
    }

    /**
     * 用户登陆初始化生成redis数据
     * @return array
     * @throws \Exception
     */
    public function check()
    {
        $username = $this->getString($this->params, 'username');
        $password = $this->getString($this->params, 'password');
        $service = common\loadClass::getService('User');
        $userInfo = $service->checkUser($username, $password);
        if(!empty($userInfo)) {
            if(!isset($_SESSION)) {
                session_start();
            }
            $token = common\Utils::setToken($userInfo->id);
            $_SESSION['user_id'] = $userInfo->id;
            $_SESSION['token'] = $token;
            return common\Utils::jump("main", "main", array(
                'uid'=>$userInfo->id,
                'token'=>$token,
            ));
        }
        return common\Utils::showMsg("登录失败，请重试");
    }


}

