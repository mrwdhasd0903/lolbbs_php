<?php
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
$link=connect();
$member_id=is_login($link);


$template['title']='首页';
$template['css']=array('style/public.css','style/index.css');
?>
<?php include 'inc/header.inc.php'?>
<!--热门热门热门热门热门热门热门-->
<div id="hot" class="auto">
	<div class="title">今日热门</div>
	<ul class="newlist">
		<li><a href="#">[未完成]</a> <a >（此处展示一些热门帖子）</a></li>
	</ul>
	<div style="clear:both;"></div>
</div>
<!--以下使用穿插交错写法：html和php交错形成的php代码
	作者：王达浩
	时间：2019-12-19
	描述：如果语句在{ }内，即使在<?php ?>之外也能执行
<?php if(10>100){?>
	<p>我是表达式成立执行的代码</p>
<?php }else{?>
	<p>我是表达式不成立执行的代码</p>
<?php }?>
-->

<?php 
$query="select * from lol_father_module order by sort desc";
$result_father=execute($link, $query);
//循环开始
while($data_father=mysqli_fetch_assoc($result_father)){
?>
<div class="box auto">
	<div class="title">
		<!--传id跳转到父板块-->
		<a href="list_father.php?id=<?php echo $data_father['id']?>" style="color:#105cb6;"><?php echo $data_father['module_name']?></a>
	</div>
	<div class="classList">
		<?php 
		$query="select * from lol_son_module where father_module_id={$data_father['id']}";
		$result_son=execute($link, $query);
		if(mysqli_num_rows($result_son)){
			while ($data_son=mysqli_fetch_assoc($result_son)){
				$query="select count(*) from lol_content where module_id={$data_son['id']} and time > CURDATE()";
				$count_today=num($link,$query);
				$query="select count(*) from lol_content where module_id={$data_son['id']}";
				$count_all=num($link,$query);
				$html=<<<A
					<div class="childBox new">
						<h2><a href="list_son.php?id={$data_son['id']}">{$data_son['module_name']}</a> <span>(今日{$count_today})</span></h2>
						帖子：{$count_all}<br />
					</div>
A;
				echo $html;
			}
		}else{
			echo '<div style="padding:10px 0;">暂无子版块...</div>';
		}
		?>
		<div style="clear:both;"></div>
	</div>
</div>
<?php }?>
<!--循环结束-->
<?php include 'inc/footer.inc.php'?>