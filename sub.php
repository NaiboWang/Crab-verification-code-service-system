<?php
include("logincheck.php");
include("connection.php");
$type = 0;//默认标记为修改图片
if($_POST['pid'] == -1)
	$type = 1;//标记为新图片
if(empty($_FILES['pfile']['tmp_name']))//没有文件要上传
{
	$file = 0;
}
else
{
	include "documentupload.php";//文件上传类
		$up = new fileupload;
		//设置属性(上传的位置， 大小， 类型， 名是是否要随机生成)
		//使用对象中的upload方法， 就可以上传文件， 方法需要传一个上传表单的名子 pic, 如果成功返回true, 失败返回false
		if($up -> upload("pfile")) {
			$file = 1;
			$fname = $up->getFileName();  
		} else {
			$fname = strip_tags($up->getErrorMsg());//错误信息去掉html标签后显示
		   echo "<script> alert('".$fname.",请返回修改'); window.location.href='deal.php?ID=".$_POST['pid']."';</script>";
		exit(-1);
		}
}
if($type)//如果是新图片，插入到数据库
{
	$sql = "insert into base(name,link,count,linkr) values('{$_POST['photoname']}','{$_POST['photolink']}',{$_POST['number']},'{$_POST['plink']}')";
	mysqli_query($link,$sql);
	$sql = "select max(ID) as ID from base";
	$result = mysqli_query($link,$sql); 
	$rst = mysqli_fetch_array($result);
	$ID = $rst['ID'];//标记当前项目ID
}
else//如果是老图片，更新数据库
{
	$ID = $_POST['pid'];
	$sql = "update base set name = '{$_POST['photoname']}',link = '{$_POST['photolink']}',count={$_POST['number']},linkr = '{$_POST['plink']}' where ID = {$ID}";
	mysqli_query($link,$sql);
}
if($file)//如果上传了新文件
{
	$sql = "update base set src = '{$fname}' where ID = {$ID}";
	mysqli_query($link,$sql);
}
	$sql = "delete from extended where BID = {$ID}";//删除原有项
	mysqli_query($link,$sql);
if(isset($_POST['word']))//如果有数据提交的话
{ 
	for ($i= 0;$i< count($_POST['word']); $i++){ 
		$sql = "insert into extended(BID,word,arrow,line,priority) values('{$ID}','{$_POST['word'][$i]}',\"{$_POST['arrow'][$i]}\",\"{$_POST['line'][$i]}\",{$_POST['priority'][$i]})";
		$result = mysqli_query($link,$sql); //然后重新插入新表信息
	}
}
echo "<script>
alert('提交成功！');
 window.location.href='deal.php?num=".$ID."';</script>";//跳转到提交成功页面

?>