<?php
//引入配置和公共函数库
include_once '../inc/config.inc.php';
include_once '../inc/mysql.inc.php';
include_once '../inc/tool.inc.php';
$link=connect();
include_once 'inc/is_manage_login.inc.php';//验证管理员是否登录
//提交 排序 监听
if(isset($_POST['submit'])){
	foreach ($_POST['sort'] as $key=>$val){
		//判断传入的排序是否数字
		if(!is_numeric($val) || !is_numeric($key)){
			skip('father_module.php','error','排序参数错误！');
		}
		$query[]="update lol_father_module set sort={$val} where id={$key}";
	}
	if(execute_multi($link,$query,$error)){
		skip('father_module.php','ok','排序修改成功！');
	}else{
		skip('father_module.php','error',$error);
	}
}
$template['title']='父板块列表页';
$template['css']=array('style/public.css');
?>

<?php
//引入页面头部（导航栏、左侧菜单）
include 'inc/header.inc.php'
?>

<div id="main">
		<div class="title">父板块列表</div>
		<form method="post">	
		<table class="list">
			<tr>
				<th>排序</th>	 	 	
				<th>版块名称</th>
				<th>操作</th>
			</tr>
			<?php
			$query="select * from lol_father_module";
			$result=execute($link, $query);
			while($data=mysqli_fetch_assoc($result)){
				//确认删除的地址，本身作为GET请求的值，所以进行编码
				$url=urlencode("father_module_delete.php?id={$data['id']}");
				//获取当前页面的地址 方便返回
				$return_url=urlencode($_SERVER['REQUEST_URI']);
				//获取当前所要删除的信息，本身作为GET请求的值，所以进行编码
				$message=urlencode("父板块-{$data['module_name']}");
				//提示确认删除的地址
				$delete_url="confirm.php?url={$url}&return_url={$return_url}&message={$message}";
$html=<<<BEGIN
			<tr>
				<td><input class="sort" type="text" name="sort[{$data['id']}]" value="{$data['sort']}" /></td>
				<td>{$data['module_name']}[id:{$data['id']}]</td>
				<td><a href="../list_father.php?id={$data['id']}">[访问]</a>&nbsp;&nbsp;<a href="father_module_update.php?id={$data['id']}">[编辑]</a>&nbsp;&nbsp;<a href="{$delete_url}">[删除]</a></td>
			</tr>
BEGIN;
			echo $html;
			}
			?>
		</table>
		<input style="margin-top:20px;cursor:pointer;" class="btn" type="submit" name="submit" value="排序" />
	</form>
	</div>



<?php
//引入页面页脚
include 'inc/footer.inc.php'
?>