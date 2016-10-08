## 基于SWOOLE的在线聊天WebIM,支持swoole的朋友给个start  ^-^.
功能介绍：
> - 在线好友及群查找加入
> - 支持在线单聊与群聊
> - 分组/添加/修改/删除
> - 群的基本操作/添加/修改/删除/加入
> - 在线消息推送/离线消息推送
> - 支持发送连接/图片附件
> - 查看历史聊天记录
> - 用户上线与隐身

##在线DEMO地址：
http://im.classba.com.cn/main.php

1 . 安装swoole 
>  pecl install swoole

### 

2 . 安装redis
>  pecl install redis

### 

启动websocket服务器

> php   chat/webroot/main.php sock_ws
### 

##访问地址
根据自身服务器配置虚拟主机域名(访问脚本文件webroot/main.php)
http://im.classba.com.cn/main.php
