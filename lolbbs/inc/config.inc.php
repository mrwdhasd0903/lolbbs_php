<?php
//设置时区
date_default_timezone_set('Asia/Shanghai');
session_start();
header('Content-type:text/html;charset=utf-8');


//主机
define("DB_HOST", "localhost");
//sql用户
define("DB_USER", "root");
//sql密码
define("DB_PASSWORD", "123456");
//数据库名
define("DB_DATABASE", "lolbbs");
//sql端口
define("DB_PORT",3306);


//项目（程序），在服务器上的绝对路径
define('SA_PATH',dirname(dirname(__FILE__)));
//项目在web根目录下面的位置（哪个目录里面）
define('SUB_URL',str_replace($_SERVER['DOCUMENT_ROOT'],'',str_replace('\\','/',SA_PATH)).'/');
?>