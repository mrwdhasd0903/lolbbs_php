<?php 
if(empty($_POST['module_id']) || !is_numeric($_POST['module_id'])){
	skip('publish.php', 'error', '所属版块id不合法！');
}
$query="select * from lol_son_module where id={$_POST['module_id']}";
$result=execute($link, $query);
if(mysqli_num_rows($result)!=1){
	skip('publish.php', 'error', '请选择所属板块');
}
if(empty($_POST['title'])){
	skip('publish.php', 'error', '标题不得为空！');
}
//学校机房的php默认配置用不了mb_strlen函数，所以注释了
//if(mb_strlen($_POST['title'])>255){
//	skip('publish.php', 'error', '标题不得超过255个字符！');
//}
?>