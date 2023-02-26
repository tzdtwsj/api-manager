<?php
if(isset($_COOKIE['token'])){
	setcookie("token", "" , time()-1);
	setcookie("adminname", "" , time()-1);
	echo '登出成功<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/"},2000)</script>';
}else{
	setcookie("adminname", "" , time()-1);
	echo '你都没登录你登出个什么嘛<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/"},2000)</script>';
}
?>
