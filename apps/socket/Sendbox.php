<?php

namespace socket;
use ZPHP\Socket\Callback\Swoole as ZSwoole;

class Sendbox extends ZSwoole
{
    public function onReceive()
    {
        $params = func_get_args();
        $data = trim($params[3]);
        if (empty($data)) {
            return;
        }
        if ('<policy' == substr($data, 0, 7)) {
            \swoole_server_send($params[0], $params[1], "<cross-domain-policy>
                    <allow-access-from domain='*' to-ports='*' />
                    </cross-domain-policy>\0");
        }

    }
}
