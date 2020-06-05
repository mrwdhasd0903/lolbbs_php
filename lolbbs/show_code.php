<?php 
session_start();
//生成并输出验证码图片
include_once 'inc/vcode.inc.php';
//使用session存放验证码的字符串格式
$_SESSION['vcode']=vcode(100,40,30,4);
?>