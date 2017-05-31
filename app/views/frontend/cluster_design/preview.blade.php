<script type="text/javascript">
    var last_zoom = 1;
    
    function Preview(){
        var key = current_image.replace("img_","");
        var boxH = $("#editPageContainer").height();
        var boxW = $("#editPageContainer").width();
        $("#preview_content").css("width",boxW+'px');
        $("#preview_content").css("height",boxH+'px');
        $("#preview_content").css("display","none");
        $("#preview_image").css("display","none");
        $("#image_box").css("display","block");
        $("#preview_box").css("display","block");
        $("#editAreaWorkArea").css("display","none");
        $("#image_bt").css("display","none");
        $("#zoom_bt").css("display","none");
        $("#zoom_bt2").css("display","block");
        $("#preview_content").html('');
        last_zoom = zoom_size;
        zoomToSvg(2.0);
        reDrawOneBox('2d');
    }
    function buildImage(callBack){
        var svghtml = $("#tmp_svg").html();
        var data = {svghtml:svghtml, product_id:'{{$product["id"]}}',width:100,height:100,mod:'preview',view_dpi:1,remove_poly:objEdgeColor.opacity};
        $.ajax({
            url: "{{URL}}/buildsvg/image",
            type: 'POST',
            data: data,
            success: function(link){
                var d = new Date();
                callBack(link+'?t='+d.getTime());
            }
        });
    }
    function Preview3D(hidden, callBack){        
        var key = current_image.replace("img_","");

        var boxH = $("#editPageContainer").height();
        var boxW = $("#editPageContainer").width();
        $("#preview_content").css("width",boxW+'px');
        $("#preview_content").css("height",boxH+'px');
        $("#preview_content").css("display","none");
        $("#preview_image").css("display","none");
        $("#image_box").css("display","block");
        $("#preview_box").css("display","block");
        $("#editAreaWorkArea").css("display","none");
        $("#image_bt").css("display","none");
        $("#zoom_bt").css("display","none");
        $("#zoom_bt2").css("display","block");
        $("#preview_content").html('');
        last_zoom = zoom_size;
        zoomToSvg(1.5);
        reDrawOneBox('3d', hidden, callBack);
    }
    var process3D = false;
    function preBuild3D(callBack,hidden){
        var key = current_image.replace("img_","");
        if( process3D )
            return false;
        process3D = true;
        var svg_content = $("#tmp_svg").html();
        svg_content = svg_content.replace('stroke="#FF8C00" stroke-width="2"','');
        svg_content =svg_content.replace('stroke="none" stroke-width="2"','');
        var data = {svghtml: svg_content , product_id: {{$product["id"]}}, product_type: 0, type: $("[name=frame_style]:checked").attr("rel")};
        if( $("#points").length ){
            data["points"] = $("#points").val();
            if( $("[name=frame_style]:checked").length ) {
                data["color"] = $("[name=frame_style]:checked").val();
            }
        }
        data["bleed"] = Math.round(objImg[key].bleed);
        data["width"] = Math.round(objImg[key].boxWidth);
        data["height"] = Math.round(objImg[key].boxHeight);

        $.ajax({
            url : "{{ URL.'/3dpreview' }}",
            type: "POST",
            data: data,
            success: function(result){
                $(".tmp-image").remove();
                if( result.status == "error" ) {
                    alert(result.message);
                } else {
                    var i, img = 0, imgQty = 0;
                    if( result.hasOwnProperty("images") ) {
                        for(i in result.images) {
                            $("body").append('<img class="tmp-image" style="display:none" id="'+i+'-img" src="/assets/images/libs/1698608.25-05-15-1432542599.jpg" />');
                            $("#"+i+"-img").load(function(){
                                img++;
                            });
                            imgQty++;
                        }
                    }
                    var interval = setInterval(function(){
                        if( img == imgQty ) {
                            clearInterval(interval);
                            console.log(result)
                            build3D(result, callBack);
                        }
                    }, 200);
                }
            }
        });

    }
    var PreviewThemeZoom = 1;
    function zoomInTheme(){
        PreviewThemeZoom += 0.2;
        PreviewInTheme();
    }
    function zoomOutTheme(){
        PreviewThemeZoom -= 0.2;
        PreviewInTheme();
    }
    function PreviewInTheme(){
        resetSVG();
        reDrawWithoutPolygon();
    }
    function BuildInTheme(){
        var d = new Date();
        var bgurl = '{{ $background[0] }}';
        var boxH = $("#editPageContainer").height();
        var boxW = $("#editPageContainer").width();
        var zoom = PreviewThemeZoom;
        var zoom_canvas = 1;
        var sofasize = 3.188;
        var dfX = -19;
        var dfY = -130;
        var canvasX = 484;
        var canvasY = 194;
        var svghtml = $("#tmp_svg").html();
        var data = {svghtml:svghtml, product_id:'{{$product["id"]}}',width:100,height:100,mod:'preview',view_dpi:20,remove_poly:1};
        $("#preview_content").css("width",boxW+'px');
        $("#preview_content").css("height",boxH+'px');
        $("#preview_content").css("display","none");
        $("#preview_image").css("display","none");
        $("#image_box").css("display","block");
        $("#preview_box").css("display","block");
        $("#editAreaWorkArea").css("display","none");
        $("#paletteLabelBackgrounds").css("display","block");
        $("#paletteLabelBackgrounds").trigger('click');
        $("#image_bt").css("display","none");
        $("#zoom_bt").css("display","none");
        $("#zoom_bt2").css("display","block");

        $.ajax({
            url: "{{URL}}/buildsvg/fill-white-fast",
            type: 'POST',
            data: data,
            success: function(link){
                $("#preview_content").html('');
                var drawview = SVG('preview_content').size(boxW, boxH).attr('id','svg_preview_box');
                img_bg = drawview.image(bgurl).loaded(function(loader){
                        var img_w,img_h;
                        var tmp = calSizeFitBox(loader.width,loader.height,boxW,boxH);
                        img_w = parseFloat(tmp['w']*zoom);
                        img_h = parseFloat(tmp['h']*zoom);
                        this.size(img_w,img_h);
                        this.x(dfX*zoom);
                        this.y(dfY*zoom);
                        this.draggable();
                    }).attr({ id: 'imgBG' });
                var img_canvas = drawview.image(link+"?t="+d.getTime()).loaded(function(loader){
                        var img_w,img_h;
                        zoom_canvas = (boxW/sofasize)/100;
                        //width
                        img_w = objSize.boxWidth/objSize.view_dpi; //true size pt
                        img_w = img_w/72; //true size inch
                        img_w = parseFloat(img_w*zoom_canvas*zoom);
                        //height
                        img_h = objSize.boxHeight/objSize.view_dpi; //true size pt
                        img_h = img_h/72; //true size inch
                        img_h = parseFloat(img_h*zoom_canvas*zoom);
                        this.size(img_w,img_h);
                        this.x(canvasX*zoom);
                        this.y(canvasY*zoom);
                        this.draggable();
                    });

                img_bg.dragmove = function(delta, event){
                    var moveX = this.x()-dfX;
                    var moveY = this.y()-dfY;
                    img_canvas.move(img_canvas.x()+moveX,img_canvas.y()+moveY);
                    dfX = this.x();
                    dfY = this.y();
                }

                $("#preview_content").css("display","block");
                $("#image_box").css("display","none");

            }
        });

    }
    function closePreview(){
        $("#preview_box").css("display","none");
        $("#preview_content").css("display","block");
        $("#editAreaWorkArea").css("display","block");
        $("#image_box").css("display","block");
        $("#image_bt").css("display","block");
        $("#paletteLabelBackgrounds").css("display","none");
        $('.paletteContent').removeClass('active');
        $("#paletteContentArrangements").addClass('active');
        $('.paletteLabel').removeClass('active');
        $("#paletteLabelArrangements").addClass('active');
        $("#zoom_bt").css("display","block");
        $("#zoom_bt2").css("display","none");
        zoomToSvg(last_zoom);
    }
    function reDrawOneBox(type, hidden, callBack){
        $("#tmp_svg").html('');
        $("#preview_content").html('');
        var key = current_image.replace("img_","");
        var count_img = $("#svg_svg_main > g").length;
        var tmp_x = objImg[key].boxX;
        var tmp_y = objImg[key].boxY;
        var tmp_width = objImg[key].boxWidth;
        var tmp_height = objImg[key].boxHeight;
        var tmp_bleed = objImg[key].boxHeight;

        var x,y,width,height;
        //lam tron de preview
        objImg[key].boxWidth = Math.round(objImg[key].boxWidth);
        objImg[key].boxHeight = Math.round(objImg[key].boxHeight);
        objImg[key].bleed = Math.round(objImg[key].bleed);
        objImg[key].boxX = objImg[key].boxY = 0;

        draw = SVG('tmp_svg').size(objImg[key].boxWidth,objImg[key].boxHeight).attr('id','svg_preview_tmp');
        var imgcanvas = $("#"+current_image);
        var maskcanvas = $("#mask_"+current_image);
        var image = draw.image(objImg[key].url).loaded(function(loader){
            width = Math.round(imgcanvas.attr('width'));
            height = Math.round(imgcanvas.attr('height'));
            this.size(width, height);
            x = objImg[key].flip_x*(imgcanvas.attr('x') - objImg[key].flip_x*maskcanvas.attr('x'));
            y = objImg[key].flip_y*(imgcanvas.attr('y') - objImg[key].flip_y*maskcanvas.attr('y'));
            if(imgcanvas.attr('transform')!=undefined && imgcanvas.attr('transform').indexOf("rotate")!=-1){
                var tmp = imgcanvas.attr('transform');
                tmp = tmp.split(")");
                tmp = tmp[0];
                tmp = tmp.replace("rotate(","");
                tmp = tmp.split(" ");
                this.rotate(tmp[0],tmp[1]- maskcanvas.attr('x'),tmp[2]- maskcanvas.attr('y'));
            }
            this.scale(objImg[key].flip_x,objImg[key].flip_y);
            this.x(Math.round(x));
            this.y(Math.round(y));
            if(type=='2d'){
                setTimeout(buildImage(function(link){
                    $("#image_box").css("display","none");
                    $("#img_svg_preview").attr("src",link);
                    $("#preview_image").css("display","block");
                }), 3000);
            }
            if(type=='3d'){
                setTimeout(preBuild3D(function(link){
                    $("#image_box").css("display","none");
                    $("#preview_content").css("display","block");
                    if( typeof callBack == 'function' ) {
                        callBack(link);
                    }
                }), 3000);
            }


        }).attr("id","tmp_img");
        var group = draw.group();

        group.add(image);
        if(objImg[key].wrap=='m_wrap')
            DrawMirror(key,group);
        addFilter(group,objImg[key].filter);
        DrawPolygon(key);
        objImg[key].boxX = tmp_x;
        objImg[key].boxY = tmp_y;
        objImg[key].boxWidth = tmp_width;
        objImg[key].boxHeigh = tmp_height;
        objImg[key].boxHeight = tmp_bleed;
    }

    function reDrawWithoutPolygon(noBuild){
        var attribute = {fill: 'none', stroke: 'black', 'stroke-width': 1};
        var minX, maxX, minY, maxY, img_w, img_h, imgsize, tmp, naturalImg_width, naturalImg_height, MaxBoxWidth,MaxBoxHeight,margin;
        var margin = 0;
        var k = 0;
        var key = 0;
        $("#tmp_svg").html('');
        draw = SVG('tmp_svg').size(objSize.boxWidth, objSize.boxHeight).attr('id','svg_preview_all_tmp');
        @foreach($svg_setup['data'] as $key=>$value)
            key = {{$key}};
            var image_{{$key}} = drawSVGImageElement(key,objImg);
            //setup choice
            image_{{$key}}.attr({'id':'reimg_{{$key}}'});
            document.getElementById(image_{{$key}}.node.id).onclick = chooseImage;
            //group
            var group_{{$key}} = draw.group();
            group_{{$key}}.add(image_{{$key}}).attr('id','group_preimg_{{$key}}');
            //mask
            var mask_{{$key}} = draw.rect(objImg[key].boxWidth-2*objImg[key].bleed, objImg[key].boxHeight-2*objImg[key].bleed).move(objImg[key].boxX+objImg[key].bleed,objImg[key].boxY+objImg[key].bleed).attr('id','mask_reimg_{{$key}}');
            //Filter
            addFilter(group_{{$key}},objImg[key].filter);
            //ve clipmask
            group_{{$key}}.clipWith(mask_{{$key}});
        @endforeach
        if( noBuild == undefined ) {
            $("#reimg_"+key).load(function(){
                setTimeout(BuildInTheme(), 3000);
            });
        }
    }


</script>

