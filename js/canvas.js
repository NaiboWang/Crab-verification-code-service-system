function draw()//画画布
{
	ctx.clearRect(0,0,520,440);//清除原有数据
	ctx.drawImage(img,offsetimage,offsetimage,pwidth,pheight);//显示图片并缩放
	paintline(ctx,xl1,yl1,xl2,yl2,colorl,ltype,0);//画线
	paintarrow(xa1,ya1,xa2,ya2,colora,atype,0);//画箭头
}

function drawall()//画画布
{
	ctx.clearRect(0,0,520,440);//清除原有数据
	ctx.drawImage(img,offsetimage,offsetimage,pwidth,pheight);//显示图片并缩放
	var tm=$("#tb1 tr");
	var ll = tm.length;
	for(var k=0;k<tm.length;k++)//对每一个仍然存在的表格行，画线和箭头
	{
		var tid = tm.eq(k).find("input[name='ID']").val();//获得该行的对应im数组的id值
		if(tid > -1)
		{
			paintline(ctx,im[tid][0],im[tid][1],im[tid][2],im[tid][3],im[tid][4],im[tid][5],0);//画线
			paintarrow(im[tid][6],im[tid][7],im[tid][8],im[tid][9],im[tid][10],im[tid][11],0);//画箭头
		}
	}
}
function refresh()//刷新信息
{
	if(nowid>=0)//若nowid为单独的选择项，从im数组中读取保存的箭头、下划线等信息
	{
		xl1 = im[nowid][0];
		yl1 = im[nowid][1];
		xl2 = im[nowid][2];
		yl2 = im[nowid][3];
		colorl = im[nowid][4];
		ltype = im[nowid][5];
		xa1 = im[nowid][6];
		ya1 = im[nowid][7];
		xa2 = im[nowid][8];
		ya2 = im[nowid][9];
		colora = im[nowid][10];
		atype = im[nowid][11];
		draw();
	}
	else
	{
		drawall();//所有图像全画上
	}
}
document.getElementById('color').onchange = function() {//颜色类如果发生变化
	if(nowid >=0)
	{
		if(type == 0)//画的是箭头
		{
			colorl = this.value;
			im[nowid][4] = this.value;//记录保存的颜色
		}
		else
		{
			colora = this.value;
			im[nowid][10] = this.value;
		}	
	}
};
$(document).ready(function() {
//绑定下拉框change事件，当下来框改变时调用 SelectChange()方法
$("#select").change(function() {//设置当下拉框给的箭头或者下划线，改变其虚实类型
	if($("#select").val() == 0)
	{
		if(type == 0)
		{
			ltype = 0;
			im[nowid][5]=0;
		}
		else
		{
			atype = 0;
			im[nowid][11]=0;
		}
			
	}
	else
	{
		if(type == 0)
		{
			ltype = 1;
			im[nowid][5]=1;
		}
		else
		{
			atype = 1;
			im[nowid][11]=1;
		}
	}
}); 
})
	 
$(document).ready(function() {
$('#cancel').click(function() {//点击取消按钮，取消所有按钮样式
$('#bline').removeClass().addClass("button");//去除所有样式只剩button样式
$('#barrow').removeClass().addClass("button");
$('#cancel').addClass('hidden');
type = 2;//什么都不做
$('#textfield').val("请点击上方按钮进行操作");
});
});
$(document).ready(function() {
$('#bline').click(function() {//当点击画线按钮时
$('#cancel').removeClass().addClass("button");
$('#barrow').removeClass().addClass("button");
$('#bline').removeClass().addClass("button");
$('#bline').addClass('hidden');
type = 0;//类型为下划线
$('#color').val(colorl);//颜色区域记忆
$('#select').val(ltype);//虚实记忆
$('#textfield').val("请先点击第一个点，再点击第二个点以画线");
});
});
$(document).ready(function() {
$('#barrow').click(function() {
$('#barrow').removeClass().addClass("button");
$('#bline').removeClass().addClass("button");
$('#cancel').removeClass().addClass("button");
$('#barrow').addClass('hidden');
type = 1;//类型为箭头
$('#color').val(colora);//颜色区域记忆
$('#select').val(atype);//虚实记忆
$('#textfield').val("请先点击第一个点，再点击第二个点以画箭头");
});
});
