<?php

use \ZPHP\Socket\Adapter\Swoole;

return array(
    'server_mode' => 'Socket',
    'project_name' => 'zphp',
    'app_path' => 'apps',
    'ctrl_path' => 'ctrl',
    'socket' => array(
        'host' => '0.0.0.0',                          //socket 监听ip
        'port' => 8991,                             //socket 监听端口
        'adapter' => 'Swoole',                          //socket 驱动模块
        'server_type' => Swoole::TYPE_WEBSOCKET,              //socket 业务模型 tcp/udp/http/websocket
        'protocol' => 'Json',                         //socket通信数据协议
        'daemonize' => 0,                             //是否开启守护进程
        'client_class' => 'socket\\SwooleWebSocket',            //socket 回调类
        'work_mode' => 3,                             //工作模式：1：单进程单线程 2：多线程 3： 多进程
        'worker_num' => 4,                                 //工作进程数
        'max_request' => 0,                            //单个进程最大处理请求数
        'debug_mode' => 1,                                  //打开调试模式
//        'heartbeat_check_interval' => 5,
//        'heartbeat_idle_time' => 8,
    ),
    'connection'=>array(
        'adapter' => 'Redis',
        'name' => 'cr',
        'pconnect' => true,
        'host' => '127.0.0.1',
        'port' => 6379,
        'timeout' => 5,
        'prefix' => 'chat'
    ),
    'cache'=>array(
        'locale' => array(
            'adapter' => 'Yac',
            'name' => 'lc',
        ),
        'net' => array( //网络cache配置
            'adapter' => 'Redis',
            'name' => 'nc',
            'pconnect' => true,
            'host' => '127.0.0.1',
            'port' => 6379,
            'timeout' => 5
        ),
    ),
    'pdo'=>array(
        'dsn' => 'mysql:host=localhost;port=3306',
        'name' => 'cd',
        'user' => 'root',
        'pass' => 'projectx2015',
        'dbname' => 'chat',
        'charset' => 'UTF8',
        'pconnect'=>false,
        'ping'=>1,
    )
);

