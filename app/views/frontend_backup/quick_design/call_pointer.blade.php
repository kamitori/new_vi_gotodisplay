<script type="text/javascript">
var pointA = {'x':8,'y':9};
var pointB = {'x':5,'y':7};
var pointC = {'x':15,'y':8};
var distance = 10;

//var BleedPoint = FindBleedPoint(pointA,pointB,pointC,distance);
//console.log(BleedPoint);

//vi du: hinh co tao do A, tam xoay B, goc xoay 30
var NewPoint = GetNewCoor(pointA,pointB,30);

function GetNewCoor(pointO,pointA,anpha){
	var newPoint={};
	newPoint.x = parseFloat(pointA.x-pointO.x)*parseFloat(Math.cos(parseFloat(anpha*(Math.PI / 180)))) - parseFloat(pointA.y-pointO.y)*parseFloat(Math.sin(parseFloat(anpha*(Math.PI / 180)))) + pointO.x;
	newPoint.y = parseFloat(pointA.y-pointO.y)*parseFloat(Math.cos(parseFloat(anpha*(Math.PI / 180)))) + parseFloat(pointA.x-pointO.x)*parseFloat(Math.sin(parseFloat(anpha*(Math.PI / 180)))) + pointO.y;
	newPoint.x = parseFloat(newPoint.x.toFixed(5));
	newPoint.y = parseFloat(newPoint.y.toFixed(5));
	return NewPoint;
}

function FindBleedPoint(pointA,pointB,pointC,distance){
	var perpendicular_line = linear_equations(pointA,pointB,"perpendicular");
	var line = linear_equations(pointA,pointB,"normal");
	var parallel_line = linear_parallel(line,distance);
	var node_0 = coor_node(perpendicular_line,parallel_line[0]);
	var node_1 = coor_node(perpendicular_line,parallel_line[1]);
	var result = {};
	if(!(two_point_same_located(line,pointC,node_0))){
		result = node_0;
	}else if(!(two_point_same_located(line,pointC,node_1))){
		result = node_1;
	}
	// console.log(perpendicular_line);
	// console.log(line);
	// console.log(parallel_line);
	// console.log(node_0);
	// console.log(node_1);
	// console.log(result);
	return result;
}
function linear_equations(pointA,pointB,mode){
	var vectorAB = {};
	var normalvector = {};
	vectorAB.x = pointB.x - pointA.x;
	vectorAB.y = pointB.y - pointA.y;
	gcl = greatest_common_divisor(vectorAB.x,vectorAB.y);
	vectorAB.x = vectorAB.x/gcl;
	vectorAB.y = vectorAB.y/gcl;
	normalvector.x = -1*vectorAB.y;
	normalvector.y = vectorAB.x;
	var line = {};
	if(mode=='normal'){
		line.a = normalvector.x;
		line.b = normalvector.y;
		line.c = normalvector.x*pointA.x + normalvector.y*pointA.y;
	}else{
		line.a = vectorAB.x;
		line.b = vectorAB.y;
		line.c = vectorAB.x*pointA.x + vectorAB.y*pointA.y;
	}
	return line;
}
function linear_parallel(line,distance){
	var line_parallel={},line_parallel_1={};
	var arr_line = [];
	if(line.a==0 && line.b!=0){
		line_parallel.a = 0;
		line_parallel.b = 1;
		line_parallel.c = (line.c/line.b)+distance;
		arr_line[0] = line_parallel;
		line_parallel_1.a = 0;
		line_parallel_1.b = 1;
		line_parallel_1.c = (line.c/line.b)-distance;
		arr_line[1] = line_parallel_1;
	} else if(line.a!=0 && line.b==0){
		line_parallel.a = 1;
		line_parallel.b = 0;
		line_parallel.c = (line.c/line.a)+distance;
		arr_line[0] = line_parallel;
		line_parallel_1.a = 1;
		line_parallel_1.b = 0;
		line_parallel_1.c = (line.c/line.a)-distance;
		arr_line[1] = line_parallel_1;
	}else{
		line_parallel.a = line.a;
		line_parallel.b = line.b;
		line_parallel.c = (distance*Math.sqrt(line.a*line.a + line.b*line.b)) - line.c;
		line_parallel_1.a = line.a;
		line_parallel_1.b = line.b;
		line_parallel_1.c = -1*(distance*Math.sqrt(line.a*line.a + line.b*line.b)) - line.c;
		arr_line[0] = line_parallel;
		arr_line[1] = line_parallel_1;
	}
	return arr_line;
}
function coor_node(lineA,lineB){
	var m={};
	var tmp = lineA.a*lineB.b - lineA.b*lineB.a;
	if(tmp!=0)
		m.y = (lineA.a*lineB.c - lineA.c*lineB.a)/tmp;
	else
		m.y = 0;
	if(lineA.a!=0)
		m.x = (lineA.c - lineA.b*m.y)/lineA.a;
	return m;
}
function two_point_same_located(line,pointA,pointB){
	var check_pA = line.a*pointA.x + line.b*pointA.y - line.c;
	var check_pB = line.a*pointB.x + line.b*pointB.y - line.c;
	if( (check_pA>0 && check_pB>0) || (check_pA<0 && check_pB<0)){
		return true;
	}else{
		return false;
	}
}
function greatest_common_divisor(a,b){
	if(a==undefined || b==undefined)
		return 1;
	var minnum,gcl;
	if(a<b) minnum = a; else minnum = b;
	var i = 1;
	do {
	    if(a%i==0 && b%i==0)
	    	gcl = i;
	    i++;
	}
	while (i <= minnum);
		return gcl;
}

</script>