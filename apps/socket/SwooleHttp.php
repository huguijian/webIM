<?php

namespace socket;

use ZPHP\Common\Formater;
use ZPHP\Protocol\Request;
use ZPHP\Protocol\Response;
use ZPHP\Socket\Callback\SwooleHttp as ZSwooleHttp;
use ZPHP\Socket\IClient;
use ZPHP\Core\Route as ZRoute;

class SwooleHttp extends ZSwooleHttp
{


    public function onRequest($request, $response)
    {
        $param = [];
        if(!empty($request->get)) {
            $param = $request->get;
        }

        if(!empty($request->post)) {
            $param += $request->post;
        }
        Request::parse($param);
        Response::setResponse($response);
        try {
            $result = ZRoute::route();
        } catch (\Exception $e) {
            $model = Formater::exception($e);
            $model['_view_mode'] = 'Json';
            $result = Response::display($model);
        }
        $response->end($result);
    }

    public function onWorkerStart($server, $workerId)
    {
        parent::onWorkerStart($server, $workerId);
        Request::setHttpServer(1);
    }

}
