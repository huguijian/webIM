<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 13-12-6
 * Time: 下午3:26
 */

namespace common;
use ZPHP\Core\Config as ZConfig,
    ZPHP\Cache\Factory as ZCache,
    ZPHP\Common\Route as ZRoute,
    ZPHP\Conn\Factory as ZConn;
use ZPHP\Protocol\Request;


class Utils
{

    public static function checkToken($uid, $token,$prefix='fd')
    {
        if(empty($uid) || empty($token)) {
            return false;
        }
        $config = ZConfig::getField('cache', 'net');
        $cacheHelper = ZCache::getInstance($config['adapter'], $config);
        $key = "{$prefix}_{$uid}_" . ZConfig::getField('connection', 'prefix');
        $tokenJson = $cacheHelper->get($key);
        $tokenArr    = json_decode($tokenJson,true);
        return $tokenArr['token'] === $token;
    }

    public static function setToken($uid,$prefix='fd')
    {
        $token = uniqid();
        $config = ZConfig::getField('cache', 'net');
        $cacheHelper = ZCache::getInstance($config['adapter'], $config);
        $key = "{$prefix}_{$uid}_" . ZConfig::getField('connection', 'prefix');
        $data = array(
            'token' => $token
        );
        if ($cacheHelper->set($key, json_encode($data))) {
            return $token;
        }
        throw new \Exception("token set error", ERROR::TOKEN_ERROR);
    }

    public static function getViewMode()
    {
        if(Request::isLongServer()) {
            return ZConfig::getField('project', 'view_mode', 'Json');
        }
        if(\ZPHP\Common\Utils::isAjax()) {
            return 'Json';
        }
        return 'Php';
    }

    public static function jump($action, $method, $params)
    {
        $url = ZRoute::makeUrl($action, $method, $params);
        self::redirect($url);

    }

    public static function makeUrl($action, $method, $params="")
    {
        return ZRoute::makeUrl($action, $method, $params);
    }

    public static function showMsg($msg)
    {
        return array(
            '_view_mode'=>self::getViewMode(),
            '_tpl_file'=>'error.php',
            'msg'=>$msg,
            'static_url'=>ZConfig::getField('project', 'static_url'),
        );
    }

    public static function online($channel='ALL')
    {

        $config = ZConfig::get('connection');
        $connection = ZConn::getInstance($config['adapter'], $config);
        return $connection->getChannel($channel);
    }


    public static function redirect($url)
    {
        $url = preg_match('/^(https?:|\/)/', $url) ? $url:'';
        header('Location: ' . $url, true, 301);
    }
} 