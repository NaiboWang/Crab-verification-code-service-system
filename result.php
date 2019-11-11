<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit"> 
<title>验证结果</title>
</head>
<body>
<link href="css/buttons.css" rel="stylesheet">
<div align="center">
<h1><?php echo $_POST['tip'];?></h1>
<?php
	if($_POST['result'] == 0){
?>
<a href="pluginpre.php?sid=<?php echo $_GET['sid']; ?>" class="button button-royal button-rounded button-large" style=" margin-left: 20px">重新验证</a>
<?php }else{?>
<a href="" onClick="javascript:window.close()" class="button button-royal button-rounded button-large" style=" margin-left: 20px">关闭窗口</a>
<script>
setTimeout(function(){window.close()},3000);//三秒后自动关闭
	</script>
<?php }?>
</div>
</body>
</html>