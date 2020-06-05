<?php 
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link=connect();
//是否管理员登录
$is_manage_login=is_manage_login($link);
//使用用户登录
$member_id=is_login($link);
if(!$member_id && !$is_manage_login){
	skip('login.php', 'error', '您没有登录!');
}
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
	skip('index.php', 'error', '该回复不存在');
}
$query="select member_id,content from lol_reply where id={$_GET['id']}";
$result_content=execute($link, $query);
//验证权限 防止篡改
if(mysqli_num_rows($result_content)==1){
	$data_content=mysqli_fetch_assoc($result_content);
	if(check_user($member_id,$data_content['member_id'],$is_manage_login)){
		if(isset($_POST['submit'])){
			include 'inc/check_reply.inc.php';
			$_POST=escape($link, $_POST);
			$query="update lol_reply set content='{$_POST['content']}' where id={$_GET['id']}";
			execute($link, $query);
			$return_url=$_GET['return_url'];
			if(mysqli_affected_rows($link)==1){
				skip($return_url, 'ok', '修改成功！');
			}else{
				skip($return_url, 'error', '修改失败，请重试！');
			}
		}
	}else{
		skip('index.php', 'error', '这个回复不属于你，你没有权限!');
	}
}else{
	skip('index.php', 'error', '帖子不存在!');
}
$template['title']='回复修改页';
$template['css']=array('style/public.css','style/publish.css');
?>
<?php include 'inc/header.inc.php'?>
<div id="position" class="auto">
	 <a href="index.php">首页</a> &gt; 修改回复
</div>
<div id="publish">
	<form method="post">
		<textarea name="content" class="content"><?php echo $data_content['content']?></textarea>
		<input class="publish" type="submit" name="submit" value="确定" />
		<div style="clear:both;"></div>
	</form>
</div>
<?php include 'inc/footer.inc.php'?>