<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<!--从父文件传入标题-->
<title><?php echo $template['title'] ?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<!--从父文件传入css地址-->
<?php 
foreach ($template['css'] as $val){
	echo "<link rel='stylesheet' type='text/css' href='{$val}' />";
}
?>
</head>
<body >
	<div class="header_wrap">
		<div id="header" class="auto">
			<div class="logo">
				<a  href="index.php">🗡英雄联盟🗡论坛</a>
			</div>
			<div class="serarch">
				<form action="search.php" method="get">
					<input class="keyword" type="text" name="keyword" placeholder="我知道你想找什么" />
					<input id="serarchtext" value="搜索" class="submit" type="submit" name="submit" />
				</form>
			</div>
			<div class="login">
<?php

				if($is_manage_login=is_manage_login($link)){//提醒当前为管理员状态
				echo "<a style=color:red; href='admin/index.php'>管理员状态!</a>";
				}
				if(isset($member_id)&& $member_id){//如果登录显示账户
$str=<<<A
					<a id='titleleft' style="color:#fff;" href="member.php?id={$member_id}" target="_blank">您好,{$_COOKIE['lol']['name']}</a><a id='titleright' href="logout.php">退出</a>
A;
					echo $str;		
				}else{//否则提示登录
$str=<<<A
					<a id='titleleft' href='login.php'>登录</a><a id='titleright' href='register.php'>注册</a>
A;
					echo $str;
				}
				?>
			</div>
		</div>
	</div>
	<div style="margin-top:55px;"></div>
