<?php
session_id($_GET['sid']);//指定sessionid对话
session_start();
$str = "对不起，验证失败，请重新验证";
if(!isset($_SESSION["answer"])||!isset($_SESSION["pass"])||!isset($_POST['tans']))//如果没答案或者确认码、答案
{  
	$_SESSION["pass"] = 0;//验证失败
}
else
{
	$_SESSION['answer'] = strtolower($_SESSION['answer']);//把答案和用户的提交转换成小写
	$_POST['tans'] = strtolower($_POST['tans']);
	if($_POST['tans'] == $_SESSION['answer'])//如果回答正确
	{
		$_SESSION["pass"] = 1;//验证成功
		$str = "恭喜，验证成功！<br/>3秒后将自动关闭窗口，您也可以手动点击下方按钮关闭。";
		include("connection.php");
		$sql = "update base set count = count - 1 where ID = {$_GET['num']}";
		mysqli_query($link,$sql); //调用次数-1
	}
	else
	{
		$_SESSION["pass"] = 0;//验证失败
	}
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit"> 
<title>验证结果</title>
</head>
<body>
<form id="theform" action="result.php?sid=<?php echo $_GET['sid']; ?>" method="post">
	验证中，请稍后。。。
	<input type="hidden" id="tip" name="tip" value="<?php echo $str;?>"></input>
	<input type="hidden" id="result" name="result" value="<?php echo $_SESSION['pass'];?>"></input>
</form>
<script><?php 
	echo "document.getElementById('theform').submit();"//由于php的延迟问题，这个页面必须要提交，不然会出现加载不完这个页面导致后台验证结果不更新的问题，因此这个页面用来中转
	?></script>

</body>
</html>