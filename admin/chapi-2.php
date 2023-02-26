<?php
$retval = mysqli_query($conn,"SELECT * FROM apidata");
if(!$retval){
	die("数据库操作失败");
}
$apidata = array("name"=>"","des"=>"","apiid"=>"","apimain"=>"");
$status = false;
$opapi = "";
$clapi = "";
while($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)){
	if($row['id']==$_GET['id']){
		$status = true;
		$apidata['name'] = $row['name'];
		$apidata['des'] = $row['des'];
		$apidata['apiid'] = $row['apiid'];
		$file = fopen("../data/".md5($apidata['apiid']).".php","r");
		$apidata['apimain'] = fread($file,filesize("../data/".md5($apidata['apiid']).".php"));
		fclose($file);
		if($row['status']==0){
			$clapi = " checked=\"checked\"";
		}
		if($row['status']==1){
			$opapi = " checked=\"checked\"";
		}
	}
}
if($status == false){
	echo 'id"'.$_GET['id'].'"不存在<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/?action=chapi"},2000)</script>';
	die();
}
echo showpage('<center><h1>更改api</h1></center>
<form method="post" action="/admin/">
<input type="hidden" name="action" value="chapi">
<input type="hidden" name="id" value="'.$_GET['id'].'">
<p>api名：<input type="text" name="apiname" maxlength="256" value="'.$apidata['name'].'" required></p>
<p>api介绍：<br><textarea cols="32" rows="6" name="apides" required>'.$apidata['des'].'</textarea></p>
<p>api标识（用于api调用）：<input type="text" name="apiid" maxlength="256" value="'.$apidata['apiid'].'" required></p>
<p>api主体（使用php写）：<br><textarea cols="32" rows="6" name="apimain" required>'.$apidata['apimain'].'</textarea></p>
<p><input type="checkbox" name="delete" value="yes">删除这个api</p>
<p><input type="radio" name="apistatus" value="1"'.$opapi.'>启用api</p>
<p><input type="radio" name="apistatus" value="0"'.$clapi.'>禁用api</p>
<p><input type="checkbox" name="usedtozero" value="yes">将已使用次数清零</p>
<p><input type="submit"></p>
</form>');
?>
