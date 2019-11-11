<?php
header( 'Access-Control-Allow-Credentials:true' );//允许其他网站ajax访问这个网址
header("Access-Control-Allow-Origin:*"); //*号表示所有域名都可以访问 
if(isset($_GET['type']))
{
	if($_GET['type'] == 0)
	{
		session_start();
		session_regenerate_id(); //重新生成sessionid
		session_unset();//销毁原来的session
		$h = parse_url($_SERVER["HTTP_REFERER"]);//得到调用本页面的网站域名 $_SERVER["HTTP_REFERER"]得到全地址 parse_url得到解析
		$_SESSION['url'] = $h['host'];//得到纯域名
		echo session_id();//打印出来分配好的sessionid
	}
	else
	{
		if(isset($_GET['sid']))
		{
			if($_GET['sid'] == -1)//保留值 -1代表未通过
			{
				echo "0";
			}
			else
			{
				session_id($_GET['sid']);//指定sessionid对话
				session_start();
				$str = 0;//默认标记未通过
				if(isset($_SESSION['pass']))//通过了打印1
				{
					if($_SESSION['pass'] == 1)
						$str = 1;
				}
				echo $str;
			}
		}
		if($_GET['type'] == 2)//当后台验证一次之后，销毁session防止恶意利用
		{
			if($_GET['sid']!=-1)
			{
				session_unset();
				session_destroy();
			}
		}
	}
}
else
	echo "0";
?>