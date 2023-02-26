<?php
require 'page.php';
require 'config.php';

if(file_exists("install.php")){
	Header("Location: install.php");
	die();
}

$conn = mysqli_connect($mysql_host,$mysql_username,$mysql_password);
mysqli_query($conn,"set names utf8;");
mysqli_select_db($conn,$mysql_database);
$retval = mysqli_query($conn,"SELECT * FROM apidata");
if(!$retval){
	die('读取数据库失败');
}
$text = "";
$URL = 'http';
if(isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on"){
	$URL .= "s";
}
if($_SERVER['SERVER_PORT']!="80"){
	$URL .= "://".$server_name.":".$_SERVER['SERVER_PORT'];
}else{
	$URL .= "://".$server_name;
}
$apistatus = "";
while($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)){
	if($row['status']==1){
		$apistatus = "启用";
	}
	if($row['status']==0){
		$apistatus = "禁用";
	}
	$text .= "<tr><td><p>api名：".$row['name']."</p><p>api介绍：<br>".str_replace("\n","<br>",$row['des'])."</p><p>api调用地址：".$URL."/api.php?apiid=".$row['apiid']."</p><p>使用次数：".$row['used']."</p><p>状态：".$apistatus."</p></td></tr>";
}
unset($apistatus);
echo showpage("<table border=\"1\">".$text."</table>");
?>
