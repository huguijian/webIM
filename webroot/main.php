<?php
require '../vendor/autoload.php';
use ZPHP\ZPHP;
error_reporting(E_ALL);
$rootPath = dirname(__DIR__);
ZPHP::run($rootPath);