<?php 
if(empty($_POST['name'])){
	skip('login.php', 'error', '用户名不得为空！');
}
//学校机房的php默认配置用不了mb_strlen函数，所以注释了
//if(mb_strlen($_POST['name'])>32){
//	skip('login.php', 'error', '用户名长度不要超过32个字符！');
//}
if(empty($_POST['pw'])){
	skip('login.php', 'error', '密码不得为空！');
}
//不知道什么原因学校机房用户不了生成验证码的函数，所以注释了吧
//if(strtolower($_POST['vcode'])!=strtolower($_SESSION['vcode'])){
//	skip('login.php', 'error','验证码输入错误！');
//}
//防止篡改 自动登陆的事件
if(empty($_POST['time']) || is_numeric($_POST['time']) || $_POST['time']>2592000){
	$_POST['time']=2592000;
}
?>