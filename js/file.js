 //Get Mouse Position 
 function getMousePos(canvas, evt) {//获得鼠标在canvas的相对位置
   var rect = canvas.getBoundingClientRect(); 
   return { 
     x: evt.clientX - rect.left * (canvas.width / rect.width),
     y: evt.clientY - rect.top * (canvas.height / rect.height)
   }
 }
