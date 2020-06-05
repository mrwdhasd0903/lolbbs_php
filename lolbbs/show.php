<?php 
include_once 'inc/config.inc.php';
include_once 'inc/mysql.inc.php';
include_once 'inc/tool.inc.php';
include_once 'inc/page.inc.php';
$link=connect();
$member_id=is_login($link);
$is_manage_login=is_manage_login($link);
if(!isset($_GET['id']) || !is_numeric($_GET['id'])){
	skip('index.php', 'error', '帖子id参数不合法!');
}
//双表查询
$query="select sc.id cid,sc.module_id,sc.title,sc.content,sc.time,sc.member_id,sc.times,sm.name,sm.photo from lol_content sc,lol_member sm where sc.id={$_GET['id']} and sc.member_id=sm.id";
$result_content=execute($link,$query);
if(mysqli_num_rows($result_content)!=1){
	skip('index.php', 'error', '本帖子不存在!');
}
//查询阅读量times  并+1
$query="update lol_content set times=times+1 where id={$_GET['id']}";
execute($link,$query);
$data_content=mysqli_fetch_assoc($result_content);
//同步数据库的times
$data_content['times']=$data_content['times']+1;
//使用htmlspecialchars将内容原样输出，防止帖子内容成为html代码
$data_content['title']=htmlspecialchars($data_content['title']);
//nl2br是将帖子的回车符换成<br/>
$data_content['content']=nl2br(htmlspecialchars($data_content['content']));
$query="select * from lol_son_module where id={$data_content['module_id']}";
$result_son=execute($link,$query);
$data_son=mysqli_fetch_assoc($result_son);
//回复次数
$query="select count(*) from lol_reply where content_id={$_GET['id']}";
$count_reply=num($link, $query);

$query="select * from lol_father_module where id={$data_son['father_module_id']}";
$result_father=execute($link,$query);
$data_father=mysqli_fetch_assoc($result_father);

$template['title']=$data_content['title'];
$template['css']=array('style/public.css','style/show.css');
?>
<?php include 'inc/header.inc.php'?>
<div id="position" class="auto">
	<!--显示当前定位-->
	 <a href="index.php">首页</a> &gt; <a href="list_father.php?id=<?php echo $data_father['id']?>"><?php echo $data_father['module_name']?></a> &gt; <a href="list_son.php?id=<?php echo $data_son['id']?>"><?php echo $data_son['module_name']?></a> &gt; <?php echo $data_content['title']?>
</div>
<div id="main" class="auto">
	<div class="wrap1">
		<div class="pages">
			<?php 
			$query="select count(*) from lol_reply where content_id={$_GET['id']}";
			$count_reply=num($link, $query);
			//每页展示的回复数(不包括楼主),用于计算楼层
			$page_size=10;
			$page=page($count_reply,$page_size);
			echo $page['html'];
			?>
		</div>
		<a class="btn reply" href="reply.php?id=<?php echo $_GET['id']?>">回复</a>
		<div style="clear:both;"></div>
	</div>
	<?php 
	if($_GET['page']==1){
	?>
	<!--楼主(只在第一页显示,使用穿插交错写法判断)-->
	<div class="wrapContent">
		<div class="left">
			<div class="face">
				<a target="_blank" href="member.php?id=<?php echo $data_content['member_id']?>">
					<img width=100 height=100 src="<?php if($data_content['photo']!=''){echo $data_content['photo'];}else{echo 'style/photo.jpg';}?>" />
				</a>
			</div>
			<div class="name">
				<a href="member.php?id=<?php echo $data_content['member_id']?>"><?php echo $data_content['name']?></a>
			</div>
		</div>
		<div class="right">
			<div class="title">
				<h2><?php echo $data_content['title']?></h2>
				<span>阅读：<?php echo $data_content['times']?>&nbsp;|&nbsp;共<?php echo $count_reply?>条回复</span>
				<div style="clear:both;"></div>
			</div>
			<div class="pubdate">
				<span class="date">发布于：<?php echo $data_content['time']?> </span>
				<span class="floor" style="color:red;font-size:14px;font-weight:bold;">楼主</span>
			</div>
			<div class="content">
				 <?php echo $data_content['content']?>
			</div>
		</div>
		<div style="clear:both;"></div>
	</div>
	<?php }?>
	<!--其他楼(使用穿插交错写法遍历)-->
	<?php 
	$query="select sm.name,sr.member_id,sr.quote_id,sm.photo,sr.time,sr.id,sr.content from lol_reply sr,lol_member sm where sr.member_id=sm.id and sr.content_id={$_GET['id']} order by id asc {$page['limit']}";
	$result_reply=execute($link, $query);
	//计算出当前楼层
	$i=($_GET['page']-1)*$page_size+1;
	while ($data_reply=mysqli_fetch_assoc($result_reply)){
	$data_reply['content']=nl2br(htmlspecialchars($data_reply['content']));
	?>
	<div class="wrapContent">
		<div class="left">
			<div class="face">
				<a target="_blank" href="member.php?id=<?php echo $data_content['member_id']?>">
					<img width=100 height=100 src="<?php if($data_reply['photo']!=''){echo $data_reply['photo'];}else{echo 'style/photo.jpg';}?>" />
				</a>
			</div>
			<div class="name">
				<a href=""><?php echo $data_reply['name']?></a>
			</div>
		</div>
		<div class="right">
			
			<div class="pubdate">
				<span class="date">回复时间：<?php echo $data_reply['time']?></span>
				<span class="floor"><?php echo $i++?>楼&nbsp;|&nbsp;<a target="_blank" href="quote.php?id=<?php echo $_GET['id']?>&reply_id=<?php echo $data_reply['id']?>">引用</a></span>
			</div>
			<div class="content">
				<?php 
				//判断该楼的回复是不是引用回复,如果是,则多输出被引用的楼层
				if($data_reply['quote_id']){
				$query="select count(*) from lol_reply where content_id={$_GET['id']} and id<={$data_reply['quote_id']}";
				//floor是被引用的楼层数,i是当前楼层数!!
				$floor=num($link,$query);
				$query="select lol_reply.content,lol_member.name from lol_reply,lol_member where lol_reply.id={$data_reply['quote_id']} and lol_reply.content_id={$_GET['id']} and lol_reply.member_id=lol_member.id";
				$result_quote=execute($link,$query);
				$data_quote=mysqli_fetch_assoc($result_quote);
				?>
				
				<!--被引用的楼层信息 判断内容是否被删除-->
				<?php if(nl2br(htmlspecialchars($data_quote['content']))==''){?>
				<div class="quote"><h2 style="font-style: italic;">被引用的回复已删除</h2> </div>
				<?php }else{?>
				<div class="quote">
				<h2>引用 <?php echo $floor?>楼 <?php echo $data_quote['name']?> 发表的: </h2>
				<?php echo nl2br(htmlspecialchars($data_quote['content']))?>
				</div>
				
				<?php }?>
				
				<?php }?>
				<?php 
				echo $data_reply['content'];
				?>
			</div>
			<?php 
			//判断权限
			if(check_user($member_id,$data_reply['member_id'],$is_manage_login)){
				$return_url=urlencode($_SERVER['REQUEST_URI']);
				$url=urlencode("reply_delete.php?id={$data_reply['id']}&return_url={$return_url}");
				$message="你真的要删除回复 {$i}楼 吗？";
				$delete_url="confirm.php?url={$url}&return_url={$return_url}&message={$message}";
				echo "<a href='reply_update.php?id={$data_reply['id']}&return_url={$return_url}'>编辑</a> <a href='{$delete_url}'>删除</a>";
			}
			?>
		</div>
		<div style="clear:both;"></div>
	</div>
	<?php 
	}
	?>
	<div class="wrap1">
		<div class="pages">
			<?php 
			echo $page['html'];
			?>
		</div>
		<a class="btn reply" href="reply.php?id=<?php echo $_GET['id']?>">回复</a>
		<div style="clear:both;"></div>
	</div>
</div>
<?php include 'inc/footer.inc.php'?>