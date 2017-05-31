
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
</style>
@stop

<div id="loading_wait" style="width:160px; margin:68px 0 0 1000px; position:absolute;display:none;float:right;">
	<img src="{{URL}}/assets/images/ajax-loader.gif" alt="title" />
	<span> Loading ...</span>
</div>
<div id="docBare" class="null cf">
   <article id="bundleBody" class="cp designawall">
	  <section id="navBarContainer">
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
		 <div id="actionContainer">
			<span>Wrap type: </span><span id="name_wrap" style="font-weight:bold;color:red;">
			  @if( isset($product_option) && isset($product_option[7]) && isset($product_option[7][$product['wrap']]) )
				  {{$product_option[7][$product['wrap']]}}
			  @endif
			</span>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<span>Total Price: $</span><span id="name_price" style="font-weight:bold;color:red;">{{$product['sell_price']}}</span>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<a id="addToCartLink" class="primaryButton" onclick="addCart()">{{ $cartEdit ? 'Update' : 'Add to'}} Cart</a><a id="returnToCartLink" class="primaryButton hidden">Return to Cart</a>
		 </div>
	  </section>
	  <input id="custom_quantity" type="hidden" value="{{ $product['quantity'] }}" />
	  <input id="price" type="hidden" value="{{ $product['sell_price'] }}" />
	  <input id="zoomImage" type="hidden" value="1" />
	  <input id="rotateImage" type="hidden" value="0" />
	  <input id="flipImageX" type="hidden" value="1" />
	  <input id="flipImageY" type="hidden" value="1" />
	  <section id="contentContainer" style="min-height:750px;">
		 <div id="paletteContainer">
			<div id="paletteContent">
				<div class="headerLine"></div>
				<div id="paletteContentUploads"  class="paletteContent viSTPWide">
				  <div class="large-5 columns" >
					<img id="import_vi" src="/assets/images/social_icon/button-vi.png" style="width:100%;max-height:90px; " alt="import_vi" title="From VI library" />
				  </div>
				  <div class="large-5 columns">
					<img id="import_mpc" src="/assets/images/social_icon/mypc-upload.jpg" alt="import_mpc" style="width:100%;" title="Upload From PC" />
				  </div>
				  <div class="large-5 columns">
					<img id="import_fb" src="/assets/images/social_icon/button-facebook.png" alt="import_fb" style="width:100%;" title="Facebook" />
				  </div>
				  <div class="large-5 columns">
					<img id="import_flickr" src="/assets/images/social_icon/button-flickr.png" alt="import_flickr" style="width:100%;" title="Upload From Facebook" />
				  </div>
				  <div class="large-5 columns" style="clear:left;">
					<img id="import_dropbox" src="/assets/images/social_icon/button-dropbox.png" alt="import_dropbox" style="width:100%;" title="Upload From DropBox" />
				  </div>
				  <div class="large-5 columns">
					<img id="import_googledrive" src="/assets/images/social_icon/button-googledrive.png" alt="import_googledrive" style="width:100%;" title="Upload From Google Driver" />
				  </div>
				  <div class="large-5 columns">
					<img id="import_picasa" src="/assets/images/social_icon/button-picasa.png" alt="import_picasa" style="width:100%;" title="Upload From Picasa" />
				  </div>
				  <div class="large-5 columns">
					<img id="import_skydrive" src="/assets/images/social_icon/button-skydrive.png" alt="import_skydrive" style="width:100%;" title="Upload From Skydrive" />
				  </div>
				  <div class="large-5 columns" style="clear:left;">
					<img id="import_instagram" src="/assets/images/social_icon/button-instagram.png" alt="import_instagram" style="width:100%;" title="Upload From Instagram" />
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
						  	<button onclick="ChoiceImgsLib()">Choice Image(s)</button>
						  </div>
						  <div id="list_album" class="of_album">
						  </div>
						  <div id="list_image">
						  </div>
				  </div>

			   </div>
			   <div id="dialog_resolution" title="Resolution Image" style="display:none;width:800px;"></div>
			   <!-- Arrangements -->
			   <div id="paletteContentArrangements"  class="paletteContent active">
				@if(isset($similar_products))
				  @foreach($similar_products as $key =>$val)
					<a href="{{URL}}/collections/{{$collection['short_name']}}/cluster-design/{{$val['short_name']}}#cluster_design">
					<div id="bundle-{{$val['id']}}" class="bundle bundle_{{$val['id']}} noHiRes" style="margin-top:0px;{{'';if($val['id']==$product['id']) echo 'border: 1px solid #428BCA;'}}">
						<div style="height:200px">
						  <img src="{{$val['image']}}" />
						</div>
					  <label class="bundlename">{{$val['name']}}</label>
					</div>
					</a>
				  @endforeach
				@endif
			   </div>

			   <div id="paletteContentFilters"  class="paletteContent" style="padding-left: 13px;">
				  @foreach($filter as $key=>$value)
					  <div class="step3-options " style="display: block;">
						  <label for="opfilter_{{$key}}">
						  <ul>
							  <li class="col1">
								  <input type="radio" id="opfilter_{{$key}}" name="filter_type" value="{{$key}}" onclick="filterImage('{{$key}}');" {{$key=='original'?"checked":""}} />
								  <span><b>{{$value['name']}}</b></span>
							  </li>
							  <li class="col2">
								  <div class="float-L thumb-img item_{{$key}}"></div>

								  <p class="price"></p>
							  </li>
						  </ul>
						  </label>
					  </div>
				  @endforeach
			   </div>
			   <a name="cluster_design"></a>
			   <a name="quick_design"></a>
			   <div id="paletteContentLayouts" class="paletteContent" data-blueprint="34-1-1">
					<div id="photoSpreadBundleWrapper"></div>
					<div class="layoutCategory" id="layoutCategory-1">
						<div class="assetCategoryLabel">1 photo</div>
						<div id="layoutThumb-38070" class="paletteLayoutThumbnail 1photo 0text">
							<img src="{{URL}}/assets/images/fetch.jpg" class="portrait">
						</div>
						<div id="layoutThumb-38068" class="paletteLayoutThumbnail 1photo 0text">
							<img src="{{URL}}/assets/images/fetch_1.jpg" class="portrait">
						</div>
						<div id="layoutThumb-38057" class="paletteLayoutThumbnail 1photo 0text selected">
							<img src="{{URL}}/assets/images/fetch_2.jpg" class="portrait">
						</div>
						<div id="layoutThumb-42658" class="paletteLayoutThumbnail 1photo 1text">
							<img src="{{URL}}/assets/images/fetch_3.jpg" class="portrait">
						</div>
					</div>
				</div>
			   <div id="paletteContentBackgrounds" class="paletteContent" style="padding-left: 13px;padding-top: 15px;">
			   		@foreach($background as $bg)
					<div class="backgroundCategory active" id="background-gray" onclick="changeBackgound(this)">
						<div class="assetCategoryLabel"></div>
						<img src="{{ $bg }}" class="paletteBgThumbnail" style="width:150px; height: auto !important;" />
					</div>
					@endforeach
					<button class="nicebt" onclick="$('#background-upload').click();">Add your background images</button>
					<input type="file" style="display:none" id="background-upload" />
					<div id="user-background">
						@foreach($arrBackground as $background)
						<div class="backgroundCategory" onclick="changeBackgound(this)">
							<div class="assetCategoryLabel"></div>
							<img src="{{ $background }}" class="paletteBgThumbnail" style="width:150px; height: auto !important;" />
						</div>
						@endforeach
					</div>
			   </div>
			   @if(isset($product_option[7]))
			   <div id="paletteContentOptions"  class="paletteContent" style="padding-left: 13px;padding-top: 15px;">
					  @foreach($product_option[7] as $key=>$value)
						  <?php
							  $checked = '';
							  if ($product['wrap']=='') {
								  $product['wrap'] = 'natural';
							  }
							  if($key == $product['wrap'])
								  $checked = 'checked="checked"';
						  ?>
						  <div id="opbox_{{$key}}" class="step3-options" style="display: block;">
							  <label for="opstyle_{{$key}}">
							  <ul>
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
			   </div>
			   <!-- Pick color -->
			  <div id="pick_color" class="paletteContent" style="padding-left: 13px;padding-top: 15px;">
				  <button type="button" class="close_picker" onclick="closePickColor()">×</button>
				  <div class="ChooseColor">
					  <div>Create your own colour</div>
					  <div id="pickcolorbox">
						   @include('frontend.cluster_design.pick_color')
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
						  <button id="btnChooseColor" type="button" class="btn btn-default cf-btn-colorPicker" onclick="closePickColor();">
							  <span>Choose Color</span>
						  </button>
					  </div>
				  </div>
			  </div>
			   @endif

			   <div id="paletteSinglePath" class="linkContainer hidden">
				  <span>
					 <span id="wallArtType">Type of Wall Art:</span>
					 <a id="singlepathLink" class="link">Single Piece</a>
					 <span class="linkDivider">|</span>
					 <a id="multipathLink" class="link">Multi Piece</a>
					 <div id="learnMoreDialog"></div>
				  </span>
			   </div>
			</div>
			<div id="priceChangeWarning" class="noShadow">The price of your product has changed</div>
		 </div>
		 <div id="paletteLabels">
			<div id="paletteLabelUploads" data-label-for="paletteContentUploads" class="paletteLabel" style="height: 110px;">
			   <div style="width: 100px; left:-34px; top: 29px;" class="noIe8 lblUploads">Uploads</div>
			</div>
			<div id="paletteLabelArrangements" data-label-for="paletteContentArrangements" class="paletteLabel active" style="height: 140px;">
			   <div style="width: 100px; left:-34px; top: 67px;" class="noIe8 lblArrangements">Arrangements</div>
			</div>
			<div id="paletteLabelFilters" data-label-for="paletteContentFilters" class="paletteLabel" style="height: 85px;">
			   <div style="width: 100px; left:-34px; top: 6px;" class="noIe8 lblFilters">Filters</div>
			</div>
			@if(isset($product_option[7]))
			  <div id="paletteLabelOptions" data-label-for="paletteContentOptions" class="paletteLabel " style="height: 90px;">
				 <div style="width: 54px; left: -11px; top: 40px;" class="noIe8 lblOptions">Options</div>
			  </div>
			@endif
			<div id="paletteLabelLayouts" data-label-for="paletteContentLayouts" class="paletteLabel " style="height: 84px;display:none">
			   <div style="width: 54px; left: -14px; top: 34px;" class="noIe8 lblLayouts">Layouts</div>
			</div>
			<div id="paletteLabelBackgrounds" data-label-for="paletteContentBackgrounds" class="paletteLabel " style="height: 120px;display:none">
			   <div style="width: 90px; left: -32px; top: 52px;" class="noIe8 lblBackgrounds">Backgrounds</div>
			</div>

		 </div>
		 <div id="editPageContainer" class="svg edit" style="width: 75%;min-height:100px;">
			<div id="editAreaToolBar" style="width: 100%;">
				<div class=" slider_bt" style="position: absolute; margin: 100px 1px 1px 0px; display: block; z-index:5;background:transparent;">
					<p style="width: 37px;font-family:verdana;">Rotate</p>
					<input type="text" id="amount" style="width: 36px;color:#f6931f;font-weight:bold;">
					<div id="slider-vertical" style="  margin: -1px 4px 4px 13px;" class="ui-slider ui-slider-vertical ui-widget ui-widget-content ui-corner-all" aria-disabled="false"><div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-max" style="height: 100%;"></div><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="bottom: 0%;"></a></div>
				</div>
				<div class=" slider_bt" style="position: absolute;   margin: 300px 1px 1px 0px; display: block;">
					<p style="width:45px;font-family:verdana;background:transparent;">Zoom</p>
					<div id="zoom-slider" style="margin: 0px 4px 4px 16px;" class="ui-slider ui-slider-vertical ui-widget ui-widget-content ui-corner-all" aria-disabled="false"><div class="ui-slider-range ui-widget-header ui-corner-all ui-slider-range-max" style="height: 100%;"></div><a class="ui-slider-handle ui-state-default ui-corner-all" href="#" style="bottom: 0%;"></a></div>
				</div>

				<div id="image_bt" class="ds_tool_group ds_border_right" style="">
				  <div id="dsbt_filter" class="ds_button dsbt">
					  <div class="ds_button_icon" style="color:green;"><i class="fa fa-fw fa-filter"></i></div>
					  <div class="ds_button_name">Filter</div>
				  </div>
				  <div class="ds_button" onclick="rotateImage()">
					  <div class="ds_button_icon"><i class="fa fa-fw fa-repeat"></i></div>
					  <div class="ds_button_name">Rotate Image</div>
				  </div>
				  <div class="ds_button" onclick="flipImageX()">
					  <div class="ds_button_icon"><i class="fa fa-fw fa-sort2"></i></div>
					  <div class="ds_button_name">Flip X</div>
				  </div>
				  <div class="ds_button" onclick="flipImageY()">
					  <div class="ds_button_icon"><i class="fa fa-fw fa-sort"></i></div>
					  <div class="ds_button_name">Flip Y</div>
				  </div>
				  <div class="ds_button" onclick="resolution()">
					  <div class="ds_button_icon"><i class="fa fa-fw fa-flag"></i></div>
					  <div class="ds_button_name">Resolution</div>
				  </div>
			  </div>
			  <div id="preview_bt" class="ds_tool_group ds_border_left right" style="display:block">
				  <div class="ds_button" onclick="PreviewInTheme()">
					  <div class="ds_button_icon" style="color:red;"><i class="fa fa-fw fa-eye"></i></div>
					  <div class="ds_button_name">Preview All</div>
				  </div>
				  <div class="ds_button" onclick="Preview3D()">
					  <div class="ds_button_icon" style="color:red;"><i class="fa fa-fw fa-cube"></i></div>
					  <div class="ds_button_name">Preview 3D</div>
				  </div>
				  <div class="ds_button" onclick="Preview()">
					  <div class="ds_button_icon" style="color:red;"><i class="fa fa-fw fa-eye"></i></div>
					  <div class="ds_button_name" title="Preview with background">Preview</div>
				  </div>
				  <input type="text" id="img-link" style="display:none" value="">



			  </div>
			  <div id="zoom_bt" class="ds_tool_group ds_border_left right" style="display:block">
				  <div class="ds_button" id="reset_zoom" onclick="resetSVG()" style="display:none">
					  <div class="ds_button_icon"><img src="{{URL}}/assets/images/zoom-reset.png" style="max-width:16px;" /></div>
					  <div class="ds_button_name">Reset Zoom</div>
				  </div>
				  <div class="ds_button" onclick="zoomInSvg()">
					  <div class="ds_button_icon"><span class="glyph zoom-in"></span></div>
					  <div class="ds_button_name">Zoom In all</div>
				  </div>
				  <div class="ds_button" onclick="zoomOutSvg()">
					  <div class="ds_button_icon"><span class="glyph zoom-out"></span></div>
					  <div class="ds_button_name">Zoom Out all</div>
				  </div>
			  </div>
			  <div id="zoom_bt2" class="ds_tool_group ds_border_left right" style="display:none">
				  <div class="ds_button" onclick="zoomInTheme()">
					  <div class="ds_button_icon"><span class="glyph zoom-in"></span></div>
					  <div class="ds_button_name">Zoom in</div>
				  </div>
				  <div class="ds_button" onclick="zoomOutTheme()">
					  <div class="ds_button_icon"><span class="glyph zoom-out"></span></div>
					  <div class="ds_button_name">Zoom out</div>
				  </div>
			  </div>
			</div>
			<div id="editAreaWorkArea" class="content" style="min-height:400px;max-height:500px;overflow:auto;">
				<div class="canvas_img_thum" style="display:none;height: 100%; width: 100%; padding:0 2% 0 2%;">
					<canvas id="canvas_imgs"></canvas>
				</div>
				<div id="svg_main" style="height: 100%; width: 100%; padding:0 2% 0 2%"></div>
			</div>
			<div id="preview_box" style="display:none; padding:0;">
				<button onclick="closePreview();" style="position: absolute;right: 5%;bottom: 15%;z-index:60;">Close</button>
				<div id="preview_content" style="margin:0;padding:0;border:0px;display:none;">
				</div>
				<div id="preview_image" style="margin:auto;padding:5% 0;border:0px;display:none;z-index:50;position: absolute;width: 1186px;background:white;border:1px solid #ddd;">
					  <img id="img_svg_preview" src="{{URL}}/assets/images/loading.gif" alt="title" style="margin-top:20px;" />
				</div>
				<div id="image_box" style="display:block;">
					<img src="{{URL}}/assets/images/loading.gif" alt="title" style="max-height:500px;margin-top: 50px;" />
				</div>
			</div>
			<div id="tmp_svg" style="display:none; padding:0;/*width:800px;height:400px;position: absolute;background: white;top: 0;left: 0;z-index: 500;*/">
			</div>
		 </div>

	  </section>
	  <section id="picturestripContainer">
		 <div id="picturestrip">
			<div class="picturestripBackground">

			 <div id="slider_image">
				@if ($product['product_type']==6)
					<?php $m=0;?>
					@foreach($arr_img as $link)
						@if($m==0)
							<div class="image_content">
							  <img class='photo' src="{{URL}}{{$link}}" alt="title" onclick="changeImage('{{URL}}{{$link}}');" />
							</div>
						@elseif($m<18)
							<div class="image_content">
							<img class='photo' src="{{URL}}{{$link}}" alt="title" onclick="changeImage('{{URL}}{{$link}}');" />
						  </div>
						@endif
						<?php $m++;?>
					@endforeach
				@else
					@foreach($arr_img as $link)
					  <div class="image_content">
						<img class='photo' src="{{URL}}{{$link}}" alt="title" onclick="changeImage('{{URL}}{{$link}}');" />
					  </div>
					@endforeach
				@endif
			 </div>

			</div>
		 </div>
	  </section>
	  <div id="dynamicColorOptionsWrapper" class="dynamicColorPopup invisible"></div>
	  <div id="dynamicColorTooltip" class="dynamicColorPopup invisible">
		 <div class="arrow left"></div>
		 <span id="message"><span class="title">Custom Color Palette</span><br>Click a color swatch to choose your own color.</span><span class="close"></span>
	  </div>
   </article>
</div>
</div>
<div class="totallyNonConflictingWrapper" id="limitedftr">
</div>



@section('pageJS')
	<script src="{{ URL }}/assets/js/jquery-ui.min.js" type="text/javascript"></script>
	<script type="text/javascript" src="{{URL}}/assets/js/jquery.filer.min.js"></script>
	<script type="text/javascript">
		$("#dialog").on( "dialogclose", function( ) {
		    $("#dialog #search_dialog").hide();
		} );
	  $('#fileup').filer({
		limit: 10
	  });
	  $('#bgfileup').filer({
		limit: 10
	  });
	  $('.jFiler-input').css('visibility','hidden');

		$("document").ready(function(){
			$("#import_mpc").on('click',function(){
				$("#fileup").click();
			});
			$(".nicebt").on('click',function(){
				$("#bgfileup").click();
			});

			$(".dsbt").click(function(){
				var cont = $(this).attr('id');
				cont = cont.replace("dsbt_","content_");
				$(".ds_button").removeClass('ds_active');
				$(this).addClass('ds_active');
				$(".content_list").css("display","none");
				$("#"+cont).css("display","table");
			});

			$("#fileup").change(function(event){
				uploadFiles(event,0,1);
			});
			$("#bgfileup").change(function(event){
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

			$('.paletteLabel').on('click',function(){
				$('.paletteContent').removeClass('active');
				$('.paletteLabel').removeClass('active');
				$(this).addClass('active');
				$("#"+$(this).attr('data-label-for')).addClass('active');
			});

			$("#getPicturesBtnLarge").on('click',function(){
				$("#dlg-container").show();
			});


			$("#dsbt_filter").on('click',function(){
				$('.paletteContent').removeClass('active');
				$('.paletteLabel').removeClass('active');
				$("#paletteContentFilters").addClass('active');
				$("#paletteLabelFilters").addClass('active');
			});
			$( "#slider-vertical" ).slider({
				orientation: "vertical",
				range: "max",
				step: 5,
				min: 0,
				max: 360,
				value: 0,
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
			$("#editAreaWorkArea").click(function(e){
				if ($("#editAreaWorkArea")[0] == e.target) {
					$(".slider_bt").hide();
				}
			});
			//open Upload
			@if(!isset($arr_img) || isset($arr_img) && count($arr_img)==0)
			  $('.paletteContent').removeClass('active');
			  $("#paletteContentUploads").addClass('active');
			  $('.paletteLabel').removeClass('active');
			  $("#paletteLabelUploads").addClass('active');
			@endif

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
					  url: "{{ URL.'/get-vi-images' }}",
					  type: 'POST',
					  data: data,
					  success: function(result) {
						  var html = '';
						  if( result.length ) {
							  for(var i in result) {
								  html += '<div class="large-2 columns block_album">' +
											  '<div class="block_image" id="block_image_'+ result[i].id +'">' +
												  '<img class="cover_album" data-check="0" data-source="'+ result[i].link +'" src="'+ result[i].thumb +'" data-store="'+ result[i].store +'" onclick="CheckItemChoice('+ result[i].id +')" data-ext="'+ result[i].ext +'" />' +
												  '<div class="icon_close5" onclick="RemoveItemChoice('+ result[i].id +')"  style="display:none;"></div>'+
											  '</div>' +
										  '</div>';
							  }
						  }
						  	$("#loading_import").hide();
						  	$(".of_album").hide();
						  	$("[text ='List Album']").hide();
						  	$("#loading_import").hide();
						  	$("#dialog").dialog({width: 1200,height: 600}).dialog("open");
							$("#search_dialog").show();
						  	$("#list_image").css('max-height','500px').css('height','478px')
										  .html(html);
					  	}
				});
			}

			$('#background-upload').change(function(){
        		var data = new FormData();
        		data.append('background', $(this)[0].files[0]);
				$.ajax({
					url: '{{ URL }}/collections/save-background',
					type: 'POST',
					data: data,
					processData: false,
					contentType: false,
					success: function(result){
						if( result.url ) {
							$('.backgroundCategory.active').removeClass('active');
							var html = '<div class="backgroundCategory active" onclick="changeBackgound(this)" style="width:150px; height: auto !important;">' +
											'<div class="assetCategoryLabel"></div>' +
											'<img src="'+ result.url +'" class="paletteBgThumbnail" style="width:150px;height:auto;" />' +
										'</div>';
							$('#user-background').append(html);
							$('#user-background .backgroundCategory.active').trigger('click');
						}
					}
				})
			});
		});
		function changeBackgound(obj)
		{
			$('.backgroundCategory.active').removeClass('active');
			$(obj).addClass('active');
			var img = $('img', obj);
			var src = img.attr('src');
			var width = img.width();
			var height = img.height();
			img_bg.attr({href: src, x: 0, y: 0});
		}
		@include('frontend.quick_design.cal_price_function', ['type' => 'cluster-design'])
	</script>

	@include('frontend.cluster_design.general')
	@include('frontend.cluster_design.wall_cluster')
	@include('frontend.cluster_design.preview')

	@include('frontend.cluster_design.preview3d')
	@if(isset($product_option[7]))
		@include('frontend.cluster_design.pick_color_image')
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
@append