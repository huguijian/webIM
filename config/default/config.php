<?php
use ZPHP\ZPHP;
define('NOW_TIME', isset($_SERVER['REQUEST_TIME']) ? $_SERVER['REQUEST_TIME'] : time());
define('TPL_PATH', ZPHP::getRootPath() . DS  . 'template'. DS . 'chat' . DS);
define('STATIC_URL', '/static/');
$config =  array(
    'server_mode' => 'Http',
    'project_name' => 'chat',
    'app_path' => 'apps',
    'ctrl_path' => 'ctrl\main',
    'project' => array(
        'log_path' => 'http',
        'default_ctrl_name'=>'main',
        'static_url' => STATIC_URL,
        'tpl_path'=> TPL_PATH,
        'view_mode'=> 'Php',
        'app_host'=> $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']
    ),
);
$publicConfig = array('pdo.php', 'cache.php', 'connection.php', 'route.php');
foreach($publicConfig as $file) {
    $file = ZPHP::getRootPath() . DS . 'config' . DS . 'public'. DS . $file;
    $config += include "{$file}";
}

return $config;
