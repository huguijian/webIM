<?php
    return array(
        'route'=>array(
            'static' => array(                  //静态路由
                '/history' => array(
                    'chat',               //ctrl类
                    'getUserHistoryMsg'                     //具体执行的方法
                ),
            ),
            'dynamic' => array(                     //动态路由
                '/^\/(\d+)\/(.*?)$/iU' => array(    //匹配 http://host/uid/token
                    'main\\main',                   //ctrl类
                    'main',                         //具体执行的方法
                    array('uid', 'token'),          //对应的参数名
                    '/{uid}/{token}'                //反向返回的格式, 通过内置的
                ),
            ),
            'cache'=>true,
        ),
    );
