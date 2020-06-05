<?php
//数据库连接
function connect($host=DB_HOST,$user=DB_USER,$password=DB_PASSWORD,$database=DB_DATABASE,$port=DB_PORT){
	$link=@mysqli_connect($host,$user,$password,$database,$port);
	//输出错误
	if(mysqli_connect_errno()){
		exit(mysqli_connect_error());
	};
	//设置字符
	mysqli_set_charset($link,'utf8');
	return $link;
}
//执行sql语句 返回对象值 或者 布尔值
function execute($link,$query){
	$result=mysqli_query($link, $query);
	if(mysqli_errno($link)){
		exit(mysqli_error($link));
	}
	return $result;
}
//执行sql语句  只返回  布尔值
function execute_bool($link,$query){
	$bool=mysqli_real_query($link, $query);
	if(mysqli_errno($link)){
		exit(mysqli_error($link));
	}
	return $bool;
}
//执行多条sql语句 
//使用案例：
//$arr_sqls=array(
//	'select * from lol_father_module',
//	'select * from lol_father_module',
//	'select * from lol_father_module',
//	'select * from lol_father_module'
//);
//var_dump(execute_multi($link, $arr_sqls,$error));
//echo $error;
// 参数1:连接 参数2:多条sql组成的数组 参数3:传入一个变量指针，存储语句执行的错误信息
function execute_multi($link,$arr_sqls,&$error){
	//数组合并字符串
	$sqls=implode(';',$arr_sqls).';';
	if(mysqli_multi_query($link,$sqls)){
		$data=array();
		$i=0;//计数
		//循环执行
		do {
			if($result=mysqli_store_result($link)){
				$data[$i]=mysqli_fetch_all($result);
				mysqli_free_result($result);
			}else{
				$data[$i]=null;
			}
			$i++;
			if(!mysqli_more_results($link)) break;
		}while (mysqli_next_result($link));
		//顺利执完成时返回数据
		if($i==count($arr_sqls)){
			return $data;
		}
		//否则赋值错误信息 并返回false
		else{
			$error="sql语句执行失败：<br />&nbsp;数组下标为{$i}的语句:{$arr_sqls[$i]}执行错误<br />&nbsp;错误原因：".mysqli_error($link);
			return false;
		}
	}else{
		$error='执行失败！请检查首条语句是否正确！<br />可能的错误原因：'.mysqli_error($link);
		return false;
	}
}
//获取表的记录数
function num($link,$link_count){
	$result=execute($link, $link_count);
	$count=mysqli_fetch_row($result);
	return $count[0];
}
//数据入库前对数据进行拆分、确保数据安全入库
function escape($link,$data){
	//如果是字符串
	if(is_string($data)){
		return mysqli_real_escape_string($link, $data);
	}
	//如果不是字符串->递归分解
	if(is_array($data)){
		foreach($data as $key=>$val){
			$data[$key]=escape($link, $val);
		}
	}
	return $data;
}
//关闭连接
function close(&$link){
	mysqli_close($link);
}
?>