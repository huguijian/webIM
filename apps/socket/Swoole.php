<?php

namespace socket;

use ZPHP\Protocol\Request;
use ZPHP\Socket\Callback\Swoole as ZSwoole;
use ZPHP\Socket\IClient;
use ZPHP\Protocol\Factory as Zprotocol;
use ZPHP\Core\Config as ZConfig;
use ZPHP\Core\Route as ZRoute;

class Swoole extends ZSwoole
{


    public function onReceive()
    {
        list($serv, $fd, $fromId, $data) = func_get_args();
        echo "get data {$data} from $fd\n";
        var_dump($data);exit;
        if (empty($data)) {
            return;
        }
        Request::parse($data);
        $result = ZRoute::route();

        $serv->send($fd, $result);
    }

}
