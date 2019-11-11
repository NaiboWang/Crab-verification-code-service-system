function imgLoad(img, callback) {//载入图片
		var timer = setInterval(function() {//设置定时器
		if (img.complete) {//当img加载完毕时，加载图片
		callback(img)
		clearInterval(timer)
		}
		}, 50)
}
function getrandom()//得到随机参数
{
	var rx1=0,rx2=0,ry1=0,ry2=0;
	//生成干扰图片
	rx2 = parseInt(pwidth*Math.random())+offsetimage;
	ry2 = parseInt(pheight*Math.random())+offsetimage;
	var rt;//0~10随机数
	rt = parseInt(10*Math.random());
	var py = 0;//y轴偏移量
	if(rt>5)
	{
		py = pheight+offsetimage;//逻辑偏移，如果x在图片范围内，y轴应该向下偏移的距离
	}
		rx1 = parseInt((pwidth + 2*offsetimage)*Math.random());//生成x轴坐标
		if(rx1>=offsetimage&&rx1<=offsetimage+pwidth)//若x坐标在图片显示范围内
		{
			ry1 = parseInt(offsetimage*Math.random())+py;
		}
		else
			ry1 = parseInt((pheight + 2*offsetimage)*Math.random());
	rt = parseInt(10*Math.random());
	if(rt >5)
	{
		rt = 1;//生成虚线
	}
	else
		rt = 0;
	var color = getRandomColor();//获得随机颜色
	im.push([-1,-1,-1,-1,"#000000",0,rx1,ry1,rx2,ry2,color,rt]);
}
function getRandomColor()//生成随机颜色
{    
  return  '#' +    
    (function(color){    
    return (color +=  '0123456789abcdef'[Math.floor(Math.random()*16)])    
      && (color.length == 6) ?  color : arguments.callee(color);    
  })('');    
} 
function drawall(randnum)//画画布
{
	ctx.clearRect(0,0,620,540);//清除原有数据
	ctx.drawImage(img,offsetimage+offset,offsetimage+offset,pwidth,pheight);//显示图片并缩放
	for(var k=im.length-randnum;k<im.length;k++)//先画随机验证码
	{
			paintline(ctx,im[k][0],im[k][1],im[k][2],im[k][3],im[k][4],im[k][5],offset);//画线
			paintarrow(im[k][6],im[k][7],im[k][8],im[k][9],im[k][10],im[k][11],offset);//画箭头
			var xa=offset+im[k][6]-img0.width/2;
			var ya = offset+im[k][7];
			if(im[k][7]<im[k][9])//如果点箭头朝下
				ya = offset+im[k][7]-img0.height;
			var timg = eval("img"+k);//画验证码
			ctx.drawImage(timg,xa,ya);
	}
	for(var k=0;k<im.length-randnum;k++)//再画真验证码
	{
			paintline(ctx,im[k][0],im[k][1],im[k][2],im[k][3],im[k][4],im[k][5],offset);//画线
			paintarrow(im[k][6],im[k][7],im[k][8],im[k][9],im[k][10],im[k][11],offset);//画箭头
			var xa=offset+im[k][6]-img0.width/2;
			var ya = offset+im[k][7];
			if(im[k][7]<im[k][9])//如果点箭头朝下
				ya = offset+im[k][7]-img0.height;
			var timg = eval("img"+k);//画验证码
			ctx.drawImage(timg,xa,ya);
	}
	//以下缩放整个画布
	var simage = new Image();  
    simage.src = canvas.toDataURL("image/png"); 
	imgLoad(simage, function() {
		var scan = document.getElementById("myCanvas");
		scan.width = simage.width*scalex;//按照scalex和scaley缩放
		scan.height = simage.height*scaley;  
		scan.getContext("2d").drawImage(simage, 0, 0,scan.width,scan.height);  
	});
	$("#tans").focus();//文本框获得焦点
}