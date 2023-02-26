<?php
require 'page.php';
echo showpage('<p>当前用户名：'.$_COOKIE['adminname'].'</p>
<form action="/admin/" method="post">
<input type="hidden" name="action" value="chpasswd">
<p>新密码：<input type="password" name="newpasswd" maxlength="256" required></p>
<p>确认密码：<input type="password" name="newpasswd2" maxlength="256" required></p>
<p><input type="submit"></p>
</form>');
?>
