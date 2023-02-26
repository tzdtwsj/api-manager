<?php
if(isset($_POST['action'])==false){
echo '<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>安装api管理器</title>';
echo '<h1>欢迎使用api管理器</h1>';
echo '<p>项目使用GPL3许可证</p>';
echo '<p>开源地址：<a href="https://github.com/tzdtwsj/api-manager">https://github.com/tzdtwsj/api-manager</a></p>';
echo '<form action="install.php" method="post">
<input type="hidden" name="action" value="install">
<p>mysql服务器地址：<input type="input" name="mysql_host" value="127.0.0.1:3306" required></p>
<p>mysql用户名：<input type="input" name="mysql_username" value="root" required></p>
<p>mysql密码：<input type="input" name="mysql_password"></p>
<p>mysql数据库名：<input type="input" name="mysql_database" required></p>
<p>网站域名/ip：<input type="input" name="server_name" required></p>
<br>
<p>管理员用户名：<input type="text" name="adminname" value="admin" required></p>
<p>管理员密码：<input type="password" name="password" required></p>
<input type="submit">
</form>';
}else{
	if($_POST['action']=="install"){
		$mysql_host = $_POST['mysql_host'];
		$mysql_username = $_POST['mysql_username'];
		$mysql_password = $_POST['mysql_password'];
		$mysql_database = $_POST['mysql_database'];
		$server_name = $_POST['server_name'];
		$admin_name = $_POST['adminname'];
		$admin_password = $_POST['password'];
		$config = '<?php
$mysql_host = "'.$mysql_host.'";
$mysql_username = "'.$mysql_username.'";
$mysql_password = "'.$mysql_password.'";
$mysql_database = "'.$mysql_database.'";

$server_name = "'.$server_name.'";
?>
';
		unlink("config.php");
		$file = fopen("config.php","w");
		fwrite($file,$config);
		fclose($file);
		$conn = mysqli_connect($mysql_host,$mysql_username,$mysql_password);
		if(!$conn){
			echo '安装失败：'.mysqli_error($conn);
			unlink("config.php");
			die();
		}
		mysqli_query($conn,"set names utf8;");
		mysqli_select_db($conn,$mysql_database);
		$retval = mysqli_query($conn,"CREATE TABLE IF NOT EXISTS apidata( id INT UNSIGNED AUTO_INCREMENT, name VARCHAR(256) NOT NULL, des VARCHAR(256) NOT NULL, apiid VARCHAR(256) NOT NULL, used INT UNSIGNED,status INT UNSIGNED,PRIMARY KEY ( id ) )ENGINE=InnoDB CHARSET=utf8;");
		if(!$retval){
			unlink("config.php");
			die("mysql语句执行失败！".mysqli_error($conn));
		}
		$retval = mysqli_query($conn,"CREATE TABLE IF NOT EXISTS admin_userdata(username VARCHAR(256) NOT NULL, password VARCHAR(256) NOT NULL,PRIMARY KEY ( username ) )ENGINE=InnoDB CHARSET=utf8;");
		if(!$retval){
			unlink("config.php");
			die("mysql语句执行失败！".mysqli_error($conn));
		}
		mysqli_query($conn,"INSERT INTO admin_userdata(username,password)VALUES('".$admin_name."','".md5($admin_password)."');");
		echo '<p>安装成功</p><p><a href="/admin/">管理员登录</a></p><p><a href="/">主页</a></p>';
		unlink("install.php");
	}
}
?>
