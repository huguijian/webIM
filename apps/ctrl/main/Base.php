<?php

namespace ctrl\main;

use ZPHP\Controller\IController,
    common,
    ZPHP\Protocol\Request;


class Base implements IController
{
    protected $server;
    protected $params = array();

    public function _before()
    {
        $this->params = Request::getParams();
        return true;
    }

    public function _after()
    {
        //common\loadClass::getDao('User')->closeDb();
    }

    protected function getInteger(array $params, $key, $default = null, $abs = true, $notEmpty = false)
    {

        if (!isset($params[$key])) {
            if ($default !== null) {
                return $default;
            }
            throw new \Exception("no params {$key}", common\ERROR::PARAM_ERROR);
        }

        $integer = isset($params[$key]) ? \intval($params[$key]) : 0;

        if ($abs) {
            $integer = \abs($integer);
        }

        if ($notEmpty && empty($integer)) {
            throw new \Exception('params no empty', common\ERROR::PARAM_ERROR);
        }

        return $integer;
    }

    protected function getString($params, $key, $default = null, $notEmpty = false)
    {
        $params = (array)$params;
        if (!isset($params[$key])) {
            if (null !== $default) {
                return $default;
            }
            throw new \Exception("no params {$key}", common\ERROR::PARAM_ERROR);
        }

        $string = \trim($params[$key]);

        if (!empty($notEmpty) && empty($string)) {
            throw new \Exception('params no empty', common\ERROR::PARAM_ERROR);
        }

        return \addslashes($string);
    }

    protected function upload($uploaddir) {
        $tmp_name =$_FILES['file']['tmp_name'];  // 文件上传后得临时文件名
        $name     =$_FILES['file']['name'];     // 被上传文件的名称
        $size     =$_FILES['file']['size'];    //  被上传文件的大小
        $type     =$_FILES['file']['type'];   // 被上传文件的类型
        $dir      = $uploaddir.date("Ym");
        @chmod($dir,0777);//赋予权限
        @is_dir($dir) or mkdir($dir,0777);
        //chmod($dir,0777);//赋予权限
        move_uploaded_file($_FILES['file']['tmp_name'],$dir."/".$name);
        $type = explode(".",$name);
        $type = @$type[1];
        $date   = date("YmdHis");
        $rename = @rename($dir."/".$name,$dir."/".$date.".".$type);
        if($rename)
        {
            return array(
                'url'  => $dir."/".$date.".".$type,
                'name' => $name
            );
        }
    }
}
