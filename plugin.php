<?php
include("connection.php");
if(!isset($_GET['num']))//若没有给图片id，生成图片id为0（0为默认未设置的那张图片）
	$_GET['num'] = 0;
$sql = "select * from base where ID = ".$_GET['num'];
$result = mysqli_query($link,$sql); 
$rst = mysqli_fetch_array($result);
$ID = $rst['ID'];//标记为老图片
$name = $rst['name'];
$lin = $rst['link'];
$linr = $rst['linkr'];
$count = $rst['count'];
$src=$rst['src'];
?>
<!DOCTYPE html> 
<html lang="en"> 
<head>
<meta charset="UTF-8">
<meta name="renderer" content="webkit"> 
<title>请验证</title>
</head>
<link href="css/buttons.css" rel="stylesheet">
<script src="js/jquery.js"></script>
<script src="js/arrow.js"></script>
<script src="js/display.js?"+Math.random();></script>
<body>
<form  method="post" action="confirm.php?num=<?php echo $_GET['num'];?>&sid=<?php echo $_GET['sid'];?>" onSubmit="return check()">
<div align="center" style="width: 100%" >
   <p>
     <canvas id="myCanvas" width="0" height="0" style="border:1px solid #c3c3c3">
       Your browser does not support the HTML5 canvas tag.
     </canvas> 
   </p>
<script>
var im =[];//动态生成画布记录
	<?php
	session_start();
	$_SESSION["pass"] = 0;//标记验证通过变量为未通过
	$id = 0;
	$priority = array();//记录优先级
	$word = array();//记录关键词
	$code = array();//记录验证码
	$sql = "select * from extended where BID = ".$_GET['num']." order by priority desc,TID asc";//以优先级从大到小的顺序查询库
	$result = mysqli_query($link,$sql);
	while($rst = mysqli_fetch_array($result))//从数据库中把每一项的优先级、关键词以及坐标等信息取出并记录
	{
		$priority[$id] = $rst['priority'];
		$word[$id] = $rst['word'];
		$code[$id] = $_SESSION['authnum_session'][$id]; 
		echo "im.push([{$rst['line']},{$rst['arrow']}]);\n";
		echo "var img".$id."=new Image();\n";
		echo "img".$id.".src ='{$_POST['ps'][$id]}';\n";
		$id++;
	}
	$idt = $id;//记录有多少实际项
	if(isset($_POST['ps']))//如果存在验证码
	{
		for($i = $id;$i<count($_POST['ps']);$i++)//对验证码信息
		{
			echo "var img".$id."=new Image();\n";
			echo "img".$id.".src ='{$_POST['ps'][$id]}';\n";
			echo "getrandom();\n";//随机画一个验证码
			$id++;
		}
	}
	$str = "此验证码信息不全，无法验证，请刷新。";
	if($idt > 0)//如果有数据
	{
		$maxpr = $priority[0];//取得优先级最大的验证码
		$sql = "select count(*) as num from extended where BID = ".$_GET['num']." and priority = {$maxpr}";//查询有多少相同优先级的项
		$result = mysqli_query($link,$sql);
		$rst = mysqli_fetch_array($result);
		$cnum  = $rst['num'];//记录优先级最高的关键词的个数
		$rnum  = rand(0,$rst['num']-1);//随机选择一个关键词进行询问
		$mnum = rand(1,100);//随机选择模式1或者模式2
		if($mnum >50)//模式1
		{
			$str = "请输入关键词“{$word[$rnum]}”对应的验证码字符。";//取出验证码
			$_SESSION['answer'] = $code[$rnum];//设置答案
		}
		else//模式2
		{
			$str = "请输入字符“{$code[$rnum]}”对应的关键词选项：";
			$words = array("夕颜","秋恋","蝉羽","浴兰","沉鱼","落雁","闭月","羞花","幽然","静微","艺雅","卉馨","轩然","子茹","萦绕","流萤","静谧","流凨","羽翼","蔓延","浅唱","轻盈","清芳","黯淡","纯洁","涤荡","皓月","思琪","绚烂","郁葱","寂寞","尘世","词语","疼痛","猜测","奔腾","丑陋","长久","模仿","固定","寒冷","恐惧","奇怪","整齐","漂浮","特殊","虚假","遥望","微弱","消灭","珍贵","祝愿","缠绕","颤抖","抚摩","拉扯","柔软","明亮","宽敞","黑暗","破旧","美好","飞翔","挖掘","搜索","期待","盼望","祈祷","微小","捕捉","思忖","思量","思想","思念","思绪","思索","思维","思考","思辨","思路","思慕","思逸","思齐","思远","思春","思韵","思虑","思辩","思玄","思恋","思凡","思致","思惟","思潮","思忆","思元","思绎","思域","思永","思怀","思越","思纬","思理","思莼","思服","思咏","思愆","思议","思秋","思言","馨香","飘散","舒展","娇美","神韵","恬静","醉人","素雅","娇嫩","幽香","淡雅","朴素","花瓣","花蕊","花粉","鲜花","怒放","含苞","孤单","孤寂","寂寞","寥寂","寂寥","落寞","孤独","寂静","僻静","宁静","寂然","清静","沉寂","沉静","安静","孤立","伶仃","孤单","零落","落莫","动静","呼吸","朝夕","取舍","善恶","兴衰","祸福","甘苦","彼此","首尾","褒贬","吞吐","黑白","是非","多少","真假","虚实","反正","生死","悲欢","前后","始末","早晚","昼夜","曲直","东西","善恶","南北","开关","左右","眷恋","留恋","依恋","迷恋","自恋","爱恋","失恋","贪恋","思恋","热恋","怀恋","初恋","顾恋","流恋","恋恋","悲恋","相恋","婉恋","忆恋","积恋","恳恋","情恋","凝恋","耽恋","欣恋","绻恋","感恋","单恋","遐恋","嫪恋","安恋","慕恋","兴奋","快乐","喜悦","愉快","畅快","欢畅","欢喜","欢腾","欢快","欣喜","激奋","激昂","激情","敬佩","佩服","崇拜","钦仰","惊恐","惊慌","惊吓","惧怕","恐惧","胆怯","畏缩","发慌","心慌","恐慌","激怒","恼火","怒斥","怒吼","震怒","气愤","担忧","发愁","犯愁","忧伤","忧愁","忧心","愁闷","悲伤","悲痛","悲惨","悲凉","哀伤","哀怨","忧伤","伤感","瘦削","憔悴","俊美","恬静","慈祥","苍白","白皙","清秀","光滑","英俊","红润","挺直","扁平","小巧","笔直","端庄","秀气","秀美","巧嘴","朱唇","苍白","红润","干裂","娇嫩","整齐","整洁","雪白","焦黄","洁净","蓬松","鲜嫩","粉红","通红","红嫩","滑润","滋润","干巴","干裂","干燥","蜡黄","洁白","炭黑","瘦弱","矮小","苗条","丰满","强壮","清秀","单薄","文弱","干瘦","轩昂","解释","文辞","短语","单词","词组","词汇","字眼","范例","语素","丰富","信息","组成","最小","最大","造句","单位","构成","方式","可以","神奇","单纯","自由","单音","双音","多音","可以","山高","水深","天高","地厚","拥有","凑整","仿佛","苍茫","参差","上面","前方","内部","中间","方位","名词","学生","老师","群众","老头","妇女","同志","叔叔","婶婶","爸爸","妈妈","哥哥","弟弟","姐姐","妹妹","事物","蜗牛","猎豹","棒球","足球","蓝求","战机","飞机","地球","思想","中学","物理","科穴","碗盘","竹筷","上午","下午","过去","将来","早晨","午夜","三更","甲戊","世纪","今天","昨天","动词","行为","跑步","唱歌","喝水","敲门","吆喝","踢求","生长","枯萎","发芽","结果","产卵","喜欢","气愤","觉得","思考","厌恶","存现","消失","显现","丢失","幻灭","使令","使人","让座","命令","禁止","勒令","能愿","如会","愿意","能够","宁可","表示","状貌","特征","状态","气急","怒火","高兴","形容","高大","高瘦","矮胖","粗细","强壮","性质","香甜","漂亮","圆滑","机智","单调","快速","浓厚","肥满","许多","迅速","悄悄","量词","单位","公尺","分寸","公里","公斤","一两","一辆","一角","一元","一次","一趟","一下","回声","一幢","一座","代词","代替","我们","你们","它们","她们","大家","咱们","疑问","谁的","什么","怎么","哪里","何以","这里","那里","那边","拟声","模拟","声音","轰隆","淅沥","噼里","啪啦","哗啦","叽喳","啪拉","哄堂","开怀","笑颜","笑容","喜笑","欢声","笑语","眉开","生气","火冒","雷霆","欣喜","心情","词汇","忧愁","丧气","雾气","满腹","满腔","愁肠","百结","欲断","寸断","九转","百愁","心事","阴郁","忧心","如焚","愁绪");//生成随机词语
			$clen = count($words);//记录词语数组长度
			$crand = rand(0,$clen -1);//生成三个随机选项
			$tw1 = $words[$crand];
			$crand = rand(0,$clen -1);
			$tw2 = $words[$crand];
			$crand = rand(0,$clen -1);
			$tw3 = $words[$crand];
			$crand = rand(0,400);//随机选择答案是A B C D其中的一个
			if($crand <100)
			{
				$str = $str."A {$word[$rnum]} B {$tw1} C {$tw2} D {$tw3}";
				$_SESSION['answer'] = "A";
			}
			else if($crand>=100 &$crand<200)
			{
				$str = $str."A {$tw1} B {$word[$rnum]} C {$tw2} D {$tw3}";
				$_SESSION['answer'] = "B";
			}
			else if($crand>=200 &$crand<300)
			{
				$str = $str."A {$tw2} B {$tw1} C {$word[$rnum]} D {$tw3}";
				$_SESSION['answer'] = "C";
			}	
			else if($crand>=300 &$crand<400)
			{
				$str = $str."A {$tw3} B {$tw1} C {$tw2} D {$word[$rnum]}";
				$_SESSION['answer'] = "D";
			}	
		}
	}	
?>
var canvas = document.createElement("canvas"); 
var ctx=canvas.getContext("2d");
canvas.width = cwidth+2*offset;
canvas.height = cheight + 2*offset;
ctx.lineWidth=2;//设置线条宽度为2
var img=new Image();//构建img图像
img.src="<?php echo "\\\\photo\\\\".$src;?>";
img.onload = function(){//当图片重新载入时
drawall(<?php echo $_POST['numrand']; ?>);//画带n个随机验证码的验证图像
}
function check()//提交验证
{
	if($("#tans").val().length <=0)
	{
		alert("请输入答案");
		$("#tans").focus();//文本框获得输入焦点
		return false;
	}
	return true;	
}
</script>
 <p><a href="<?php echo $linr;?>" target="_blank" class="button button-caution button-rounded button-large">去看看</a><a href="pluginpre.php?sid=<?php echo $_GET['sid']; ?>" class="button button-royal button-rounded button-large" style=" margin-left: 20px">看不清刷新</a></p>
 <p><?php echo $str;?></p>
 <p>
<label for="textfield">请输入答案:</label>
<input type="text" name="tans" style="border-left: none;border-right: none;border-top:none;text-align: center" id="tans">
 <input class="button button-primary button-rounded button-small"  type="submit" style=" margin-left: 20px" value="确定"></input>
 </p>
</div>
</form>
</body>
</html>
