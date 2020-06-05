<?php 
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
if(!is_manage_login($link)){
	header('Location:login.php');
}
//释放session
session_unset();
session_destroy();//销毁一个会话中的全部数据
setcookie(session_name(),'',time()-3600,'/');//销毁保存在客户端session id
//跳转至登录页
header('Location:login.php');
?>