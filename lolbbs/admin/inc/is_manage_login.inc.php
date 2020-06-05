<?php 
//未登录状态 直接退出
if(!is_manage_login($link)){
	header('Location:login.php');
	exit();
}
//判断当前管理员的权限。在进行一些管理员操作时用到
if(basename($_SERVER['SCRIPT_NAME'])=='manage_delete.php' || basename($_SERVER['SCRIPT_NAME'])=='manage_add.php'){
	if($_SESSION['manage']['level']!='0'){
		if(!isset($_SERVER['HTTP_REFERER'])){
			$_SERVER['HTTP_REFERER']='index.php';
		}
		skip($_SERVER['HTTP_REFERER'],'error','对不起您权限不足！');
	}
}
?>