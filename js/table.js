$(document).ready(function () {
	loading(tr);
	drawall();
	//动态加载表格
	function loading(tr){//把已经存在的项添加进去
		for(var j=0;j<tr.length;j++)
		{
			var i=0;
			var tr1=tr[j];
			$('#tb1').append("<tr>"+
						 "<td><input type='button' name='sbtn' id='sbtn"+id+"' value='选择'  class='selectbtn'></td>"+
						 "<td><input type='button' value='删除' class='removebtn'></td>"+
						 "<td><input style='text-align:center' type='text' name='word[]' id='word"+id+"' placeholder ='关键词' value='"+tr1[i++]+"' required></td>"+
						 "<input type='hidden' name='line[]' style='width:35px;border:none' readonly id='line"+id+"' value=\""+tr1[i++]+"\" required>"+
						 "<input type='hidden' name='arrow[]' style='width:35px;border:none' readonly id='arrow"+id+"' value=\""+tr1[i++]+"\" required>"+
						 "<td><input type='number' name='priority[]' style='width:35px' id='priority"+id+"' value='"+tr1[i++]+"' required></td>"+
						 "<input type = 'hidden' name='ID' value='"+id+"' ></tr>");
				id++;
		}
		updateBind(); 
	}

	function updateBind(){//绑定按钮事件
		$("input").on('input',function(e){  
		});  
	$(".removebtn").click(function(){//绑定删除按钮的删除事件
		var tmpp=$("#tb1 tr");
		var tm=$(this).parent().parent();
		for(var k=0;k<tmpp.length;k++)
		{
			if(tmpp[k]==tm[0])
				break;
		}
		var tem = tm.find("input[name='ID']").val();//得到点击的删除按钮那一列的index值
		tm.remove();
		if(nowid ==tem)//如果删除的是正在操作的ID
		{
			nowid = -1;//重置ID
			$("#ylbtn").removeClass().addClass("selectbtn hidden");//重置成预览状态
		}
		refresh();//刷新画布
	});
		$(".selectbtn").click(function(){//当选择按钮按下时
		var tmpp=$("#tb1 tr");
		var tm=$(this).parent().parent();
		for(var k=0;k<tmpp.length;k++)
		{
			if(tmpp[k]==tm[0])
				break;
		}
		$(".selectbtn").removeClass().addClass("selectbtn");
		tm.find("input[name='sbtn']").addClass("hidden");
		nowid = tm.find("input[name='ID']").val();//设置ID为正在操作的ID
		refresh();//更新画面
		$('#bline').removeClass().addClass("button");
		$('#barrow').removeClass().addClass("button");
		$('#cancel').addClass('hidden');//重置，使空操作按钮有效
		type = 2;//什么都不做
		$('#textfield').val("请点击上方按钮进行操作");
	});
	}
	$('#addbtn').click(function(){//点击增加一行时的操作
		$('#tb1').append("<tr>"+
						 "<td><input type='button' name='sbtn' id='sbtn"+id+"' value='选择' class='selectbtn'></td>"+
						 "<td><input type='button' value='删除' class='removebtn'></td>"+
						 "<td><input style='text-align:center' type='text' name='word[]' id='word"+id+"' placeholder ='关键词' required></td>"+
						 "<input type='hidden' name='line[]' style='width:35px;border:none' readonly id='line"+id+"' value=\"-1,-1,-1,-1,'#000000',0\" required>"+
						 "<input type='hidden' name='arrow[]' style='width:35px;border:none' readonly id='arrow"+id+"' value=\"-1,-1,-1,-1,'#000000',0\" required>"+
						 "<td><input type='number' name='priority[]' style='width:35px' id='priority"+id+"' value='0' required></td>"+
						 "<input type = 'hidden' name='ID' value='"+id+"' ></tr>");//增加一行
		im.push([-1,-1,-1,-1,"#000000",0,-1,-1,-1,-1,"#000000",0]);//添加新的默认线条
		$(".selectbtn").removeClass().addClass("selectbtn");
		$("#sbtn"+id).addClass("hidden");
		nowid = id;//设置ID为正在操作的ID
		refresh();//更新画面
		$('#bline').removeClass().addClass("button");
		$('#barrow').removeClass().addClass("button");
		$('#cancel').addClass('hidden');
		type = 2;//什么都不做
		$('#textfield').val("请点击上方按钮进行操作");
		id++;
		updateBind();
	});
});