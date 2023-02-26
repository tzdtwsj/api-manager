<?php
function showpage($text = ""){
$page = '<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta charset="UTF-8">
<link rel="stylesheet" href="template.css">
<title>api网站</title>
</head>
<body>
<div class="bg-img"></div>
<header>欢迎使用api网站</header>
<main>'.$text.'</main>
<footer>
<center><p>Copyright © 2023,By Tzdtwsj</p></center>
 </footer>
</body>
</html>';
return $page;
}
?>
