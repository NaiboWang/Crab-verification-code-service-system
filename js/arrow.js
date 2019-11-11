var scalex = 0.7; //显示画布的x轴比例
var scaley = 0.7; //显示画布的y轴比例
var pwidth = 320;
var pheight = 240; //记录图片宽和高
var offsetimage = 100; //记录图片相对画布移动的偏移量
var offset = 50; //记录地图偏移量
var cwidth = pwidth + 2 * offsetimage;
var cheight = pheight + 2 * offsetimage; //计算画布应该有的大小
//处理画箭头和下划线的js脚本
function getBeveling(x, y) {
	return Math.sqrt(Math.pow(x, 2) + Math.pow(y, 2));
}

function drawDashLine(context, x1, y1, x2, y2, dashLen) //画虚线的函数
{
	dashLen = dashLen === undefined ? 5 : dashLen;
	//得到斜边的总长度  
	var beveling = getBeveling(x2 - x1, y2 - y1);
	//计算有多少个线段  
	var num = Math.floor(beveling / dashLen);

	for (var i = 0; i < num; i++) {
		context[i % 2 == 0 ? 'moveTo': 'lineTo'](x1 + (x2 - x1) / num * i, y1 + (y2 - y1) / num * i);
	}
	context.stroke();
}
var windowToCanvas = function(canvas, x, y) {
	var bbox;
	bbox = canvas.getBoundingClientRect();
	return {
		x: (x - bbox.left) * (canvas.width / bbox.width),
		y: (y - bbox.top) * (canvas.height / bbox.height)
	};
};
var canvasListener = function(canvas) {
	var func;
	this.canvas = canvas;
	func = function(obj) {
		var listener;
		listener = obj;
		return function(e) {
			return listener.dealEvents(e);
		};
	};
	this.canvas.click(func(this));
	this.events = [];
};
//添加自定义事件，参数为坐标范围（左上角坐标与范围宽高）和事件回调函数
canvasListener.prototype.addEvent = function(x, y, width, height, callback) {
	var newEvent;
	newEvent = {
		x: x,
		y: y,
		width: width,
		height: height,
		callback: callback
	};
	this.events.push(newEvent);
};
//点击事件触发时，判断该触发哪个自定义事件
canvasListener.prototype.dealEvents = function(e) {
	var loc, i$, ref$, len$, event;
	loc = windowToCanvas(this.canvas[0], e.clientX, e.clientY);
	for (i$ = 0, len$ = (ref$ = this.events).length; i$ < len$; ++i$) {
		event = ref$[i$];
		if (loc.x >= event.x && loc.x < event.x + event.width && loc.y >= event.y && loc.y < event.y + event.height) {
			event.callback();
		}
	}
};; (function(window, document) {
	if (window.mapleque == undefined) window.mapleque = {};
	if (window.mapleque.arrow != undefined) return;

	/**
  * 组件对外接口
  */
	var proc = {
		/**
  * 接收canvas对象，并在此上画箭头（箭头起止点已经设置）
  * @param context
  */
		paint: function(context, type) {
			paint(this, context, type);
		},
		/**
  * 设置箭头起止点
  * @param sp起点
  * @param ep终点
  * @param st强度
  */
		set: function(sp, ep, st) {
			init(this, sp, ep, st);
		},
		/**
  * 设置箭头外观
  * @param args
  */
		setPara: function(args) {
			this.size = args.arrow_size; //箭头大小
			this.sharp = args.arrow_sharp; //箭头锐钝
		}
	};

	var init = function(a, sp, ep, st) {
		a.sp = sp; //起点
		a.ep = ep; //终点
		a.st = st; //强度
	};
	var paint = function(a, context, type) {
		var sp = a.sp;
		var ep = a.ep;
		if (context == undefined) return;
		//画箭头主线
		var h = _calcH(a, sp, ep, context);
		context.beginPath();
		if (type == 0) {

			context.moveTo(sp.x, sp.y);
			context.lineTo(ep.x, ep.y);
			//画箭头头部
			context.moveTo(ep.x, ep.y);
			context.lineTo(h.h1.x, h.h1.y);
			context.moveTo(ep.x, ep.y);
			context.lineTo(h.h2.x, h.h2.y);
			context.stroke();

		} else {
			drawDashLine(context, sp.x, sp.y, ep.x, ep.y, 5);
			//画箭头头部
			drawDashLine(context, ep.x, ep.y, h.h1.x, h.h1.y, 5);
			drawDashLine(context, ep.x, ep.y, h.h2.x, h.h2.y, 5);
		}
		context.closePath();
	};
	//计算头部坐标
	var _calcH = function(a, sp, ep, context) {
		var theta = Math.atan((ep.x - sp.x) / (ep.y - sp.y));
		var cep = _scrollXOY(ep, -theta);
		var csp = _scrollXOY(sp, -theta);
		var ch1 = {
			x: 0,
			y: 0
		};
		var ch2 = {
			x: 0,
			y: 0
		};
		var l = cep.y - csp.y;
		ch1.x = cep.x + l * (a.sharp || 0.025);
		ch1.y = cep.y - l * (a.size || 0.05);
		ch2.x = cep.x - l * (a.sharp || 0.025);
		ch2.y = cep.y - l * (a.size || 0.05);
		var h1 = _scrollXOY(ch1, theta);
		var h2 = _scrollXOY(ch2, theta);
		return {
			h1: h1,
			h2: h2
		};
	};
	//旋转坐标
	var _scrollXOY = function(p, theta) {
		return {
			x: p.x * Math.cos(theta) + p.y * Math.sin(theta),
			y: p.y * Math.cos(theta) - p.x * Math.sin(theta)
		};
	};
	var arrow = new Function();
	arrow.prototype = proc;
	window.mapleque.arrow = arrow;
})(window, document);
function paintarrow(x1, y1, x2, y2, color, type, offset) {//画箭头，根据x1 y1 x2 y2 color 以及偏移量offset
	ctx.strokeStyle = color;
	var a1 = new window.mapleque.arrow();
	a1.set({
		x: x1 + offset,
		y: y1 + offset
	},
	{
		x: x2 + offset,
		y: y2 + offset
	});
	a1.setPara({
		arrow_size: 0.1,
		arrow_sharp: 0.05
	});
	a1.paint(ctx, type);
}
function paintline(context, x1, y1, x2, y2, color, type, offset) {//画线，根据x1 y1 x2 y2 color 以及偏移量offset
	context.strokeStyle = color;
	if (type == 0) //若画实线
	{
		context.beginPath();//一定要begin和close
		context.moveTo(x1 + offset, y1 + offset);
		context.lineTo(x2 + offset, y2 + offset);
		context.stroke();
		context.closePath();
	} else //若画虚线
	{
		context.beginPath();
		drawDashLine(context, x1 + offset, y1 + offset, x2 + offset, y2 + offset, 5);
		context.closePath();
	}
}