<?php 
if(empty($_POST['name'])){
	skip('login.php','error','管理员名称不得为空！');
}
//学校机房的php默认配置用不了mb_strlen函数，所以注释了
//if(mb_strlen($_POST['name'])>32){
//	skip('login.php','error','管理员名称不得多余32个字符！');
//}
//学校机房的php默认配置用不了mb_strlen函数，所以注释了
//if(mb_strlen($_POST['pw'])<6){
//	skip('login.php','error','密码不得少于6位！');
//}
//不知道什么原因学校机房用户不了生成验证码的函数，所以注释了吧
//if(strtolower($_POST['vcode'])!=strtolower($_SESSION['vcode'])){
//	skip('login.php', 'error','验证码输入错误！');
//}
?>