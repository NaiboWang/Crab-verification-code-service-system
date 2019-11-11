<?php
//检查用户用户名密码是否正确的页面
session_start();
include("connection.php");
$sql = "select * from user where username = ".$_POST['username'];
$result = mysqli_query($link,$sql); 
$_SESSION['yy'] = 0;
$rst = @mysqli_fetch_array($result) or die("<script> alert('用户名或密码错误！'); window.location.href='login.php' </script>");//若用户名不存在，报错返回
	$pass = $rst['password'];//取出密码
	if($pass == md5($_POST['password']))
		$_SESSION['yy'] = 1;

if($_SESSION['yy'] == 0)
{
	echo "<script> alert('用户名或密码错误！'); window.location.href='login.php' </script>"; 
}
else
{
	echo "<script> window.location.href='deallist.php' </script>"; 
}


?>