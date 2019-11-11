<?php
include("logincheck.php");
include('connection.php');
$sql = "delete from extended where BID = {$_GET['num']}";
$result = mysqli_query($link,$sql);
$sql = "delete from base where ID = {$_GET['num']}";
$result = mysqli_query($link,$sql);
?>
<script>
alert("删除成功");
window.location.href = "deallist.php";
</script>