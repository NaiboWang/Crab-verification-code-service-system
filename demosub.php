<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit"> 
<link href="css/buttons.css" rel="stylesheet">
<title>验证demo</title>
</head>

<body>
<div align="center">
	<p>
  <?php
$url='http://localhost/getcaptcha.php?type=2&sid='.$_POST['vcaptcha']; 
$html = file_get_contents($url);//得到后台的验证结果
if($html == 1)
{
	echo "验证成功";
}
	else
	{
		echo "验证失败";
	}
?>
</p>
<p>&nbsp; </p>
<a href="demo.html"><button class="button button-royal button-rounded button-large">返回</button></a>
</div>
</body>
</html>