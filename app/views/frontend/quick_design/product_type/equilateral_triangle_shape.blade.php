//===============================================================================
//Tam giac iDE la tam giac trong
// i la dinh cua goc doi
// D,E la 2 dinh cua 2 goc ke
//DE la trung diem cua doan D-E

//Tam giac ABC bao gom bleed va tam giac iDE nam o trung tam
// A la dinh cua goc doi
// B,C la 2 dinh cua 2 goc ke
//BC la trung diem cua doan B-C

var x_A, y_A, x_B, x_C, y_BC,
    width_pt, height_pt,
    y_Ai, x_D, x_E, y_DE,
    AB, DE, fh, Ah, hi,
    x_f, y_f, x_g, y_g, x_m, y_m, x_n, y_n, x_r, y_r, x_t, y_t,
    d0, d1, d2, d3;

width_pt = objSize.boxWidth;
height_pt = objSize.boxHeight;

x_B = 0;
y_A = 0;
AB = Math.sqrt(width_pt*width_pt/4 + height_pt*height_pt);
x_C = width_pt + x_B;
x_A  = (x_C+x_B)/2;
y_BC = height_pt + y_A;
y_DE = y_BC-bleed;
y_Ai = y_A + 2*bleed*AB/width_pt;

hi = y_DE - y_Ai;
DE = hi * width_pt / height_pt; // tam giac dong dang
x_D = x_B + (width_pt - DE)/2;
x_E = x_C - (width_pt - DE)/2;

fh = bleed*(height_pt/AB); // fh la keo vuong goc xuong AH
Ah = fh*2*height_pt/width_pt;

x_f = x_A - fh;
y_f = y_A + Ah;

x_g = x_A + fh;
y_g = y_f;

x_m = x_D;
y_m = y_BC;

x_n = x_E;
y_n = y_BC;

x_r = x_D - fh;
y_r = y_DE - (y_Ai - Ah);

x_t = x_E + fh;
y_t = y_r;
AB = Math.sqrt(Math.pow(width_pt/2,2) + Math.pow(height_pt,2));

var angle_clone = Math.asin(((width_pt/2)/AB))*(180/Math.PI)*2;
if( $("[name=frame_style]:checked").val() == "m_wrap" ) {
    var     left, leftRect, leftUse, leftClip,
                top, topRect, topUse, topClip,
                right, rightRect, rightUse, rightClip,
                bottom, bottomRect, bottomUse, bottomClip;
        defs = draw.defs();
        //left




        leftRect = draw.path("M"+x_A+' '+y_Ai+' '+'L'+(x_A+(x_A-x_f))+' '+(y_Ai+(y_Ai-y_f))+' '+'L'+(x_D+(x_A-x_f))+' '+(y_DE+(y_Ai-y_f))+' '+'L'+x_D+' '+y_DE+' Z');
        leftClip = draw.clip().attr({id : "left-clip"}).add(leftRect);
        leftUse = draw.use(group).attr({"clip-path" : "url(#left-clip)",x:-(x_A-x_f),y:-(y_Ai-y_f)});
        left = draw.group().add(leftUse).add(leftClip).attr({id : "left", "transform" : "scale(-1,1) translate(0,0)"});

        //right
        rightRect = draw.path("M"+x_A+' '+y_Ai+' '+'L'+(x_A-(x_A-x_f))+' '+(y_Ai+(y_Ai-y_f))+' '+'L'+(x_E-(x_A-x_f))+' '+(y_DE+(y_Ai-y_f))+' '+'L'+x_E+' '+y_DE+' Z');
        rightClip = draw.clip().attr({id : "right-clip"}).add(rightRect);
        rightUse = draw.use(group).attr({"clip-path" : "url(#right-clip)",x:-(x_A-x_f),y:-(y_Ai-y_f)});
        right = draw.group().add(rightUse).add(rightClip).attr({id : "right", "transform" : "scale(-1,1) translate(0,0)"});

        //bottom
        bottomRect = draw.rect((objSize.boxWidth - bleed * 2), bleed).attr({x: bleed, y: (objSize.boxHeight - bleed * 2)});
        bottomClip = draw.clip().attr({id : "bottom-clip"}).add(bottomRect);
        bottomUse = draw.use(group).attr({"clip-path" : "url(#bottom-clip)"});
        bottom = draw.group().add(bottomUse).add(bottomClip).attr({id : "bottom", "transform" : "scale(1,-1) translate(0,0)"});

        defs = draw.defs().add(left).add(right);
        // var pos = -180;
        // var pos = bleed * 2;
        x_left =2*((width_pt/2)/AB)*(height_pt/AB)*(height_pt)+(x_A-x_f);
        y_left =2*((width_pt/2)/AB)*(height_pt/AB)*(width_pt/2)+(y_Ai-y_f);
        y_left = -y_left;
        draw.use(left).attr({
            "id" : "left-use",
             x : x_left,
             y :y_left,
             "transform" : "rotate("+angle_clone+")"
         });

        x_right =2*((width_pt/2)/AB)*(height_pt/AB)*(height_pt)-(x_A-x_f)*3;
        y_right =2*((width_pt/2)/AB)*(height_pt/AB)*(width_pt/2)-(y_Ai-y_f);
        // y_right = -y_right;
        draw.use(right).attr({
            "id" : "right-use",
             x : x_right,
             y :y_right,
             "transform" : "rotate("+(-angle_clone)+")"
         });

        // draw.use(right).attr({"id" : "right-use", x : (objSize.boxWidth - bleed) * 2, y: 0});
        draw.use(bottom).attr({"id" : "bottom-use", x : 0, y: (objSize.boxHeight - bleed) * 2});
}
// d0: chu nhat ben trai; d1: chu nhat ben phai; d2: chu nhat ben duoi; d3: vien ngoai xoa mat

d0 = "M " + x_r + " " + y_r + " L " + x_D + " " + y_DE + " L " + x_A + " " + y_Ai + " L " + x_f + " " + y_f + " Z"; // left edge
d1 = "M " + x_t + " " + y_t + " L " + x_E + " " + y_DE + " L " + x_A + " " + y_Ai + " L " + x_g + " " + y_g + " Z"; // right edge
d2 = "M " + x_m + " " + y_m + " L " + x_D + " " + y_DE + " L " + x_E + " " + y_DE + " L " + x_n + " " + y_n + " Z"; // bottom edge
d3 = "M -1 " + (height_pt+1) + " L -1 -1 L " + (width_pt+1) + " -1 L " + (width_pt+1) + " " + (height_pt+1) + " L " + x_n + " " + (y_n+1) + " L " + x_E + " " + y_DE + " L " + x_t + " " + y_t + " L " + x_g + " " + y_g + " L " + x_A + " " + y_Ai + " L " + x_f + " " + y_f + " L " + x_r + " " + y_r + " L " + x_D + " " + y_DE + " L " + x_m + " " + (y_m+1) + " Z"; //fill white out
// draw.path("M"+x_A+' '+y_Ai+' '+'L'+(x_A-(x_A-x_f))+' '+(y_Ai+(y_Ai-y_f))+' '+'L'+(x_E-(x_A-x_f))+' '+(y_DE+(y_Ai-y_f))+' '+'L'+x_E+' '+y_DE+' Z').attr({fill:"red"})
//Ve Tam giac
draw.path(d0).attr(attribute); // left edge
draw.path(d1).attr(attribute); // right edge
draw.path(d2).attr(attribute); // bottom edge
draw.path(d3).attr({fill:'#FFF','fill-opacity': 1,stroke:'white','stroke-width': 0}); // fill white out
//draw.path("M"+x_A+" "+y_Ai+"L" +x_D+" "+y_DE+"L" +x_E+" "+y_DE +"Z").attr({fill:"red"}); //fill center
var points = {

                "center": {
                    "points": [
                        x_A,    y_Ai,
                        x_E,    y_DE,
                        x_D,    y_DE
                    ]
                },
                "left": {
                    "points": [
                        x_D,    y_DE,
                        x_A,    y_Ai,
                        x_f,    y_f,
                        x_r,    y_r
                    ],
                    "angle":  angle_clone,
                    "length": Math.sqrt(Math.pow(x_A - x_B,2) + Math.pow(y_A - y_BC,2))
                },
                "right": {
                    "points": [
                        x_E,    y_DE,
                        x_A,    y_Ai,
                        x_g,    y_g,
                        x_t,    y_t
                    ],
                    "angle": -angle_clone,
                    "length": Math.sqrt(Math.pow(x_A - x_B,2) + Math.pow(y_A - y_BC,2))
                },
                "bottom": {
                    "points": [
                        x_D,    y_DE,
                        x_E,    y_DE,
                        x_n,    y_n,
                        x_m,    y_m,
                    ],
                    "length": Math.sqrt(Math.pow(x_B - x_C,2))
                }
};
updatePoint(points);
updateBleed(bleed);
//End Tam giac