<?php
require 'page.php';
echo showpage('<center><h1>添加api</h1></center>
<form method="post" action="/admin/">
<input type="hidden" name="action" value="addapi">
<p>api名：<input type="text" name="apiname" maxlength="256" required></p>
<p>api介绍：<br><textarea cols="32" rows="6" name="apides" required></textarea></p>
<p>api标识（用于api调用）：<input type="text" name="apiid" maxlength="256" required></p>
<p>api主体（使用php写）：<br><textarea cols="32" rows="6" name="apimain" required>
<?php
?>
</textarea></p>
<p><input type="submit"></p>
</form>');
?>
