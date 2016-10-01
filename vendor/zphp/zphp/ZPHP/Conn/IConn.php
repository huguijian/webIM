<?php

namespace ZPHP\Conn;


interface IConn
{
    //fd与uid关联起来
    public function addFd($fd, $uid);
    //通过fd获得uid
    public function getUid($fd);
    //把uid和fd关联
    public function add($uid, $fd);
    //把uid加入到指定的channel
    public function addChannel($uid, $channel);
    //把uid从指定的channel删除
    public function delChannel($uid, $channel);
    //获取指定的channel
    public function getChannel($channel);
    //获取指定的uid的信息
    public function get($uid);
    //更新指定uid的心跳信息
    public function uphb($uid);
    //心跳检测
    public function heartbeat($uid, $ntime);
    //删除指定fd，uid相关的信息
    public function delete($uid, $old);
    //获取指定fd的buff信息，用于粘包处理
    public function getBuff($fd, $prev);
    //存入指定fd粘包处理后多有的包数据
    public function setBuff($fd, $data, $prev);
    //清除指定fd粘包处理后多有的包数据
    public function delBuff($fd, $prev);
    //清除库
    public function clear();

    //生成离线聊天记录
    public function offLineData($msg_data,$user_id);
    //获取离线聊天记录
    public function getOffData($key);
    //删除离线聊天记录
    public function deleteOffData($key);
    //判断一个key是否存在
    public function exists($key);
    //设置消息
    public function setNews($data,$uid);
    //获取消息
    public function getNews($uid);
    public function deleteNews($key);

}