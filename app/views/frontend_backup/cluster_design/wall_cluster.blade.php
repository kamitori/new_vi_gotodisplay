<script type="text/javascript">
addHoverClipPathByID();
selectWrapFrame("{{$product['wrap']}}",'white');
drawDesignSVG(objImg,objSize,objOrientation,objEdgeColor);
default_objImg = JSON.parse(JSON.stringify(objImg));

function drawDesignSVG(objImg,objSize,objOrientation,objEdgeColor){
    var attribute = {fill: 'none', stroke: 'black', 'stroke-width': 1};
    var minX, maxX, minY, maxY, img_w, img_h, imgsize, tmp, naturalImg_width, naturalImg_height, MaxBoxWidth,MaxBoxHeight,margin;
    var margin = 0;
    var k = 0;
    var key = 0;
    $("#svg_main").html('');
    draw = SVG('svg_main').size(objSize.boxWidth, objSize.boxHeight).attr('id','svg_svg_main');
    @foreach($svg_setup['data'] as $key=>$value)
        key = {{$key}};
        var image_{{$key}} = drawSVGImageElement(key,objImg);
        //setup choice
        image_{{$key}}.attr({'id':'img_{{$key}}', 'data-id': objImg[key].dataId});
        document.getElementById(image_{{$key}}.node.id).onclick = chooseImage;
        if(first_run==0)
            current_image = image_{{$key}}.node.id;
        else
            current_image = 'img_'+after_position;
        first_run++;
        //group
        var group_{{$key}} = draw.group();
        group_{{$key}}.add(image_{{$key}}).attr('id','group_img_{{$key}}');
        //mask
        var mask_{{$key}} = draw.rect(objImg[key].boxWidth, objImg[key].boxHeight).move(objImg[key].boxX,objImg[key].boxY).attr('id','mask_img_{{$key}}');
        //border
        attribute = {fill: 'none', stroke: '#dddddd', 'stroke-width': 1,'id':'border_img_{{$key}}'};
        var border_{{$key}} = draw.rect(objImg[key].boxWidth-1-2*objImg[key].bleed, objImg[key].boxHeight-1-2*objImg[key].bleed).move(objImg[key].boxX+objImg[key].bleed+0.2,objImg[key].boxY+objImg[key].bleed+0.2).attr(attribute);
        //group_{{$key}}.add(border_{{$key}});
        if(objEdgeColor.wrap=='m_wrap')
            DrawMirror(key,group_{{$key}});
        //ve clipmask
        group_{{$key}}.clipWith(mask_{{$key}});
        //polygon
        var polygon_{{$key}} = DrawPolygon(key);
        //Filter
        addFilter(group_{{$key}},objImg[key].filter);
    @endforeach
}
function drawSVGImageElement(key,objImg){
      var box_x = objImg[key].boxX;
      var box_y = objImg[key].boxY;
      var center_rotate_x = 0;
      var center_rotate_y = 0;
      var naturalImg_width,naturalImg_height;
      var image_ = draw.image(objImg[key].url).loaded(function(loader){
                    naturalImg_width = loader.width;
                    naturalImg_height = loader.height;
                    k = 0;
                    if(objImg[key].rotate==90 || objImg[key].rotate==270){ //rotate
                        naturalImg_width = loader.height;
                        naturalImg_height = loader.width;
                    }
                    MaxBoxWidth = objImg[key].boxWidth;
                    MaxBoxHeight = objImg[key].boxHeight;
                    margin = objImg[key].bleed + objImg[key].border;
                    //tinh size img_w, img_h
                    if(objImg[key].opacity==1)
                        imgsize = calSizeFitBox( naturalImg_width, naturalImg_height, MaxBoxWidth-2*margin, MaxBoxHeight-2*margin);
                    else
                        imgsize = calSizeFitBox( naturalImg_width, naturalImg_height, MaxBoxWidth, MaxBoxHeight);
                    img_w = parseFloat(imgsize['w']*objImg[key].zoom);
                    img_h = parseFloat(imgsize['h']*objImg[key].zoom);
                    if(objImg[key].rotate==90 || objImg[key].rotate==270){ //Rotate
                        tmp = img_h;
                        img_h = img_w;
                        img_w = tmp;
                        k= parseFloat((MaxBoxWidth-MaxBoxHeight)/2);
                    }
                    //tinh min max
                    minX = -1*(img_w-MaxBoxWidth)-k;
                    maxX = img_w+k;
                    minY = -1*(img_h-MaxBoxHeight)+k;
                    maxY = img_h-k;
                    if(objImg[key].opacity==1){
                        minX = minX-margin;
                        maxX = maxX+margin;
                        minY = minY-margin;
                        maxY = maxY+margin;
                    }
                    minX +=box_x;
                    maxX +=box_x;
                    minY +=box_y;
                    maxY +=box_y;

                    //tinh toa do x,y
                    if(objImg[key].x==undefined) //load lan dau
                        objImg[key].x = this.x();
                    if(objImg[key].y==undefined)  //load lan dau
                        objImg[key].y = this.y();
                    if(objImg[key].resetXY===true){
                        objImg[key].x = box_x - (img_w-MaxBoxWidth)/2;
                        objImg[key].y = box_y - (img_h-MaxBoxHeight)/2;

                        if(objImg[key].changeflip_x || objImg[key].changeflip_y){
                            // if(objImg[key].flip_x==-1 && objImg[key].flip_y==-1){
                            //     objImg[key].x = objImg[key].flip_x*maxX;
                            //     objImg[key].y = objImg[key].flip_y*maxY;
                            // }else if(objImg[key].flip_x==-1){
                            //     objImg[key].x = objImg[key].flip_x*maxX;
                            //     objImg[key].y = objImg[key].flip_y*minY;
                            // }else if(objImg[key].flip_y==-1){
                            //     objImg[key].x = objImg[key].flip_x*minX;
                            //     objImg[key].y = objImg[key].flip_y*maxY;
                            // }
                            // console.log(objImg[key]);
                            if(objImg[key].flip_x==-1)
                                objImg[key].changeflip_x = false;
                            if(objImg[key].flip_y==-1)
                                objImg[key].changeflip_y = false;
                        }
                    }

                    if(objImg[key].specialKey == 'change_wrap_add'){
                        objImg[key].x = objImg[key].x - margin;
                        objImg[key].y = objImg[key].y - margin;
                        objImg[key].specialKey = '';
                    }
                    objImg[key].imgW = img_w;
                    objImg[key].imgH = img_h;
                    this.x(objImg[key].x);
                    this.y(objImg[key].y);
                    this.size(objImg[key].imgW, objImg[key].imgH);
                    objImg[key].resetXY = false;

                    this.rotate(objImg[key].rotate,minX+(maxX-minX)/2,minY+(maxY-minY)/2);
                    this.scale(objImg[key].flip_x,objImg[key].flip_y);
                    this.draggable({minX:minX, maxX:maxX, minY:minY, maxY:maxY, flipX: objImg[key].flip_x, flipY: objImg[key].flip_y});
            });
        //Drag Image
        image_.dragmove = function(delta, event){
            objImg[key].deltaX = this.x()-objImg[key].x;
            objImg[key].deltaY = this.y()-objImg[key].y;
            objImg[key].x = Number(this.x().toFixed(8));
            objImg[key].y = Number(this.y().toFixed(8));
            objImg[key].resetXY = false;
            console.log(objImg[key].x,objImg[key].y);
        }
        return image_;
}
function DrawPolygon(n){
    var border = 1;
    var bh = border/2;
    var bleed = objImg[n].bleed;
    var width = objImg[n].boxWidth;
    var height = objImg[n].boxHeight;
    var x_0 = objImg[n].boxX;
    var x_1 = x_0+bleed;
    var x_2 = x_0+width-bleed;
    var x_3 = x_0+width;
    var y_0 = objImg[n].boxY;
    var y_1 = y_0+bleed;
    var y_2 = y_0+height-bleed;
    var y_3 = y_0+height;
    var polygon_points  = x_1+','+y_0+' '+ x_2+','+y_0+' '+ x_2+','+y_1+' '+ x_3+','+y_1+' '+ x_3+','+y_2+' '+ x_2+','+y_2+' '+ x_2+','+y_3+' '+ x_1+','+y_3+' '+ x_1+','+y_2+' '+ x_0+','+y_2+' '+ x_0+','+y_1+' '+ x_1+','+y_1+' '+ x_1+','+y_2+' '+ x_2+','+y_2+' '+ x_2+','+y_1+' '+ x_1+','+y_1+' ';
    var attribute = {fill: objEdgeColor.colors,'fill-opacity': objEdgeColor.opacity,stroke: objEdgeColor.stroke,'stroke-width': objEdgeColor.strokeWidth,'class':'bleed'};
    var _polygon = draw.polygon(polygon_points).attr(attribute);

    var rect_0_x = x_0-1;
    var rect_0_y = y_0-1;
    var rect_0_width = x_1-x_0+1;
    var rect_0_height = y_1-y_0+1;
    rect_0 = draw.rect(rect_0_width,rect_0_height).x(rect_0_x).y(rect_0_y).attr({fill:'white','fill-opacity': 1,stroke:'white','stroke-width': 0,'id':'rect_0_'+n,'index':n});

    var rect_1_x = x_2;
    var rect_1_y = y_0-1;
    var rect_1_width = x_3-x_2+1;
    var rect_1_height = y_1-y_0+1;
    rect_1 = draw.rect(rect_1_width,rect_1_height).x(rect_1_x).y(rect_1_y).attr({fill:'white','fill-opacity': 1,stroke:'white','stroke-width': 0,'id':'rect_1_'+n,'index':n});

    var rect_2_x = x_2;
    var rect_2_y = y_2;
    var rect_2_width = x_3-x_2+1;
    var rect_2_height = y_3-y_2+1;
    rect_2 = draw.rect(rect_2_width,rect_2_height).x(rect_2_x).y(rect_2_y).attr({fill:'white','fill-opacity': 1,stroke:'white','stroke-width': 0,'id':'rect_2_'+n,'index':n});

    var rect_3_x = x_0-1;
    var rect_3_y = y_2;
    var rect_3_width = x_1-x_0+1;
    var rect_3_height = y_3-y_2+1;
    rect_3 = draw.rect(rect_3_width,rect_3_height).x(rect_3_x).y(rect_3_y).attr({fill:'white','fill-opacity': 1,stroke:'white','stroke-width': 0,'id':'rect_3_'+n,'index':n});

    return _polygon;
}
function DrawMirror(n,group){
    var left, leftRect, leftUse, leftClip,
                top, topRect, topUse, topClip,
                right, rightRect, rightUse, rightClip,
                bottom, bottomRect, bottomUse, bottomClip;
        defs = draw.defs();
        var bleed = objImg[n].bleed;
        var width = objImg[n].boxWidth;
        var height = objImg[n].boxHeight;
        var boxX = objImg[n].boxX;
        var boxY = objImg[n].boxY;
        //left
        leftRect = draw.rect(bleed, (height - bleed * 2)).attr({x: bleed+boxX, y: bleed+boxY});
        leftClip = draw.clip().attr({id : "left-clip-"+n}).add(leftRect);
        leftUse = draw.use(group).attr({"clip-path" : "url(#left-clip-"+n+")"});
        left = draw.group().add(leftUse).add(leftClip).attr({id : "left-"+n, "transform" : "scale(-1,1) translate(0,0)"});
        //top
        topRect = draw.rect((width - bleed * 2), bleed).attr({x: bleed+boxX, y: bleed+boxY});
        topClip = draw.clip().attr({id : "top-clip-"+n}).add(topRect);
        topUse = draw.use(group).attr({"clip-path" : "url(#top-clip-"+n+")"});
        top = draw.group().add(topUse).add(topClip).attr({id : "top-"+n, "transform" : "scale(1,-1) translate(0,0)"});
        //right
        rightRect = draw.rect(bleed, (height - bleed * 2)).attr({x: (width - bleed * 2+boxX), y: bleed+boxY});
        rightClip = draw.clip().attr({id : "right-clip-"+n}).add(rightRect);
        rightUse = draw.use(group).attr({"clip-path" : "url(#right-clip-"+n+")"});
        right = draw.group().add(rightUse).add(rightClip).attr({id : "right-"+n, "transform" : "scale(-1,1) translate(0,0)"});
        //bottom
        bottomRect = draw.rect((width - bleed * 2), bleed).attr({x: bleed+boxX, y: (height - bleed * 2+boxY)});
        bottomClip = draw.clip().attr({id : "bottom-clip-"+n}).add(bottomRect);
        bottomUse = draw.use(group).attr({"clip-path" : "url(#bottom-clip-"+n+")"});
        bottom = draw.group().add(bottomUse).add(bottomClip).attr({id : "bottom-"+n, "transform" : "scale(1,-1) translate(0,0)"});

        defs = draw.defs().add(left).add(top).add(right).add(bottom);
        var pos = -180;
        var pos = bleed * 2;
        draw.use(left).attr({"id" : "left-use-"+n, x : pos+2*boxX, y: 0});
        draw.use(top).attr({"id" : "top-use-"+n, x : 0, y: pos+2*boxY});
        draw.use(right).attr({"id" : "right-use-"+n, x : (width - bleed) * 2+2*boxX, y: 0});
        draw.use(bottom).attr({"id" : "bottom-use-"+n, x : 0, y: (height - bleed) * 2+2*boxY});
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
// choose image
function chooseImage(id){
    removeHoverClipPathByID();
    current_image = id.target.id;
    addHoverClipPathByID();
    last_x = id.clientX;
    last_y = id.clientY;
}
function removeHoverClipPathByID(){
    $("#border_"+current_image).attr({'stroke-width':'1','stroke':'#dddddd'});
}
function addHoverClipPathByID(){
    $("#border_"+current_image).attr({'stroke-width':'3','stroke':'#CA7642'});
    //reset zoom, rotate
    var data_position = current_image.replace("img_","");
    if(data_position!=undefined && data_position!=''){
        $("#zoomImage").val(objImg[data_position].zoom);
        $("#zoom-slider").slider({
            orientation: "vertical",
            range: "max",
            step: 0.2,
            min: 1,
            max: 3.6,
            value: objImg[data_position].zoom,
        });
        $("#amount").val(objImg[data_position].rotate);
        $( "#slider-vertical" ).slider({
            orientation: "vertical",
            range: "max",
            step: 5,
            min: 0,
            max: 360,
            value: objImg[data_position].rotate,
        });
        $(".slider_bt").css("display","block");
    }
}
function CommonFunction(data_position){
    after_position = data_position;
    drawDesignSVG(objImg,objSize,objOrientation,objEdgeColor);
    current_position = data_position;
    addHoverClipPathByID();
}
function changeImage(url, dataId){
    if( typeof url == 'object' ) {
        var url = $(url).attr('src');
    }
    var data_position = current_image.replace("img_","");
    objImg[data_position].url = url;
    objImg[data_position].resetXY = true;
    objImg[data_position].changeflip_x = true;
    if( dataId ) {
        objImg[data_position].dataId = dataId;
    }
    CommonFunction(data_position);
}
function rotateImage(){
    var data_position = current_image.replace("img_","");
    objImg[data_position].rotate = Math.round(objImg[data_position].rotate/90)*90+90;
    if(objImg[data_position].rotate>=360)
        objImg[data_position].rotate = 0;
    objImg[data_position].resetXY = true;
    objImg[data_position].changeflip_x = true;
    $("#amount").val( objImg[data_position].rotate );
    $("#amount").change();
}
function resetSVG(){
    var data_position = current_image.replace("img_","");
    zoom_size = 1;
    zoomSvg();
    CommonFunction(data_position);
}
function zoomInSvg(){
    var data_position = current_image.replace("img_","");
    zoom_size += 0.2;
    zoomSvg();
    CommonFunction(data_position);
    var height_edit = parseInt($("#editPageContainer").height());
}
function zoomOutSvg(){
    var data_position = current_image.replace("img_","");
    zoom_size -= 0.2;
    zoomSvg();
    CommonFunction(data_position);
}
function zoomToSvg(value){
    var data_position = current_image.replace("img_","");
    zoom_size = value;
    zoomSvg();
    CommonFunction(data_position);
    var height_edit = parseInt($("#editPageContainer").height());
    if(height_edit>120)
        $("#editAreaWorkArea").height(height_edit-120);
}
function zoomSvg(){
    var zoom_for_now = (svg_data[0].width*zoom_size)/objImg[0].boxWidth;
        zoom_for_now = parseFloat(zoom_for_now);
    var key;
    objSize.boxWidth = zoom_for_now * objSize.boxWidth;
    objSize.boxHeight = zoom_for_now * objSize.boxHeight;
    $("#svg_main").width(objSize.boxWidth);
    $("#svg_main").height(objSize.boxHeight);
    $("#canvas_img_thum").width(objSize.boxWidth);
    $("#canvas_img_thum").height(objSize.boxHeight);
    @if(isset($svg_setup['data']) && !empty($svg_setup['data']))
        @foreach($svg_setup['data'] as $key =>$val)
            key = {{$key}};
            objImg[key].bleed = objImg[key].bleed * zoom_for_now;
            objImg[key].boxX = objImg[key].boxX *  zoom_for_now;
            objImg[key].boxY = objImg[key].boxY * zoom_for_now;
            objImg[key].boxWidth = objImg[key].boxWidth * zoom_for_now;
            objImg[key].boxHeight = objImg[key].boxHeight * zoom_for_now;
            objImg[key].x = objImg[key].x *  zoom_for_now;
            objImg[key].y = objImg[key].y *  zoom_for_now;
        @endforeach
    @endif
    if(zoom_size!=1)
        $("#reset_zoom").show();
    else
        $("#reset_zoom").hide();
    closePickColor();
}
function filterImage(types){
    var data_position = current_image.replace("img_","");
    objImg[data_position].filter = types;
    objImg[data_position].resetXY = false;
    CommonFunction(data_position);
}
function flipImageX(){
    var data_position = current_image.replace("img_","");
    objImg[data_position].resetXY = true;
    if(objImg[data_position].flip_x ==1){
        objImg[data_position].flip_x = -1;
        objImg[data_position].changeflip_x = true;
    }else
        objImg[data_position].flip_x = 1;
    if(objImg[data_position].flip_y ==-1)
        objImg[data_position].changeflip_y = true;
    CommonFunction(data_position);
}
function flipImageY(){
    var data_position = current_image.replace("img_","");
     objImg[data_position].resetXY = true;
    if(objImg[data_position].flip_y ==1){
        objImg[data_position].flip_y = -1;
        objImg[data_position].changeflip_y = true;
    }else
        objImg[data_position].flip_y = 1;

    if(objImg[data_position].flip_x ==-1)
        objImg[data_position].changeflip_x = true;
    CommonFunction(data_position);
}
function selectWrapFrame(types,color){
    var colors = 'white';
    var opacity = 0.4;
    var stroke = '#333333';
    var strokeWidth = 0;
    var data_position = current_image.replace("img_","");

    if(color==undefined || color=='')
        color = 'white';

    if(types=='white' || types=="white_edge" || types == "w_frame"){
        colors = 'white';
        opacity = 1;
        stroke = '#333333';
        strokeWidth = 1;
    }else if(types=='black' || types == "blackedge" || types == "black_frame"){
        colors = 'black';
        opacity = 1;
        stroke = 'black';
        strokeWidth = 0;
    }else if(types=='red'){
        color = $("input:radio[name=frame_style]:checked").attr("rel");
        colors = color;
        opacity = 1;
        stroke = color;
        strokeWidth = 0;
    }else if(types=='color'){
        colors = color;
        opacity = 1;
        stroke = color;
        strokeWidth = 0;
    }else if(types=="silver_edge") {
        colors = 'silver';
        opacity = 1;
        stroke = '#ddd';
        strokeWidth = 1;
    }else if(types=="m_frame"){
        colors = '#4E0E0E';
        opacity = 1;
        stroke = '#4E0E0E';
        strokeWidth = 0;
    }

    if(data_position==undefined || data_position==''){
        @if(isset($svg_setup['data']) && !empty($svg_setup['data']))
            @foreach($svg_setup['data'] as $key =>$val)
                objImg[{{$key}}].colors = colors;
                objImg[{{$key}}].opacity = opacity;
                objImg[{{$key}}].stroke = stroke
                objImg[{{$key}}].strokeWidth = strokeWidth;
            @endforeach
        @endif
    }else{

        @if(isset($svg_setup['data']) && !empty($svg_setup['data']))
            @foreach($svg_setup['data'] as $key =>$val)
                if(objImg[{{$key}}].opacity==opacity)
                    objImg[{{$key}}].resetXY = false;
                else if(objImg[{{$key}}].opacity>opacity){
                    objImg[{{$key}}].resetXY = false;
                    objImg[{{$key}}].specialKey = 'change_wrap_add';
                }else{
                    objImg[{{$key}}].resetXY = true;
                    objImg[{{$key}}].changeflip_x = true;
                    //objImg[{{$key}}].specialKey = 'change_wrap_min';
                }
                objImg[{{$key}}].colors = colors;
                objImg[{{$key}}].opacity = opacity;
                objImg[{{$key}}].stroke = stroke;
                objImg[{{$key}}].strokeWidth = strokeWidth;
            @endforeach
        @endif
    }
    objEdgeColor.colors = colors;
    objEdgeColor.opacity = opacity;
    objEdgeColor.stroke = stroke;
    objEdgeColor.strokeWidth = strokeWidth;
}
function changeWrapFrame(types,title,color){
    var data_position = current_image.replace("img_","");
    objEdgeColor.wrap = types;
    if(color==undefined)
        color = objImg[data_position].colors;
    selectWrapFrame(types,color);
    CommonFunction(data_position);
    $("#name_wrap").html(title);
    if(types=='red'){
        $('.paletteContent').removeClass('active');
        $('#pick_color').addClass('active');
    }
}
function sliderotateImage(x){
    var data_position = current_image.replace("img_","");
    objImg[data_position].rotate = parseFloat(x);
    if(objImg[data_position].rotate>=360)
        objImg[data_position].rotate = 0;
    $("#rotateImage").val(objImg[data_position].rotate);
    objImg[data_position].resetXY = true;
    objImg[data_position].changeflip_x = true;
    CommonFunction(data_position);
}

var InUpload = false;
var CountUpload = 0;
var timeElapsed;
var UploadKey = '';

function ColckProgress(){
    setTimeout(function(){
        var Ukey = Object.keys(objUpload)[CountUpload];
        var obj = $("#img_upload_"+Ukey+" .imgthumb_progress");
        var w = obj.css("width"); w = w.replace("%","");
        w = parseInt(w)+10
        obj.css("width",w+"%");
        ColckProgress();
    },50);
}

function ClockRunUpload(files, position){
    var Sum = Object.keys(objUpload).length;
    if(!InUpload){
        timeStarted = new Date();
        InUpload = true;
        UploadKey = Object.keys(objUpload)[CountUpload];
        var data = objUpload[UploadKey];
        CountUpload++;
        if( files != undefined ) {
            if( position == undefined ) {
                position = 0;
            }
            if( files[position] == undefined ) {
                return false;
            }
            var f = files[position];
            position++;
            var reader = new FileReader();
            reader.readAsDataURL(f);
            reader.onload = function(e){
                var src = e.target.result;
                var img = new Image();
                img.src = src;
                img.onload = function(){
                    f.name = f.name.replace(/ /g, '');
                    InUpload = false;
                    $("#img_upload_"+UploadKey+" .imgthumb_progress").css("width","100%");
                    $("#img_upload_"+UploadKey+" .imgthumb_loading").hide();
                    $("#img_upload_"+UploadKey+" .img_icon_complete").css("display","block");
                    $("<img class=\"photo\" src=\""+ src +"\" alt=\"\" data-id=\""+f.name+"\" onclick=\"changeImage(this, '"+ f.name +"');\" />").appendTo("#img_upload_"+UploadKey);
                    $("#img_upload_"+UploadKey+" .img_icon").remove()
                };
            };
        }
        $.ajax({
            url: '/collections/gettheme/saveimg',
            type: 'POST',
            data: data,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                if (myXhr.upload) {
                    /*myXhr.upload.addEventListener('progress',function(ev) {
                        if (ev.lengthComputable) {
                            console.info('Uploaded1 '+ev.loaded+' bytes / ' + ev.total +' bytes');
                            var percentUploaded = Math.floor(ev.loaded * 100 / ev.total);
                            console.info('Uploaded2 '+percentUploaded+'%');
                            // update UI to reflect percentUploaded
                            timeElapsed = (new Date()) - timeStarted; //assumng that timeStarted is a Data Object
                            uploadSpeed = ev.loaded / (timeElapsed/1000); //upload speed in second (bytes/s)
                            var time_remaining = (ev.total - ev.loaded) / uploadSpeed;
                            console.log("Time remaining: " + time_remaining);
                            $("#img_upload_"+UploadKey+" .imgthumb_progress").css("width",percentUploaded+"%");

                        } else {
                            console.info('Uploaded3 '+ev.loaded+' bytes');
                            // update UI to reflect bytes uploaded
                        }
                   }, false);*/
                }
                return myXhr;
            },
            success: function(result)
            {
                if(result.error==''){
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
            }
        });
    }
    if(CountUpload<Sum){
        Clock = setTimeout(function(){
          ClockRunUpload(files, position);
        },1000);
    }else{//end upload
        clearInterval(Clock);
        InUpload = false;
        CountUpload = 0;
        objUpload ={};
    }
}


function uploadFiles(event,items,add,mode){
    var files = event.target.files;

    var count = 0; var imgkey;
    $.each(files, function(key, value){
        var data = new FormData();
        data.append(key, value);
        imgkey = value.name;
        imgkey = imgkey.split(".");
        imgkey = imgkey[0];
        imgkey = imgkey.toLocaleLowerCase();
        for (var i = 0; i < imgkey.search(/\s/g); i++) {
            imgkey = imgkey.replace(" ", "-");
        };
        objUpload[imgkey] = data;

        $('<div class="image_content" id="img_upload_'+imgkey+'"><div class="img_icon"><div class="imgthumb_loading"><div class="imgthumb_progress" style="width: 0%;"></div></div><div class="img_icon_complete" style="display:none;"><img src="{{URL}}/assets/images/others/ajax-loader.gif" /></div></div></div>').prependTo("#slider_image");
        count++;
    });
    ClockRunUpload(files);
    //ColckProgress();
    //console.log(objUpload);
    //$('#loading_none').show();
    // $.ajax({
    //     url: '/collections/gettheme/saveimg',
    //     type: 'POST',
    //     data: data,
    //     cache: false,
    //     dataType: 'json',
    //     processData: false, // Don't process the files
    //     contentType: false, // Set content type to false as jQuery will tell the server its a query string request
    //     success: function(result)
    //     {
    //         var _data = result.data;
    //         if(result.error==''){
    //             for(key in _data){
    //                 var width = _data[key].width; // pixels
    //                 var height = _data[key].height;
    //                 check_list_quality(width,height);
    //                 $('#loading_none').hide();
    //                 $("#"+current_image).attr('href','{{URL}}/assets/images/loading.gif');
    //                 uploadImageHandler(_data[key],key);
    //             }
    //         }
    //     }
    // });
}
function check_list_quality(width,height){
    var ele = $(".full_width tbody tr.range td[align='left']");
    $.each(ele,function(index,value){
        var html_inch = $(value).html();
        var start = html_inch.indexOf('<span');
        html_inch = html_inch.slice(0,start);
        var arr_inch = html_inch.split("&nbsp;x&nbsp;");
        var rating = check_size(width,height,arr_inch[0],arr_inch[1]);
        if (rating==1) {
            $(value).children(".check_quality").html(" (Excellent)");
            $(value).css("color", "#009900");
        } else if (rating==2) {
            $(value).children(".check_quality").html(" (Good)");
            $(value).css("color", "rgb(0,0,255)");
        } else if (rating==3) {
            $(value).children(".check_quality").html(" (Fair)");
            $(value).css("color", "#6600CC");
        } else {
            $(value).children(".check_quality").html(" (Poor)");
            $(value).css("color", "rgb(112,112,112)");
        }
    });
}
function uploadImageHandler(result,index){
    var data_position = current_image.replace("img_","");
    if(data_position>index) data_position = 0;
    else data_position = index;
    if(typeof objImg[data_position] =='undefined') data_position = 0;
    objImg[data_position].url = '{{URL}}'+result.url+result.files[0];
    var html = "<img class='photo' src='"+objImg[data_position].url+"' alt='title' onclick='changeImage(\""+objImg[data_position].url+"\");' />";
    var another_img = '<div class="image_content">'+html+'</div>';
    $("#content_my_upload").prepend(html);
    $("#slider_image").prepend(another_img);
    $(".content_list").hide();
    $("#content_my_upload").css("display","table");
    objImg[data_position].resetXY = true;
    objImg[data_position].changeflip_x = true;
    CommonFunction(data_position);
}


$("#zoom-slider").slider({
    orientation: "vertical",
    range: "max",
    step: 0.2,
    min: 1,
    max: 3.6, // 360 <-> 3.6
    value: 1,
    slide: function( event, ui ) {
        var data_position = current_image.replace("img_","");
        objImg[data_position].zoom =  parseFloat(ui.value);
        $("#zoomImage").val(objImg[data_position]);
        if(objImg[data_position].zoom>1)
            objImg[data_position].resetXY = false;
        else{
            objImg[data_position].resetXY = true;
            objImg[data_position].changeflip_x = true;
        }
        CommonFunction(data_position);
    }
});



function resolution(){
    $("#dialog_resolution").html('');
    $("#dialog_resolution").dialog({
        width:920
    });
    $.ajax({
        url:"{{URL}}/collections/analyze_image",
        type:"POST",
        data:{img: $("#"+current_image).attr("href")},
        success: function(ret){
            ret = JSON.parse(ret);
            var html = '';
            var d = new Date();
            html += '<div id="content" style="width:900px;">';
                html += '<div style="float:left; margin-right: 20px">';
                    html += '<img style="width: 385px " src="'+ ret.image +'?t='+d.getTime()+'" />';
                html += '</div>';
                html += '<div class="info">';
                html += ' <ul >';
                                html += '<li><h2>About your picture: </h2></li>';
                                html += '<li>Your file size: <b>'+ret.size+'</b> MB </li>';
                                html += '<li>Your file resolution: <b>'+ret.width+'</b> by <b>'+ret.height+'</b> pixels </li>';
                                html += '<li><b>'+ret.mp+'</b> Megapixels<br /><br /><br /> </li>';
                    html += '</ul>';
                html += '</div>';
                // html += '<div class="clear"></div>';
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

function addFilter(objF,typeF){
    if(typeF=='grayscale'){
        objF.filter(function(add) {
          add.colorMatrix('saturate', 0);
        });
    }else if(typeF=='sepia'){
        objF.filter(function(add) {
          add.colorMatrix('matrix', [ .543, .669, .119, 0, 0
                                    , .249, .626, .130, 0, 0
                                    , .172, .334, 0.2, 0, 0
                                    , .000, .000, .000, 1, 0 ]);
        });
    }
}


// function zoomInImage(){
//     var data_position = current_image.replace("img_","");
//     objImg[data_position].zoom += 0.2;
//     $("#zoomImage").val(objImg[data_position].zoom);
//     $("#zoom-slider").slider({
//         orientation: "vertical",
//         range: "max",
//         step: 0.2,
//         min: 1,
//         max: 3.6,
//         value: objImg[data_position].zoom,
//     });
//     objImg[data_position].resetXY = true;
//     CommonFunction(data_position);
// }
// function zoomOutImage(){
//     var data_position = current_image.replace("img_","");
//     objImg[data_position].zoom -= 0.2;
//     if(objImg[data_position].zoom<1)
//         objImg[data_position].zoom =1;
//     $("#zoomImage").val(objImg[data_position].zoom);
//     $("#zoom-slider").slider({
//         orientation: "vertical",
//         range: "max",
//         step: 0.2,
//         min: 1,
//         max: 3.6,
//         value: objImg[data_position].zoom,
//     });
//     objImg[data_position].resetXY = true;
//     CommonFunction(data_position);
// }


</script>