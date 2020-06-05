<?php 
//引入配置和公共函数库
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
include_once 'inc/is_manage_login.inc.php';//验证管理员是否登录
if(isset($_POST['submit'])){
	//设置操作 方式 传入check
	$check_flag='add';
	include 'inc/check_father_module.inc.php';
	//添加至数据库操作
	$query="insert into lol_father_module(module_name,sort) values('{$_POST['module_name']}',{$_POST['sort']})";
	execute($link,$query);
	//跳转至指定页面
	if(mysqli_affected_rows($link)==1){
		skip('father_module.php','ok','恭喜你，添加成功！');
	}else{
		skip('faher_module_add.php','error','对不起，添加失败，请重试！');
	}
}
$template['title']='父版块添加页';
$template['css']=array('style/public.css');
?>
<?php include 'inc/header.inc.php'?>
<div id="main">
	<div class="title" style="margin-bottom:20px;">添加父版块</div>
	<form method="post">
		<table class="au">
			<tr>
				<td>版块名称</td>
				<td><input name="module_name" type="text" /></td>
				<td>
					版块名称不得为空，最大不得超过66个字符
				</td>
			</tr>
			<tr>
				<td>排序</td>
				<td><input name="sort" value="0" type="text" /></td>
				<td>
					填写一个数字即可
				</td>
			</tr>
		</table>
		<input style="margin-top:20px;cursor:pointer;" class="btn" type="submit" name="submit" value="添加" />
	</form>
</div>
<?php include 'inc/footer.inc.php'?>