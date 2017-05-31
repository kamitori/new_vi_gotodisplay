var x, y, a, b, c, d;
x = objSize.boxWidth / 2;
y = objSize.boxHeight / 4;
//Triangle
draw.path("M 0 0 L "+x+" 0 L 0 "+y+" Z ").attr(whiteFill);
draw.path("M "+(objSize.boxWidth-x)+" 0 L "+objSize.boxWidth+" "+y+" L "+objSize.boxWidth+" 0 Z ").attr(whiteFill);
draw.path("M "+(objSize.boxWidth-x)+" "+objSize.boxHeight+" L "+objSize.boxWidth+" "+objSize.boxHeight+" L "+objSize.boxWidth+" "+(objSize.boxHeight - y)+" Z ").attr(whiteFill);
draw.path("M 0 "+(objSize.boxHeight  - y)+" L 0 "+objSize.boxHeight+" L "+x+" "+objSize.boxHeight+" Z ").attr(whiteFill);
//==================================================================================================================
draw.rect(2 ,objSize.boxHeight).attr($.extend(whiteFill,{ x: x - 1, y: 0}));
draw.path("M 0 "+(y-1)+" L "+objSize.boxWidth+" "+(objSize.boxHeight - y - 1)+" L "+objSize.boxWidth+" "+(objSize.boxHeight - y + 1)+" L 0 "+(y+1)+" Z ").attr(whiteFill);
draw.path("M "+objSize.boxWidth+" "+(y-1)+" L "+objSize.boxWidth+" "+(y+1)+" L 0 "+(objSize.boxHeight - y + 1)+" L 0 "+(objSize.boxHeight - y - 1)+" Z ").attr(whiteFill);
//==================================================================================================================
var path = "";
var t = objSize.boxHeight / 6;
a = objSize.boxWidth;
var intersectA = findIntersect({x1: x, y1: t, x2: a, y2: y + t}, {x1: objSize.boxWidth, y1: y, x2: 0, y2: objSize.boxHeight - y});
path += x+","+t+" "+intersectA.x+","+intersectA.y;
a = intersectA.x;
b = objSize.boxHeight - y;
var intersectB = findIntersect({x1: 0, y1: y, x2: objSize.boxWidth, y2: objSize.boxHeight - y}, {x1: intersectA.x, y1: intersectA.y, x2: a, y2: b});
path += " "+intersectB.x+","+intersectB.y;
a = intersectB.x * x / objSize.boxHeight;
b = intersectB.y * objSize.boxHeight/ (objSize.boxHeight - y);
var intersectC = findIntersect({x1: x, y1: 0, x2: x, y2: objSize.boxHeight}, {x1: intersectB.x, y1: intersectB.y, x2: a, y2: b});
path += " "+intersectC.x+","+intersectC.y;
path += " "+(objSize.boxWidth - intersectB.x)+","+intersectB.y;
path += " "+(objSize.boxWidth - intersectA.x)+","+intersectA.y;
/* path += " "+(objSize.boxWidth - intersectA.x + 8)+","+intersectA.y;
path += " "+(objSize.boxWidth - intersectB.x + 8)+","+intersectB.y;
path += " "+(intersectC.x + 8)+","+intersectC.y;
path += " "+(intersectB.x + 8)+","+intersectB.y;
path += " "+(intersectA.x + 8)+","+intersectA.y;*/
draw.polygon(path).attr(whiteFill);