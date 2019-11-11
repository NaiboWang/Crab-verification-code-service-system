<?php
session_id($_GET['sid']);//指定sessionid对话
session_start();//生成验证码之前的预处理
include("connection.php");
if(!isset($_SESSION['url']))//没有指定url，给一个随机url
	$_SESSION['url'] = "qwewqer";
$crand = array();
$cid = 0;
$sql = "select * from base where count > 0 and link like '%{$_SESSION['url']}%'";//寻找特定网站的图片验证码
$result = mysqli_query($link,$sql); 
while($rst = mysqli_fetch_array($result))
{
	$crand[$cid++] = $rst['ID'];//依次记录有多少项
}
$trid = 0;//定义一个空的图片
if($cid > 0)
{
	$trid = rand(0,$cid - 1);
	$trid = $crand[$trid];
}
$sql = "select * from base where ID = ".$trid;
$result = mysqli_query($link,$sql); 
$rst = mysqli_fetch_array($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit"> 
<title>验证码模块</title>
</head> 
<script type="text/javascript" src="js/jquery.js"></script>
<script src="js/display.js"></script>
<body>
<form  method="post" action="plugin.php?num=<?php echo $trid;?>&sid=<?php echo $_GET['sid'];?>" id="theform" name="theform">
<input type="hidden" name="numrand" id="numrand" value = '0'/>
</form>
<script>
	var id = 0;//记录有多少项
	function getBase64Image(img) {//img转base64
			var canvas = document.createElement("canvas");
			canvas.width = img.width;
			canvas.height = img.height;
			var ctx = canvas.getContext("2d");
			ctx.drawImage(img, 0, 0, img.width, img.height);
			var dataURL = canvas.toDataURL("image/png");
           return dataURL; // return dataURL.replace("data:image/png;base64,", ""); 
        } 
<?php
	$id = 0;
	$sql = "select count(*) as num from extended where BID = ".$trid;
	$result = mysqli_query($link,$sql);
	$rst = mysqli_fetch_array($result);
	$randnum = 1;//干扰图片数量
	$sum = 0;
	if($cid >0)
		$sum = $rst['num'] + $randnum;//计算验证码总数
	echo "var ids = {$sum};\n";//记录图片内容
	$sql = "select * from extended where BID = ".$trid." order by priority desc,TID asc";
	$result = mysqli_query($link,$sql);
	while($rst = mysqli_fetch_array($result))//有多少项，生成多少个验证码
	{
		echo "var img".$id."=new Image();\n";
		echo "img".$id.".src ='captcha.php?type=0&sid={$_GET['sid']}&num={$id}&'+Math.random();\n";
		echo "imgLoad(img{$id}, function() {
		id++;
		})\n";
		$id++;
	}
	if($cid > 0)
	{
		for($i = 0;$i<$randnum;$i++)//对干扰验证码信息
		{
			echo "var img".$id."=new Image();\n";
			echo "img".$id.".src ='captcha.php?type=2&sid={$_GET['sid']}&'+Math.random();\n";
			echo "imgLoad(img{$id}, function() {
			id++;//每加载成功一张图，id+1
			})\n";
			$id++;
		}
	}
?>
function getbase()
{
	var i = 0;
	<?php
		for($i = 0;$i<$id;$i++)
		{
			echo "$('#theform').append(\"<input type='hidden' id = 'ps{$i}' name ='ps[]'>\");\n";//以base64的方式向显示页面提交图片
			echo "$('#ps{$i}').val(getBase64Image(img{$i}));\n";	
		}
	if($cid >0)
		echo "$('#numrand').val('{$randnum}');\n";//告诉显示页面有多少个随机验证码
	echo '$("#theform").submit();';
	?>
	
}
	var timee = setInterval(function() {
		if (id == ids) {//所有图片加载完后，提交表单
		getbase();
			clearInterval(timee);
		}
		}, 50);
</script>

</body>
</html>
