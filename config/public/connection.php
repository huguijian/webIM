<?php
    return array(
        'connection'=>array(
            'adapter' => 'Redis',
            'name' => 'cr',
            'pconnect' => true,
            'host' => '127.0.0.1',
            'port' => 6379,
            'timeout' => 5,
            'prefix' => 'chat'
        )
    );