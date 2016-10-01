<?php
    return array(
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
    );
