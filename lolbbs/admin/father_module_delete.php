<?php 
//引入配置和公共函数库
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
include_once 'inc/is_manage_login.inc.php';//验证管理员是否登录
//防止直接进入此页
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
	skip('father_module.php','error','id参数错误！');
}

//判断子版块是否为空
$query="select * from lol_son_module where father_module_id={$_GET['id']}";
$result=execute($link,$query);
if(mysqli_num_rows($result)){
	skip('father_module.php','error','该父版块下面存在子版块，请先将对应的子版块先删掉！');
}

$query="delete from lol_father_module where id={$_GET['id']}";
execute($link,$query);
if(mysqli_affected_rows($link)==1){
	skip('father_module.php','ok','删除成功！');
}else{
	skip('father_module.php','error','删除失败，请重试！');
}
?>