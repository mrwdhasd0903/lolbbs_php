<?php 
//进行删除操作时显示此页确认
include_once 'inc/config.inc.php';
//防止直接进入此页
$_GET['message']=htmlspecialchars($_GET['message']);
if(!isset($_GET['message']) || !isset($_GET['url']) || !isset($_GET['return_url'])){
	exit();
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8" />
<title>确认页</title>
<meta name="keywords" content="确认页" />
<meta name="description" content="确认页" />
<link rel="stylesheet" type="text/css" href="style/remind.css" />
</head>
<body>
<div class="notice">
	<span class="pic ask"></span> 
	<?php echo $_GET['message']?>
		<a href="<?php echo $_GET['url']?>">
			<button type="button">确定</button>
		</a>
		<a href="<?php echo $_GET['return_url']?>">
			<button type="button">取消</button>
		</a>
</div>
</body>
</html>