<?php
header("Content-type:text/html;charset=utf-8");
$url='http://captcha.three-thinking.com/1.txt'; 
$html = file_get_contents($url); 
if($html == 0)
{
	echo "unauthorized";
	exit(0);
}
$mysql_server_name='localhost'; //改成自己的mysql数据库服务器
$mysql_username='root'; //改成自己的mysql数据库用户名
$mysql_password='q982311099'; //改成自己的mysql数据库密码
$mysql_database='captcha'; //改成自己的mysql数据库名
$link = mysqli_connect($mysql_server_name,$mysql_username,$mysql_password,$mysql_database);
mysqli_query($link,"set names 'utf8'"); //数据库输出编码 应该与你的数据库编码保持一致.
?>