<?php
require 'config.php';
if(isset($_GET['apiid'])){
	$conn = mysqli_connect($mysql_host,$mysql_username,$mysql_password);
	mysqli_select_db($conn,$mysql_database);
	if(!$conn){
		die("连接数据库失败");
	}
	mysqli_query($conn,"set charset utf8;");
	$retval = mysqli_query($conn,"SELECT * FROM apidata");
	$status = false;
	$used = 0;
	$apistatus = 0;
	while($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)){
		if($row['apiid']==$_GET['apiid']){
			$status = true;
			$used = $row['used'];
			$apistatus = $row['status'];
		}
	}
	if($status==true){
		if($apistatus==1){
		mysqli_query($conn,"UPDATE apidata SET used=".$used+1 ." WHERE apiid='".$_GET['apiid']."';");
		require 'data/'.md5($_GET['apiid']).'.php';
		}
		if($apistatus==0){
			die("api已被禁用");
		}
	}else{
		echo 'apiid不存在';
	}
	mysqli_close($conn);
}else{
	echo '请使用GET方式给此路径传apiid';
}
?>
