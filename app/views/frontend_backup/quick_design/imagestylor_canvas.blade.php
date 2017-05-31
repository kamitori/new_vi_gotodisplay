<script type="text/javascript">
    $( "#dialog" ).dialog({
        resizable: false,
        draggable:false,
        modal: true,
        width: 900,
        minHeight:500,
        autoOpen: false,
    });
    selectWrapFrame('{{$border_frame}}','');
    @if(!$multi_piece)
        var draw = SVG('svg_main').size(objSize.boxWidth, objSize.boxHeight).attr({'id':"svg_svg_main"});
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
    @else
        var draw = SVG('svg_main').size(1100, 400);
        draw_multi_piece();
    @endif
    @if( isset($svgInfo['options']['wrap']) )
    $('input[name=frame_style][title="{{ $svgInfo['options']['wrap'] }}"]').click();
    @endif
    @if( isset($svgInfo['options']['bleed']) )
    $('input[name=bleed_style][value*="{{ $svgInfo['options']['bleed'] }}"]').click();
    @endif
    function reset_global_variable(){
        current_position = 0;
    }
    function draw_multi_piece(){

        @foreach($svg_setup as $key =>$val)
            current_position = parseInt({{$key}},10);
            drawDesignSVG( objImg[{{$key}}], objSize[{{$key}}], objOrientation, objEdgeColor[{{$key}}]);
        @endforeach
    }
    @if($multi_piece)
        reset_global_variable();
        addHoverClipPathByID();
    @endif
    function removeHoverClipPathByID(){
        $("#polygon-"+current_image).attr({'stroke-width':'','stroke':''});
    }
    function addHoverClipPathByID(){
        $("#polygon-"+current_image).attr({'stroke-width':'3px','stroke':'red'});
    }
    $( "#slider-vertical" ).slider({
        orientation: "vertical",
        range: "max",
        step: 5,
        min: 0,
        max: 360,
        value: objImg.rotate,
        slide: function( event, ui ) {
            $( "#amount" ).val( ui.value); //  + " º"
            sliderotateImage(ui.value);
        }
    });
    $( "#amount" ).val( $( "#slider-vertical" ).slider( "value" ) );
    $( "#amount" ).change(function(){
        var val = $( "#amount" ).val();
        $( "#slider-vertical" ).slider({
            orientation: "vertical",
            range: "max",
            step: 5,
            min: 0,
            max: 360,
            value: val,
        });
        sliderotateImage(val);
    });
    $("#amount").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    function chooseImage(id){
        removeHoverClipPathByID();
        current_image = id.target.id;
        addHoverClipPathByID();
    }
    $("#zoom-slider").slider({
        orientation: "vertical",
        range: "max",
        step: 0.2,
        min: 1,
        max: 3.6,
        value: objImg.zoom,
        slide: function( event, ui ) {
            objImg.zoom =  parseFloat(ui.value);
            $("#zoomImage").val(objImg.zoom);
            objImg.resetXY = true;
            $("#svg_main").html('');
            drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
        }
    });
    setTimeout(function(){
        $("input[name=frame_style]:checked").trigger("click");
    }, 200);
    setTimeout(function(){
        $("input[name=custom_quantity]").trigger("change");
    }, 200);
    function resetSetup(){
        $("#zoomImage").val(1);
        objImg.resetXY = true;
    }
    function change_layout(layout_id,type){
        layout_id = parseInt(layout_id,10);
        var svg_width = $("#editPageContainer").width();
        var svg_length = $("#editPageContainer").height();
        $.ajax({
            url: "{{URL}}/drawFrame",
            type:"POST",
            data: { txt_piece:layout_id,txt_type:type,svg_width:svg_width,svg_length:400},
            beforeSend:function(){
                if(layout_id>1){
                    $("#svg_main").css('margin-left','20px');
                }
                $("#svg_main").empty();
                // return false;
                //
            },
            success: function(data_return){
                for(var i=0;i<data_return.length;i++){
                    var current_temp = data_return[i];
                    var sysTimeOut;
                    var objImg ={};
                    var objSize = {};
                    var objOrientation = {};
                    var objEdgeColor = {};
                    //Image
                    objImg.url = 'http://vi.anvyonline.com/assets/upload/themes/14.161.71.220/Music-image-music-36556275-1680-1050.jpg';
                    objImg.zoom = 1;
                    objImg.rotate = 0;
                    objImg.filter = 'original';
                    objImg.resetXY = true;
                    objImg.flip_x = objImg.flip_y = 1;
                    objImg.x = current_temp.image.x;
                    objImg.y = current_temp.image.y;

                    //Size
                    objSize.boxWidth = svg_width;
                    objSize.boxHeight = 400;
                    objSize.boxBleed = current_temp.svg.bleed;
                        objSize.boxBleedInc = 1;
                        objSize.viewZoom = current_temp.svg.view_dpi;

                    //objEdgeColor
                    objEdgeColor.points = current_temp.polygon.points;
                    objEdgeColor.rect = current_temp.rect;
                    var polygon_clip_path_points = '';
                    for(var j=0;j<current_temp.rect.length;j++){
                        polygon_clip_path_points += (current_temp.rect[j].x + current_temp.svg.bleed) + ',' + (current_temp.rect[j].y + current_temp.svg.bleed);
                        if(j<current_temp.rect.length-1) polygon_clip_path_points +=', ';
                    }
                    objEdgeColor.polygon_clip_path = polygon_clip_path_points;
                    objEdgeColor.path = current_temp.path;
                    drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
                    // $("#svg_svg_main").attr('width',svg_width);
                    // $("#svg_svg_main").attr('height',svg_length);
                }
                // $("#svg_main").empty();
            }
        });
    }
    function changeOneImage(objectOrSrc){
        if( typeof objectOrSrc == 'object' ) {
            url = $(objectOrSrc).attr('src');
        } else {
            url = objectOrSrc;
        }
        objImg.url = url;
        $img = new Image();
        $img.src = url;
        $img.onload = function(){
            width = this.width;
            height = this.height;
            //alert(width+' x ' + height);
            check_list_quality(width,height);
            objImg.zoom = 1;
            $("#svg_main").html('');
            resetSetup();
            drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
        }
    }
    function changePieceImage(url){
        if( typeof objectOrSrc == 'object' ) {
            url = $(objectOrSrc).attr('src');
        } else {
            url = objectOrSrc;
        }
        var data_position = parseInt($("#"+current_image).attr('data-position'),10);
        objImg[data_position].url = url;
        $img = new Image();
        $img.src = url;
        $img.onload = function(){
            width = this.width;
            height = this.height;
            //alert(width+' x ' + height);
            check_list_quality(width,height);
            objImg.zoom = 1;
            $("#svg_main").html('');
            resetSetup();
            $("#svg_main").html('');
            draw = SVG('svg_main').size(1100, 400);
            after_position = data_position;
            draw_multi_piece();
            current_position = data_position;
            addHoverClipPathByID();
        }
    }
    function changeImage(objectOrSrc){
         @if(!$multi_piece)
            changeOneImage(objectOrSrc);
        @else
            changePieceImage(objectOrSrc);
        @endif
    }
    function switchImage(url,obj){
        objImg.url = url;
        $("#content_my_upload img").removeClass('imgFocus');
        $(obj).addClass("imgFocus");
        $img = new Image();
        $img.src = url;
        $img.onload = function(){
            //width = this.width;
            //height = this.height;
            //alert(width+' x ' + height);
            //check_list_quality(width,height);
            //objImg.zoom = 1;
            //resetSetup();
            //drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
        }
        /*if (selected == 0)
            alert("Please choose object to add image");
        else
            addImage(selected);*/
    }
    function zoomOneImage(){
       objImg.zoom = parseFloat($("#zoomImage").val())+0.2;
        $("#zoomImage").val(objImg.zoom);
        $("#zoom-slider").slider({
            orientation: "vertical",
            range: "max",
            step: 0.2,
            min: 1,
            max: 3.6,
            value: objImg.zoom,
        });
        // $('body').css('zoom',(objImg.zoom*100)+'%');
        // $('body').css('zoom',objImg.zoom);
        objImg.resetXY = true;
        $("#svg_main").html('');
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
    }
    function zoomPieceImage(){
        var data_position = parseInt($("#"+current_image).attr('data-position'),10);
        objImg[data_position].zoom = parseFloat($("#zoomImage").val())+0.2;
        $("#zoomImage").val(objImg[data_position].zoom);
        $("#zoom-slider").slider({
            orientation: "vertical",
            range: "max",
            step: 0.2,
            min: 1,
            max: 3.6,
            value: objImg[data_position].zoom,
        });
        objImg[data_position].resetXY = true;
        $("#svg_main").html('');
        draw = SVG('svg_main').size(1100, 400);
        after_position = data_position;
        draw_multi_piece();
        current_position = data_position;
        addHoverClipPathByID();
    }
    function zoomInImage(){
        @if(!$multi_piece)
            zoomOneImage();
        @else
            zoomPieceImage();
        @endif
    }
    function zoomOutOneImage(){
        objImg.zoom = parseFloat($("#zoomImage").val())-0.2;
        if(objImg.zoom<1)
            objImg.zoom =1;
        $("#zoomImage").val(objImg.zoom);
        $("#zoom-slider").slider({
            orientation: "vertical",
            range: "max",
            step: 0.2,
            min: 1,
            max: 3.6,
            value: objImg.zoom,
        });
        objImg.resetXY = true;
        $("#svg_main").html('');
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
    }
    function zoomOutPieceImage(){
        var data_position = parseInt($("#"+current_image).attr('data-position'),10);
        objImg[data_position].zoom = parseFloat($("#zoomImage").val())-0.2;
        if(objImg[data_position].zoom<1)
            objImg[data_position].zoom =1;
        $("#zoomImage").val(objImg[data_position].zoom);
        $("#zoom-slider").slider({
            orientation: "vertical",
            range: "max",
            step: 0.2,
            min: 1,
            max: 3.6,
            value: objImg[data_position].zoom,
        });
        objImg[data_position].resetXY = true;
        $("#svg_main").html('');
        draw = SVG('svg_main').size(1100, 400);
        after_position = data_position;
        draw_multi_piece();
        current_position = data_position;
        addHoverClipPathByID();
    }
    function zoomOutImage(){
        @if(!$multi_piece)
            zoomOutOneImage();
        @else
            zoomOutPieceImage();
        @endif

    }
    function rotateImage(){
        @if(!$multi_piece)
            rotateOneImage();
        @else
            rotatePieceImage();
        @endif

    }
    function show_log(data){
        console.log(data);
    };
    function rotatePieceImage(){
        var data_position = parseInt($("#"+current_image).attr('data-position'),10);
        objImg[data_position].rotate = parseFloat($("#rotateImage").val());
        objImg[data_position].rotate = Math.round(objImg[data_position].rotate/90)*90+90;
        if(objImg[data_position].rotate>=360)
            objImg[data_position].rotate = 0;
        $("#amount").val( objImg[data_position].rotate );
        $("#amount").change();
        $("#rotateImage").val(objImg[data_position].rotate);
        objImg[data_position].resetXY = true;
        $("#svg_main").html('');
        draw = SVG('svg_main').size(1100, 400);
        after_position = data_position;
        draw_multi_piece();
        current_position = data_position;
        addHoverClipPathByID();
    }
    function rotateOneImage(){
        objImg.rotate = parseFloat($("#rotateImage").val());
        objImg.rotate = Math.round(objImg.rotate/90)*90+90;
        if(objImg.rotate>=360)
            objImg.rotate = 0;
        $("#amount").val( objImg.rotate );
        $("#amount").change();
        $("#rotateImage").val(objImg.rotate);
        objImg.resetXY = true;
        $("#svg_main").html('');
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
    }
    function slideRotateOneImage(x){
        objImg.rotate = parseFloat(x);
        if(objImg.rotate>=360)
            objImg.rotate = 0;
        $("#rotateImage").val(objImg.rotate);
        objImg.resetXY = true;
        $("#svg_main").html('');
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
    }
    function slideRotateSlideImage(x){
        var data_position = parseInt($("#"+current_image).attr('data-position'),10);
        objImg[data_position].rotate = parseFloat(x);
        if(objImg[data_position].rotate>=360)
            objImg[data_position].rotate = 0;
        $("#rotateImage").val(objImg[data_position].rotate);
        objImg[data_position].resetXY = true;
         $("#svg_main").html('');
        draw = SVG('svg_main').size(1100, 400);
        after_position = data_position;
        draw_multi_piece();
        current_position = data_position;
        addHoverClipPathByID();
    }
    function sliderotateImage(x){
        @if(!$multi_piece)
            slideRotateOneImage(x);
        @else
            slideRotateSlideImage(x);
        @endif
    }
    function flipImageX(){
        @if(!$multi_piece)
            flipOneImageX();
        @else
            flipPieceImageX();
        @endif
    }
    function flipPieceImageX(){
        var data_position = parseInt($("#"+current_image).attr('data-position'),10);
        objImg[data_position].resetXY = true;
        if(objImg[data_position].flip_x ==1)
            objImg[data_position].flip_x = -1;
        else
            objImg[data_position].flip_x = 1;
        $("#svg_main").html('');
        draw = SVG('svg_main').size(1100, 400);
        after_position = data_position;
        draw_multi_piece();
        current_position = data_position;
        addHoverClipPathByID();

    }
    function flipOneImageX(){
        objImg.resetXY = true;
        if(objImg.flip_x ==1)
            objImg.flip_x = -1;
        else
            objImg.flip_x = 1;
        $("#svg_main").html('');
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
    }
    function flipOneImageY(){
        objImg.resetXY = true;
        if(objImg.flip_y ==1)
            objImg.flip_y = -1;
        else
            objImg.flip_y = 1;
        $("#svg_main").html('');
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
    }
    function flipPieceImageY(){

        var data_position = parseInt($("#"+current_image).attr('data-position'),10);
         objImg[data_position].resetXY = true;
        if(objImg[data_position].flip_y ==1)
            objImg[data_position].flip_y = -1;
        else
            objImg[data_position].flip_y = 1;
        $("#svg_main").html('');
        draw = SVG('svg_main').size(1100, 400);
        after_position = data_position;
        draw_multi_piece();
        current_position = data_position;
        addHoverClipPathByID();

    }
    function flipImageY(){
         @if(!$multi_piece)
            flipOneImageY();
        @else
            flipPieceImageY();
        @endif
    }
    function resolution(){
        $("#dialog_resolution").dialog({width: 900,height: 600}).dialog("open");
        $.ajax({
            url:"{{URL}}/collections/analyze_image",
            type:"POST",
            data:{img: $("#main_image").attr("href")},
            success: function(ret){
                ret = JSON.parse(ret);
                var html = '';
                html += '<div id="content">';
                    html += '<div style="float:left; margin-right: 20px">';
                        html += '<img style="width: 385px " src="'+ ret.image +'" />';
                    html += '</div>';
                    html += '<div class="info">';
                    html += ' <ul >';
                                    html += '<li><h2>About your picture: </h2></li>';
                                    html += '<li>Your file size: <b>'+ret.size+'</b> MB </li>';
                                    html += '<li>Your file resolution: <b>'+ret.width+'</b> by <b>'+ret.height+'</b> pixels </li>';
                                    html += '<li><b>'+ret.mp+'</b> Megapixels</li>';
                        html += '</ul>';
                    html += '</div>';
                    html += '<div class="clear"></div>';
                    html += '<table id="result" border="0" cellpadding="0" cellspacing="0">';
                    for(var i in ret.dimensions){
                        html += '<tr>';
                        html += '<td width="220" valign="top" class="txmedium" style="padding:10px;spacing:5px">';
                        html += '<b>'+ret.dimensions[i][0]+'x</b><b>'+ret.dimensions[i][1]+' inches</b>';
                        html += '</td>';
                        html += '<td class="tx2" style="padding:10px;spacing:5px">';
                        html += ret.dimensions[i][3];
                        html += '</td>';
                        html += '</tr>';
                    }
                    html += '</table>';
                html += '</div>'
                $("#dialog_resolution").html(html);
            }
        });
    }
    function rotateFrame(){
        var rotate = parseFloat($("#rotateFrame").val())+90;
        if(rotate>=360)
            rotate = 0;
        $("#rotateFrame").val(rotate);
        objImg.rotateFrame = rotate;
        delete objImg.oldX;
        delete objImg.oldY;
        var sizes = $("input:radio[name=sizes]:checked").val();
        var w,h;
        if(sizes!='' || sizes.indexOf("x")==-1){
           sizes = sizes.split("x");
           w = parseFloat(sizes[0]);
           h = parseFloat(sizes[1]);
           if(rotate==90 || rotate==270)
                rebuildFrame(h,w);
           else
                rebuildFrame(w,h);
        }else
            console.log('Can not found size');
    }
    function check_size(width,height,x_inch,y_inch){
        var rating;
        var diagonal = Math.sqrt(x_inch*x_inch + y_inch*y_inch);
        var viewdis = 1.5 * diagonal;
        var ppineed = 3438/viewdis;
        var ppi = (width*height)/(x_inch*y_inch);
        var quantity = ppi/ppineed;
        if ( (width/height <= 1 && x_inch/y_inch<=1) || (width/height >= 1 && x_inch/y_inch>=1) ) {
            if (quantity>=100) {
                rating = 1 //excellent
            } else if (quantity>=80) {
                rating = 2 //good
            } else if (quantity>=35) {
                rating = 3 //fair
            } else {
                rating = 4 //poor
            }
        } else {
            rating = 4 //poor
        }
        return rating;
    }
    function changeSize(sizes,price){ // sizes = 10x12
        var src = $("#main_image").attr('href'); // Sv
        var img = new Image();
        if( src ) {
            img.src = src;
            img.onload = function(){
                var width = this.width;
                var height = this.height;
                check_list_quality(width,height);
            }
            var width = img.width; // pixel
            var height = img.height; // pixel
        } else {
            var width = 0, height = 0;
        }
        var w,h;
        if(sizes!='' && sizes.indexOf("x")!=-1){
            sizes = sizes.split("x");
            w = parseFloat(sizes[0]); // inch
            h = parseFloat(sizes[1]); // inch
            //check Result
            var rating = check_size(width,height,w,h);
            if (rating==1) {
                $("#quan_custom").html("With selected size: Excellent");
                $("#quan_custom").css("color", "#009900");
            } else if (rating==2) {
                $("#quan_custom").html("With selected size: Good");
                $("#quan_custom").css("color", "rgb(0,0,255)");
            } else if (rating==3) {
                $("#quan_custom").html("With selected size: Fair");
                $("#quan_custom").css("color", "#6600CC");
            } else {
                $("#quan_custom").html("With selected size: Poor");
                $("#quan_custom").css("color", "rgb(112,112,112)");
            }
            var rotate = parseFloat($("#rotateFrame").val());
            if(rotate==90 || rotate==270)
                rebuildFrame(h,w);
            else
                rebuildFrame(w,h);
        }else
            console.log('Can not found size');

        price = caculationPrice(w,h,1, false);
        $("#name_price").html(' - $'+price);
        // $(".price_cal").html('$'+price);
    }
    function selectWrapFrame(types,color){
        if(types=='white' || types=="white_edge" || types == "w_frame"){
            objEdgeColor.color = 'white';
            objEdgeColor.opacity = 1;
            objEdgeColor.stroke = '#ddd';
            objEdgeColor.strokeWidth = 1;
        }else if(types=='black' || types == "blackedge" || types == "black_frame"){
            objEdgeColor.color = 'black';
            objEdgeColor.opacity = 1;
            objEdgeColor.stroke = 'black';
            objEdgeColor.strokeWidth = 0;
        }else if(types=='red'){
            var color = $("input:radio[name=frame_style]:checked").attr("rel");
            objEdgeColor.color = color;
            objEdgeColor.opacity = 1;
            objEdgeColor.stroke = color;
            objEdgeColor.strokeWidth = 0;
        }else if(types=='color'){
            objEdgeColor.color = color;
            objEdgeColor.opacity = 1;
            objEdgeColor.stroke = color;
            objEdgeColor.strokeWidth = 0;
        }else if(types=="silver_edge") {
            objEdgeColor.color = 'silver';
            objEdgeColor.opacity = 1;
            objEdgeColor.stroke = '#ddd';
            objEdgeColor.strokeWidth = 1;
        }else if(types=="m_frame") {
            objEdgeColor.color = '#4E0E0E';
            objEdgeColor.opacity = 1;
            objEdgeColor.stroke = '#4E0E0E';
            objEdgeColor.strokeWidth = 0;
        } else{
            objEdgeColor.color = '#ddd';
            objEdgeColor.opacity = 0.4;
            objEdgeColor.stroke = '#ddd';
            objEdgeColor.strokeWidth = 0;
        }
    }
    function changeWrapFrame(types,title,color){
        if(color==undefined)
            color = '';
        selectWrapFrame(types,color);
        objImg.resetXY = true;
        @if( $product['product_type']!=6 )
        	$("#svg_main").html('');
        @endif
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
        $("#name_border").html(' - '+title);
        if(types=='red'){
            $("#content_style").hide();
            $("#pick_color").show();
        }
    }
    function changeBleed(thickness){
        var rotate = parseFloat($("#rotateFrame").val());
        var sizes = $("input:radio[name=sizes]:checked").val();
        var w=objSize.boxWidth;
        var h=objSize.boxHeight;
        if(sizes!='' || sizes.indexOf("x")==-1){
           sizes = sizes.split("x");
           w = parseFloat(sizes[0]);
           h = parseFloat(sizes[1]);
           if(rotate==90 || rotate==270){
                var tmp = w;
                w=h;
                h=tmp;
           }
        }
        objSize.boxBleedInc = parseFloat(thickness);
        rebuildFrame(w,h);
        $("#name_bleed").html(' - '+thickness+'" depth');
        var size = $("[name=sizes]:checked").val();
        size = size.split("x");
        var widthInch = size[0];
        var heightInch = size[1];
        var quantity = $("#custom_quantity").val();
        var price = caculationPrice(widthInch,heightInch,quantity, true);
        $("#name_price").html(' - $'+price);
    }
    function changeBorder(border){
        var rotate = parseFloat($("#rotateFrame").val());
        var sizes = $("input:radio[name=sizes]:checked").val();
        var w=objSize.boxWidth;
        var h=objSize.boxHeight;
        if(sizes!='' || sizes.indexOf("x")==-1){
           sizes = sizes.split("x");
           w = parseFloat(sizes[0]);
           h = parseFloat(sizes[1]);
           if(rotate==90 || rotate==270){
                var tmp = w;
                w=h;
                h=tmp;
           }
        }
        objSize.boxBorderInc = parseFloat(border);
        rebuildFrame(w,h);
        $("#name_borders").html(' - '+border+'" ');
        $("input[type=radio].size_list:checked").click();

    }
    function filterImage(types){
        objImg.filter = types;
        objImg.resetXY = false;
        $("#svg_main").html('');
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
    }
    function rebuildFrame3(w,h,b){
        $.ajax({
            url: "{{URL}}/scaleSvg",
            type:"POST",
            data: { txt_content : $("#svg_main").html() },
            success: function(data_return){
                console.log(data_return);
            }
        });

    }
    function rebuildFrame(w,h,b){
        $.ajax({
            url: "{{URL}}/collections/{{$collection['short_name']}}/quick-design/{{$product['short_name']}}",
            type:"POST",
            data: { width:w, height:h,bleed:objSize.boxBleedInc,border:objSize.boxBorderInc},
            success: function(data_return){
                data_return = JSON.parse(data_return);
                objSize.boxWidth = data_return.svg.width;
                objSize.boxHeight = data_return.svg.height;
                objSize.boxBleed = data_return.svg.bleed;
                objSize.boxBorder = data_return.svg.border;
                objEdgeColor.points = data_return.polygon.points;
                objEdgeColor.borders = data_return.border.points;
                objEdgeColor.rect = data_return.rect;
                objImg.resetXY = true;
                @if($product['product_type']!=6 || $product['number_img']==NULL) //  $product['number_img'] = NULL(1)
                    $("#svg_main").html('');
                @endif
                // $("#svg_main").css('width',data_return.svg.width);
                drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
                $("#name_size").html(' ('+w+'"x'+h+'")');
            }
        });

    }
    function calSizeFitBox(img_w,img_h,box_w,box_h){
        var ret = {};
        if(box_w/box_h > img_w/img_h){ //fit width
            ret['w'] = box_w;
            ret['h'] = Math.round(img_h/(img_w/box_w));
            ret['scale'] = img_w/box_w;
            ret['scale_by'] = 'W';
        }else{
            ret['w'] = Math.round(img_w/(img_h/box_h));
            ret['h'] = box_h;
            ret['scale'] = img_h/box_h;
            ret['scale_by'] = 'H';
        }
        return ret;
    }

    function drawDesignSVG(objImg,objSize,objOrientation,objEdgeColor){
        $("#img-link").val("");
        var img_w,img_h;
        var defs;
        // var draw = SVG('svg_main').size(objSize.boxWidth, objSize.boxHeight);
        if($("#svg_main").html()==''){
            draw = SVG('svg_main').size(objSize.boxWidth, objSize.boxHeight);
        }
        @if( $product['product_type']==6 && $product['number_img'] ) //Attached file
            var content = $("#content_display").val();
            if(content!='' && $('#svg_main svg').length==1) {
                content_svg = draw.svg(content);
            }
            //$("#svg_main > svg > svg > g").attr("id", "layer1");
            //var g = SVG.get('layer1');
            //g.scale(scale_w, scale_h);
            // alert(scale_w + ' - ' + scale_h);
            path_mask = svg_main.getElementsByTagNameNS('*','path');
            $.each(path_mask,function(key,element){
                $(element).attr({
                    'transform':'scale('+scale_w+','+scale_h+')'
                });
            })
            // alert(scale_w + 'x' + scale_h);
            // **** Update draw *****
            draw.width(400*scale_w);
            draw.height(400*scale_h);

            @if ($product['number_img']>1)
                for(i = 1; i<={{$product['number_img']}}; i++){
                    image = eval('image'+i);
                    if( image != undefined){
                        var id_clippath;
                        svg_main = $('#svg_main')[0];
                        id_clippath = svg_main.getElementsByTagNameNS('*','clipPath')[i-1].id;
                        var naturalImg_width = image.width(); // kich thuoc that
                        var naturalImg_height = image.height();
                        mask_width  = $("#"+id_clippath)[0].getBoundingClientRect().width;
                        mask_height = $("#"+id_clippath)[0].getBoundingClientRect().height;
                        margin_top  = $("#"+id_clippath).offset().top - $("#svg_main > svg").offset().top;
                        margin_left = $("#"+id_clippath).offset().left - $("#svg_main > svg").offset().left;

                        if(naturalImg_width > naturalImg_height){
                            img_h = mask_height;
                            img_w = naturalImg_width*(img_h/naturalImg_height);
                            maxX  = img_w+margin_left;
                            maxY  = img_h+margin_top;
                            minY  = margin_top;
                            minX  = mask_width-img_w+margin_left;
                            if(img_w < mask_width){
                                img_w = mask_width;
                                img_h = naturalImg_height*(img_w/naturalImg_width);
                                maxX  = img_w+margin_left;
                                maxY  = img_h+margin_top;
                                minY  = margin_top - (img_h - mask_height);
                                minX  = mask_width-img_w+margin_left;
                            }
                        } else {
                            img_w = mask_width;
                            img_h = naturalImg_height*(img_w/naturalImg_width);
                            maxX  = img_w+margin_left;
                            maxY  = img_h+margin_top;
                            minY  = margin_top - (img_h - mask_height);
                            minX  = mask_width-img_w+margin_left;
                            if(img_h < mask_height){
                                mg_h  = mask_height;
                                img_w = naturalImg_width*(img_h/naturalImg_height);
                                maxX  = img_w + margin_left;
                                maxY  = img_h + margin_top;
                                minY  = margin_top;
                                minX  = mask_width - img_w + margin_left;
                            }
                        }
                        if(objImg.resetXY){
                            objImg.x = -1*(img_w-draw.width())/2; //-1*Math.round((img_w-draw.width())/2);
                            objImg.y = (draw.height()/2 - img_h)/2;//-1*Math.round((img_h-draw.height())/2);

                            //Flip only when resetXY
                            if(objImg.flip_x==-1){
                                objImg.x = -1*(draw.width()-objImg.x);
                            }
                            if(objImg.flip_y==-1){
                                objImg.y = -1*(draw.height()-objImg.y);
                            }
                        }
                        // if (typeof $("#svg_main > svg > svg") != 'undefined')
                        //         $("#svg_main > svg > svg").remove();
                        image.x(margin_left);
                        image.y(margin_top);
                        image.size(img_w, img_h);
                        image.draggable({minX:minX, maxX:maxX, minY:minY, maxY:maxY, flipX: objImg.flip_x, flipY: objImg.flip_y});
                    }
                }
	     		$("#svg_main > svg > svg > path").each(function(index, element1){
                    d = $(this).attr("d");
                    $.each($("#svg_main defs path"),function(key,element2){
                            d_e = $(element2).attr("d");
                            if(d==d_e){
                                $(element1).attr('id', 'path' +( key+1));
                            }
                    })

                });
                @for($i=1;$i<=$product['number_img'];$i++)
                    $("#path{{$i}}").on("click",function(){
                        selected = {{$i}};
                        current_image = "#path{{$i}}";
                        $("path").attr('stroke', 'none');
                        $(this).attr({stroke:"#FF8C00",
                                      "stroke-width":"2"});
                    });
                    $("#svg_main").on("click",".image{{$i}}",function(){
                        selected = {{$i}};
                        current_image = "#path{{$i}}";
                        $("path").attr('stroke', 'none');
                        $("#path{{$i}}").attr({stroke:"#FF8C00",
                                      "stroke-width":"2"});
                    });
                @endfor

            @else
                if ($(".image_type6").length)
                    $(".image_type6").remove();
                var id_clippath;
                svg_main = $('#svg_main')[0];
                id_clippath = svg_main.getElementsByTagNameNS('*','clipPath')[0].id;
                image = draw.image(objImg.url).loaded(function(loader){
                    var naturalImg_width = loader.width; // kich thuoc that
                    var naturalImg_height = loader.height;
                    mask_width  = $("#"+id_clippath)[0].getBoundingClientRect().width;
                    mask_height = $("#"+id_clippath)[0].getBoundingClientRect().height;
                    margin_top  = $("#"+id_clippath).offset().top - $("#svg_main > svg").offset().top;
                    margin_left = $("#"+id_clippath).offset().left - $("#svg_main > svg").offset().left ;

                    if(naturalImg_width > naturalImg_height){
                        img_h = mask_height;
                        img_w = naturalImg_width*(img_h/naturalImg_height);
                        maxX  = img_w+margin_left;
                        maxY  = img_h+margin_top;
                        minY  = margin_top;
                        minX  = mask_width-img_w+margin_left;
                        if(img_w<mask_width){
                            img_w = mask_width;
                            img_h = naturalImg_height*(img_w/naturalImg_width);
                            maxX  = img_w+margin_left;
                            maxY  = img_h+margin_top;
                            minY  = margin_top - (img_h - mask_height);
                            minX  = mask_width-img_w+margin_left;
                        }
                    }else{
                        img_w = mask_width;
                        img_h = naturalImg_height*(img_w/naturalImg_width);
                        maxX  = img_w+margin_left;
                        maxY  = img_h+margin_top;
                        minY  = margin_top - (img_h - mask_height);
                        minX  = mask_width-img_w+margin_left;
                        if(img_h < mask_height){
                            mg_h  = mask_height;
                            img_w = naturalImg_width*(img_h/naturalImg_height);
                            maxX  = img_w + margin_left;
                            maxY  = img_h + margin_top;
                            minY  = margin_top;
                            minX  = mask_width-img_w + margin_left;
                        }
                    }
                    if(objImg.resetXY){
                        objImg.x = -1*(img_w-draw.width())/2; //-1*Math.round((img_w-draw.width())/2);
                        objImg.y = (draw.height()/2 - img_h)/2;//-1*Math.round((img_h-draw.height())/2);
                        //Flip only when resetXY
                        if(objImg.flip_x == -1){
                            objImg.x = -1*(draw.width()-objImg.x);
                        }
                        if(objImg.flip_y==-1){
                            objImg.y = -1*(draw.height()-objImg.y);
                        }
                    }
                    // if (typeof $("#svg_main > svg > svg") != 'undefined')
                    //         $("#svg_main > svg > svg").remove();
                    this.x(margin_left);
                    this.y(margin_top);
                    this.size(img_w, img_h);
                    this.draggable({minX:minX, maxX:maxX, minY:minY, maxY:maxY, flipX: objImg.flip_x, flipY: objImg.flip_y});
                });
                image.attr({
                    'clip-path':"url(#"+id_clippath+")"
                });
                image.addClass('image_type6');
            @endif
        @else
            image = draw.image(objImg.url).attr({"id":"main_image", 'data-id': objImg.dataId}).loaded(function(loader){
                var naturalImg_width = loader.width;
                var naturalImg_height = loader.height;
                var k = 0;
                if(objImg.rotate==90 || objImg.rotate==270){ //rotate
                    naturalImg_width = loader.height;
                    naturalImg_height = loader.width;
                }
                var margin = objSize.boxBleed+objSize.boxBorder;
                var imgsize;
                if(objEdgeColor.opacity==1)
                    imgsize = calSizeFitBox( naturalImg_width, naturalImg_height, objSize.boxWidth-2*margin, objSize.boxHeight-2*margin);
                else
                    imgsize = calSizeFitBox( naturalImg_width, naturalImg_height, objSize.boxWidth, objSize.boxHeight);

                img_w = parseFloat(imgsize['w']*objImg.zoom); //Math.round(imgsize['w']*objImg.zoom);
                img_h = parseFloat(imgsize['h']*objImg.zoom); //Math.round(imgsize['h']*objImg.zoom);

                if(objImg.rotate==90 || objImg.rotate==270){ //Rotate
                    var tmp = img_h;
                    img_h = img_w;
                    img_w = tmp;
                    k= (objSize.boxWidth-objSize.boxHeight)/2;
                }
                minX = -1*(img_w-objSize.boxWidth)-k;
                maxX = img_w+k;
                minY = -1*(img_h-objSize.boxHeight)+k;
                maxY = img_h-k;
                if(objEdgeColor.opacity==1){
                    minX = minX-margin;
                    maxX = maxX+margin;
                    minY = minY-margin;
                    maxY = maxY+margin;
                }
                if(objImg.resetXY){
                    // alert(3);
                    objImg.x = -1*(img_w-objSize.boxWidth)/2; //-1*Math.round((img_w-objSize.boxWidth)/2);
                    objImg.y = -1*(img_h-objSize.boxHeight)/2;//-1*Math.round((img_h-objSize.boxHeight)/2);

                    // //Flip only when resetXY
                    // if(objImg.flip_x==-1){
                    //     objImg.x = -1*(objSize.boxWidth - objImg.x);
                    // }
                    // if(objImg.flip_y==-1){
                    //     objImg.y = -1*(objSize.boxHeight - objImg.y);
                    // }
                }

                //MoveCenterZoom(imgsize['scale_by'],img_w,img_h,naturalImg_width,naturalImg_height);
                if( objImg.oldX ) {
                    this.x(objImg.oldX);
                } else {
                    this.x(objImg.x);
                }
                if( objImg.oldY ) {
                    this.y(objImg.oldY);
                } else {
                    this.y(objImg.y);
                }
                this.size(img_w, img_h);
                @if(!$multi_piece)
                    this.rotate(objImg.rotate,objSize.boxWidth/2,objSize.boxHeight/2);
                @else
                    var current_rect = objEdgeColor.rect;
                    var current_width = current_rect[0].x + current_rect[1].x + current_rect[0].width*2;
                    var current_height = current_rect[1].y + current_rect[2].y + current_rect[0].height * 2;
                    show_log(current_width);
                    show_log(typeof current_width);
                    this.rotate(objImg.rotate,current_width/2,current_height/2);
                @endif
                this.scale(objImg.flip_x, objImg.flip_y);
                this.draggable({minX:minX, maxX:maxX, minY:minY, maxY:maxY, flipX: objImg.flip_x, flipY: objImg.flip_y});
            });
            image.dragmove = function(delta, event) {
                // redraw = true;
                var deltaX = this.x()-objImg.x;
                var deltaY = this.y()-objImg.y;
                objImg.x = this.x();
                objImg.y = this.y();
                objImg.zoomX -= deltaX;
                objImg.zoomY -= deltaY;

                if(defs != undefined){
                    draw.add(defs);
                }
            }
            @if(!$multi_piece)
                image.click(function(){
                    $('#zoom_bt').show();
                    $('#image_bt').show();
                    $(".slider_bt").show();
                });
            @else
                image.attr({'data-position':current_position});
                document.getElementById(image.node.id).onclick = chooseImage;
                if(first_run==0) current_image = image.node.id;
                if(after_position == current_position) current_image = image.node.id;
            @endif
            first_run++;
            $(".right_content").click(function(e){
                if ($(".right_content")[0] == e.target) {
                    $('#zoom_bt').hide();
                    $('#image_bt').hide();
                    $(".slider_bt").hide();
                }
            });

            var group = draw.group();
                group.add(image);
                // test add clip path
            if(typeof objEdgeColor.polygon_clip_path !='undefined'){
                var clip_path_polygon = draw.polygon(objEdgeColor.polygon_clip_path).attr({
                                                                fill: objEdgeColor.color,
                                                                'fill-opacity': objEdgeColor.opacity,
                                                                stroke: objEdgeColor.stroke,
                                                                'stroke-width': objEdgeColor.strokeWidth
                                                            });
                var clip_path = draw.clip().attr({id : "clip-"+image.node.id}).add(clip_path_polygon);
                image.attr('clip-path',"url(#clip-"+image.node.id+")");
            }
            // end test
            var bleed = objEdgeColor.rect[0].width;
            //mirror wrap

            //Filter
            @include('frontend.quick_design.filter_draw')
            // Include filter_draw tại đây để chạy filter

            if(objImg.filter=='grayscale'){
                group.filter(function(add) {
                  add.colorMatrix('saturate', 0);
                });
            }else if(objImg.filter=='sepia'){
                group.filter(function(add) {
                  add.colorMatrix('matrix', [ .543, .669, .119, 0, 0
                                            , .249, .626, .130, 0, 0
                                            , .172, .334, 0.2, 0, 0
                                            , .000, .000, .000, 1, 0 ]);
                });
            }
            var color = $("[name=frame_style]:checked").val();
            if(color==undefined)
                color = $("#wrapstyle").val();
            switch(color) {
                case "white":
                case "white_edge":
                case "w_frame":
                    attribute = {fill: "white",stroke:'#dddddd','stroke-width': 1};
                    break;
                case "black":
                case "blackedge":
                case "black_frame":
                    attribute = {fill: "black",stroke:'black','stroke-width': 1};
                    break;
                case "silver_edge":
                    attribute = {fill: "silver",stroke:'silver','stroke-width': 1};
                    break;
                default:
                    attribute = {fill: objEdgeColor.color,'fill-opacity': objEdgeColor.opacity,stroke: objEdgeColor.stroke,'stroke-width': objEdgeColor.strokeWidth};
                    break;
            }
            if( $("[name=frame_style]:checked").attr("id") == "opstyle_red" ) {
                attribute = {fill: color, stroke: color, 'stroke-width': 1};
            }
            attribute = $.extend(attribute, {"class": "bleed",'id': 'polygon-'+image.node.id});
            var whiteFill = {fill:'white',"stroke-width": 1,"stroke": "white"};


            <?php //================= Folder  themes/default/quick_design/ product_type/ ============== ?>

            @if( $product['product_type']==1 ) //Hexagon Shape
                @include('frontend..quick_design.product_type.hexagon_shape')

            @elseif( $product['product_type']==2 ) //Right Triangle Shape
                @include('frontend..quick_design.product_type.right_triangle_shape')

            @elseif( $product['product_type']==3 ) //Equilateral Triangle Shape
                @include('frontend..quick_design.product_type.equilateral_triangle_shape')

            @elseif( $product['product_type']==4 ) //Rhombus Shape
                @include('frontend..quick_design.product_type.rhombus_shape')

            @elseif( $product['product_type']==5 ) //Polygon 1 Shape
                @include('frontend..quick_design.product_type.polygon1_shape')

            @elseif( $product['product_type']==9 ) //Flower Split
                @include('frontend..quick_design.product_type.flower_split')
            //====================================================================================


            @else
            if( $("[name=frame_style]:checked").val() == "m_wrap" ) {
                     var     left, leftRect, leftUse, leftClip,
                            top, topRect, topUse, topClip,
                            right, rightRect, rightUse, rightClip,
                            bottom, bottomRect, bottomUse, bottomClip;
                    defs = draw.defs();
                    //left
                    leftRect = draw.rect(bleed, (objSize.boxHeight - bleed * 2)).attr({x: bleed, y: bleed});
                    leftClip = draw.clip().attr({id : "left-clip"}).add(leftRect);
                    leftUse = draw.use(group).attr({"clip-path" : "url(#left-clip)"});
                    left = draw.group().add(leftUse).add(leftClip).attr({id : "left", "transform" : "scale(-1,1) translate(0,0)"});
                    //top
                    topRect = draw.rect((objSize.boxWidth - bleed * 2), bleed).attr({x: bleed, y: bleed});
                    topClip = draw.clip().attr({id : "top-clip"}).add(topRect);
                    topUse = draw.use(group).attr({"clip-path" : "url(#top-clip)"});
                    top = draw.group().add(topUse).add(topClip).attr({id : "top", "transform" : "scale(1,-1) translate(0,0)"});
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


                    defs = draw.defs().add(left).add(top).add(right).add(bottom);
                    var pos = -180;
                    var pos = bleed * 2;
                    draw.use(left).attr({"id" : "left-use", x : pos, y: 0});
                    draw.use(top).attr({"id" : "top-use", x : 0, y: pos});
                    draw.use(right).attr({"id" : "right-use", x : (objSize.boxWidth - bleed) * 2, y: 0});
                    draw.use(bottom).attr({"id" : "bottom-use", x : 0, y: (objSize.boxHeight - bleed) * 2});
            }
            @if($product['product_type']==8)
                objEdgeColor.color = '{{URL}}/assets/images/Silver.jpg';
                var bordermargin = draw.polygon(objEdgeColor.borders).attr({
                                                                        fill: objEdgeColor.color,
                                                                        'fill-opacity': objEdgeColor.opacity,
                                                                        stroke: objEdgeColor.stroke,
                                                                        'stroke-width': 0
                                                                        ,'id': 'polygon-'+image.node.id

                                                                    });
                attribute.fill = objEdgeColor.color;
                attribute["stroke-width"] = 0;

            @endif

            var polygon = draw.polygon(objEdgeColor.points).attr(attribute);
            @if($product['product_type']==8)
                var polygon_map = draw.polygon(objEdgeColor.points).attr({
                                                                        fill: 'white',
                                                                        'fill-opacity': objEdgeColor.opacity,
                                                                        stroke: objEdgeColor.stroke,
                                                                        'stroke-width': 0
                                                                        ,'id': 'polygon-'+image.node.id
                                                                    });
                addShapeBox();

            @endif
            var arrRect = objEdgeColor.rect;
            var rect = {};
            for (key in arrRect) {
                rect[key] = draw.rect(bleed,bleed).x(arrRect[key].x).y(arrRect[key].y).attr({fill:'white','fill-opacity': 1,stroke:'white','stroke-width': 0,'id':'rect-'+image.node.id+'-'+key,'index':key});
            }
            @endif
            $("#newPreview").val();
            setTimeout(function(){
                image.dragmove();
            }, 100);

        @endif
    }
    function findIntersect(objectLine1, objLine2) {
        var x1 = objectLine1.x1;
        var y1 = objectLine1.y1;
        var x2 = objectLine1.x2;
        var y2 = objectLine1.y2;
        var x3 = objLine2.x1;
        var y3 = objLine2.y1;
        var x4 = objLine2.x2;
        var y4 = objLine2.y2;
        var b1 = x1-x2 != 0 ? x1-x2 : 1;
        var b2 = x3-x4 != 0 ? x3-x4 : 1;
        var a1 = (y1-y2)/b1;
        var a2 = (y3-y4)/b2;
        var x = (a1*x1 - a2*x3 - y1 + y3)/(a1-a2);
        var y = a1*(x-x1) + y1;
        return {x: x, y: y};
    }
    function updatePoint(points) {
        points = JSON.stringify(points);
        if( !$("#points").length ){
            $("body").append('<input type="hidden" name="points" id="points" />');
        }
        $("#points").val(points);
    }
    function updateBleed(bleed) {
        if( !$("#bleed").length ){
            $("body").append('<input type="hidden" name="bleed" id="bleed" />');
        }
        $("#bleed").val(bleed);
    }
    function uploadFiles(event,items,add){
        $('#loading_none').show();
        var files = event.target.files;
        var check =  true;
        for( var i = 0; i < files.length; i++ ) {
            var f = files[i];
            if (!f.type.match('image.*')) {
                alert("please upload image file");
                continue;
            }
            var reader = new FileReader();
            reader.readAsDataURL(f);
            $arr_remove = [];
            reader.onload = function(e){
                var src = e.target.result;
                var img = new Image();
                img.src = src;
                img.onload = function(){
                    var width = this.width;
                    var height = this.height;
                    if(check_list_quality(width,height)){
                        uploadImageHandler({ width: width, height: height, url: src, dataId: f.name.replace(/ /g, '') });
                        var data = new FormData();
                        $.each(files, function(key, value){
                            data.append(key, value);
                        });
                        $.ajax({
                            url: '/collections/gettheme/saveimg',
                            type: 'POST',
                            data: data,
                            cache: false,
                            dataType: 'json',
                            processData: false, // Don't process the files
                            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                            success: function(result)
                            {
                                $('#loading_none').hide();
                                var _data = result.data;
                                for(var key in _data){
                                    var src = '{{ URL }}'+_data[key].url+_data[key].files[0];
                                    var img = new Image();
                                    img.src = src;
                                    img.name = _data[key].files[0];
                                    img.onload = function() {
                                        var src = this.src;
                                        $('[data-id="'+ this.name +'"]').each(function(){
                                            $(this).attr('src', src);
                                            $(this).attr('href', src);
                                        });
                                    };
                                }
                            }
                        });
                    }else{
                        ///
                        var sizes = $("#name_size").text().trim().split("x");
                        var x_inch = parseFloat(sizes[0].replace("(",""));
                        var y_inch = parseFloat(sizes[1]);
                        var diagonal = Math.sqrt(x_inch*x_inch + y_inch*y_inch);
                        var viewdis = 1.5 * diagonal;
                        var ppineed = 3438/viewdis;
                        var w_need = Math.ceil(0.8*ppineed*x_inch);
                        var h_need = Math.ceil(0.8*ppineed*y_inch);
                        alert("please upload larger image.\nMinimum size: "+w_need+" px - "+h_need+" px");
                        $('#loading_none').hide();
                        check = check && false;
                        event.preventDefault();
                        return false;
                    }
                    
                    
                };
            };
        }
        $('#loading_none').hide();
    }
    function change_upload_image_one_image(result){
        if( result.files != undefined  ) {
            objImg.url = '{{URL}}'+result.url+result.files[0];
        } else {
            objImg.url = result.url;
        }
        objImg.dataId = '';
        if( result.dataId ) {
            objImg.dataId = result.dataId;
        }
         $(".ds_button").removeClass('ds_active');
        $("#dsbt_my_upload").addClass('ds_active');
        @if( $product['product_type']==6 && ($product['number_img']==2 || $product['number_img']==3))
            var html = "<img class='imgFocus photo img_upload ui-draggable' src='"+objImg.url+"' alt='title' onclick='changeImage(this);' />";
            $("#content_my_upload img").removeClass('imgFocus');
        @else
            var html = "<img class='photo img_upload ui-draggable' src='"+objImg.url+"' alt='title' data-id="+ objImg.dataId +" onclick='changeImage(this);' />";
            $("#svg_main").html('');
        @endif
        var another_img = '<div class="image_content">'+html+'</div>';
        $("#content_my_upload").prepend(html);
        $("#slider_image").prepend(another_img);
        $(".content_list").hide();
        $("#content_my_upload").css("display","table");
        resetSetup();
        drawDesignSVG( objImg, objSize, objOrientation, objEdgeColor);
        $("#newPreview").val(result.url+result.files);
        $("#content_my_upload img").draggable({
                    helper: "clone",
                    revert: "invalid"
            });
    }
     function change_upload_image_piece_image(result){
        var data_position = parseInt($("#"+current_image).attr('data-position'),10);
        objImg[data_position].url = '{{URL}}'+result.url+result.files[0];
        $(".ds_button").removeClass('ds_active');
        $("#dsbt_my_upload").addClass('ds_active');
        @if( $product['product_type']==6 && ($product['number_img']==2 || $product['number_img']==3))
            var html = "<img class='imgFocus photo' src='"+objImg[data_position].url+"' alt='title' onclick='changeImage(this);' />";
            $("#content_my_upload img").removeClass('imgFocus');
        @else
            var html = "<img class='photo' src='"+objImg[data_position].url+"' alt='title' onclick='changeImage(this);' />";
            $("#svg_main").html('');
            $("#svg_main").html('');
            draw = SVG('svg_main').size(1100, 400);
        @endif
        var another_img = '<div class="image_content">'+html+'</div>';
        $("#content_my_upload").prepend(html);
        $("#slider_image").prepend(another_img);
        $(".content_list").hide();
        $("#content_my_upload").css("display","table");
        resetSetup();

        after_position = data_position;
        draw_multi_piece();
        current_position = data_position;
        addHoverClipPathByID();

        $("#newPreview").val(result.url+result.files);
    }
    function uploadImageHandler(result){
        @if(!$multi_piece)
            change_upload_image_one_image(result);
        @else
            change_upload_image_piece_image(result);
        @endif
    }
    function check_list_quality(width,height){
        // var ele = $(".full_width tbody tr.range td[align='left']");
        // $.each(ele,function(index,value){
        //     var html_inch = $(value).html();
        //     var start = html_inch.indexOf('<span');
        //     html_inch = html_inch.slice(0,start);
        //     var arr_inch = html_inch.split("&nbsp;x&nbsp;");
        //     var rating = check_size(width,height,arr_inch[0],arr_inch[1]);
        //     if (rating==1) {
        //         $(value).children(".check_quality").html(" (Excellent)");
        //         $(value).css("color", "#009900");
        //     } else if (rating==2) {
        //         $(value).children(".check_quality").html(" (Good)");
        //         $(value).css("color", "rgb(0,0,255)");
        //     } else if (rating==3) {
        //         $(value).children(".check_quality").html(" (Fair)");
        //         $(value).css("color", "#6600CC");
        //     } else {
        //         $(value).children(".check_quality").html(" (Poor)");
        //         $(value).css("color", "rgb(112,112,112)");
        //     }
        // });
        var sizes = $("#name_size").text().trim().split("x");
        var w_inch = parseFloat(sizes[0].replace("(",""));
        var h_inch = parseFloat(sizes[1]);
        var rating = check_size(width,height,w_inch,h_inch);
        return (rating>0&&rating<3);
    }
    function customSize(el){
        // update lai $(input#sizes_custom)
        var maxW = {{$max_w}};
        var maxH = {{$max_h}};
        if($("#custom_width").val()>maxW){
            $("#custom_width").val(maxW);
        }

        if($("#custom_height").val()>maxH){
            $("#custom_height").val(maxH);
        }
        @if( $product['product_type_id'] >= 1 && $product['product_type_id'] <=5 )
        if( defaultRatio ) {
            var name = $(el).attr('name').replace('custom_', '');
            var width = $("#custom_width").val();
            var height = $("#custom_height").val();
            if( name == 'width' ) {
                width = parseFloat(width);
                height = width / defaultRatio;
                $("#custom_height").val( height.toFixed(2) );
            } else {
                height = parseFloat(height);
                width = height * defaultRatio;
                $("#custom_width").val( width.toFixed(2) );
            }
        }
        @endif

        $("#sizes_custom").val($("#custom_width").val()+'x'+$("#custom_height").val());
        $("#sizes_custom").attr("onclick","changeSize('"+$("#custom_width").val()+'x'+$("#custom_height").val()+"')");

        @if( $product['product_type']==6 )
            if ($(el).attr('id') == 'custom_width') {
                x = $(el).val()/$("#custom_height").val();
                if(x>1){
                    scale_w = 1;
                    scale_h = 1/x;
                }else{
                    scale_w = x;
                    scale_h = 1;
                }
            } else if ($(el).attr('id') == 'custom_height') {
                x = $(el).val()/$("#custom_width").val();
                if(x>1){
                    scale_h = 1;
                    scale_w = 1/x;
                }else{
                    scale_h = x;
                    scale_w = 1;
                }
            }
        @endif

        $(".size_list").prop('checked', false);
        $("#sizes_custom").prop('checked', true);
        changeSize($("#custom_width").val()+'x'+$("#custom_height").val());
    }
    function Preview(){
        $("#loading_wait").show();
        $("#close_preview").show();
        //$("#loading_wait").css("margin-left",parseInt($(".quick_design").width())-120);
        buildImage(100,100,'preview',function(result){
            $("#preview_content").hide();
            $("#cont3d").hide();
            $("#paletteContainer").hide();
            $("#paletteLabels").hide();
            $("#editPageContainer").css('width','100%');
            $("#image_box").show();
            $(".content").hide();
            $("#preview_box").show();
            $(".final_name").css("margin-left","0");
            $(".final_name").css("width","100%");
            $("#img_svg_precview").attr("src", result).load(function(){
                $("#loading_wait").hide();
            });
        });
    }
    function closePreview(){
        $(".content").css("display","table");
        $("#editAreaWorkArea").css("display","");
        $("#preview_box").hide();
        $("#preview_content").html("");
        $(".final_name").css("margin-left","30%");
        $(".final_name").css("width","70%");
        $("#loading_wait").hide();

        $("#paletteContainer").show();
        $("#paletteLabels").show();
        $("#editPageContainer").css('width','75%');

    }
    var process3D = false;
    function Preview3D(hidden, callBack){
        if( process3D )
            return false;
        process3D = true;
        $("#loading_wait").show();

        svg_content = $("#svg_main").html();
        svg_content = svg_content.replace('stroke="#FF8C00" stroke-width="2"','');
        svg_content =svg_content.replace('stroke="none" stroke-width="2"','');
        @if($product['category_id']==43)
            if($(current_image).length ){
                svg_content = '<svg id="svg2" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" height="400" width="400" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg" style="overflow: visible;">';
                    svg_content += $(current_image)[0].outerHTML;
                svg_content += '</svg>';
            }
        @endif
        var data = {svghtml: svg_content , product_id: {{$product["id"]}}, product_type: {{$product["product_type"] or 0}}, type: $("[name=frame_style]:checked").attr("rel")};
        if( $("#points").length ){
            data["points"] = $("#points").val();
            if( $("[name=frame_style]:checked").length ) {
                data["color"] = $("[name=frame_style]:checked").val();
            }
        }
        data["bleed"] = objSize.boxBleed;
        data["width"] = objSize.boxWidth;
        data["height"] = objSize.boxHeight;

        $.ajax({
            url : "{{ URL.'/3dpreview' }}",
            type: "POST",
            async:false,
            data: data,
            success: function(result){
                $(".tmp-image").remove();
                if( result.status == "error" ) {
                    $("#loading_wait").hide();
                    alert(result.message);
                } else {
                    if( hidden == undefined ) {
                        $("#paletteContainer").hide();
                        $("#paletteLabels").hide();
                        $("#editPageContainer").css('width','100%');
                    }
                    var i, img = 0, imgQty = 0;
                    if( result.hasOwnProperty("images") ) {
                        for(i in result.images) {
                            $("body").append('<img class="tmp-image" style="display:none" id="'+i+'-img" src="'+result.images[i]+'" />');
                            $("#"+i+"-img").load(function(){
                                img++;
                            });
                            imgQty++;
                        }
                    }
                    var interval = setInterval(function(){
                        if( img == imgQty ) {
                            clearInterval(interval);
                            if( hidden == undefined ) {
                                $("#image_box").hide();
                                $("#cont3d").show();
                                $(".content").hide();
                                $("#preview_content").html("").show();
                            }
                            build3D(result, callBack);
                            setTimeout(render(), 1000);
                            if( hidden == undefined ) {
                                $("#preview_box").show();
                                $(".final_name").css({"margin-left":"0", "width":"100%"});
                            }
                            $("#loading_wait").hide();
                        }
                    }, 200);
                }
            }
        });
    }
    function buildImage(w,h,mod,callback){ // w,h (px)
        @if(!$multi_piece)
            var svghtml = $("#svg_main").html();
            var data = {svghtml:svghtml, product_id:'{{$product["id"]}}',width:w,height:h,mod:mod,view_dpi:"{{$svg_setup['svg']['view_dpi']}}",remove_poly:objEdgeColor.opacity};
            if( $("#points").length ) {
                data["points"] = $("#points").val();
            }

            @if(isset($product['product_type']) && $product['product_type']==8)
                var url = '{{URL}}/buildsvg/fast-thums';
            @else
                var url = '{{URL}}/buildsvg/image';
            @endif

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(link){
                    var find = 'svg';
                    var re = new RegExp(find, 'g');
                    link = link.replace(re,"png");
                    $("#newPreview").val(link);
                    callback(link);
                }
            });
        @else
            @foreach($svg_setup as $key =>$val)
                var svghtml = $("#svg_main").html();
                var data = {svghtml:svghtml, product_id:'{{$product["id"]}}',width:w,height:h,mod:mod,view_dpi:"{{$svg_setup[$key]['svg']['view_dpi']}}",remove_poly:objEdgeColor.opacity};
                if( $("#points").length ) {
                    data["points"] = $("#points").val();
                }

                @if(isset($product['product_type']) && $product['product_type']==8)
                    var url = '{{URL}}/buildsvg/fast-thums';
                @else
                    var url = '{{URL}}/buildsvg/image';
                @endif

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: data,
                    success: function(link){
                        var find = 'svg';
                        var re = new RegExp(find, 'g');
                        link = link.replace(re,"png");
                        $("#newPreview").val(link);
                        callback(link);
                    }
                });
            @endforeach
        @endif

    }
    function doubleImage(imglink,callback){
        @if(!$multi_piece)
            var w=100;
            var h = 100;
            var mod = '';
            $.ajax({
                url: '{{URL}}/buildsvg/double-image',
                type: 'POST',
                data: {imglink:imglink,width:w,height:h,mod:mod,view_dpi:"{{$svg_setup['svg']['view_dpi']}}"},
                success: function(result){
                    $("#newPreview").val(result);
                    callback(result);
                }
            });
        @else
            @foreach($svg_setup as $key =>$val)
                var w=100;
                var h = 100;
                var mod = '';
                $.ajax({
                    url: '{{URL}}/buildsvg/double-image',
                    type: 'POST',
                    data: {imglink:imglink,width:w,height:h,mod:mod,view_dpi:"{{$svg_setup[$key]['svg']['view_dpi']}}"},
                    success: function(result){
                        $("#newPreview").val(result);
                        callback(result);
                    }
                });
            @endforeach
        @endif
    }


    function addShapeBox(){
        var width,height,margin_top,margin_left;
        width = objSize.boxWidth-2*objSize.boxBleed;
        height = objSize.boxBorder;
        margin_top = -400+objSize.boxHeight-objSize.boxBleed-objSize.boxBorder-21;
        margin_left= (($(".right_content").width()-objSize.boxWidth)/2 )+objSize.boxBleed+1;
        $("#shapdown_view_bot").css("width",width+"px");
        $("#shapdown_view_bot").css("height",height+"px");
        $("#shapdown_view_bot").css("margin-top",margin_top+"px");
        $("#shapdown_view_bot").css("margin-left",margin_left+"px");


        width = objSize.boxBorder;
        height = objSize.boxHeight-2*objSize.boxBleed-1;
        margin_top = -400+objSize.boxBleed-19;
        margin_left= (($(".right_content").width()-objSize.boxWidth)/2 )+objSize.boxWidth-objSize.boxBleed-objSize.boxBorder-2;
        $("#shapdown_view_right").css("width",width+"px");
        $("#shapdown_view_right").css("height",height+"px");
        $("#shapdown_view_right").css("margin-top",margin_top+"px");
        $("#shapdown_view_right").css("margin-left",margin_left+"px");

    }

 function update_img_upload(url){
            html = '<img class="imgFocus img_upload ui-draggable"  class="" src="'+url+'" alt="title" onclick="changeImage(this);">'
            $("#content_my_upload").prepend(html);
            $(".content_list").hide();
            $("#content_my_upload").css("display","table");
            $("#content_my_upload img").draggable({
                    helper: "clone",
                    revert: "invalid"
            });
}

function ChooseImage_fb(obj){
        $("#dialog").dialog("close");
        $("#loading_wait").show();
        var link_img = $(obj).attr("data-source");
        if($(obj).attr("data-ext"))
            ext = $(obj).attr("data-ext")
        else
            ext = '';
        var data = $(obj).data();
        $.ajax({
            url: '{{URL}}/socials/get-image',
                        type: 'POST',
                        async:false,
                        data:{
                            link:link_img,
                            ext:ext,
                            data: data
                        },
                        async:false,
                        dataType:'json',
                        success: function(result){
                            if(result.error==0){
                                changeImage(result.data);
                                update_img_upload(result.data);
                            }else{
                                alert("Error save image");
                            }
                        }
        })
        $("#loading_wait").hide();
    }

function MoveCenterZoom(scaleby,img_w,img_h,naturalImg_width,naturalImg_height){
    var zoomX = objSize.boxWidth/2; //toa do zoom luon la tam cua box
    var zoomY = objSize.boxHeight/2;
    //tam zoom so voi toa do hinh khi hinh co objImg.width, mac dinh la tam cua hinh
    if(objImg.zoomX==0 && objImg.zoomY==0){
        objImg.zoomX = naturalImg_width/2;
        objImg.zoomY = naturalImg_height/2;
    }
    if(objImg.width ==0 && objImg.height ==0){
        objImg.width = naturalImg_width;
        objImg.height = naturalImg_height;
    }
    var imgscale = 0;
    if(scaleby=='W')
        imgscale = objImg.width/img_w;
    else
        imgscale = objImg.height/img_h;
    var new_zoomX = objImg.zoomX/imgscale+objImg.x; //toa do tam moi so voi svg, sau khi da scale
    var new_zoomY = objImg.zoomY/imgscale+objImg.y;
    //set new
    objImg.zoomX = objImg.zoomX/imgscale; //tam zoom so voi goc toa do hinh
    objImg.zoomY = objImg.zoomY/imgscale;
    objImg.width = img_w;
    objImg.height = img_h;

    if((objImg.width-objImg.zoomX) >= (objSize.boxWidth-new_zoomX) && (objImg.height-objImg.zoomY) >= (objSize.boxHeight-new_zoomY)){
        objImg.x = objImg.x + (zoomX-new_zoomX);
        objImg.y = objImg.y + (zoomY-new_zoomY);
    }else{
        new_zoomX = objSize.boxWidth - objImg.width + objImg.zoomX;
        new_zoomY = objSize.boxHeight - objImg.height + objImg.zoomY;
        objImg.x = objImg.x + (zoomX-new_zoomX);
        objImg.y = objImg.y + (zoomY-new_zoomY);
    }
 }

     @include('frontend..quick_design.cal_price_function')

</script>