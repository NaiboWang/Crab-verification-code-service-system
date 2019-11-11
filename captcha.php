<?php
//生成验证码的页面
session_id($_GET['sid']);
session_start();
require './ValidateCode.class.php';  //先把类包含进来，实际路径根据实际情况进行修改。
$_vc = new ValidateCode();  //实例化一个对象
$_vc->doimg();
if($_GET['type'] == 0)
{
	$_SESSION['authnum_session'][$_GET['num']] = $_vc->getCode();//验证码添加保存到SESSION中，指定num
}
	
?>