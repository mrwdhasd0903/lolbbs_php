<?php 
if(empty($_POST['name'])){
	skip('register.php', 'error', '用户名不得为空！');
}


//学校机房的php默认配置用不了mb_strlen函数，所以注释了
//if(mb_strlen($_POST['name'])>32){
//	skip('register.php', 'error', '用户名长度不要超过32个字符！');
//}
//学校机房的php默认配置用不了mb_strlen函数，所以注释了
//if(mb_strlen($_POST['pw'])<6){
//	skip('register.php', 'error','密码不得少于6位！');
//}


if($_POST['pw']!=$_POST['confirm_pw']){
	skip('register.php', 'error','两次密码输入不一致！');
}


//不知道什么原因学校机房用户不了生成验证码的函数，所以注释了吧
//将验证码转化为小写 再判断
//if(strtolower($_POST['vcode'])!=strtolower($_SESSION['vcode'])){
//	skip('register.php', 'error','验证码输入错误！');
//}
$_POST=escape($link,$_POST);
$query="select * from lol_member where name='{$_POST['name']}'";
$result=execute($link, $query);
if(mysqli_num_rows($result)){
	skip('register.php', 'error', '这个用户名已经被别人注册了！');
}
?>