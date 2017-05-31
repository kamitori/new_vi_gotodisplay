<script src="{{URL}}/assets/js/pms.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/rgbcolor.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/StackBlur.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/canvg.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
// ================== Pick color form image ======================//
	var canvasThums=document.getElementById("canvas_imgs");

	function pickColorHex(x,y){
		var canvasThumsS=canvasThums.getContext("2d");
		var cData = canvasThumsS.getImageData(x, y, 1, 1).data;
	    var mr = cData[0];//Math.round((cData[0] + cData[4] + cData[8] + cData[12] + cData[16] + cData[20] + cData[24]+ cData[28]+ cData[32]) / 9 );
	    var mg = cData[1];//Math.round((cData[1] + cData[5] + cData[9] + cData[13] + cData[17]+ cData[21]+ cData[25]+ cData[29]+ cData[33]) / 9) ;
	    var mb = cData[2];//Math.round((cData[2] + cData[6] + cData[10] + cData[14] + cData[18]+ cData[22]+ cData[26]+ cData[30]+ cData[34]) / 9) ;
	    var hex = dec2hex(mr) + dec2hex(mg) + dec2hex(mb);
	    return hex;
	}
	function pickColorFromImage(){
		canvg('canvas_imgs', $("div#svg_main").html(), {
			renderCallback: function(){
				$("#svg_main").click( function(e){
					var offset = $(this).offset();
					offsetLeft = Math.round(offset.left);
					offsetTop = Math.round(offset.top);
					x = e.pageX - offsetLeft-17;
					y = e.pageY - offsetTop+29;
					var hex = pickColorHex(x,y);
					$("#colorhex").val(hex.toUpperCase());
					$("#colorbg").css("backgroundColor","#"+hex);
					var c = hex2hsb(hex);
					setColor(c, true, 'HEX');
					moveColorByHex(hex);
					changeWrapFrame('color','Spot Colour','#'+hex);
					$("#svg_main").css("cursor"," url({{URL}}/assets/images/cursor-colorpicker.cur), pointer");
					$("#svg_main image").css("cursor"," url({{URL}}/assets/images/cursor-colorpicker.cur), pointer");
				    $("input:radio[name=frame_style]:checked").attr('rel','#'+hex);
				});
				$("#svg_main").css("cursor"," url({{URL}}/assets/images/cursor-colorpicker.cur), pointer");
				$("#svg_main image").css("cursor"," url({{URL}}/assets/images/cursor-colorpicker.cur), pointer");
			}/*, forceRedraw: function(){
				var update = redraw;
				redraw = false;
				return update;
			}*/
		});
	}

	function closePickColor(){
		inprogress = 0;
  		var context = canvasThums.getContext('2d');
  		context.clearRect(0, 0, canvasThums.width, canvasThums.height);
  		$("#svg_main").unbind("click");
		$("#svg_main").css("cursor"," auto");
		$("#svg_main image").css("cursor"," move");
		$('.paletteContent').removeClass('active');
    	$('#paletteContentOptions').addClass('active');
	}
	function ReDrawCanvas(){
		//ve lai canvas neu dang pick color form image
	    var cursor_svg = $("#svg_main").css('cursor');
	    if(cursor_svg.indexOf('image')!=-1){
	    	$("#svg_main").load(
	    		function(){
	    			pickColorFromImage();
	    		}
	    	);
	    }
	}


</script>

