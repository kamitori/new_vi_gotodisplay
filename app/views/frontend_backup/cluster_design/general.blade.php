<script src="{{URL}}/assets/js/svgjs/svg.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/svgjs/svg.draggable/svg.draggable.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/svgjs/svg.filter/svg.filter.min.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/svgjs/svg.parser/svg.parser.min.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/svgjs/svg.importer/svg.import.min.js" type="text/javascript" charset="utf-8"></script>

<script type="text/javascript">
    var current_image = '';
    var last_x = last_y = 0;
    var current_polygon = '';
    var current_zoom_all_state = 1;
    var current_position = 0;
    var after_position = 0;
    var first_run=0;
    var objImg =[];
    var default_objImg =[];
    var objSize = {};
    objSize.boxWidth = {{isset($svg_setup['width'])?$svg_setup['width']:1000}};
    objSize.boxHeight = {{isset($svg_setup['height'])?$svg_setup['height']:1000}};
    objSize.view_dpi = {{isset($svg_setup['view_dpi'])?$svg_setup['view_dpi']:1}};

    var objOrientation = {};
    var objEdgeColor = [];
    var change_size = false;
    var zoom_size = 1;
    var draw = '';
    var objUpload = {};
    var Clock;

    var svg_data = '{{json_encode($svg_setup['data'])}}';
    svg_data = $.parseJSON(svg_data);

    var imgurl = '{{URL}}/assets/images/default.jpg';

    @if(isset($arr_img) && isset($arr_img[0]))
        imgurl = '{{URL.$arr_img[0]}}';
    @endif
    @if( $cartEdit )
    @foreach($svg_setup['data'] as $key =>$val)
        var current_array = {};
        @foreach($val as $k_opt => $opt)
        @if(is_numeric($opt))
            current_array.{{$k_opt}} = {{ $opt }};
        @else
            current_array.{{$k_opt}} = '{{ $opt }}';
        @endif
        @endforeach
        objImg.push(current_array);
    @endforeach
    @else
    @if(isset($svg_setup['data']) && !empty($svg_setup['data']))
        @foreach($svg_setup['data'] as $key =>$val)
            var current_array = {};
            @if(isset($arr_img[$key]))
                current_array.url = '{{URL.$arr_img[$key]}}';
            @else
                current_array.url = imgurl;
            @endif
            current_array.zoom = 1;
            current_array.rotate = 0;
            current_array.filter = 'black';
            current_array.resetXY = true;
            current_array.flip_x = 1;
            current_array.flip_y = 1;
            current_array.changeflip_x = current_array.changeflip_y = true;
            current_array.bleed = svg_data[{{$key}}].bleed;
            current_array.border = 0;
            current_array.wrap = "{{$product['wrap']}}";
            current_array.boxX = svg_data[{{$key}}].coor_x;
            current_array.boxY = svg_data[{{$key}}].coor_y;
            current_array.boxWidth = svg_data[{{$key}}].width;
            current_array.boxHeight = svg_data[{{$key}}].height;
            current_array.x = 0;
            current_array.y = 0;
            current_array.imgW = svg_data[{{$key}}].width; //ko dung
            current_array.imgH = svg_data[{{$key}}].height; // ko dung
            current_array.deltaX = 0;
            current_array.deltaY = 0;
            current_array.colors = 'white';
            current_array.opacity = 0.4;
            current_array.stroke = '#333333';
            current_array.strokeWidth = 0;
            current_array.specialKey = '';
            objImg.push(current_array);
        @endforeach
    @endif
    @endif
    // default_objImg = objImg.concat();
    objEdgeColor.wrap = "{{$product['wrap']}}";
    objEdgeColor.colors = 'white';
    objEdgeColor.opacity = 0.4;
    objEdgeColor.stroke = '#333333';
    objEdgeColor.strokeWidth = 0;

    $("#svg_main").css("width",objSize.boxWidth+'px');
    $("#svg_main").css("height",objSize.boxHeight+'px');
    $("#canvas_img_thum").css("width",objSize.boxWidth+'px');
    $("#canvas_img_thum").css("height",objSize.boxHeight+'px');




    function CheckItemChoice(id){
        $("#block_image_"+String(id)+" .icon_close5").css("display","block");
        $("#block_image_"+String(id)+" .cover_album").addClass("choice_image");
        $("#block_image_"+String(id)+" .cover_album").attr("data-check",1);

    }
    function RemoveItemChoice(id){
        $("#block_image_"+String(id)+" .icon_close5").css("display","none");
        $("#block_image_"+String(id)+" .cover_album").removeClass("choice_image");
        $("#block_image_"+String(id)+" .cover_album").attr("data-check",0);
    }
    function ChoiceImgsLib(){
        var html;
        var d = new Date();
        var arrImgs = [];
        $.each($("[data-check=1]"),function( key, value ) {
            var link = $( this ).attr("data-source");
            var data = $(this).data();
            if( data.store == 'google-drive' ) {
                var ext = data.ext;
                $.ajax({
                    url: '{{URL}}/socials/get-image',
                    type: 'POST',
                    async:false,
                    data:{
                        link:link,
                        ext:ext,
                        data: data
                    },
                    async:false,
                    success: function(result){
                        if(result.error==0){
                            link = result.data;
                        }else{
                            link = false;
                        }
                    }
                })
            }
            if( !link ) {
                return;
            }
            html = '<div class="image_content" id="img_upload_vi'+d.getTime()+'">'+
                   "<img class=\"photo\" src=\""+link+"\" alt=\"\" onclick=\"changeImage('"+link+"');\">"+
                   '</div>';
            $(html).prependTo("#slider_image");
            arrImgs.push(link);
        });
        //save session
        $.ajax({
            url:"{{URL}}/save-session-imgs",
            type:"POST",
            data:{'arrImgs':arrImgs},
            success: function(ret){
                console.log(ret);
            }
        });
        $("#dialog" ).dialog({width: 1200}).dialog("close");

    }
</script>