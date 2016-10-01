<?php
    return array(
        'storage'=>array(
            'adapter' => 'RL',
            'servers' => array(
                array(
                    "master" => array(
                        'name' => 'master',
                        'pconnect' => true,
                        'host' => '127.0.0.1',
                        'port' => 2379,
                        'timeout' => 5
                    ),
                )
            ),
        )
    );