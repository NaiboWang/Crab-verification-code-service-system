<?php
include("logincheck.php");
include("connection.php");
$type = 0;//默认修改状态
if($_GET['num']==-1)//得到的参数为-1，说明这是要增加的新图片
	$type = 1;//新增状态
if($type)//新增状态
{
	$name="";
	$lin="";
	$linr ="";
	$count=10000;//默认初始次数
	$ID = -1;//标记为新图片
	$src=null;
}
else
{
	$sql = "select * from base where ID = ".$_GET['num'];//从数据库中取出已经存在的信息
	$result = mysqli_query($link,$sql); 
	$rst = mysqli_fetch_array($result);
	$ID = $rst['ID'];//标记为老图片
	$name = $rst['name'];
	$lin = $rst['link'];
	$linr =$rst['linkr'];
	$count = $rst['count'];
	$src=$rst['src'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit"> 
<title>处理模块</title>
</head> 
<link href="css/deal.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/arrow.js"></script>
<script type="text/javascript" src="js/file.js"></script>
<body>
<form  enctype="multipart/form-data"  method="post" action="sub.php" onSubmit="return check()">
<!--要注意上传文件一定要用 enctype="multipart/form-data" --->
<div align="center">
  <h1>图片信息管理</h1></div>
<div  style="float: left; position: relative;width: 45%;display: inline">
<table border="1" align="center" id="tb1" style="width: 100%">
<caption>请在下方选择项，并在右方操作<br>
<br>
<input type="hidden" name="pid" id="pid" value="<?php echo $ID;?>">
<label for="textfield2">图片名称:</label><input type="text" style="text-align: center" name="photoname" id="photoname" placeholder ="请输入图片名称" value="<?php echo $name;//从数据库中把已存在的信息写上
	?>" required>
<br>
<br>
<label for="number">次数:</label>
<input type="number"  style="text-align: center" name="number" id="number" value="<?php echo $count;?>" required>
<br>
<br>
<label for="textfield3">图片网址链接:</label>
<textarea name="photolink" placeholder ="请输入网址链接，必须带http://或https://" required style="width: 300px;height: 50px" id="photolink"><?php echo $lin;?></textarea>
<br>
<br>
<label for="textfield4">去看看链接:</label>
<textarea name="plink" placeholder ="请输入网址链接，必须带http://或https://" style="width: 300px;height: 50px" id="plink"><?php echo $linr;?></textarea>
<br>
<br>
</caption>
	<tr>
		<th><input type='button' id="ylbtn" name='sbtn' value='预览' class='selectbtn hidden'></th>
		<th>操作</th>
		<th>关键词</th>
		<th>优先度</th>
		<input type = 'hidden' name='ID' value='-1' >
	</tr>
</table>
<p>
  <input type="button" style="width:100px;height:50px;font-size:18px;display: block;margin: 0 auto"  id="addbtn"  value="增加一行">
  </p>
<p>
  <input type="submit" style="width:100px;height:50px;font-size:18px;display: block;margin: 0 auto"  id="addbtn"  value="提交修改">
</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</div>
 <div  style="padding-right: 30px;float: right; position: relative; width: 40%;display: inline">
  <p>
   <input style="width:250px;height:40px;font-size:18px"  type="file" id="pfile" name="pfile"  accept="image/x-
png,image/gif,image/jpeg,image/bmp" />
  </p>
<p>
  <input class="button hidden" type="button" name="cancel" id="cancel" value="空操作">
  <input class="button" type="button" name="barrow" id="barrow" value="箭头">
  <input class="button" align="middle" type="button" name="bline" id="bline" value="下划线">
  <label for="color">颜色:</label>
  <input type="color" name="color" id="color">
  <label for="select">线条类型:</label>
  <select name="select" id="select">
    <option value="0" selected="selected">实线</option>
    <option value="1">虚线</option>
  </select>
</p>
<p>
  <label for="textfield">操作提示:</label>
  <input type="text" name="textfield" id="textfield" style="width: 440px;border: none;height: auto;font-size: 17px" value="请点击上方按钮操作" readonly>
</p>
<canvas id="myCanvas" width="0" height="0" style="border:1px solid #c3c3c3;">
  Your browser does not support the HTML5 canvas tag.
</canvas>
 </div></form>
</body>
</html>
<script>
var tr=[];//动态生成表格数组
var im =[];//动态生成画布记录
<?php
if($type==0)//如果是修改状态，就从库中把已经存在的项信息取出来
{
	$sql = "select * from extended where BID = ".$_GET['num']." order by TID";
	$result = mysqli_query($link,$sql);
	while($rst = mysqli_fetch_array($result))
	{
		echo "tr.push([\"{$rst['word']}\",\"{$rst['line']}\",\"{$rst['arrow']}\",{$rst['priority']}]);\n";
		echo "im.push([{$rst['line']},{$rst['arrow']}]);\n";
	}
}
?>
var id = 0;//项目ID
var nowid = -1;//当前操作的ID
var type = 2;//画线还是画箭头
var stepa = 0;//画箭头步骤
var stepl = 0;//画线步骤
var xa1=-1,xa2=-1,ya1=-1,ya2=-1;//箭头坐标
var xl1=-1,xl2=-1,yl1=-1,yl2=-1;//线坐标
var colora = "#000000",colorl = "#000000";//箭头和线的颜色
var ltype=0,atype = 0;//箭头和线的虚实
var canvas = document.getElementById("myCanvas"); //得到画布id
canvas.width = cwidth;
canvas.height = cheight;
var ctx=canvas.getContext("2d");
ctx.lineWidth=2;//设置线条宽度为2
var img=new Image();//构建img图像
<?php
if($src != null){//如果数据库中文件不为空，从服务器photo文件夹加载图片
?>
img.src="<?php echo "\\\\photo\\\\".$src;?>";
<?php
				}
?>
img.onload = function(){//当图片重新载入时
	if(nowid>=0)//选择按钮选择的是某一项，就画某一项，不然点击预览按钮画全部
		draw();
	else
		drawall();
};
document.getElementById('pfile').onchange = function() {//对文件类如果变化
    var imgFile = this.files[0];
    var fr = new FileReader();
    fr.onload = function() {
        img.src = fr.result;//文件载入并更新给canvas
    };
    fr.readAsDataURL(imgFile);//base64方式读取到画布上
};
 //report the mouse position on click 
 canvas.addEventListener("click", function (evt) {//鼠标监听事件
	var mousePos = getMousePos(canvas, evt);//得到鼠标在画布的坐标 
	if(nowid>=0)//如果在选择编辑，不是预览状态
	{
		if(type == 0)//如果画线
		{
			if(stepl == 0)//第一步
			{
				stepl = 1;
				xl1 = parseInt(mousePos.x);
				yl1 = parseInt(mousePos.y);
			}
			else
			{
				xl2 = parseInt(mousePos.x);
				yl2 = parseInt(mousePos.y);
				stepl = 0;	
				draw();
				im[nowid][0] = xl1;
				im[nowid][1] = yl1;
				im[nowid][2] = xl2;
				im[nowid][3] = yl2;
				var str = xl1+","+yl1+","+xl2+","+yl2+",'"+colorl+"',"+ltype;
				$("#line"+nowid).val(str);//更新提交到后台的坐标、颜色、虚实值
			}
		}
		else if(type == 1)//如果画箭头
		{
			if(stepa == 0)
			{
				stepa = 1;
				xa1 = parseInt(mousePos.x);
				ya1 = parseInt(mousePos.y);
			}
			else
			{
				xa2 = parseInt(mousePos.x);
				ya2 = parseInt(mousePos.y);
				stepa = 0;	
				draw();
				im[nowid][6] = xa1;
				im[nowid][7] = ya1;
				im[nowid][8] = xa2;
				im[nowid][9] = ya2;
				var str = xa1+","+ya1+","+xa2+","+ya2+",'"+colora+"',"+atype;
				$("#arrow"+nowid).val(str);
			}
		}
	}
 }, false); 
</script>
<script src="js/canvas.js"></script>
<script src="js/table.js"></script>
<script>
function check()//提交验证
{
	if(img.src == null||img.src=="")
	{
		alert("请插入图片");
		return false;
	}
	if(confirm("确认要提交修改吗？"))
		return true;
	else
		return false;
}
</script>
