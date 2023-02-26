<?php
require 'page.php';
if(isset($_GET['id'])){
	require 'chapi-2.php';
	die();
}
$retval = mysqli_query($conn,"SELECT * FROM apidata;");
if(!$retval){
	die("读取数据库失败");
}
$text = "";
$apistatus = "";
while($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)){
	if($row['status']==1){
		$apistatus = "启用";
	}
	if($row['status']==0){
		$apistatus = "禁用";
	}
	$text .= "<tr><td>".$row['name']."</td><td>".str_replace("\n","<br>",$row['des'])."</td><td>".$row['apiid']."</td><td>".$apistatus."</td><td>".$row['used']."</td><td><a href=\"/admin/?action=chapi&id=".$row['id']."\">编辑</a></td></tr>";
}
unset($apistatus);
echo showpage('<center>
<table border="1">
<caption><h1>api列表</h1></caption>
<tr><th>api名</th><th>api简介</th><th>api标识</th><th>状态</th><th>使用次数</th><th>操作</th></tr>
'.$text.'
</table>
</center>
');
?>
