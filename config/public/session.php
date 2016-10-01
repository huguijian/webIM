<?php
    return array(
        'session' => array(
            'adapter' => 'Redis',
            'name' => 'nc',
            'pconnect' => true,
            'host' => '127.0.0.1',
            'port' => 16379,
            'timeout' => 5
        ),
    );