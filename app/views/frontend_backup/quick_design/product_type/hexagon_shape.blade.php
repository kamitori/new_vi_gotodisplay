var color,
    x, y, halfHeight,
    D, a, b, c, d,
    intersect,
    x1, y1,
    x2, y2,
    x3, y3,
    x4, y4;

x = objSize.boxWidth / 4.5;
y = objSize.boxHeight / 4.5;
halfHeight = objSize.boxHeight / 2;
y = halfHeight;
//Triangle
draw.path("M -1 0 L "+x+" 0 L -1  "+halfHeight+" Z ").attr(whiteFill);
draw.path("M "+(objSize.boxWidth-x)+" 0 L "+(objSize.boxWidth+1)+" "+halfHeight+" L "+(objSize.boxWidth+1)+" 0 Z ").attr(whiteFill);
draw.path("M "+(objSize.boxWidth-x)+" "+objSize.boxHeight+" L "+(objSize.boxWidth+1)+" "+objSize.boxHeight+" L "+(objSize.boxWidth+1)+" "+halfHeight+" Z ").attr(whiteFill);
draw.path("M -1  "+halfHeight+" L -1 "+objSize.boxHeight+" L "+x+" "+objSize.boxHeight+" Z ").attr(whiteFill);
//End Triangle

D =  Math.sqrt( Math.pow(x,2) + Math.pow(halfHeight,2));
a = (bleed * D / halfHeight);
b = Math.sqrt( Math.pow(a,2) - Math.pow(bleed,2));
c = halfHeight * b / D;
d = Math.sqrt( Math.pow(b,2) - Math.pow(c,2));

intersect = findIntersect({x1: x + a - d, y1: c, x2: a, y2: halfHeight}, {x1: 0, y1: bleed, x2: objSize.boxWidth, y2: bleed});

x1 = d+intersect.x-a;
y1 = intersect.y - c;

x2 = intersect.x;
y2 = intersect.y;

x3 = a;
y3 = halfHeight;

x4 = d;
y4 = halfHeight - c;
cons_x = objSize.boxWidth-(2*x2);
var angle_clone = Math.asin(((x)/D))*(180/Math.PI)*2;
if( $("[name=frame_style]:checked").val() == "m_wrap" ) {
    var     topleft, topleftRect, topleftUse, topleftClip,
                botleft, botleftRect, botleftUse, botleftClip,
                topright, toprightRect, toprightUse, toprightClip,
                botright, botrightRect, botrightUse, botrightClip,
                top, topRect, topUse, topClip,
                bottom, bottomRect, bottomUse, bottomClip;
        defs = draw.defs();

        //topleft
        topleftRect = draw.path("M"+x2+' '+y2+' '+'L'+(x2+(x2-x1))+' '+(y2+(y2-y1))+' '+'L'+(x3+(x2-x1))+' '+(y3+(y2-y1))+' '+'L'+x3+' '+y3+' Z');
        topleftClip = draw.clip().attr({id : "topleft-clip"}).add(topleftRect);
        topleftUse = draw.use(group).attr({"clip-path" : "url(#topleft-clip)",x:-(x2-x1),y:-(y2-y1)});
        topleft = draw.group().add(topleftUse).add(topleftClip).attr({id : "topleft", "transform" : "scale(-1,1) translate(0,0)"});

        //topright
        toprightRect = draw.path("M"+(cons_x+x2)+' '+y2+' '+'L'+(cons_x+x2-(x2-x1))+' '+(y2+(y2-y1))+' '+'L'+((objSize.boxWidth-x3)-(x2-x1))+' '+(y3+(y2-y1))+' '+'L'+(objSize.boxWidth-x3)+' '+y3+' Z')
        toprightClip = draw.clip().attr({id : "topright-clip"}).add(toprightRect);
        toprightUse = draw.use(group).attr({"clip-path" : "url(#topright-clip)",x:-(x2-x1),y:-(y2-y1)});
        topright = draw.group().add(toprightUse).add(toprightClip).attr({id : "topright", "transform" : "scale(-1,1) translate(0,0)"});

        //botleft
        botleftRect = draw.path("M"+x3+' '+y3+' '+'L'+(x3+(x2-x1))+' '+(y3-(y2-y1))+' '+'L'+(x2+(x2-x1))+' '+((objSize.boxHeight-y2)-(y2-y1))+' '+'L'+x2+' '+(objSize.boxHeight-y2)+' Z')
        botleftClip = draw.clip().attr({id : "botleft-clip"}).add(botleftRect);
        botleftUse = draw.use(group).attr({"clip-path" : "url(#botleft-clip)",x:-(x2-x1),y:-(y2-y1)});
        botleft = draw.group().add(botleftUse).add(botleftClip).attr({id : "botleft", "transform" : "scale(-1,1) translate(0,0)"});

        //botright
        botrightRect = draw.path("M"+(objSize.boxWidth - x3)+' '+y3+' '+'L'+((objSize.boxWidth - x3)-(x2-x1))+' '+(y3-(y2-y1))+' '+'L'+(cons_x+x2-(x2-x1))+' '+((objSize.boxHeight-y2)-(y2-y1))+' '+'L'+(cons_x+x2)+' '+(objSize.boxHeight-y2)+' Z')
        botrightClip = draw.clip().attr({id : "botright-clip"}).add(botrightRect);
        botrightUse = draw.use(group).attr({"clip-path" : "url(#botright-clip)",x:-(x2-x1),y:-(y2-y1)});
        botright = draw.group().add(botrightUse).add(botrightClip).attr({id : "botright", "transform" : "scale(-1,1) translate(0,0)"});

         //top
        topRect =draw.rect((objSize.boxWidth - x2*2), bleed).attr({x: x2, y: y2});
        topClip = draw.clip().attr({id : "top-clip"}).add(topRect);
        topUse = draw.use(group).attr({"clip-path" : "url(#top-clip)"});
        top = draw.group().add(topUse).add(topClip).attr({id : "top", "transform" : "scale(1,-1) translate(0,0)"});

        //bottom
                    bottomRect =  draw.rect((objSize.boxWidth - x2 * 2), bleed).attr({x: x2, y: (objSize.boxHeight - bleed * 2)});
                    bottomClip = draw.clip().attr({id : "bottom-clip"}).add(bottomRect);
                    bottomUse = draw.use(group).attr({"clip-path" : "url(#bottom-clip)"});
                    bottom = draw.group().add(bottomUse).add(bottomClip).attr({id : "bottom", "transform" : "scale(1,-1) translate(0,0)"});

        defs = draw.defs().add(topleft).add(topright).add(botleft).add(botright).add(top).add(bottom);
        var pos = -180;
        var pos = bleed * 2;
        d_x = x2-x;


        x_topleft =2*(x/D)*(y/D)*(y)+x2-x1;
        y_topleft = 2*(x/D)*(y/D)*(x)+y2-y1;
        y_topleft = -y_topleft;
        draw.use(topleft).attr({
            "id" : "topleft-use",
             x : x_topleft,
             y :y_topleft,
             "transform" : "rotate("+angle_clone+")"
         });

        x_botleft =2*(x/D)*(y/D)*(y)-(x2-x1);
        y_botleft = 2*(x/D)*(y/D)*(x)-(y2-y1)*3;
        x_botleft = -x_botleft;
        y_botleft = -y_botleft;
        draw.use(botleft).attr({
            "id" : "botleft-use",
             x : x_botleft,
             y :y_botleft,
             "transform" : "rotate("+(-angle_clone)+")"
         });

        x_topright =3.5*(2*(x/D)*(y/D)*(y))-(x2-x1)*3;
        y_topright = 2*(x/D)*(y/D)*(x+cons_x+2*d_x)-(y2-y1);
        // y_topright = -y_topright;
        draw.use(topright).attr({
            "id" : "topright-use",
             x : x_topright,
             y :y_topright,
             "transform" : "rotate("+(-angle_clone)+")"
         });

        x_botright =5.5*(2*(x/D)*(y/D)*(y))-(x2-x1)*3
        y_botright = 2*(2*(x/D)*(y/D)*(x))-(y2-y1)*2 + (2*(x/D)*(y/D)*(x+cons_x+2*d_x)-(y2-y1));
        y_botright = -y_botright;
        draw.use(botright).attr({
            "id" : "botright-use",
             x : x_botright,
             y :y_botright,
             "transform" : "rotate("+(angle_clone)+")"
         });

        draw.use(top).attr({"id" : "top-use", x : 0, y: pos});
        draw.use(bottom).attr({"id" : "bottom-use", x : 0, y: (objSize.boxHeight - bleed) * 2});


}

//Bleed
bleedTopLeft = draw.path("M "+x1+" "+y1+" L "+x2+" "+y2+" L "+x3+" "+y3+" L "+x4+" "+y4+" Z ").attr(attribute);
bleedTop = draw.rect((objSize.boxWidth - x2*2), bleed).attr($.extend(attribute,{x: x2, y:0}));

bleedTopRight = draw.path("M "+(objSize.boxWidth - x1)+" "+y1+" L "+(objSize.boxWidth - x2)+" "+y2+" L "+(objSize.boxWidth - x3)+" "+y3+" L "+(objSize.boxWidth - x4)+" "+y4+" Z ").attr(attribute);
bleedBottomRight = draw.path("M "+(objSize.boxWidth - x3)+" "+y3+" L "+(objSize.boxWidth - x2)+" "+(objSize.boxHeight - y2)+" L "+(objSize.boxWidth - x1)+" "+(objSize.boxHeight - y1)+" L "+(objSize.boxWidth - x4)+" "+(objSize.boxHeight - y4)+" Z ").attr(attribute);

bleedBottom = draw.rect((objSize.boxWidth - x2*2), bleed).attr($.extend(attribute,{x: x2, y: objSize.boxHeight - bleed}));
bleedBottomLeft = draw.path("M "+x3+" "+y3+" L "+x2+" "+(objSize.boxHeight - y2)+" L "+x1+" "+(objSize.boxHeight - y1)+" L "+x4+" "+(objSize.boxHeight - y4)+" Z ").attr(attribute);
//End Bleed
//Polygon
topLeftPolygon = draw.path("M 0 0 L "+x2+" 0 L "+x2+" "+y2+" L "+x1+" "+y1+" Z ").attr($.extend(whiteFill, {'class': 'extra-polygon'}));
topRightPolygon = draw.path("M "+objSize.boxWidth+" 0 L "+(objSize.boxWidth- x2)+" 0 L "+(objSize.boxWidth- x2)+" "+y2+" L "+(objSize.boxWidth- x1)+" "+y1+" Z ").attr($.extend(whiteFill, {'class': 'extra-polygon'}));

rightPolygon = draw.path("M "+(objSize.boxWidth - x3)+" "+y3+" L "+(objSize.boxWidth - x4)+" "+y4+" L "+(objSize.boxWidth+10)+" "+halfHeight+" L "+(objSize.boxWidth - x4)+" "+(objSize.boxHeight - y4)+" Z ").attr($.extend(whiteFill, {'class': 'extra-polygon'}));

bottomRightPolygon = draw.path("M "+(objSize.boxWidth- x2)+" "+(objSize.boxHeight - bleed)+" L "+(objSize.boxWidth- x2)+" "+objSize.boxHeight+" L "+objSize.boxWidth+" "+objSize.boxHeight+" L "+(objSize.boxWidth - x1)+" "+(objSize.boxHeight - y1)+" Z ").attr($.extend(whiteFill, {'class': 'extra-polygon'}));
bottomLeftPolygon = draw.path("M "+x2+" "+(objSize.boxHeight - bleed)+" L "+x2+" "+objSize.boxHeight+" L 0 "+objSize.boxHeight+" L "+x1+" "+(objSize.boxHeight - y1)+" Z ").attr($.extend(whiteFill, {'class': 'extra-polygon'}));
leftPolygon = draw.path("M "+x3+" "+y3+" L "+x4+" "+y4+" L-10 "+halfHeight+" L "+x4+" "+(objSize.boxHeight - y4)+" Z ").attr($.extend(whiteFill, {'class': 'extra-polygon'}));
//End Polygon
/*draw.path("M"+x2+" "+y2+"L "+(objSize.boxWidth - x2)+" "+y2+"L"+(objSize.boxWidth - x3)+" "+y3+"L"+(objSize.boxWidth - x2)+" "+(objSize.boxHeight - y2)+"L"+x2+" "+(objSize.boxHeight - bleed)+"L"+x3+" "+y3+"Z").attr(whiteFill);*/


var points = {

            "center": {
                "points": [
                    x2,                     y2,
                    objSize.boxWidth - x2,  y2,
                    objSize.boxWidth - x3,  y3,
                    objSize.boxWidth - x2,  objSize.boxHeight - y2,
                    x2,                     objSize.boxHeight - bleed,
                    x3,                     y3
                ]
            },
           "top": {
                "points": [
                    x2,                        0,
                    objSize.boxWidth - x2,     0,
                    objSize.boxWidth - x2,     bleed,
                    x2,                        bleed,
                ],
                "length": Math.sqrt(Math.pow( objSize.boxWidth,2))
            },
            "bottom": {
                "points": [
                    x2,                        objSize.boxHeight - bleed,
                    objSize.boxWidth - x2,     objSize.boxHeight - bleed,
                    objSize.boxWidth - x2,     objSize.boxHeight,
                    x2,                        objSize.boxHeight,
                ],
                "length": Math.sqrt(Math.pow( objSize.boxWidth,2))
            },
            "top_left": {
                "points": [
                    x1,    y1,
                    x2,    y2,
                    x3,    y3,
                    x4,    y4
                ],
                "angle": angle_clone,
                "length": Math.sqrt(Math.pow( x3 - x2,2 ) + Math.pow( y3 - y2,2 ))
            },
            "top_right": {
                "points": [
                    objSize.boxWidth - x1,    y1,
                    objSize.boxWidth - x2,    y2,
                    objSize.boxWidth - x3,    y3,
                    objSize.boxWidth - x4,    y4
                ],
                "angle": -angle_clone,
                "length": Math.sqrt(Math.pow( x3 - x2,2 ) + Math.pow( y3 - y2,2 ))
            },
            "bottom_right": {
                "points": [
                    objSize.boxWidth - x3,    y3,
                    objSize.boxWidth - x2,    objSize.boxHeight - y2,
                    objSize.boxWidth - x1,    objSize.boxHeight - y1,
                    objSize.boxWidth - x4,    objSize.boxHeight - y4,
                ],
                "angle": angle_clone,
                "length": Math.sqrt(Math.pow( x3 - x2,2 ) + Math.pow( y3 - y2,2 ))
            },
            "bottom_left": {
                "points": [
                    x3,    y3,
                    x2,    objSize.boxHeight - y2,
                    x1,    objSize.boxHeight - y1,
                    x4,    objSize.boxHeight - y4,
                ],
                "angle": -angle_clone,
                "length": Math.sqrt(Math.pow( x3 - x2,2 ) + Math.pow( y3 - y2,2 ))
            },

};
updatePoint(points);
updateBleed(bleed);
