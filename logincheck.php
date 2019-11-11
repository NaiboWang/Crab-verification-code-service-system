<?php
session_start();//处理验证页面的类，若没有登录，直接退出。
if(!isset($_SESSION['yy']))
   $_SESSION['yy'] = 0;
if($_SESSION['yy'] == 0)
{
	echo "<script> alert('Wrong login information!'); window.location.href='login.php' </script>"; 
	exit(0);
}
?>