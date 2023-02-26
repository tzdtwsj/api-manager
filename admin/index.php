<?php
require '../config.php';
	if(isset($_COOKIE['token'])==false){
		if(isset($_POST['username'])==true&&isset($_POST['password'])==true){
			$username = $_POST['username'];
			$password = md5($_POST['password']);
			$conn = mysqli_connect($mysql_host, $mysql_username, $mysql_password);
			if(! $conn){
				die('连接mysql服务器错误'/* . mysqli_error($conn)*/);
			}
			mysqli_select_db($conn, $mysql_database);
			mysqli_query($conn , "set names utf8");
			$retval = mysqli_query($conn, "SELECT * FROM admin_userdata");
			if(! $retval){
				die("操作失败！");
			}
			$status = false;
			while($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)){
				if($row['username']==$username&&$row['password']==$password){
					$status = true;
				}
			}
			if($status){
				setcookie("token", md5($username).$password, time()+86400);
				setcookie("adminname", $username, time()+86400);
				echo '登录成功，请等待几秒钟<script>setTimeout(function(){location.href="/admin/"},3000)</script><meta name="viewport" content="width=device-width,initial-scale=1">';
			}else{
				echo '用户名或密码错误<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/"},2000)</script>';
			}
			mysqli_close($conn);
		}else{
			require 'login_panel.php';
		}
	}else{
		$conn = mysqli_connect($mysql_host, $mysql_username, $mysql_password);
		if(!$conn){
			die("数据库连接失败");
		}
		mysqli_select_db($conn, $mysql_database);
		mysqli_query($conn , "set names utf8");
		$retval = mysqli_query($conn, "SELECT * FROM admin_userdata;");
		if(!$retval){
			die("数据库操作失败");
		}
		$status = false;
		while($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)){
			if(md5($row['username']).$row['password']==$_COOKIE['token']&&$row['username']==$_COOKIE['adminname']){
				$status = true;
			}
		}
		if($status==false){
		    setcookie("token", "" , time()-1);
			setcookie("adminname", "" , time()-1);
			echo 'token验证失败<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/"},2000)</script>';
			die();
		}
		unset($status);
		
		if(isset($_GET['action'])){
			if($_GET['action']=="logout"){
				require 'logout.php';
			}
			if($_GET['action']=="chpasswd"){
				require 'chpasswd.php';
			}
			if($_GET['action']=="addapi"){
				require 'addapi.php';
			}
			if($_GET['action']=="chapi"){
				require 'chapi.php';
			}
		}else if(isset($_POST['action'])){
			if($_POST['action']=="chpasswd"){
				if($_POST['newpasswd']==$_POST['newpasswd2']){
					$retval = mysqli_query($conn, "UPDATE admin_userdata SET password=\"".md5($_POST['newpasswd'])."\" WHERE username=\"".$_COOKIE['adminname']."\"");
					if(!$retval){
						die('数据库操作失败<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/?action=chpasswd"},2000)</script>');
					}
					setcookie("token", md5($_COOKIE['adminname']).md5($_POST['newpasswd']), time()+86400);
					echo '密码更改成功<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/?action=chpasswd"},2000)</script>';
				}else{
					echo '更改失败：新密码与确认密码不相同<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/?action=chpasswd"},2000)</script>';
				}
			}
			if($_POST['action']=="addapi"){
				$apiname = $_POST['apiname'];
				$apides = $_POST['apides'];
				$apiid = $_POST['apiid'];
				$apimain = $_POST['apimain'];
				$retval = mysqli_query($conn,"SELECT * FROM apidata;");
				if(!$retval){
					die('读取数据库失败!<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/?action=addapi"},2000)</script>');
				}
				while($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)){
					if($row['apiid']==$apiid){
						die('api标识"'.$apiname.'"已存在<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/?action=addapi"},2000)</script>');
					}
				}
				$retval = mysqli_query($conn,"INSERT INTO apidata(
				name,des,apiid,used,status
				)VALUES(
				'".$apiname."','".$apides."','".$apiid."',0,1);");

				if(!$retval){
					die('数据插入失败！<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/?action=addapi"},2000)</script>');
				}
				$filename = md5($apiid).".php";
				$file = fopen("../data/".$filename,"w");
				fwrite($file,$apimain);
				fclose($file);
				echo '<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/?action=addapi"},2000)</script>添加api成功！';
			}
			if($_POST['action']=="chapi"){
				$id = $_POST['id'];
				$apiname = $_POST['apiname'];
				$apides = $_POST['apides'];
				$apiid = $_POST['apiid'];
				$apimain = $_POST['apimain'];
				$oldapiiid = "";
				$apistatus = $_POST['apistatus'];
				$retval = mysqli_query($conn,"SELECT * FROM apidata WHERE id=".$id.";");
				if(!$retval){
					die('读取数据库失败<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/?action=chapi"},2000)</script>');
				}
				while($row = mysqli_fetch_array($retval, MYSQLI_ASSOC)){
					if($row['id']==$id){
						$oldapiid = $row['apiid'];
					}
				}
				if(isset($_POST['delete'])){
					if($_POST['delete']=="yes"){
						$retval = mysqli_query($conn, "DELETE FROM apidata WHERE id=".$id.";");
						if(!$retval){
							die('更新数据库失败<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/?action=chapi"},2000)</script>');
						}
						unlink("../data/".md5($oldapiid).".php");
						echo '删除api成功<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/?action=chapi"},2000)</script>';
						die();
					}
				}
				$usedtozero = "";
				if(isset($_POST['usedtozero'])){
					if($_POST['usedtozero']=="yes"){
						$usedtozero = ",used=0";
					}
				}
				$retval = mysqli_query($conn,"UPDATE apidata SET name='".$apiname."',des='".$apides."',apiid='".$apiid."',status=".$apistatus.$usedtozero." WHERE id=".$id.";");
				if(!$retval){
					die('更新数据库失败<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/?action=chapi"},2000)</script>');
				}
				unlink("../data/".md5($oldapiid).".php");
				$file = fopen("../data/".md5($apiid).".php","w");
				fwrite($file,$apimain);
				fclose($file);
				echo '更新api成功<meta name="viewport" content="width=device-width,initial-scale=1"><script>setTimeout(function(){location.href="/admin/?action=chapi"},2000)</script>';
			}
		}else{
			require 'page.php';
			echo showpage();
		}
		mysqli_close($conn);
	}
?>
