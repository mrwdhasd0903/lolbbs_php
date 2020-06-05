<?php 
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
include_once 'inc/is_manage_login.inc.php';//验证管理员是否登录
//防止直接进入此页
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
	skip('father_module.php','error','id参数错误！');
}
$query="select * from lol_father_module where id={$_GET['id']}";
$result=execute($link,$query);
//判断id是否正确 防篡改
if(!mysqli_num_rows($result)){
	skip('father_module.php','error','版块不存在！');
}
if(isset($_POST['submit'])){
	//设置操作
	$check_flag='update';
	include 'inc/check_father_module.inc.php';
	$query="update lol_father_module set module_name='{$_POST['module_name']}',sort={$_POST['sort']} where id={$_GET['id']}";
	execute($link,$query);

	if(mysqli_affected_rows($link)==1){
		skip('father_module.php','ok','修改成功！');
	}else{
		skip('father_module.php','error','修改失败,请重试！');
	}
}
$data=mysqli_fetch_assoc($result);
$template['title']='父版块-修改';
$template['css']=array('style/public.css');
?>
<?php include 'inc/header.inc.php'?>
<div id="main">
	<div class="title" style="margin-bottom:20px;">修改父版块 - <?php echo $data['module_name']?></div>
	<form method="post">
		<table class="au">
			<tr>
				<td>版块名称</td>
				<td><input name="module_name" value="<?php echo $data['module_name']?>" type="text" /></td>
				<td>
					版块名称不得为空，最大不得超过66个字符
				</td>
			</tr>
			<tr>
				<td>排序</td>
				<td><input name="sort" value="<?php echo $data['sort']?>" type="text" /></td>
				<td>
					填写一个数字即可
				</td>
			</tr>
		</table>
		<input style="margin-top:20px;cursor:pointer;" class="btn" type="submit" name="submit" value="修改" />
	</form>
</div>
<?php include 'inc/footer.inc.php'?>