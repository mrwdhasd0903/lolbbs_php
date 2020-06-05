<?php 
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link=connect();
if($member_id=is_login($link)){
	skip('index.php','error','你已经登录，请不要重复注册！');
}
if(isset($_POST['submit'])){
	include 'inc/check_register.inc.php';
	$query="insert into lol_member(name,pw,register_time) values('{$_POST['name']}',md5('{$_POST['pw']}'),now())";
	execute($link,$query);
	if(mysqli_affected_rows($link)==1){
		//用户名和密码保存在cookie ，并且双重加密
		setcookie('lol[name]',$_POST['name']);
		setcookie('lol[pw]',sha1(md5($_POST['pw'])));
		skip('index.php','ok','注册成功！');
	}else{
		skip('register.php','eror','注册失败,请重试！');
	}
}
$template['title']='会员注册页';
$template['css']=array('style/public.css','style/register.css');
?>
<?php include 'inc/header.inc.php'?>
	<div id="register" class="auto">
		<h2>欢迎注册成为论坛会员</h2>
		<form method="post">
			<label>用户名：<input type="text" name="name"  /><span>*用户名不得为空，并且长度不得超过32个字符</span></label>
			<label>密码：<input type="password" name="pw"  /><span>*密码不得少于6位</span></label>
			<label>确认密码：<input type="password" name="confirm_pw"  /><span>*请输入与上面一致</span></label>
			<label>验证码：<input name="vcode" name="vocode" type="text"  /><span>*请输入下方验证码</span></label>
			<!--本来可以调用show_code.php动态获得随机验证码地址，但是在学校机房电脑不行，所以换成静态的-->
			<!--<img class="vcode" src="show_code.php" />-->
			<img class="vcode" src="style/image_code.jpg" />
			<div style="clear:both;"></div>
			<input class="btn" name="submit" type="submit" value="确定注册" />
		</form>
	</div>
<?php include 'inc/footer.inc.php'?>