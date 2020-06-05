<?php 

//跳转指定页面函数 参数：地址 要提示的图标 提示的信息
function skip($url,$pic,$message){
$html=<<<A
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<meta http-equiv="refresh" content="3;URL={$url}" />
<title>正在跳转中</title>
<link rel="stylesheet" type="text/css" href="style/remind.css" />
</head>
<body>
<div class="notice"><span class="pic {$pic}"></span> {$message} <a href="{$url}">3秒后自动跳转中!</a></div>
</body>
</html>
A;
echo $html;
exit();
}
//用cookie对比数据库 判断前台用户的登陆状态
function is_login($link){
	if(isset($_COOKIE['lol']['name']) && isset($_COOKIE['lol']['pw'])){
		//因为在cookie存密码时用了 sha1 和md5双重加密，而数据库中存的只有md5加密，所以这里要对数据库的pw多一层sha1，即sha1(pw)
		$query="select * from lol_member where name='{$_COOKIE['lol']['name']}' and sha1(pw)='{$_COOKIE['lol']['pw']}'";
		$result=execute($link,$query);
		if(mysqli_num_rows($result)==1){
			$data=mysqli_fetch_assoc($result);
			return $data['id'];
		}else{
			return false;
		}
	}else{
		return false;
	}
}
//当前有 管理员登陆或用户登录 （判断是否可以删帖时用到）
function check_user($member_id,$content_member_id,$is_manage_login){
	if($member_id==$content_member_id || $is_manage_login){
		return true;
	}else{
		return false;
	}
}
//用SESSION对比数据库 ，验证后台管理员是否登录
function is_manage_login($link){
	if(isset($_SESSION['manage']['name']) && isset($_SESSION['manage']['pw'])){
		$query="select * from lol_manage where name='{$_SESSION['manage']['name']}' and sha1(pw)='{$_SESSION['manage']['pw']}'";
		$result=execute($link,$query);
		if(mysqli_num_rows($result)==1){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}
?>