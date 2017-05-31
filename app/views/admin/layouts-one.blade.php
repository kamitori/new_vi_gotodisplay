<div class="portlet">
    {{ Form::open(array('url'=>URL.'/admin/layouts/update-layout', 'method'=>"POST", 'class'=>"form-horizontal", 'id' => 'layout-form') ) }}
        <?php
            if(Session::has('_old_input')) {
                $layout = Session::get('_old_input');
            }
        ?>
        <div class="portlet-title">
            <div class="actions btn-set text-right">
                <a class="btn default"  href="{{ URL.'/admin/layouts' }}"><i class="fa fa-angle-left"></i> Back</a>
                <button class="btn default" type="reset"><i class="fa fa-reply"></i> Reset</button>
                <button class="btn green" type="submit"><i class="fa fa-check"></i> Save</button>
                <button class="btn green" type="submit" name="continue" value="continue"><i class="fa fa-check-circle"></i> Save &amp; Continue Edit</button>
                @if( isset($layout['id']) )
                <div class="btn-group">
                    <a class="btn yellow dropdown-toggle" href="#" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-share"></i> More <i class="fa fa-angle-down"></i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="javascript:void(0)">
                            Duplicate </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" onclick="deleteRecord({ 'deleteUrl' : '{{ URL.'/admin/layouts/delete-product-layout/'.$layout['id'] }}', returnUrl : '{{ URL.'/admin/layouts' }}' })">
                            Delete </a>
                        </li>
                        <li class="divider">
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                            Print </a>
                        </li>
                    </ul>
                </div>
                @endif
            </div>
        </div>
        <div class="portlet-body form">
            <div class="tabbable">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#tab_info" data-toggle="tab">
                        Main infomation </a>
                    </li>
                </ul>
                <div class="tab-content no-space">
                    <div class="tab-pane active" id="tab_info">
                        <div class="form-body">
                            @if( isset($layout['id']) )
                            <input type="hidden" name="id" value="{{ $layout['id'] }}" />
                            @endif
                            <input type="hidden" id="svg" name="svg" value="" />
                            <input type="hidden" id="svg_content" name="svg_content" value="" />
                            <input type="hidden" id="dpi" name="dpi" value="" />
                            <div class="form-group">
                                <label class="col-md-2 control-label">Name<span class="required">
                                * </span>
                                </label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="name" value="{{ $layout['name'] or '' }}" placeholder="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Wall Size
                                </label>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="col-md-2 control-label">Width
                                            </label>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control number text-right" id="wall_size_w" name="wall_size_w" value="{{ $layout['wall_size_w'] or 0 }}" placeholder="" />
                                            </div>
                                            <label class="col-md-2 control-label"> inch
                                            </label>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="col-md-2 control-label">Height
                                            </label>
                                            <div class="col-md-4">
                                                <input type="text" class="form-control number text-right" id="wall_size_h" name="wall_size_h" value="{{ $layout['wall_size_h'] or 0 }}" placeholder="" />
                                            </div>
                                            <label class="col-md-2 control-label"> inch
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Active
                                </label>
                                <div class="col-md-10">
                                    <input type="checkbox" class="form-control" name="active" @if( !isset($layout['active']) || $layout['active'] ) checked @endif value="1" placeholder="" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-2 control-label">Layout Builder
                                </label>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div id="layout-builder" class="form-control height-auto" style="min-height: 150px;">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Width
                                                </label>
                                                <div class="col-md-6">
                                                    <input type="text" id="w" class="form-control number text-right" value="0" placeholder="" />
                                                </div>
                                                <label class="col-md-2 control-label">inch</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Height
                                                </label>
                                                <div class="col-md-6">
                                                    <input type="text" id="h" class="form-control number text-right" value="0" placeholder="" />
                                                </div>
                                                <label class="col-md-2 control-label">inch</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Margin left
                                                </label>
                                                <div class="col-md-6">
                                                    <input type="text" id="x" class="form-control number text-right" value="0" placeholder="" />
                                                </div>
                                                <label class="col-md-2 control-label">inch</label>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-4 control-label">Margin top
                                                </label>
                                                <div class="col-md-6">
                                                    <input type="text" id="y" class="form-control number text-right" value="0" placeholder="" />
                                                </div>
                                                <label class="col-md-2 control-label">inch</label>
                                            </div>
                                            <div class="form-group text-right">
                                                <button id="add-object" class="btn btn-primary col-md-3" data-toggle="tooltip" title="Add new Object"><i class="fa fa-plus"></i></button>
                                                <button id="update-object" class="btn green-meadow col-md-3"  data-toggle="tooltip" disabled  title="Update current Object"><i class="fa fa-edit"></i></button>
                                                <button id="delete-object" class="btn red-sunglo col-md-3"  data-toggle="tooltip" disabled  title="Delete current Object"><i class="fa fa-times"></i></button>
                                                <button id="delete-all-object" class="btn red col-md-3"  data-toggle="tooltip" title="Delete All Object"><i class="fa fa-times-circle"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{ Form::close() }}
</div>
@section('pageCSS')
<link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css' ) }}">
@stop
@section('pageJS')
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/jquery-validation/js/jquery.validate.min.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/svgjs/svg.js' ) }}"></script>
<script type="text/javascript" src="{{ URL::asset( 'assets/global/plugins/svgjs/svg.draggable/svg.draggable.js' ) }}"></script>
<script type="text/javascript">
    closeMenu();

    var config = {
        focus_color    : '#33bbee',
        normal_color   : '#88eeff',
        dpi         : {{ $data['dpi'] }},
        view_dpi    : {{ $data['view_dpi'] }},
        box_size_w  : {{ $layout['box_size_w_pt'] or $data['max_w'] }},
        box_size_h  : {{ $layout['box_size_h_pt'] or $data['max_h'] }},
        max_zoom_in : 2,
        max_zoom_out: 0.02,
        zoom_step   : 0.02,
        move_step   : 50,
        max_w       : {{ $data['max_w'] }},
        max_h       : {{ $data['max_h'] }},

    };

    var draw, defs, group,
        focusBox,
        arrBoxs = {};

    layoutBuilder();

    $("#layout-form").validate({
        errorElement: 'span',
        errorClass: 'help-block help-block-error',
        focusInvalid: false,
        ignore: "",
        messages: {
            select_multi: {
                maxlength: $.validator.format("Max {0} items allowed for selection"),
                minlength: $.validator.format("At least {0} items must be selected")
            }
        },
        rules: {
            name: {
                required: true,
                minlength: 3
            },
            wall_size_w: {
                min: 5
            },
            wall_size_h: {
                min: 5
            },
        },
        invalidHandler: function (event, validator) {
            $(".alert-danger","#layout-form").show();
            Metronic.scrollTo($(".alert-danger","#layout-form"), -200);
        },
        highlight: function (element) {
            $(element)
                .closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element)
                .closest('.form-group').removeClass('has-error');
        },
        success: function (label) {
            label
                .closest('.form-group').removeClass('has-error');
        },
        submitHandler: function (form) {
            $(".alert-danger","#layout-form").hide();
            $("#layout-builder").trigger("click");
            $("#svg_content").val(htmlEntities($("#layout-builder").html()));
            this.submit();
        }
    });

    $("[data-toggle=tooltip]", "#layout-form").tooltip();

    function closeMenu()
    {
        if( !$("ul.sidebar-menu").hasClass("page-sidebar-menu-closed") ) {
            $(".sidebar-toggler", ".sidebar-toggler-wrapper").trigger("click");
        }
    }

    function layoutBuilder(bindEvent)
    {
        var dpi = config.dpi / config.view_dpi;
        var reverse_dpi = 1 / dpi;
        $("#dpi").val(dpi);

        var layout = $("#layout-builder");

        if( $("svg#layout-builder-svg").length ) {
            $("svg#layout-builder-svg").remove();
        }
        layout.css({ width: config.box_size_w +"px", height: config.box_size_h +"px", padding: 0 });
        draw = SVG("layout-builder").attr({
            "width" : config.box_size_w,
            "height": config.box_size_h,
            "id"    : "layout-builder-svg"
        });
        defs    = draw.defs();
        group   = draw.group();

        @if( isset($layout['boxs']) && !empty($layout['boxs']) )
        var x, y, w, h, id;
        @foreach($layout['boxs'] as $box)
        w = {{ $box['width']  }};
        h = {{ $box['height'] }};
        x = {{ $box['coor_x'] }};
        y = {{ $box['coor_y'] }};
        id = {{ $box['id'] }};
        drawObject(w, h, x, y, id);
        @endforeach
        @endif

        if( bindEvent != false ) {
            layout.click(function(e){
                var tag = e.target.innerHTML;
                if ( tag.indexOf("rect") >= 0) {
                    if (focusBox!=undefined ) {
                        focusBox.fill({ color : config.normal_color });
                    }
                    $("#x").val(0);
                    $("#y").val(0);
                    $("#w").val(0);
                    $("#h").val(0);

                    $("#add-object").removeAttr("disabled")
                    $("#update-object").attr("disabled", true);
                    $("#delete-object").attr("disabled", true);
                }
            });

            $("#add-object").click(function(e){
                e.preventDefault();
                var x, y, w, h;
                w = $("#w").val();
                h = $("#h").val();
                x = $("#x").val();
                y = $("#y").val();
                drawObject(w, h, x, y);
            });

            $("#delete-object").click(function(e){
                e.preventDefault();
                var id = $(focusBox.node).attr("id");
                if( id.indexOf("Svgjs") == -1 ) {
                    arrBoxs[id]["deleted"] = 1;
                    updateInputSVG();
                } else {
                    delete arrBoxs[id];
                }
                focusBox.remove();
                layout.trigger("click");
            });

            $("#update-object").click(function(e){
                e.preventDefault();

                var x, y, w, h;
                w = $("#w").val();
                h = $("#h").val();
                x = $("#x").val();
                y = $("#y").val();

                w = w * dpi;
                h = h * dpi;

                x = x * dpi;
                y = y * dpi;

                if( w > config.box_size_w ){
                    toastr.warning("Object is oversize, it will be auto resize.", "Warning");
                    w = config.box_size_w;
                    $("#var_w").val( (w * reverse_dpi).toFixed(2) ).focus();
                }
                if( h > config.box_size_h){
                    toastr.warning("Object is oversize, it will be auto resize.", "Warning");
                    h = config.box_size_w;
                    $("#h").val( (h * reverse_dpi).toFixed(2)  ).focus();
                }

                var x = $("#x").val() * dpi;
                var y = $("#y").val() * dpi;

                if(x < 0 ) {
                    x = 0;
                } else if( x + w >config.box_size_w ) {
                    x = config.box_size_w - w;
                }

                if( y < 0 ) {
                    y = 0;
                } else if( y + h > config.box_size_h ) {
                    y = config.box_size_h - h;
                }
                $("#x").val( (x*reverse_dpi).toFixed(2) );
                $("#y").val( (y*reverse_dpi).toFixed(2) );

                focusBox.move(x, y);
                focusBox.size(w, h);
                var id = $(focusBox.node).attr("id");
                arrBoxs[id].x = x;
                arrBoxs[id].y = y;
                arrBoxs[id].width = w;
                arrBoxs[id].height = h;
                $(focusBox.node).trigger("click");
                updateInputSVG();
            });

            $("#delete-all-object").click(function(e){
                e.preventDefault();

                layout.trigger("click");
                bootbox.confirm("Are you sure you want to delete all objects?", function(result){
                    if( result ) {
                        for(var id in arrBoxs) {
                            console.log(id.indexOf("Svgjs"));
                            if( id.indexOf("Svgjs") == -1 ) {
                                arrBoxs[id]["deleted"] = 1;
                                updateInputSVG();
                            } else {
                                delete arrBoxs[id];
                            }
                            $("svg#layout-builder-svg #"+ id, layout).remove();
                        }
                        updateInputSVG();
                        toastr.success("All objects were deleted successful.", "Message");
                    }
                });
            });

            $("#w, #h, #x, #y").change(function(){
                if( focusBox != undefined && $("#update-object").attr("disabled") == undefined  ) {
                    $("#update-object").trigger("click");
                }
            });

            $("#wall_size_w, #wall_size_h").change(function(){
                updatePanel();
            });
        }
    }

    function drawObject(w, h, x, y, id)
    {
        var dpi = config.dpi / config.view_dpi;
        var reverse_dpi = 1 / dpi;


        var w = w * dpi;
        var h = h * dpi;

        var x = x * dpi;
        var y = y * dpi;

        if( w > 0 && h > 0 ) {
            var attribute = {
                    "width"         : w,
                    "height"        : h,
                    "x"             : x,
                    "y"             : y,
                    "stroke"        : "black",
                    "stroke-width"  : 1,
                    "fill"          : config.normal_color,
            };

            if( id != undefined ) {
                attribute["id"] = id;
            }

            var rect = draw.rect().attr(attribute);
            arrBoxs[rect.id()] = attribute;
            group.add(rect);
            rect.draggable({minX:0, minY:0, maxX: config.box_size_w, maxY: config.box_size_h});

            var updateInput = function (x, y, w, h) {

                var x = x * reverse_dpi;
                var y = y * reverse_dpi;
                if( x < 0 ) {
                    x = 0;
                }
                if( y < 0 ) {
                    y = 0;
                }

                var w = w * reverse_dpi;
                var h = h * reverse_dpi;

                $("#x").val(x.toFixed(2));
                $("#y").val(y.toFixed(2));
                $("#w").val(w.toFixed(2));
                $("#h").val(h.toFixed(2));
            }

            rect.mousedown(function(){
                updateInput(this.x(), this.y(), this.width(), this.height());

                if ( focusBox!=undefined ) {
                    focusBox.fill({ color : config.normal_color });
                }
                this.fill({ color : config.focus_color });
                focusBox = this;
            });
            rect.mouseup(function(){
                updateInput(this.x(), this.y(), this.width(), this.height());

                $("#add-object").attr("disabled", true);
                $("#update-object").removeAttr("disabled").tooltip();
                $("#delete-object").removeAttr("disabled").tooltip();
                updateInputSVG();
            });
            rect.dragmove = function(delta, event) {
                var id = this.id();
                arrBoxs[id].x = this.x();
                arrBoxs[id].y = this.y();
                updateInputSVG();

                if(defs != undefined){
                    draw.add(defs);
                }
            }
            updateInputSVG();
        } else {
            toastr.error("Please enter valid width and height", "Error");
        }
    }

    function updatePanel()
    {
        var wall_size_w = $("#wall_size_w").val()* config.dpi ;
        var wall_size_h = $("#wall_size_h").val()* config.dpi ;

        if( parseFloat(wall_size_w/config.max_w) > parseFloat(wall_size_h/config.max_h) ) {
           config.box_size_w    = config.max_w;
           config.view_dpi      = parseFloat(wall_size_w/ config.box_size_w);
           config.box_size_h    = parseFloat(wall_size_h  / config.view_dpi);
        } else {
           config.box_size_h    = config.max_h;
           config.view_dpi      = parseFloat(wall_size_h  / config.box_size_h);
           config.box_size_w    = parseFloat(wall_size_w  / config.view_dpi);
        }
        layoutBuilder(false);
    }

    function updateInputSVG()
    {
        $("input#svg").val(JSON.stringify(arrBoxs));
    }

    function htmlEntities(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }
</script>
@stop
