<?php
function showpage($text = ""){
$page = '<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="template.css">
<meta charset="UTF-8">
<title>api管理</title>
</head>
<body>
<div class="bg-img"></div>
<header>欢迎使用api网站 | 后台管理</header>
<main>
<center><p>| <a href="/admin/?action=chpasswd">更改当前用户密码</a> | <a href="/admin/?action=logout">登出</a> | <a href="/admin/?action=addapi">添加api</a> | <a href="/admin/?action=chapi">更改api</a> |</p></center>
'.$text.'</main>
<footer>
<center><p>Copyright © 2023,By Tzdtwsj</p></center>
 </footer>
</body>
</html>';
return $page;
}
?>
