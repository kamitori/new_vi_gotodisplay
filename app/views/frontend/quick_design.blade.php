@section('pageCSS')
<input type="hidden" id="bg_square_image" value="{{$square_background_image}}">
<input type="hidden" id="bg_horizontal_image" value="{{$horizontal_image}}">
<input type="hidden" id="bg_vertical_image" value="{{$vertical_image}}">
<input type="hidden" id="bg_back_image" value="{{$background_image}}">
<input type="hidden" id="origin_image" value="">
<link href="{{URL}}/assets/css/font-awesome.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="{{URL}}/assets/css/jquery-ui.min.css">
<style type="text/css">
    #preview_content:hover {
        cursor: url(/assets/images/openhand.cur), auto;
    }
    #preview_content:active {
        cursor: url(/assets/images/closedhand.cur), auto;
    }
    #preview_content canvas {
        background-color: black;
    }
    .fancybox-slide .col-sm-6 , .fancybox-slide .col-xs-6{
        width:100% !important;
        height:100px ;
        float:left;
    }
    .fancybox-slide .col-sm-6 img ,.fancybox-slide .col-xs-6 img {
        max-height:400px;   
        padding:2px;
    }
    .box_image img {
        /*max-height: 200px !important;*/
        max-width: 100%;
        height: auto;
    }
    #content_my_upload{
        height:500px !important;
        overflow-y: scroll !important;
        display: block;
    }
    #content_my_upload img {
        margin-right: 2%;
        margin-bottom: 2%;
    }
    .box_image {
        width:70px;float:left;text-align: center; padding:1%;
    }
</style>
<!--[if lt IE 9]>
    <link rel="stylesheet" type="text/css" href="{{URL}}/assets/themes/default/css/ie.css" />
<![endif]-->
@stop

@section('body')

<body class="page-the-floral-paradox-tee template-product" data-twttr-rendered="true">
@stop
    <div id="fb-root"></div>
    <textarea id="content_display" style="display:none">
        {{$contents or ''}}
    </textarea>
    <section class="main-content">
        <!-- 
            <a name="detail"></a>
            <header>
                <div class="row show-for-medium-up">
                    <div class="columns">
                        <ul class="breadcrumbs colored-links">
                            <li><a href="{{URL}}/">Home</a></li>
                            @if(!isset($from_product))
                                <li><a href="{{URL}}/collections">Shop</a></li>
                                <li><a href="{{URL}}/collections/{{$collection['short_name']}}">{{$collection['name']}}</a></li>
                            @else
                                <li><a href="{{URL}}/collections/">{{$collection['name']}}</a></li>
                            @endif
                                <li>{{$product['name']}}</li>
                        </ul>
                    </div>
                </div>
            </header> 
        -->
        <div class="breadcrumbs">
            <img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
            <ul class="unstyled-list">
                <!-- <li><a href="{{URL}}/">Home</a></li>
                @if(!isset($from_product))
                    <li><a href="{{URL}}/collections">Shop</a></li>
                    <li><a href="{{URL}}/collections/{{$collection['short_name']}}">{{$collection['name']}}</a></li>
                @else
                    <li><a href="{{URL}}/collections/">{{$collection['name']}}</a></li>
                @endif
                    <li>{{$product['name']}}</li> -->
                <li>{{$product['name']}}</li>
            </ul>
        </div>
        <a name="quick_design"></a>
        <article class="design">
            <div class="quick_design">
                <div class="header">
                    <div id="preview_bt" class="ds_tool_group ds_border_left right" style="display:block;position:relative;">
                        <div id="zoom_bt" class="ds_tool_group" style="display:none">
                            <div class="ds_button zoom_in" onclick="zoomInImage()">
                                <!-- <div class="ds_button_icon"><span class="glyph fa fa-search-plus"></span></div>
                                <div class="ds_button_name">Zoom in</div> -->
                            </div>
                            <div class="ds_button zoom_out" onclick="zoomOutImage()">
                                <!-- <div class="ds_button_icon"><span class="glyph fa fa-search-minus"></span></div>
                                <div class="ds_button_name">Zoom out</div> -->
                            </div>
                        </div>
                        <div class="ds_button view3d" onclick="Preview3D()">
                            <!-- <div class="ds_button_icon" style="color:red;"><i class="fa fa-fw fa-cube"></i></div>
                            <div class="ds_button_name">Preview 3D</div> -->
                        </div>
                        <input type="text" id="img-link" style="display:none" value="" >
                        <div class="ds_button preview" onclick="Preview()">
                            <!-- <div class="ds_button_icon" style="color:red;"><i class="fa fa-fw fa-eye"></i></div>
                            <div class="ds_button_name">Preview</div> -->
                        </div>
                        @if (!($product['product_type']==6 && $product['number_img']))
                        <div class=" slider_bt" style="position: absolute;margin: 13px 1px 1px 33px;display:none;right: 30px;">
                            <span style="width:54px;float:left;font-family:verdana;">Rotate</span>
                            <input type="text" id="amount" style="width: 36px;color:#f6931f;font-weight:bold;text-align: center;margin-bottom: 5px;" />
                            <div id="slider-vertical" style="margin: -1px 4px 4px 65px;"></div>
                        </div>
                        <div class=" slider_bt" style="position: absolute;margin: 167px 1px 155px 33px;display:none;right: 30px;">
                            <span style="width:54px;float:left;font-family:verdana;">Zoom</span>
                            <div id="zoom-slider" style="margin: -1px 4px 4px 30px;"></div>
                        </div>
                        @endif
                    </div>
                    

                    <div id="upload_bt" class="ds_tool_group ds_border_right" style="">
                        <div id="dsbt_upload" class="upload ds_button dsbt {{ Session::has('user_ip')?'':'ds_active' }} ">
                        </div>
                        <div id="dsbt_my_upload" class="my_upload ds_button dsbt  {{ Session::has('user_ip')?'ds_active':'' }}">
                            <!-- <div class="ds_button_icon" style="color:green;"><i class="fa fa-fw fa-picture-o"></i></div>
                            <div class="ds_button_name">My upload</div> -->
                        </div>
                        <div id="dsbt_layout_size" class=" size ds_button dsbt">
                            <!-- <div class="ds_button_icon"><i class="fa fa-fw fa-th-large"></i></div>
                            <div class="ds_button_name">Size</div> -->
                        </div>
                        @if( (isset($product_option[7]) && !empty($product_option[7])) || (isset($product_option[2]) && !empty($product_option[2])) || (isset($product_option[5]) && !empty($product_option[5])) )
                        <div id="dsbt_style" class="style ds_button dsbt">
                            <!-- <div class="ds_button_icon"><i class="fa fa-fw fa-cubes"></i></div>
                            <div class="ds_button_name">Style</div> -->
                        </div>
                        @endif
                        @if( !empty($arrbleeb) && count($arrbleeb) > 1 )
                        <div id="dsbt_frame_depth" class="depth ds_button dsbt">
                            <!-- <div class="ds_button_icon"><i class="fa fa-fw fa-cube"></i></div>
                            <div class="ds_button_name">Depth</div> -->
                        </div>
                        @endif
                        @if(isset($product_option[8]) && !empty($product_option[8]))
                        <div id="dsbt_border" class="border ds_button dsbt">
                            <!-- <div class="ds_button_icon"><i class="fa fa-fw fa-square-o"></i></div>
                            <div class="ds_button_name">Border</div> -->
                        </div>
                        @endif
                        @if( !empty($jt_options) )
                        <?php
                            $optionShow = 0;
                            foreach($jt_options as $option) {
                                if( isset($option['hidden'])&&$option['hidden'] ) continue;
                                $optionShow++;
                            }
                        ?>
                        @if( $optionShow > 0 )
                        <!--
                        <div id="dsbt_option" class=" option ds_button dsbt">
                           
                        </div> 
                        -->
                        @endif
                        @endif
                    </div>
                    <div id="image_bt" class="ds_tool_group" style="display:none">
                        <div id="dsbt_filter" class="filter ds_button dsbt">
                            <!-- <div class="ds_button_icon" style="color:green;"><i class="fa fa-fw fa-filter"></i></div>
                            <div class="ds_button_name">Filter</div> -->
                        </div>
                        <div id="dsbt_addjust_filter" class="ds_button dsbt" style="display:none;">
                            <!-- <div class="ds_button_icon" style="color:green;"><i class="fa fa-fw fa-filter"></i></div>
                            <div class="ds_button_name">Adjust Filter</div> -->
                        </div>
                        <div class="ds_button rotate" onclick="rotateImage()">
                            <!-- <div class="ds_button_icon"><i class="fa fa-fw fa-repeat"></i></div>
                            <div class="ds_button_name">Rotate Image</div> -->
                        </div>
                        <div class="ds_button rotate_frame"  onclick="rotateFrame()">
                            <!-- <div class="ds_button_icon"><i class="fa fa-fw fa-share-square"></i></div>
                            <div class="ds_button_name">Rotate Frame</div> -->
                        </div>
                        <div class="ds_button flipx" onclick="flipImageX()">
                            <!-- <div class="ds_button_icon"><i class="fa fa-fw fa-sort2"></i></div>
                            <div class="ds_button_name">Flip X</div> -->
                        </div>
                        <div class="ds_button flipy" onclick="flipImageY()">
                            <!-- <div class="ds_button_icon"><i class="fa fa-fw fa-sort"></i></div>
                            <div class="ds_button_name">Flip Y</div> -->
                        </div>
                        <div class="ds_button resolution" onclick="resolution()">
                            <!-- <div class="ds_button_icon"><i class="fa fa-fw fa-flag"></i></div>
                            <div class="ds_button_name">Resolution</div> -->
                        </div>
                    </div>
                    <div id="loading_wait" style="width:160px; margin:68px 0 0 1000px; position:absolute;display:none;float:right;">
                        <img src="{{URL}}/assets/images/others/ajax-loader.gif" alt="title" />
                        <span> Loading ...</span>
                    </div>
                    <input id="zoomImage" type="hidden" value="1" />
                    <input id="rotateImage" type="hidden" value="0" />
                    <input id="flipImageX" type="hidden" value="1" />
                    <input id="flipImageY" type="hidden" value="1" />
                    <input id="rotateFrame" type="hidden" value="{{$product['rotate_frame']}}" />
                    <input id="productQuantity" type="hidden" value="{{$product['quantity']}}" />
                    <input id="newPreview" type="hidden" value="" />
                    <input id="trueSizeH" type="hidden" value="{{(float)$svg_setup['svg']['height']*(float)$svg_setup['svg']['view_dpi']}}" />
                    <input id="trueSizeW" type="hidden" value="{{(float)$svg_setup['svg']['width']*(float)$svg_setup['svg']['view_dpi']}}" />
                    <input id="wrapstyle" type="hidden" value="white" />
                    @if($product['id']==188)
                        <input name="frame_style" type="radio" value="black" rel="aluminum_border" checked="checked" style="display:none" />
                    @endif
                </div>
                <div class="content" style="display:table;">
                    <div class="left_content">
                        <!-- Upload -->
                        <div id="content_upload" class="content_list" style=" {{ Session::has('user_ip')?'display:none':'display:table' }} ;width:100%;">
                            <h3>From your computer:</h3>
                            <form id="upload_file" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
                                <input type="file" name="upload_file" id="fileup" accept="image/*"/>
                                <br/>
                                <input type="submit" class="btn btn-4 btn-white" value="Upload" id="upload_file_bt" />
                            </form>
                            <div id="loading_none" style="display:none;">
                                <img src="{{URL}}/assets/images/others/loading.gif" alt="title" />Loading ...
                            </div>
                            <div id="import_image" style="margin-top:20px;">
                                    <h3>From:</h3>
                                    <!-- <div class="col-xs-4" >
                                            <img id="import_vi" src="/assets/images/social_icon/button-vi.png" alt="" style="width:100%; max-height: 90px;" alt="VI" title="VI">
                                    </div> -->
                                    @if(isset($arr_socail_id['facebook']))
                                    <div class="col-xs-4" >
                                            <img onclick="importFB()" id="import_fb" src="/assets/images/social_icon/button-facebook.png" alt="" style="width:100%;" alt="Facebook" title="Facebook">
                                    </div>
                                    @endif
                                    @if(isset($arr_socail_id['flickr']))
                                    <div class="col-xs-4" >
                                            <img onclick="importFlickr()" id="import_flickr" src="/assets/images/social_icon/button-flickr.png" alt="" style="width:100%;" alt="Flickr" title="Flickr">
                                    </div>
                                    @endif
                                    @if(isset($arr_socail_id['dropbox']))
                                    <div class="col-xs-4" >
                                            <img onclick="importDropbox()" id="import_dropbox" src="/assets/images/social_icon/button-dropbox.png" alt="" style="width:100%;" alt="Dropbox" title="Dropbox">
                                    </div>
                                    @endif
                                    @if(isset($arr_socail_id['googledrive']))
                                    <div class="col-xs-4" >
                                            <img onclick="importGoogledrive()" id="import_googledrive" src="/assets/images/social_icon/button-googledrive.png" alt="" style="width:100%;" alt="Goole Drive" title="Goole Drive">
                                    </div>
                                    <!-- <div class="col-xs-4" >
                                            <img id="import_picasa" src="/assets/images/social_icon/button-picasa.png" alt="" style="width:100%;" alt="Google Picasa" title="Google Picasa">
                                    </div> -->
                                    @endif

                                    @if(isset($arr_socail_id['skydrive']))
                                    <div class="col-xs-4" >
                                            <img onclick="importSkydrive()" id="import_skydrive" src="/assets/images/social_icon/button-skydrive.png" alt="" style="width:100%;" alt="Skydrive or Onedrive" title="Skydrive or Onedrive">
                                    </div>
                                    @endif
                                    @if(isset($arr_socail_id['instagram']))
                                    <div class="col-xs-4" >
                                            <img onclick="importInstagram()" id="import_instagram" src="/assets/images/social_icon/button-instagram.png" alt="" style="width:100%;" alt="Instagram" title="Instagram">
                                    </div>
                                    @endif
                                    @if(isset($arr_socail_id['pinterest']))
                                    <div class="col-xs-4" >
                                            <img id="import_pinterest" src="/assets/images/social_icon/button-pinterest.png" alt="" style="width:100%;" alt="Pinterest" title="Pinterest">
                                    </div>
                                    @endif

                            </div>
                            <div id="loading_import" style="display:none;margin-top:20px;">
                                <img src="{{URL}}/assets/images/others/loading.gif" alt="title" style="width:200px;"/>Loading ...
                            </div>
                            <div id="dialog" title="Import Image" style="display:none;">
                                    <div class="lib_box_search" style="display:none">
                                        <input name="searchlib_text" id="searchlib_text" type="text" placeholder="Search by tags">
                                        <input id="searchlib_bt" type="button" value=" Search ">
                                    </div>
                                    <h3 class="of_album">List Album</h3>
                                    <div id="list_album" class="of_album">
                                    </div>
                                    <h3>List Image</h3>
                                    <div id="list_image">
                                    </div>
                            </div>
                            <div id="dialog_resolution" title="Resolution Image" style="display:none;">

                            </div>
                        </div>
                        <!-- My Upload -->
                        <div id="content_my_upload" class="content_list" style="height:500px;        {{ Session::has('user_ip')?'display:block':'display:none' }}">
                            @if ($product['product_type']==6 && ($product['number_img']==2 || $product['number_img']==3))
                                <?php $m=0;?>
                                @foreach($arr_img as $link)
                                    @if($m==0)
                                    <div class="box_image">
                                        <img src="{{URL}}{{$link}}" alt="title" onclick="switchImage('{{URL}}{{$link}}',this);" class="imgFocus img_upload"/>
                                    </div>
                                    @elseif($m<18)
                                        <div class="box_image">
                                            <img src="{{URL}}{{$link}}" alt="title" onclick="switchImage('{{URL}}{{$link}}',this);" class="img_upload"/>
                                        </div>
                                    @endif
                                    <?php $m++;?>
                                @endforeach
                            @else

                                <?php $m=0;?>
                                @foreach($arr_img as $link)
                                    @if($m<18)
                                        <div class="box_image">
                                            <img src="{{URL}}{{$link}}" alt="title" onclick="changeImage('{{URL}}{{$link}}');" class="img_upload"/>
                                        </div>
                                    @endif
                                    <?php $m++;?>
                                @endforeach
                            @endif
                        </div>
                        <!-- Layout & Size -->
                        <div id="content_layout_size" class="content_list" style="display:none;">
                            <table class="full_width">
                                <thead>
                                    <tr>
                                        <td align="left">Size (inch)</td>
                                        <td style="text-align:right;">C$</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $df='10x10'; $checked_cus = 'checked="checked"'; ?>
                                    @if(!empty($product_sizes))
                                    @foreach($product_sizes as $key=>$value)
                                    <?php
                                        $vv = str_replace("&nbsp;","",$value["size"]);
                                        $df = $size_default[0].'x'.$size_default[1];
                                        $checked = '';
                                        if($vv==$df){
                                            $checked = 'checked="checked"';
                                            $checked_cus = '';
                                        }

                                    ?>
                                    <tr class="range">
                                        <td align="left">{{$value['size']}}<span class="check_quality"></span></td>
                                        <td style="text-align:right;">{{number_format($value['sell_price'],2)}}</td>
                                        <td class="ds_check_box">

                                            <input name="sizes" id="sizes_{{$key}}" class="size_list" type="radio" value="{{$vv}}" {{$checked}}  onclick="changeSize('{{$vv}}','{{number_format($value['sell_price'],2)}}')" rel="{{number_format($value['sell_price'],2)}}" />
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                    <tr @if( !$product['custom_size'] ) style="display: none;" @endif >
                                        <td colspan="3"><h3>Custom size</h3></td>
                                    </tr>
                                    <tr @if( !$product['custom_size'] ) style="display: none;" @endif>
                                        <td align="left">
                                            <p>Width<p/>
                                            <input type="text" class="custom_size" name="custom_width" id="custom_width" value="{{$size_default[0]}}" style="text-align:right;" onchange="customSize(this)" />
                                            <p>Heigth<p/>
                                            <input type="text" class="custom_size" name="custom_height" id="custom_height" value="{{$size_default[1]}}" style="text-align:right;" onchange="customSize(this)" />
                                        </td>
                                        <td style="text-align:right;"><span class="price_cal">Custom price</span></td>
                                        <td class="ds_check_box">
                                            <input name="sizes" id="sizes_custom" type="radio" value="{{$df}}" onclick="changeSize('{{$df}}')" {{$checked_cus}} rel="custom" />
                                        </td>

                                    </tr>
                                    <tr style="display: none">
                                        <td colspan="1"><h3>Quantity</h3></td>
                                    </tr>
                                    <tr style="display: none">
                                            <td align="left">
                                                <input type="number" class="custom_size" name="custom_quantity" id="custom_quantity" value="{{$product['quantity']}}" style="text-align:right;" onchange="changeQuantity()" />
                                            </td>
                                            <td colspan="2"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Style -->
                        <div id="content_style" class="content_list" style="display:none;">
                            @if(isset($product_option[7]))
                            <h3>Select Wrap Option</h3>
                            @foreach($product_option[7] as $key=>$value)
                            <?php
                                $checked = '';
                                if ($border_frame=='') {
                                    $border_frame = 'natural';
                                }
                                if($key == $border_frame)
                                    $checked = 'checked="checked"';
                            ?>
                                <div id="opbox_{{$key}}" class="step3-options" style="display: block;">
                                    <label for="opstyle_{{$key}}">
                                    <ul class="list-unstyled">
                                        <li class="col1">
                                            <input type="radio" id="opstyle_{{$key}}" name="frame_style" value="{{$key}}" onclick="changeWrapFrame('{{$key}}','{{$value}}')" title="{{$value}}" rel="{{($key!='red')?$key:'#ff0000'}}"  {{$checked}} />
                                            <span><b>{{$value}}</b></span>
                                        </li>
                                        <li class="col2">
                                            <div class="float-L thumb-img item_{{$key}}"></div>
                                            <p class="price"></p>
                                        </li>
                                    </ul>
                                    </label>
                                </div>
                            @endforeach
                            @endif

                            @if(isset($product_option[5]))
                            <h3 class="col-md-12 col-sm-12 col-xs-12">Select Frame Colour</h3>
                            @foreach($product_option[5] as $key=>$value)
                                 <?php
                                    if ($product['canvasframe']=='') {
                                        $product['canvasframe'] = 'black_frame';
                                    }

                                    if($key == $product['canvasframe'])
                                        $checked = 'checked="checked"';
                                    else
                                        $checked = '';
                                ?>
                                <div id="opbox_{{$key}}" class="step3-options" style="display: block;">
                                    <label for="opstyle_{{$key}}">
                                    <ul class="list-unstyled">
                                        <li class="col1">
                                            <input type="radio" id="opstyle_{{$key}}" name="frame_style" value="{{$key}}" onclick="changeWrapFrame('{{$key}}','{{$value}}')" title="{{$value}}" rel="{{($key!='red')?$key:'#623031'}}"  {{$checked}} />
                                            <span><b>{{$value}}</b></span>
                                        </li>
                                        <li class="col2">
                                            <div class="float-L thumb-img item_{{$key}}"></div>
                                            <p class="price"></p>
                                        </li>
                                    </ul>
                                    </label>
                                </div>
                            @endforeach
                            @endif

                            @if(isset($product_option[2]))
                            <h3 class="col-md-12 col-xs-12 col-sm-12">Select Edge Color</h3>
                            @foreach($product_option[2] as $key=>$value)
                                 <?php
                                 $checked = '';
                                    if ($edge_color=='') {
                                        $edge_color = 'blackedge';
                                    }
                                    if($key == $edge_color)
                                        $checked = 'checked="checked"';
                                ?>
                                <div id="opbox_{{$key}}" class="step3-options" style="display: block;">
                                    <label for="opstyle_{{$key}}">
                                    <ul class="list-unstyled">
                                        <li class="col1">
                                            <input type="radio" id="opstyle_{{$key}}" name="frame_style" value="{{$key}}" onclick="changeWrapFrame('{{$key}}','{{$value}}')" title="{{$value}}" rel="{{($key!='red')?$key:'#abcdef'}}"  {{$checked}} />
                                            <span><b>{{$value}}</b></span>
                                        </li>
                                        <li class="col2">
                                            <div class="float-L thumb-img item_{{$key}}"></div>
                                            <p class="price"></p>
                                        </li>
                                    </ul>
                                    </label>
                                </div>
                            @endforeach
                            @endif
                        </div>
                        @if(isset($product_option[7]))
                        <!-- Pick color -->
                        <div id="pick_color" class="content_list" style="display:none;">
                            <button type="button" class="close_picker" onclick="closePickColor()">×</button>
                            <div class="ChooseColor">
                                <div>Create your own colour</div>
                                <div id="pickcolorbox">

                                        @include('frontend.quick_design.pick_color')

                                </div>
                                <div id="divPMS">
                                    <div id="Matchingto">
                                        <div class="choiced_color_box"><div class="choiced_color_img picker_h" style="background-color:#ffffff;" id="colorbg"></div><div class="choiced_color_text">HEX: <input type="text" value="121212" id="colorhex" maxlength="6" /> </div></div>
                                    </div>
                                </div>
                                <div class="choiced_color_box" style="border:none;">
                                    <button id="btnChooseColorFromImg" type="button" class="btn btn-default cf-btn-colorPicker">
                                        <span class="cf-btn-colorPicker-icon"></span>
                                        <span class="cf-btn-colorPicker-text LocalizedStrings" data-localizedstringname="WrapColorPickerButton">Choose a colour<br>from your photo...</span>
                                    </button>
                                </div>
                                <div class="choiced_color_box" style="border:none;">
                                    <button id="btnChooseColor1" type="button" class="btn btn-4 btn-white" onclick="closePickColor();">
                                        <span>Choose Color</span>
                                    </button>
                                </div>
                            </div>

                        </div>
                        @endif
                        <!-- Option -->
                        <div id="content_frame_depth" class="content_list" style="display:none;">
                            <h3>Select Depth </h3>

                            @if(!empty($arrbleeb))
                            @foreach($arrbleeb as $key=>$value)
                            <?php
                                $checked = '';
                                $key2 = str_replace(".","_",$key);
                                if($product['bleed']==0)
                                    $product['bleed'] = 2.0;
                                if((float)$key == (float)$product['bleed'])
                                    $checked = 'checked="checked"';
                            ?>
                                <div id="bleed_{{$key2}}" class="step3-options" style="display: block;">
                                    <label for="bleedstyle_{{$key2}}">
                                    <ul class="list-unstyled">
                                        <li class="col1">
                                            <input type="radio" id="bleedstyle_{{$key2}}" name="bleed_style" value="{{$key}}" onclick="changeBleed('{{$key}}')"  {{$checked}} />
                                            <span><b>{{$value}}</b></span>
                                        </li>
                                        <li class="col2">
                                            <div class="float-L thumb-img item_{{$key2}}"
                                            <?php if(isset($dept_image[$key])) echo "style='background: url(".$dept_image[$key].") no-repeat 0px 0px; !important' " ?>
                                            ></div>
                                            <p class="price"></p>
                                        </li>
                                    </ul>
                                    </label>
                                </div>
                            @endforeach
                            @endif
                        </div>
                        <!-- Border -->
                        <div id="content_border" class="content_list" style="display:none;">
                            <h3>Select Border</h3>
                            @if(isset($product_option[8]) && !empty($product_option[8]))
                            @foreach($product_option[8] as $key=>$value)
                            <?php
                                $checked = '';
                                $border_value = str_replace("border","",$key);
                                $border_value = (float)$border_value;
                                if($border_value == (float)$product['border_in'])
                                    $checked = 'checked="checked"';
                            ?>
                                <div id="border_{{$border_value}}" class="step3-options" style="display: block;">
                                    <label for="borderstyle_{{$border_value}}">
                                    <ul class="list-unstyled">
                                        <li class="col1">
                                            <input type="radio" id="borderstyle_{{$border_value}}" name="border_style" value="{{$key}}" onclick="changeBorder('{{$key}}')"  {{$checked}} />
                                            <span><b>{{$value}}</b></span>
                                        </li>
                                        <li class="col2">
                                            <div class="float-L thumb-img item_{{$border_value}}"></div>
                                            <p class="price"></p>
                                        </li>
                                    </ul>
                                    </label>
                                </div>
                            @endforeach
                            @endif
                        </div>
                         <!-- Addjust Filter -->
                        <div id="content_addjust_filter" class="content_list" style="display:none; width:100%;">
                            <h3>Addjust Filter</h3>
                             @include('frontend.quick_design.filter_view')
                        </div>
                        <!-- Filter -->
                        <div id="content_filter" class="content_list" style="display:none;">
                            <h3>Filter image</h3>
                             @foreach($filter as $key=>$value)
                             @if($key=='original')
                                <div class="step3-options " style="display: block;">
                                    <label for="opfilter_{{$key}}">
                                    <ul class="list-unstyled">
                                        <li class="col1">
                                            <input type="radio" id="opfilter_{{$key}}" name="filter_type" value="{{$key}}" onclick="filterImage('{{$key}}');" checked="checked" />
                                            <span><b>{{$value['name']}}</b></span>
                                        </li>
                                        <li class="col2">
                                            <div class="float-L thumb-img item_{{$key}}"></div>
                                            <p class="price"></p>
                                        </li>
                                    </ul>
                                    </label>
                                </div>
                            @else
                                <div class="step3-options " style="display: block;">
                                    <label for="opfilter_{{$key}}">
                                    <ul class="list-unstyled">
                                        <li class="col1">
                                            <input type="radio" id="opfilter_{{$key}}" name="filter_type" value="{{$key}}" onclick="filterImage('{{$key}}');" />
                                            <span><b>{{$value['name']}}</b></span>
                                        </li>
                                        <li class="col2">
                                            <div class="float-L thumb-img item_{{$key}}"></div>
                                            <p class="price"></p>
                                        </li>
                                    </ul>
                                    </label>
                                </div>
                            @endif
                            @endforeach
                        </div>
                        @if( !empty($jt_options) )
                        @if( $optionShow > 0 )
                        <!-- 
                        <div id="content_option" class="content_list" style="display:none;">
                            <table class="full_width">
                                <thead>
                                    <tr>
                                        <td align="left">Options</td>
                                        <td style="text-align:right;">Price (CAD)</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jt_options as $key => $option)
                                    <?php if( isset($option['hidden'])&&$option['hidden'] ) continue; ?>
                                    <tr class="range">
                                        <td align="left">
                                            {{$option['name']}}
                                        </td>
                                        <td style="text-align:right;">
                                            {{ number_format($option['unit_price'], 2) }}
                                        </td>
                                        <td class="ds_check_box">
                                            <input class="product_options" name="options[]" type="checkbox" onchange="$('[name=sizes]:checked').trigger('click')" value="{{$key}}" />
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        -->
                        @endif
                        @endif
                    </div>
                    <div class="right_content">
                        <div class="canvas_img_thum" style="display:none; text-align: center;">
                            <canvas id="canvas_imgs"></canvas>
                        </div>

                        <div id="svg_main" style="display:block;text-align: center;line-height: 450px;">

                        </div>

                        @if($product['product_type']==8)
                            <div id="shapdown_view_bot" style=""></div>
                            <div id="shapdown_view_right" style="width: 36px;height:327px;margin: -382px 0px 0 519px;"></div>
                        @endif

                    </div>
                </div>
                <div id="preview_box" style="display:none;">
                    <div id="preview_content" style="width:100%;">
                    </div>
                    <div id="image_box" style="display:none;">
                        <div id="img_precview" style="width:100%; margin:auto;display:block;">
                            <img id="img_svg_precview" src="{{URL}}/assets/images/others/loading.gif" class="box-sharow-2d-request 2box-sharow-2d-request-mright2percent" alt="title" style="max-height:300px;" />
                        </div>
                        <div style="background: url({{url()}}/{{$bg_2d_image}}) bottom center no-repeat !important;min-height:270px">&nbsp;
                        </div>
                    </div>

                    <button onclick="closePreview();" style="margin-top:15px;" class="btn btn-4 btn-white">Close</button>
                </div>
                <div class="footer">
                    <div class="alert_text" id='quan_custom'></div>
                    <div class="final_name">
                        <h3 style="margin-top: 5px;margin-bottom: 5px;">
                            <span id="name_name">{{isset($product['name'])?$product['name']:''}}</span>
                            <span id="name_size">
                                ({{$size_default[0].'"x'.$size_default[1].'"'}})
                            </span>
                            <span id="name_border">
                                @if( isset($product_option) && isset($product_option[7]) && isset($product_option[7][$border_frame]) )
                                    - {{$product_option[7][$border_frame]}}
                                @endif
                             </span>
                             <span id="name_borders">
                                @if(isset($product['border_in']) && (float)$product['border_in']>0)
                                    - border {{$product['border_in']}} "
                                @endif
                             </span>
                            <span id="name_bleed">
                                @if( isset($arrbleeb[$product['bleed']]))
                                    - {{$arrbleeb[$product['bleed']]}} -
                                @endif
                             </span>
                            <span id="check_quality" class="check_quality"></span>
                            <span id="name_price" style="color:red;">
                                @if( isset($product['sell_price']))
                                  ${{number_format($product['sell_price'],2)}}
                                @endif
                            </span>

                        </h3>
                        <button class="btn btn-4 btn-white pull-right" onclick="checkAddCart();" style="margin-top: -30px;"> {{ isset($svgInfo['cart_id']) ? 'Update' : 'Add to' }} cart </button>
                    </div>
                </div>
            </div>
        </article>
</section>

<div id="popupmsm" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default nobt" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary yesbt" id="yesbt">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


@section('pageJS')
<script src="{{ URL }}/assets/js/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var arr_bleeds = {{json_encode($arrbleeb)}};
    $("#dialog").on( "dialogclose", function( ) {
        $("#dialog .lib_box_search").hide();
    } );
        var defaultRatio = {{ $defaultRatio }};
        @if( $product['product_type']==6 && $product['number_img'])
        var scale_w = 1;
        var scale_h = 1;
        var selected = 0;
            @for($i=1;$i<=$product['number_img'];$i++)
                var image{{$i}};
            @endfor
        @endif
        $("document").ready(function(){
            $(".dsbt").click(function(){
                var cont = $(this).attr('id');
                cont = cont.replace("dsbt_","content_");
                $(".ds_button").removeClass('ds_active');
                $(this).addClass('ds_active');
                $(".content_list").css("display","none");
                if(cont=='content_my_upload') $("#"+cont).css("display","block");
                else $("#"+cont).css("display","table");
            });
            //fix header and footer
            $( window ).scroll(function(){
                // if($(document).scrollTop()>316)
                //     $(".header").addClass('fixintop');
                // else
                //    $(".header").removeClass('fixintop');
               var tmp = $(".content").height()+$(".content").offset().top;
               var tmp2 = $(window).height()+$(document).scrollTop();
               // if(tmp2-tmp<49)
               //      $(".footer").addClass('fixinbottom');
               //  else
               //     $(".footer").removeClass('fixinbottom');
            });
            $("#fileup").change(function(event){
                uploadFiles(event,0,1);
            });

            $(".ds_button").click(function(){
                if($(this).attr("onclick")!="rotateImage()" && $(this).attr("onclick")!="zoomInImage()" && $(this).attr("onclick")!="zoomOutImage()" && $(this).attr("onclick")!=undefined)
                    $(".slider_bt").css("display","none");
                else
                    $(".slider_bt").css("display","block");

                if($(this).attr("onclick")=="Preview3D()" || $(this).attr("onclick")=="Preview()")
                    $("#quan_custom").html("");
            });
            $("#btnChooseColorFromImg").click(function(){
                pickColorFromImage();
            });

        });

    $('#import_vi').click(function(){
        getVIImages();
    });
    $('#searchlib_bt').click(function(){
        var tags = $('#searchlib_text').val();
        getVIImages(tags);
    });
    function getVIImages(tags)
    {
        var data = {};
        if( tags ) {
            data['tags'] = tags;
        }
        $.ajax({
            url: '{{ URL.'/get-vi-images' }}',
            type: 'POST',
            data: data,
            success: function(result) {
                var html = '';
                if( result.length ) {
                    for(var i in result) {
                        html += '<div class="large-2 columns block_album">' +
                                    '<div class="block_image">' +
                                        '<img class="cover_album" data-source="'+ result[i].link +'" src="'+ result[i].thumb +'" onclick="ChooseImage_fb(this)" data-ext="'+ result[i].ext +'" data-store="'+ result[i].store +'"/>' +
                                    '</div>' +
                                '</div>';
                    }
                }
                $("#loading_import").hide();
                $(".of_album").hide();
                $("[text ='List Album']").hide();
                $("#loading_import").hide();
                $("#dialog").dialog("open");
                $('#dialog .lib_box_search').show();
                $("#list_image").css('min-height','350px')
                                .html(html);
            }
        });
    }
    @include('frontend.quick_design.cal_price_function')
</script>
    @include('frontend.quick_design.general')
    @include('frontend.quick_design.drag_image')
    @include('frontend.quick_design.filter_script')
    <!--
         // include('frontend.quick_design.filter_script')
         // Include filter_script tại đây để chạy filter
     -->
    @include('frontend.quick_design.imagestylor_canvas')
    @include('frontend.quick_design.preview3d')
    @if(isset($product_option[7]))
        @include('frontend.quick_design.pick_color_image')
    @endif
    @if(isset($arr_socail_id['facebook']))
    	@include('frontend.quick_design.image_from_facebook')
    @endif
    @if(isset($arr_socail_id['flickr']))
    	@include('frontend.quick_design.image_from_flickr')
    @endif
    @if(isset($arr_socail_id['dropbox']))
    	@include('frontend.quick_design.image_from_dropbox')
    @endif
    @if(isset($arr_socail_id['googledrive']))
    	@include('frontend.quick_design.image_from_googledrive')
    	@include('frontend.quick_design.image_from_picasa')
    @endif
    @if(isset($arr_socail_id['skydrive']))
      @include('frontend.quick_design.image_from_skydrive')
    @endif
    @if(isset($arr_socail_id['instagram']))
    	@include('frontend.quick_design.image_from_instagram')
    @endif

    @if($product['product_type']==6 ) //Wall splits
        @include('frontend.quick_design.product_type.wall_splits')
    @endif

    @include('frontend.quick_design.call_pointer')

@append
