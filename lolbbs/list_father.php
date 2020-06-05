<?php 
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/page.inc.php';
$link=connect();
$member_id=is_login($link);
//是否管理员登陆
$is_manage_login=is_manage_login($link);
//防止直接进入此页
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
	skip('index.php', 'error', '父版块id参数不合法!');
}
//对比数据库，防篡改
$query="select * from lol_father_module where id={$_GET['id']}";
$result_father=execute($link, $query);
if(mysqli_num_rows($result_father)==0){
	skip('index.php', 'error', '父版块不存在!');
}
$data_father=mysqli_fetch_assoc($result_father);

$query="select * from lol_son_module where father_module_id={$_GET['id']}";
$result_son=execute($link,$query);
$id_son='';
$son_list='';
while($data_son=mysqli_fetch_assoc($result_son)){
	$id_son.=$data_son['id'].',';
	$son_list.="<a href='list_son.php?id={$data_son['id']}'>{$data_son['module_name']}</a> ";
}
$id_son=trim($id_son,',');
//如果没有子版块为空的话，就赋值-1，不然下面的sql语句会报错
if($id_son==''){
	$id_son='-1';
}
//计算总帖子数
$query="select count(*) from lol_content where module_id in({$id_son})";
$count_all=num($link,$query);
//计算今天贴子数
$query="select count(*) from lol_content where module_id in({$id_son}) and time>CURDATE()";
$count_today=num($link,$query);

$template['title']= $data_father['module_name'];
$template['css']=array('style/public.css','style/list.css');
?>
<?php include 'inc/header.inc.php'?>
<div id="position" class="auto">
	 <a href="index.php">首页</a> &gt;<?php echo $data_father['module_name']?></a>
</div>
<div id="main" class="auto">
	<div id="left">
		<div class="box_wrap">
			<h3><?php echo $data_father['module_name']?></h3>
			<div class="num">
			    今日：<span><?php echo $count_today?></span>&nbsp;&nbsp;&nbsp;
			    总帖：<span><?php echo $count_all?></span>
			  <div class="moderator"> 子版块：  <?php echo $son_list?></div>
			</div>
			<div class="pages_wrap">
				<a class="btn publish" href="publish.php?father_module_id=<?php echo $_GET['id']?>" target="_blank">发帖</a>
				<div class="pages">
					<?php 
					$page=page($count_all,10);
					echo $page['html'];
					?>
				</div>
				<div style="clear:both;"></div>
			</div>
		</div>
		<div style="clear:both;"></div>
		<ul class="postsList">
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
			//多表查询语句 按照时间倒叙
			$query="select 
lol_content.title,lol_content.id,lol_content.time,lol_content.times,lol_content.member_id,lol_member.name,lol_member.photo,lol_son_module.module_name,lol_son_module.id ssm_id 
from lol_content,lol_member,lol_son_module where 
lol_content.module_id in ({$id_son}) and 
lol_content.member_id=lol_member.id and 
lol_content.module_id=lol_son_module.id ORDER BY lol_content.time DESC {$page['limit']}";
			$result_content=execute($link,$query);
			while($data_content=mysqli_fetch_assoc($result_content)){
			//使用htmlspecialchars将内容原样输出，防止帖子内容成为html代码	
			$data_content['title']=htmlspecialchars($data_content['title']);
			//获取帖子的最后回复时间
			$query="select time from lol_reply where content_id={$data_content['id']} order by id desc limit 1";
			$result_last_reply=execute($link, $query);
			if(mysqli_num_rows($result_last_reply)==0){
				$last_time='暂无回复';
			}else{
				$data_last_reply=mysqli_fetch_assoc($result_last_reply);
				$last_time=$data_last_reply['time'];
			}
			$query="select count(*) from lol_reply where content_id={$data_content['id']}";
			?>
			<li>
				<div class="smallPic">
					<a target="_blank" href="member.php?id=<?php echo $data_content['member_id']?>">
						<img width="45" height="45"src="<?php if($data_content['photo']!=''){echo $data_content['photo'];}else{echo 'style/photo.jpg';}?>">
					</a>
				</div>
				<div class="subject">
					<div class="titleWrap"><a href="list_son.php?id=<?php echo$data_content['ssm_id']?>">[<?php echo $data_content['module_name']?>]</a>&nbsp;&nbsp;<h2><a target="_blank" href="show.php?id=<?php echo $data_content['id']?>"><?php echo $data_content['title']?></a></h2></div>
					<p>
						楼主：<?php echo $data_content['name']?>&nbsp;<?php echo $data_content['time']?>&nbsp;&nbsp;&nbsp;&nbsp;最后回复：<?php echo $last_time?><br />
						<?php 
						//如果有权限
						if(check_user($member_id,$data_content['member_id'],$is_manage_login)){
							$return_url=urlencode($_SERVER['REQUEST_URI']);
							$url=urlencode("content_delete.php?id={$data_content['id']}&return_url={$return_url}");
							$message="你真的要删除帖子 {$data_content['title']} 吗？";
							$delete_url="confirm.php?url={$url}&return_url={$return_url}&message={$message}";
							echo "<a href='content_update.php?id={$data_content['id']}&return_url={$return_url}'>编辑</a> <a href='{$delete_url}'>删除</a>";
						}
						?>
					</p>
				</div>
				<div class="count">
					<p>
						回复<br /><span><?php echo num($link,$query)?></span>
					</p>
					<p>
						浏览<br /><span><?php echo $data_content['times']?></span>
					</p>
				</div>
				<div style="clear:both;"></div>
			</li>
			<?php 
			}
			?>
		</ul>
		<div class="pages_wrap">
			<a class="btn publish" href="publish.php?father_module_id=<?php echo $_GET['id']?>" target="_blank">发帖</a>
			<div class="pages">
				<?php 
				echo $page['html'];
				?>
			</div>
			<div style="clear:both;"></div>
		</div>
	</div>
	<!--右侧板块列表-->
	<div id="right">
		<div class="classList">
			<div class="title">板块列表</div>
			<ul class="listWrap">
				<?php 
				$query="select * from lol_father_module";
				$result_father=execute($link, $query);
				while($data_father=mysqli_fetch_assoc($result_father)){
				?>
				<li>
					<h2><a href="list_father.php?id=<?php echo $data_father['id']?>"><?php echo $data_father['module_name']?></a></h2>
					<ul>
						<?php 
						$query="select * from lol_son_module where father_module_id={$data_father['id']}";
						$result_son=execute($link, $query);
						while($data_son=mysqli_fetch_assoc($result_son)){
						?>
						<li><h3><a href="list_son.php?id=<?php echo $data_son['id']?>"><?php echo $data_son['module_name']?></a></h3></li>
						<?php 
						}
						?>
					</ul>
				</li>
				<?php 
				}
				?>
			</ul>
		</div>
	</div>
	<div style="clear:both;"></div>
</div>
<?php include 'inc/footer.inc.php'?>