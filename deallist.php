<?php
include("logincheck.php");?>
<style>
tr
{
text-align: center;	
}
</style>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit"> 
<title>处理模块</title>
</head>
<body>
<h1 align="center">请点击项目进行操作</h1>

<p align="center">
  <a href="deal.php?num=-1" target="_blank" style="text-decoration: none" ><input type="button" style="width:130px;height:50px;font-size:18px;display: block;margin: 0 auto"  id="addbtn"  value="增加新图片"></a>
</p>
<h2 align="center">已存在图片信息</h2>
<table align="center" border="1">
  <tbody>
    <tr>
      <th scope="col">序号</th>
      <th scope="col">图片名称及链接</th>
      <th scope="col">次数</th>
      <th scope="col" colspan="2">操作</th>
    </tr>
    <?php
	include("connection.php");
	$sql = "select * from base where ID > 0 order by ID asc";
	$result = mysqli_query($link,$sql);
	$i = 1;
	while($rst = mysqli_fetch_array($result))
	{
		echo "<tr><td>{$i}</td><td><a href='{$rst['link']}' target ='_blank'>{$rst['name']}</a></td><td>{$rst['count']}</td><td><a href='deal.php?num={$rst['ID']}' target='_blank'>修改</a></td><td><a href='delete.php?num={$rst['ID']}' onclick='javascript:return check();'>删除</a></td></tr>";
		$i++;
	}
	  ?>
    
  </tbody>
</table>
<script>
function check()
{
	if(confirm("确实要删除吗？"))
		return true;
	return false;
}
</script>
</body>
</html>