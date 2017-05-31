 @section('pageCSS')
<link href="{{URL}}/assets/css/font-awesome.css" type="text/css" rel="stylesheet" />
<link href="{{URL}}/assets/css/bundle.css" type="text/css" rel="stylesheet" />
<link href="{{URL}}/assets/css/stylesheet.css" type="text/css" rel="stylesheet" />
<link href="{{URL}}/assets/css/font.css" type="text/css" rel="stylesheet" />
<link href="{{URL}}/assets/css/slide.css" type="text/css" rel="stylesheet" />
<link href="{{URL}}/assets/css/popup.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="{{URL}}/assets/css/jquery-ui.min.css">
<style type="text/css">
body { font-family: "Comfortaa", sans-serif; font-weight: normal; font-style: normal !important;
}
#preview_content:hover {
cursor: url(/assets/images/openhand.cur), auto;
}
#preview_content:active {
cursor: url(/assets/images/closedhand.cur), auto;
}
#dlg-container{
position: absolute;
z-index: 9999;
width: 100%;
top:0%;
height: 100%;
display: none;
margin:0 auto;
}
.cursor_zoomin {
cursor : url(/assets/images/cursors/icon-tool-zoom.png) !important;
}
#svg-main .main-image {
	cursor: move;
}
#svg-main .shape-path.active{
	stroke: #CA7642;
	stroke-width: 2px;
}
#svg-main.preview .group-bleed  {
	display: block !important;
}
#svg-main.preview .group-bleed .bleed {
	fill: #fff !important;
	fill-opacity: 1 !important;
	stroke: #fff !important;
  	stroke-width: 3px !important;
}

#svg-main.preview .group-mirror-bleed{
	display: none !important;
}
#svg-main.preview-bg .group-bleed .bleed {
	display: none !important;
}
#svg-preview {
	cursor: default !important;
}
</style>
@stop
<input type="hidden" id="bg_back_image" value="{{$background_image}}">
<div id="loading_wait" style="width:160px; margin:68px 0 0 1000px; position:absolute;display:none;float:right;">
	<img src="{{URL}}/assets/images/ajax-loader.gif" alt="title" />
	<span> Loading ...</span>
</div>
<div class="breadcrumbs">
    <img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
    <ul class="unstyled-list">
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
<div id="upload_bt" class="ds_tool_group" style="">
    <div id="dsbt_upload" data-fancybox="modal" href="#paletteContentUploads" class="paletteLabel upload ds_button dsbt {{ Session::has('user_ip')?'':'ds_active' }} ">
    </div>
    <div id="dsbt_my_upload" data-fancybox="modal" href="#paletteContentArrangements" class="paletteLabel style ds_button dsbt  {{ Session::has('user_ip')?'ds_active':'' }}">
        <!-- <div class="ds_button_icon" style="color:green;"><i class="fa fa-fw fa-picture-o"></i></div>
        <div class="ds_button_name">My upload</div> -->
    </div>
    <div id="" data-fancybox="modal" href="#paletteContentFilters" class="paletteLabel filter ds_button dsbt">
        <!-- <div class="ds_button_icon"><i class="fa fa-fw fa-th-large"></i></div>
        <div class="ds_button_name">Size</div> -->
    </div>
    <div id="" data-fancybox="modal" href="#paletteContentOptions" class="paletteLabel paletteLabel depth ds_button dsbt">
        <!-- <div class="ds_button_icon"><i class="fa fa-fw fa-th-large"></i></div>
        <div class="ds_button_name">Size</div> -->
    </div>
</div>

<div id="editAreaWorkArea" class="content" style="min-height:400px;max-height:500px;overflow:auto;">
	<div class="canvas_img_thum" style="display:none;height: 100%; width: 100%; padding:0 2% 0 2%;">
		<canvas id="canvas_imgs"></canvas>
	</div>
	<div id="svg_div" style="height: 100%; width: 100%; padding:0 2% 0 2%"></div>
</div>


<div id="docBare" class="null cf">
	<article id="bundleBody" class="designawall design">
		
		<section id="contentContainer">
			<div id="image_bt" class="ds_tool_group" style="">
				<div id="dsbt_filter" class="ds_button dsbt filter" style="height:64px">
					<!-- <div class="ds_button_icon" style="color:green;"><i class="fa fa-fw fa-filter"></i></div>
					<div class="ds_button_name">Filter</div> -->
				</div>
				<div class="ds_button rotate" onclick="WallCollage.rotate()" style="height:64px">
					<!-- <div class="ds_button_icon"><i class="fa fa-fw fa-repeat"></i></div>
					<div class="ds_button_name">Rotate Image</div> -->
				</div>
				<div class="ds_button flipx" onclick="WallCollage.flipX()" style="height:64px">
					<!-- <div class="ds_button_icon"><i class="fa fa-fw fa-sort2"></i></div>
					<div class="ds_button_name">Flip X</div> -->
				</div>
				<div class="ds_button flipy" onclick="WallCollage.flipY()" style="height:64px">
					<!-- <div class="ds_button_icon"><i class="fa fa-fw fa-sort"></i></div>
					<div class="ds_button_name">Flip Y</div> -->
				</div>
				<div class="ds_button resolution" onclick="Main.resolution()" >
					<!-- <div class="ds_button_icon"><i class="fa fa-fw fa-flag"></i></div>
					<div class="ds_button_name">Resolution</div> -->
				</div>
			</div>
			<div id="paletteContainer" style="display:none">
				<div id="paletteContent">
					<div class="headerLine"></div>
					
					<div id="dialog_resolution" title="Resolution Image" style="display:none;width:800px;"></div>
					<div id="paletteContentUploads"  class="paletteContent viSTPWide">
						<div class="row">
							<!-- <div class="col-xs-4" >
								<img id="import_vi" src="/assets/images/social_icon/button-vi.png" style="width:100%;" alt="import_vi" title="From VI library" />
							</div> -->
							<div class="col-xs-4">
								<img onclick="importPC()" id="import_mpc" src="/assets/images/social_icon/mypc-upload.jpg" alt="import_mpc" style="width:100%;" title="Upload From PC" />
							</div>
							<div class="col-xs-4">
								<img onclick="importFB()" id="import_fb" src="/assets/images/social_icon/button-facebook.png" alt="import_fb" style="width:100%;" title="Facebook" />
							</div>
							<div class="col-xs-4">
								<img onclick="importFlickr()" id="import_flickr" src="/assets/images/social_icon/button-flickr.png" alt="import_flickr" style="width:100%;" title="Upload From Facebook" />
							</div>
							<div class="col-xs-4">
								<img onclick="importDropbox()" id="import_dropbox" src="/assets/images/social_icon/button-dropbox.png" alt="import_dropbox" style="width:100%;" title="Upload From DropBox" />
							</div>
							<div class="col-xs-4">
								<img onclick="importGoogledrive()" id="import_googledrive" src="/assets/images/social_icon/button-googledrive.png" alt="import_googledrive" style="width:100%;" title="Upload From Google Driver" />
							</div>
							<!-- <div class="col-xs-4">
								<img id="import_picasa" src="/assets/images/social_icon/button-picasa.png" alt="import_picasa" style="width:100%;" title="Upload From Picasa" />
							</div> -->
							<div class="col-xs-4">
								<img onclick="importSkydrive()" id="import_skydrive" src="/assets/images/social_icon/button-skydrive.png" alt="import_skydrive" style="width:100%;" title="Upload From Skydrive" />
							</div>
							<div class="col-xs-4">
								<img onclick="importInstagram()" id="import_instagram" src="/assets/images/social_icon/button-instagram.png" alt="import_instagram" style="width:100%;" title="Upload From Instagram" />
							</div>
						</div>
						<form id="upload_file" method="POST" accept-charset="UTF-8" enctype="multipart/form-data">
							<input type="file" name="upload_file[]" id="fileup" style="visibility: hidden;"  accept="image/*"/>
							<input type="submit" value="Upload" id="upload_file_bt" />
						</form>
						<div id="loading_none" style="display:none;">
							<img src="{{URL}}/assets/images/loading.gif" alt="title" />Loading ...
						</div>
						<div id="loading_import" style="display:none;margin-top:20px;">
							<img src="{{URL}}/assets/images/loading.gif" alt="title" />Loading ...
						</div>
						<div id="dialog" title="Import Image" style="display:none;width:800px;">
							<h3 class="of_album">List Album</h3>
							<div id="search_dialog" style="display:none">
								<div class="lib_box_search">
									<input name="searchlib_text" id="searchlib_text" type="text" placeholder="Search by tags" />
									<input id="searchlib_bt" type="button" value=" Search " />
								</div>
								<button class="btn btn-4 btn-white" onclick="Main.chooseImages()">Choice Images</button>
							</div>
							<div id="list_album" class="of_album">
							</div>
							<div id="list_image">
							</div>
						</div>
					</div>
					<!-- Arrangements -->
					<div id="paletteContentArrangements"  class="paletteContent content_list content_3column">
						@foreach($product['similar_products'] as $similar)
						<a href="{{ URL.'/collections/'.$product['category']['short_name'].'/wall-collage-design/'.$similar['short_name'] }}">
							<div id="bundle-203" class="bundle bundle_203 noHiRes" style="margin-top:0px;">
								<div>
									<img src="{{ $similar['image'] }}">
								</div>
								<label class="bundlename">{{ $similar['name'] }}</label>
							</div>
						</a>
						@endforeach
					</div>
					<div id="paletteContentFilters"  class="paletteContent" style="padding-left: 13px;">
						@foreach($filters as $key => $filter)
						<div class="step3-options " style="display: block;">
							<label for="opfilter_{{ $key }}">
								<ul class="list-unstyled">
									<li class="col1">
										<input type="radio" id="opfilter_{{ $key }}" name="filter_type" value="{{ $key }}" onclick="WallCollage.filter('{{ $key }}');" checked="">
										<span><b>{{ $filter }}</b></span>
									</li>
									<li class="col2">
										<div class="float-L thumb-img item_{{ $key }}"></div>
									</li>
								</ul>
							</label>
						</div>
						@endforeach
					</div>
					<div id="paletteContentBackgrounds" class="paletteContent" style="padding-left: 13px;padding-top: 15px;">
						@foreach($background as $bg)
						<div class="backgroundCategory active" id="background-gray" onclick="Main.changeBackgound(this)">
							<div class="assetCategoryLabel"></div>
							<img src="{{ $bg }}" class="paletteBgThumbnail" style="width:150px; height: auto !important;" />
						</div>
						@endforeach

						<button class="nicebt btn-4 btn btn-white" onclick="$('#background-upload').click();">Add your background images</button>
						<input type="file" style="display:none" id="background-upload" />
						<div id="user-background">
							@foreach($arrBackground as $bg)
							<div class="backgroundCategory" onclick="Main.changeBackgound(this)">
								<div class="assetCategoryLabel"></div>
								<img src="{{ $bg }}" class="paletteBgThumbnail" style="width:150px; height: auto !important;" />
							</div>
							@endforeach
						</div>
					</div>
					<!-- Pick color -->
					@if( $product['wrap_option'] !== false )
					<div id="paletteContentOptions"  class="paletteContent" style="padding-left: 13px;padding-top: 15px;">
						@foreach($product['options'] as $wrap)
						@if( $wrap['option_group_id'] == $product['wrap_option'] )
						<div id="opbox_{{ $wrap['key'] }}" class="step3-options" style="display: block;">
							<label for="opstyle_{{ $wrap['key'] }}">
								<ul class="list-unstyled">
									<li class="col1">
										<input type="radio" id="opstyle_{{ $wrap['key'] }}" name="frame_style" value="{{ $wrap['key'] }}" onclick="Main.changeWrap('{{ $wrap['key'] }}', '{{ $wrap['name'] }}')" title="{{ $wrap['name'] }}" {{ $product['layout']['wrap'] == $wrap['key'] ? 'checked' : '' }} />
										<span><b>{{ $wrap['name'] }}</b></span>
									</li>
									<li class="col2">
										<div class="float-L thumb-img item_{{ $wrap['key'] }}"></div>
										<p class="price"></p>
									</li>
								</ul>
							</label>
						</div>
						@endif
						@endforeach
					</div>
					<!-- Pick color -->
					<div id="pick_color" class="paletteContent" style="padding-left: 13px;padding-top: 15px;">
						<button type="button" class="close_picker" onclick="ColorPicker.close()">×</button>
						<div class="ChooseColor">
							<div>Create your own colour</div>
							<div id="pickcolorbox">
								<div class="picker">
								    <div class="picker-colors">
								        <div class="picker-colorPicker"></div>
								    </div>
								    <div class="picker-hues">
								        <div class="picker-huePicker"></div>
								    </div>

								    <div class="picker_color_rgb">
								        <div align="center" class="picker_t_rgb">
								            <span>R</span><br>
								            <input type="text" value="" id="colorR" maxlength="3">
								        </div>
								        <div align="center" class="picker_t_rgb">
								            <span>G</span><br>
								            <input type="text" value="" id="colorG" maxlength="3">
								        </div>
								        <div align="center" class="picker_t_rgb">
								            <span>B</span><br>
								            <input type="text" value="" id="colorB" maxlength="3">
								        </div>
								    </div>

								    <div class="picker_color_hsv">
								        <div align="center" class="picker_t_hsv">
								            <span>H</span><br>
								            <input type="text" value="" id="colorH" maxlength="3">
								        </div>
								        <div align="center" class="picker_t_hsv">
								            <span>S</span><br>
								            <input type="text" value="" id="colorS" maxlength="3">
								        </div>
								        <div align="center" class="picker_t_hsv">
								            <span>V</span><br>
								            <input type="text" value="" id="colorV" maxlength="3">
								        </div>
								    </div>

								    <div class="picker_color_cmyk">
								        <div align="center" class="picker_t_1">
								            <span>C</span><br>
								            <input type="text" value="" id="colorC" maxlength="3">
								        </div>
								        <div align="center" class="picker_t_1">
								            <span>M</span><br>
								            <input type="text" value="" id="colorM" maxlength="3">
								        </div>
								        <div align="center" class="picker_t_1">
								            <span>Y</span><br>
								            <input type="text" value="" id="colorY" maxlength="3">
								        </div>
								        <div align="center" class="picker_t_1">
								            <span>K</span><br>
								            <input type="text" value="" id="colorK" maxlength="3">
								        </div>
								    </div>

								</div>
							</div>
							<div id="divPMS">
								<div id="Matchingto">
									<div class="choiced_color_box">
										<div class="choiced_color_img picker_h" style="background-color:#ffffff;" id="colorbg"></div>
										<div class="choiced_color_text" style="width: 150px;">HEX: <input type="text" value="121212" id="colorhex" maxlength="6" style="width: 100px;" /> </div>
									</div>
								</div>
							</div>
							<div class="choiced_color_box" style="border:none;">
								<button id="btnChooseColorFromImg" type="button" class="btn btn-default cf-btn-colorPicker">
								<span class="cf-btn-colorPicker-icon"></span>
								<span class="cf-btn-colorPicker-text LocalizedStrings" data-localizedstringname="WrapColorPickerButton">Choose a colour<br>from your photo...</span>
								</button>
							</div>
							<div class="choiced_color_box" style="border:none;">
								<button id="btnChooseColor" type="button" class="btn btn-default cf-btn-colorPicker" onclick="ColorPicker.close()">
								<span>Choose Color</span>
								</button>
							</div>
						</div>

					</div>
					@endif
				</div>
			</div>
			<!-- <div id="paletteLabels">
				<div id="paletteLabelUploads" data-label-for="paletteContentUploads" class="paletteLabel" style="height: 110px;">
					<div style="width: 100px; left:-34px; top: 29px;" class="noIe8 lblUploads">Uploads</div>
				</div>
				<div id="paletteLabelArrangements" data-label-for="paletteContentArrangements" class="paletteLabel active" style="height: 140px;">
					<div style="width: 100px; left:-34px; top: 67px;" class="noIe8 lblArrangements">Arrangements</div>
				</div>
				<div id="paletteLabelFilters" data-label-for="paletteContentFilters" class="paletteLabel" style="height: 85px;">
					<div style="width: 100px; left:-34px; top: 6px;" class="noIe8 lblFilters">Filters</div>
				</div>
				<div id="paletteLabelLayouts" data-label-for="paletteContentLayouts" class="paletteLabel " style="height: 84px;display:none">
					<div style="width: 54px; left: -14px; top: 34px;" class="noIe8 lblLayouts">Layouts</div>
				</div>
				<div id="paletteLabelBackgrounds" data-label-for="paletteContentBackgrounds" class="paletteLabel " style="height: 120px;display:none">
					<div style="width: 90px; left: -32px; top: 52px;" class="noIe8 lblBackgrounds">Backgrounds</div>
				</div>
				@if( $product['wrap_option'] !== false )
				<div id="paletteLabelOptions" data-label-for="paletteContentOptions" class="paletteLabel" style="height: 90px;">
				 	<div style="width: 54px; left: -11px; top: 40px;" class="noIe8 lblOptions">Options</div>
			  	</div>
				@endif
			</div> -->
			<div id="editPageContainer" class="svg edit" style="width:100%;min-height:70px;">
				<div id="editAreaToolBar" style="width: 100%;">
					<div class=" slider_bt" style="position: absolute; margin: 100px 1px 1px 0px; display: block; z-index:5;background:transparent;">
						<p style="width: 37px;font-family:verdana;">Rotate</p>
						<input type="text" id="amount" style="width: 36px;color:#f6931f;font-weight:bold;">
						<div id="slider-vertical" style="  margin: -1px 4px 4px 13px;" class="ui-slider ui-slider-vertical ui-widget ui-widget-content ui-corner-all" aria-disabled="false">
							<div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-max" style="height: 100%;"></div>
							<a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="bottom: 0%;"></a>
						</div>
					</div>
					<div class=" slider_bt" style="position: absolute;   margin: 300px 1px 1px 0px; display: block;">
						<p style="width:45px;font-family:verdana;background:transparent;">Zoom</p>
						<div id="zoom-slider" style="margin: 0px 4px 4px 16px;" class="ui-slider ui-slider-vertical ui-widget ui-widget-content ui-corner-all" aria-disabled="false">
							<div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-max" style="height: 100%;"></div>
							<a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="bottom: 0%;"></a>
						</div>
					</div>
					
					<div id="preview_bt" class="ds_tool_group" style="display:block">
						<div id="zoom_bt" class="ds_tool_group" style="display:block">
							<div class="ds_button resetzoom" id="reset_zoom" onclick="WallCollage.resetZoom()" style="display:none">
								<!-- <div class="ds_button_icon"><img src="{{URL}}/assets/images/zoom-reset.png" style="max-width:16px;" /></div>
								<div class="ds_button_name">Reset Zoom</div> -->
							</div>
							<div class="ds_button zoom_in" onclick="WallCollage.zoomInAll()">
								<!-- <div class="ds_button_icon"><span class="glyph fa fa-search-plus"></span></div>
								<div class="ds_button_name">Zoom In all</div> -->
							</div>
							<div class="ds_button zoom_out" onclick="WallCollage.zoomOutAll()">
								<!-- <div class="ds_button_icon"><span class="glyph fa fa-search-minus"></span></div>
								<div class="ds_button_name">Zoom Out all</div> -->
							</div>
						</div>
						<div class="ds_button preview" onclick="Main.previewBG()"  style="display:none">
							<!-- <div class="ds_button_icon" style="color:red;"><i class="fa fa-fw fa-eye"></i></div>
							<div class="ds_button_name">Preview All</div> -->
						</div>
						<div class="ds_button view3d" onclick="Main.preview3D()" data-fancybox="modal" href="#preview_box">
							<!-- <div class="ds_button_icon" style="color:red;"><i class="fa fa-fw fa-cube"></i></div>
							<div class="ds_button_name">Preview 3D</div> -->
						</div>
						<div class="ds_button preview" onclick="Main.preview()" data-fancybox="modal" href="#preview_box">
							<!-- <div class="ds_button_icon" style="color:red;"><i class="fa fa-fw fa-eye"></i></div>
							<div class="ds_button_name" title="Preview with background">Preview</div> -->
						</div>
						<input type="text" id="img-link" style="display:none" value="">
					</div>
					
					<div id="zoom_bt2" class="ds_tool_group right" style="display:none">
						<div class="ds_button zoom_in" onclick="Main.zoomInPreview()">
							<!-- <div class="ds_button_icon"><span class="glyph fa fa-search-plus"></span></div>
							<div class="ds_button_name">Zoom in</div> -->
						</div>
						<div class="ds_button zoom_out" onclick="Main.zoomOutPreview()">
							<!-- <div class="ds_button_icon"><span class="glyph fa fa-search-minus"></span></div>
							<div class="ds_button_name">Zoom out</div> -->
						</div>
					</div>
				</div>
				
				<div id="preview_box" style="display:none; padding:0; overflow: auto">
					<button class="btn btn-4 btn-white" data-fancybox-close onclick="Main.preview(false)" style="position: absolute;right: 5%;bottom: 15%;z-index:60;">Close</button>
					<img id="loading-image" src="{{URL}}/assets/images/loading.gif" alt="title" style="max-height:500px;margin-top: 50px;" />
					<div id="preview_content" style="margin:0;padding:0;border:0px; cursor: pointer;padding-bottom: 15px;">
					</div>
				</div>
				<div id="tmp_svg" style="display:none; padding:0;/*width:800px;height:400px;position: absolute;background: white;top: 0;left: 0;z-index: 500;*/">
				</div>
			</div>
		</section>
		<section id="picturestripContainer">
			<div id="picturestrip">
				<div class="picturestripBackground">
					<div class="clearall">
						<a href="javascript:void(0)" onclick="Main.removeAllUpload()" title="Clear All" style="cursor:pointer;">	<i class="fa fa-times"></i>&nbsp;Clear All
						</a>
					</div>
					<div id="slider_image" class="content_list content_3column">
						@foreach($arrImages as $image)
						<div class="image_content">
							<img class="photo" src="{{ URL.'/'.$image }}" alt="" onclick="WallCollage.changeImage('{{ URL.'/'.$image }}');">
							<div class="icon_close5" onclick="Main.removeUpload(this)" style=""></div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</section>
		<section id="navBarContainer">
			<!-- <div class="columns">
				<ul class="breadcrumbs colored-links">
					<li><a href="{{URL}}/">Home</a></li>
				</ul>
			</div> -->
			<div id="actionContainer">
				<span>Wrap type: </span>
				<span id="name_wrap" style="font-weight:bold;color:red;">
				</span>
				<span>Total Price: $</span><span id="name_price" style="font-weight:bold;color:red;"> {{ Product::viFormat($product['sell_price']) }} </span>
				<br/>
				<p class="tetx-center col-xs-8 col-xs-offset-2" style="margin-top: 20px;"><a id="addToCartLink" class="btn btn-4 btn-white btn-block" onclick="Main.addCart()">{{ isset($product['cart_id']) ? 'Update' : 'Add to'}} Cart</a><a id="returnToCartLink" class="primaryButton hidden">Return to Cart</a></p>
			</div>
		</section>
		<div id="dynamicColorOptionsWrapper" class="dynamicColorPopup invisible"></div>
		<div id="dynamicColorTooltip" class="dynamicColorPopup invisible">
			<div class="arrow left"></div>
			<span id="message"><span class="title">Custom Color Palette</span><br>Click a color swatch to choose your own color.</span><span class="close"></span>
		</div>
	</article>
</div>
<div style="display:none">
	<canvas id="main-canvas"></canvas>
	<div id="canvas-collection"></div>
</div>
@section('pageJS')
<script src="{{ URL }}/assets/js/jquery-ui.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{{URL}}/assets/js/jquery.filer.min.js"></script>
<script src="{{URL}}/assets/js/svgjs/svg.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/svgjs/svg.draggable/svg.draggable.js" type="text/javascript"></script>
<script src="{{URL}}/assets/js/svgjs/svg.filter/svg.filter.min.js" type="text/javascript"></script>
<script src="{{URL}}/assets/js/pms.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/rgbcolor.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/StackBlur.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/canvg.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/canvas3d/three.min.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/canvas3d/requestAnimFrame.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/canvas3d/OrbitControls.js" type="text/javascript" charset="utf-8"></script>
<script src="{{URL}}/assets/js/canvas3d/Detector.js" type="text/javascript" charset="utf-8"></script>
@include('frontend.wall_collage_design.js.main')
@include('frontend.wall_collage_design.js.wall_collage')
@include('frontend.wall_collage_design.js.pointer')
@include('frontend.wall_collage_design.js.color_picker')
@include('frontend.wall_collage_design.js.preview_3d')
<script type="text/javascript">
	Main.bind();
	@if( !empty($svgInfo) )
	WallCollage.svgSetup({{ json_encode($svgInfo) }});
	@endif
	WallCollage.draw();
	ColorPicker.bind();
</script>
@stop