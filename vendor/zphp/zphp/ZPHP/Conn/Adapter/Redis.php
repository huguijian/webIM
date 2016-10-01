<?php

namespace ZPHP\Conn\Adapter;
use ZPHP\Core\Config as ZConfig,
    ZPHP\Conn\IConn,
    ZPHP\Manager\Redis as ZRedis;

/**
 *  redis 容器
 */
class Redis implements IConn
{

    private $redis;

    public function __construct($config)
    {
        if(empty($this->redis)) {
            $this->redis = ZRedis::getInstance($config);
            $db = ZConfig::getField('connection', 'db', 0);
            if(!empty($db)) {
                $this->redis->select($db);
            }
        }
    }


    public function addFd($fd, $uid = 0)
    {
        return $this->redis->set($this->getKey($fd, 'fu'), $uid);
    }


    public function getUid($fd)
    {
        return $this->redis->get($this->getKey($fd, 'fu'));
    }

    public function add($user_info, $fd)
    {
        $user_info['fd'] = $fd;
        $this->redis->set($this->getKey($user_info['id']), \json_encode($user_info));
        return true;
    }
    //离线聊天记录保存
    public function offLineData($msg_data,$user_id)
    {
        $key = "fd_{$user_id}_off";
        if($this->redis->exists($key)) {
            $data = $this->redis->get($key);
            $data = json_decode($data,true);
            array_push($data,$msg_data);
            $this->redis->set($key, \json_encode($data));
        }else{
            $off_data[0] = $msg_data;
            $this->redis->set($key, \json_encode($off_data));
        }
        return true;
    }

    public function addChannel($uid, $channel)
    {
        $uinfo = $this->get($uid);
        if(empty($uinfo)) return;
        $uinfo['types'][$channel] = 1;
        if ($this->redis->hSet($this->getKey($channel), $uid, $uinfo['fd'])) {
            $this->redis->set($this->getKey($uid), json_encode($uinfo));
        }
    }

    public function delChannel($uid, $channel)
    {
        if($this->redis->hDel($this->getKey($channel), $uid)){
            $uinfo = $this->get($uid);
            if(!empty($uinfo['types'][$channel])) {
                unset($uinfo['types'][$channel]);
                $this->redis->set($this->getKey($uid), json_encode($uinfo));
            }
        }
        return true;
    }

    public function getChannel($channel = 'ALL')
    {
        return $this->redis->hGetAll($this->getKey($channel));
    }

    public function get($uid)
    {
        $data = $this->redis->get($this->getKey($uid));
        if (empty($data)) {
            return false;
        }

        return json_decode($data, true);
    }
    //获取离线信息
    public function getOffData($key) {
        return $this->redis->get($key);
    }
    public function uphb($uid)
    {
        $uinfo = $this->get($uid);
        if (empty($uinfo)) {
            return false;
        }
        $uinfo['time'] = time();
        return $this->redis->set($this->getKey($uid), json_encode($uinfo));
    }

    public function heartbeat($uid, $ntime = 60)
    {
        $uinfo = $this->get($uid);
        if (empty($uinfo)) {
            return false;
        }
        $time = time();
        if ($time - $uinfo['time'] > $ntime) {
            $this->delete($uinfo['fd'], $uid);
            return false;
        }
        return true;
    }

    public function delete($uid = null, $old = true)
    {
        if ($old) {
            $this->redis->delete($this->getKey($uid, 'fd'));
        }
    }

    public function getBuff($fd, $prev='buff')
    {
        return $this->redis->get($this->getKey($fd, $prev));
    }

    public function setBuff($fd, $data, $prev='buff')
    {
        return $this->redis->set($this->getKey($fd, $prev), $data);
    }

    public function delBuff($fd, $prev='buff')
    {
        return $this->redis->delete($this->getKey($fd, $prev));
    }
    public function deleteOffData($key) {
        return $this->redis->delete($key);
    }

    private function getKey($uid, $prefix = 'fd')
    {
        return "{$prefix}_{$uid}_" . ZConfig::getField('connection', 'prefix');
    }

    public function exists($key)
    {
        return $this->redis->exists($key);
    }

    //清空所有redis
    public function clear()
    {
        $this->redis->flushDB();
    }

    public function setNews($data,$uid='') {
        $key = "fd_{$uid}_news";
        if($this->redis->exists($key)) {
            $msg = $this->redis->get($key);
            $msg = json_decode($msg,true);
            array_push($msg,$data);
            $this->redis->set($key, \json_encode($msg));
        }else{
            $news[0] = $data;
            $this->redis->set($key, \json_encode($news));
        }
    }

    public function getNews($uid){
        return $this->redis->get("fd_{$uid}_news");
    }

    public function deleteNews($key){
        return $this->redis->delete($key);
    }
}