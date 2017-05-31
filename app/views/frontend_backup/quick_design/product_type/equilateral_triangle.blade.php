//===============================================================================
var x0, x1, x2, x3, x4, x5, x6;
var y0, y1, y2, y3, y4;
var width_pt, height_pt, bleed_pt;

width_pt = objSize.boxWidth-2*objSize.boxBleed;
height_pt = objSize.boxHeight-2*objSize.boxBleed;
bleed_pt = objSize.boxBleed*(width_pt/objSize.boxWidth);


x1 = bleed_pt;
x3 = (width_pt+2*x1)/2;
x5 = width_pt+x1;

y1 = bleed_pt;
y3 = height_pt+y1;
y4 = y3 + bleed_pt;

//Tam giac ABC can o A
//tinh do dai canh AB
var AB = Math.sqrt( ((width_pt/2)*(width_pt/2)) + height_pt*height_pt);

// console.log(AB);
//Tinh 2 goc ke B va C
var AnphaB = Math.cos((width_pt/2)/AB); //radian
	// AnphaB = AnphaB*180/Math.PI; //do
// console.log(AnphaB);

//tinh x2, x4, x0, x6
var disx = Math.abs(Math.sin(AnphaB)*bleed_pt);
// console.log('test:'+disx);
x0 = x1 - disx;
x6 = x5 + disx;
x2 = x3 - disx;
x4 = x3 + disx;

//tinh y0, y2
var disy = Math.abs(Math.cos(AnphaB)*bleed_pt);
// console.log(disy);
y0 = y1 - disy;
y2 = y3 - disy;


console.log(x0);
console.log(x1);
console.log(x2);
console.log(x3);
console.log(x4);
console.log(x5);
console.log(x6);
console.log("================");
console.log(y0);
console.log(y1);
console.log(y2);
console.log(y3);
console.log(y4);


var more =bleed_pt;

d0 = "M " + x2 + " " + y0 + " L " + x3 + " " + y1 + " L " + x1 + " " + y3 + " L " + x0 + " " + y2 + " Z"; // left edge
d1 = "M " + x4 + " " + y0 + " L " + x6 + " " + y2 + " L " + x5 + " " + y3 + " L " + x3 + " " + y1 + " Z"; // right edge
d2 = "M " + x1 + " " + y3 + " L " + x5 + " " + y3 + " L " + x5 + " " + y4 + " L " + x1 + " " + y4 + " Z"; // bottom edge
d3 = "M " + (x0-more) + " " + (y0-more) + " L " + (x6+more) + " " + (y0-more) + " L " + (x6+more) + " " + (y4+more) + " L " + (x0-more) + " " + (y4+more) + " L " + (x0-more) + " " + y4 + " L " + x1 + " " + y4 + " L " + x5 + " " + y4 + " L " + x5 + " " + y3 + " L " + x6 + " " + y2 + " L " + x4 + " " + y0 + " L " + x3 + " " + y1  + " L " + x2 + " " + y0 + " L " + x0 + " " + y2  + " L " + x1 + " " + y3 + " L " + x1 + " " + (y4+more/2) + " L " + (x0-more) + " " + (y4+more/2) + " Z"; // fill white out

//Ve Tam giac
draw.path(d0).attr({fill:objEdgeColor.color,'fill-opacity': 0.5,stroke:'white','stroke-width': 0}); // left edge
draw.path(d1).attr({fill:objEdgeColor.color,'fill-opacity': 0.5,stroke:'white','stroke-width': 0}); // right edge
draw.path(d2).attr({fill:objEdgeColor.color,'fill-opacity': 0.5,stroke:'white','stroke-width': 0}); // bottom edge
//draw.path(d3).attr({fill:'white','fill-opacity': 1,stroke:'white','stroke-width': 0}); // fill white out

var points = [
                x3,    y1,
                x1,    y3,
                x5,    y3,
];
updatePoint(points);
updateBleed(bleed);
//End Tam giac