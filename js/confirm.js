var success = -1;
var theurl = "";//服务器链接
function getconfirm(callback)
{
	$.ajaxSetup({async : false});//设置ajax为同步执行
	$.get(theurl+"getcaptcha.php",{type:0},//首先得到一个sessionid
	function(data,status)
	{
		if(status == "success")
		{
			success = data;
			$("#scaptcha").attr("disabled",false);//成功得到之后使验证按钮有效
		}
			
	});
	$("#scaptcha").attr("onclick","javascript:window.open('"+theurl+"pluginpre.php?sid="+success+"','验证界面','toolbar=no, status=no,resizable=no,menubar=no, scrollbars=yes');return false;");//为验证按钮添加链接
	$("#ssss").attr("src",theurl+"pluginpre.php?sid="+success);
	$("#vcaptcha").val(success);//设置传到后台的sessionid
	var timer = setInterval(function() {//每隔0.5秒循环检测是否验证成功
		var c;
		var ht = $.get(theurl+"getcaptcha.php",{type:1,sid:success},function(result){//循环ajax
    	c = result;
	if (c == 1) {//检测成功的时候，执行回调函数
		if(callback!=null)
			callback();//生成重置函数
		clearInterval(timer);
		}
	}
			);
	}, 500);
}