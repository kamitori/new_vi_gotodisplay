var x, y,
    D, a, b, c, d,
    color;
x = objSize.boxWidth / 3;
y = objSize.boxHeight;
//Triangle
draw.path("M 0 0 L "+x+" 0 L 0 "+y+" Z ").attr(whiteFill);
//End Triangle
//Bleed
D = Math.sqrt(Math.pow(x,2) + Math.pow(y,2));
a = bleed * D / y;
b = Math.sqrt( Math.pow(a,2) - Math.pow(bleed,2));
c = y * b / D;
d = Math.sqrt( Math.pow(b,2) - Math.pow(c,2));

intersect = findIntersect({x1: x + a, y1: 0, x2: a, y2: y}, {x1: 0, y1: bleed, x2: objSize.boxWidth, y2: bleed});

x1 = d+intersect.x-a;
y1 = intersect.y - c;

x2 = intersect.x;
y2 = intersect.y;

intersect = findIntersect({x1: x + a, y1: 0, x2: a, y2: y}, {x1: 0, y1: objSize.boxHeight - bleed, x2: objSize.boxWidth, y2: objSize.boxHeight - bleed});
x3 = intersect.x;
y3 = intersect.y;

x4 = d+intersect.x-a;
y4 = intersect.y - c;

var angle_clone = Math.asin(((x)/D))*(180/Math.PI)*2;
console.log(angle_clone);
if( $("[name=frame_style]:checked").val() == "m_wrap" ) {
    var     left, leftRect, leftUse, leftClip,
                top, topRect, topUse, topClip,
                right, rightRect, rightUse, rightClip,
                bottom, bottomRect, bottomUse, bottomClip;
        defs = draw.defs();
         var position_clone = Math.ceil(bleed*((x)/D));

        //left
        leftRect = draw.path("M"+x2+' '+y2+' '+'L'+(x2+(x2-x1))+' '+(y2+(y2-y1))+' '+'L'+(x3+(x2-x1))+' '+(y3+(y2-y1))+' '+'L'+x3+' '+y3+' Z');
        leftClip = draw.clip().attr({id : "left-clip"}).add(leftRect);
        leftUse = draw.use(group).attr({"clip-path" : "url(#left-clip)",x:-(x2-x1),y:-(y2-y1)});
        left = draw.group().add(leftUse).add(leftClip).attr({id : "left", "transform" : "scale(-1,1) translate(0,0)"});

        //right
        rightRect = draw.rect(bleed, (objSize.boxHeight - bleed * 2)).attr({x: (objSize.boxWidth - bleed * 2), y: bleed});
        rightClip = draw.clip().attr({id : "right-clip"}).add(rightRect);
        rightUse = draw.use(group).attr({"clip-path" : "url(#right-clip)"});
        right = draw.group().add(rightUse).add(rightClip).attr({id : "right", "transform" : "scale(-1,1) translate(0,0)"});

        //bottom
        bottomRect = draw.rect((objSize.boxWidth - bleed * 2), bleed).attr({x: bleed, y: (objSize.boxHeight - bleed * 2)});
        bottomClip = draw.clip().attr({id : "bottom-clip"}).add(bottomRect);
        bottomUse = draw.use(group).attr({"clip-path" : "url(#bottom-clip)"});
        bottom = draw.group().add(bottomUse).add(bottomClip).attr({id : "bottom", "transform" : "scale(1,-1) translate(0,0)"});

          //top
          topRect =  draw.rect((objSize.boxWidth - bleed - x2), bleed).attr($.extend(attribute,{x: x2, y: bleed}));
          topClip = draw.clip().attr({id : "top-clip"}).add(topRect);
          topUse = draw.use(group).attr({"clip-path" : "url(#top-clip)"});
          top = draw.group().add(topUse).add(topClip).attr({id : "top", "transform" : "scale(1,-1) translate(0,0)"});

        defs = draw.defs().add(left).add(right).add(bottom).add(top);
        var pos = -180;
        var pos = bleed * 2;

        x_left =2*(x/D)*(y/D)*(y)+x2-x1;
        y_left = 2*(x/D)*(y/D)*(x)+y2-y1;
        y_left = -y_left;
        draw.use(left).attr({
            "id" : "left-use",
             x : x_left,
             y :y_left,
             "transform" : "rotate("+angle_clone+")"
         });
         draw.use(top).attr({"id" : "top-use", x : 0, y: bleed*2});
        draw.use(right).attr({"id" : "right-use", x : (objSize.boxWidth - bleed) * 2, y: 0});
        draw.use(bottom).attr({"id" : "bottom-use", x : 0, y: (objSize.boxHeight - bleed) * 2});
}
//Bleed
left = draw.path("M "+x1+" "+y1+" L "+x2+" "+y2+" L "+x3+" "+y3+" L "+x4+" "+y4+" Z ").attr(attribute);
top = draw.rect((objSize.boxWidth - bleed - x2), bleed).attr($.extend(attribute,{x: x2, y: 0}));
right = draw.rect(bleed, y - bleed*2).attr($.extend(attribute,{x: objSize.boxWidth - bleed, y: bleed}));
bottom = draw.rect((objSize.boxWidth - bleed - x3), bleed).attr($.extend(attribute,{x: x3, y: y - bleed}));
//End Bleed
//Polygon
topLeftPolygon = draw.path("M "+x+" 0 L "+x2+" 0 L "+x2+" "+y2+" L "+x1+" "+y1+" Z ").attr(whiteFill);
topRightPolygon = draw.path("M "+(objSize.boxWidth-bleed)+" 0 L "+objSize.boxWidth+" 0 L "+objSize.boxWidth+" "+(bleed)+" L "+(objSize.boxWidth-bleed)+" "+bleed+" Z ").attr(whiteFill);
bottomRightPolygon = draw.path("M "+(objSize.boxWidth-bleed)+" "+(y - bleed)+" L "+objSize.boxWidth+" "+(objSize.boxHeight - bleed)+" L "+objSize.boxWidth+" "+y+" L "+(objSize.boxWidth-bleed)+" "+y+" Z ").attr(whiteFill);
bottomLeftPolygon = draw.path("M "+x4+" "+y4+" L "+x3+" "+y3+" L "+x3+" "+y+" L 0 "+y+" Z ").attr(whiteFill);
/*draw.path("M"+x2+" "+y2+"L"+(objSize.boxWidth-bleed)+" "+bleed+"L"+(objSize.boxWidth-bleed)+" "+(y-bleed)+"L"+x3+" "+y3+" Z").attr(whiteFill);*/
var points = {
                "center" : {
                    "points": [
                        x2,                     y2,
                        objSize.boxWidth-bleed, bleed,
                        objSize.boxWidth-bleed, y-bleed,
                        x3,                     y3
                    ]
                },
                "left": {
                    "points": [
                        x1,                     y1,
                        x2,                     y2,
                        x3,                     y3,
                        x4,                     y4
                    ],
                    "angle":  angle_clone,
                    "length": Math.sqrt(Math.pow(x2 - x3,2) + Math.pow(y2 - y3,2))
                },
                "top":  {
                    "points": [
                        x2,                     y2,
                        objSize.boxWidth-bleed, bleed,
                        objSize.boxWidth-bleed, 0,
                        x2,                     0
                    ],
                    "length": Math.sqrt(Math.pow(objSize.boxWidth-bleed-x2,2) + Math.pow(bleed-y2,2))
                },
                "right": {
                    "points": [
                        objSize.boxWidth-bleed, bleed,
                        objSize.boxWidth,       bleed,
                        objSize.boxWidth,       y-bleed,
                        objSize.boxWidth-bleed, y-bleed
                    ],
                    "length": y-bleed*2
                },
                "bottom": {
                    "points": [
                        x3,                     y3,
                        objSize.boxWidth-bleed, y-bleed,
                        objSize.boxWidth-bleed, y,
                        x3, y,

                    ],
                    "length": Math.sqrt(Math.pow(objSize.boxWidth-bleed-x3,2) + Math.pow(y-bleed-y3,2))
                }
};
updatePoint(points);
updateBleed(bleed);
//End Polygon