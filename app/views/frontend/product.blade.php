@section('head')

<!-- Shareaholic Content Tags -->
<meta name='shareaholic:site_name' content='vi1.anvyonline.com' />
<meta name='shareaholic:language' content='en-US' />
<meta name='shareaholic:url' content='{{Request::url()}}' />
<meta name='shareaholic:keywords' content='{{ $product['name']}}' />

<meta name='shareaholic:shareable_page' content='true' />
<meta name='shareaholic:site_id' content='b158f52a060535ba05e1c27644d137f2' />
<meta name='shareaholic:image' content='{{ $product['image']}}' />
<!-- end -->

<meta name="description"  content="{{ $product['name']}}" />
<meta name="keywords"  content="{{ $product['name']}}" />
<meta property="og:url"           content="{{Request::url()}}" />
<meta property="og:type"          content="website" />
<meta property="og:title"         content="{{ $product['name']}}" />
<meta property="og:description"   content="{{ $product['name']}}" />
<meta property="og:image"         content="{{ $product['image']}}" />


<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $product['name']}}" />
<meta name="twitter:description" content="{{ $product['name']}}" />
<meta name="twitter:image" content="{{ $product['image']}}" />
<meta itemprop="image" content="{{ $product['image']}}" />

<script type="text/javascript" data-cfasync="false" src="//dsms0mj1bbhn4.cloudfront.net/assets/pub/shareaholic.js" data-shr-siteid="b158f52a060535ba05e1c27644d137f2" async="async"></script>
@stop
@section('body')
<body class="page-the-floral-paradox-tee template-product" data-twttr-rendered="true">
@stop
<div class="breadcrumbs">
	<img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
	<ul class="unstyled-list">
		<li><a href="{{ URL }}">Home</a></li>
		<li><a href="{{ URL }}/collections">Shop</a></li>
		<li><a href="{{ URL.'/collections/'.$product['categories'][0]['short_name'] }}">{{ $product['categories'][0]['name'] }}</a></li>
		<li>{{ $product['name'] }}</li>
	</ul>
</div>

<article class="row" itemscope="" itemtype="" id="detail">	
		
	<!-- .left-column -->
	<div class="middle-column photos left col-md-6 show-for-medium-up show-for-ie9-down">
		<div class="clearfix clickable">
			<a class="show_image" data-fancybox="gallery" id="a-main-image" href="{{ $product['image'] }}">
				<img style="min-width:335px;min-height: 300px;max-height: 400px;padding-bottom:20px" id="main-image" main-src="{{ $product['image'] }}" src="{{ $product['image'] }}" class="zoomImg">
			</a>
		</div>
		<!-- #product-photo-container -->
		<div class="col-md-12 col-xs-12 col-sm-12" style="clear:both">
			@foreach($product['image_list'] as $image)
				<div class="col-md-3 col-xs-3 col-sm-4 image-item" style="height:120px;line-height:120px">
					<a class="show_image" data-fancybox="gallery" href="{{ $image }}" >
						<img style="max-height:120px" main-src="{{ $image }}" src="{{ $image }}" >
					</a>
				</div>
			@endforeach
		</div>
	</div>

	<div class="col-md-6 col-xs-12 col-sm-12">
			<div class='shareaholic-canvas' data-app='share_buttons' data-app-id="b158f52a060535ba05e1c27644d137f2"></div>
			<form class="custom" action="{{ URL.'/collections/'.$product['categories'][0]['short_name'].'/'.$product['link_design'].'/'.$product['short_name'].'#quick_design' }}" method="post" novalidate>
				<input name="id" type="hidden" value="{{ $product['id'] }}" />
				<input name="collection[name]" type="hidden" value="{{ $product['categories'][0]['name'] }}" />
				<input name="collection[short_name]" type="hidden" value="{{ $product['categories'][0]['short_name'] }}" />
				<h1 class="page-title" itemprop="name">{{ $product['name'] }}</h1>
				<div class="prices">
					<meta itemprop="priceCurrency" content="CAD">
					<link itemprop="availability">
					<p>
						<span class="actual-price" itemprop="price">$ {{ Product::viFormat($product['sell_price']) }}</span>&nbsp;
						<span class="compare-price" style="display: none;">Was $ 00.00</span>
					</p>
				</div>
				<div class="right-column description">
					<div itemprop="description colored-links rte-content" style="text-align: justify;">
						{{ $product['description'] }}
					</div>
				</div>
				<div class="quanity-cart-row clearfix">
					<div class="variants vifull">
						<input type="hidden" class="calculate-price" id="bleed" value="0" />
						@if( isset($product['size_lists']) && !empty($product['size_lists']) )
						<div class="selector-wrapper">
							<label for="size-select">Size</label>
							<select id="size-select" class="single-option-selector">
							@foreach($product['size_lists'] as $sizeList)
							<option value="{{ $sizeList['id'] }}" {{ $sizeList['default'] ? 'selected' : '' }}  >{{ $sizeList['sizew'] }} x {{ $sizeList['sizeh'] }}</option>
							@endforeach
							@if( $product['custom_size'] )
							<option value="custom-size">Custom Size</option>
							@endif
							</select>
							<div id="custom-size" style="text-align: center; margin-top: 10px; display: none;" >
								<input name="custom-width" class="calculate-price" style="width: 45%; display: inline" id="width" placeholder="Width" value="" type="number"> x
								<input name="custom-height" class="calculate-price" style="width: 45%; display: inline" id="height" placeholder="Height" value="" type="number">
							</div>
							<input type="hidden" id="size-select-value" name="size" />
						</div>
						@endif
						<?php $arrSelect = [];  ?>
						@foreach($product['option_groups'] as $group)
						<?php $select = Str::slug($group['name']).'-select'; ?>
						<div class="selector-wrapper">
							<label for="{{ $select }}">{{ $group['name'] }}</label>
							<select id="{{ $select }}" class="single-option-selector">
								@foreach($product['options'] as $option)
								@if( $option['option_group_id'] == $group['id'] )
								<option value="{{ $option['key'] }}" data-description="{{ $option['id'] }}" {{ in_array($option['id'], $product['default_view']) ? 'selected' : '' }} >{{ $option['name'] }}</option>
								@endif
								@endforeach
							</select>
							<input type="hidden" class="options" id="{{ $select }}-value" name="option[{{ $group['id'] }}]" />
						</div>
						<?php $arrSelect[] = $select;  ?>
						@endforeach
						<div class="selector-wrapper">
							<label for="quantity">Quantity</label>
							<input id="quantity" type="number" min='1' step='1' class="calculate-price form-control" name="quantity" value="1">
						</div>
					</div>
					<div class="add-to-cart">
						<input type="submit" name="quick_order" id="quick_order" value="Quick Upload and Order" class="btn btn-3" style="display:block;">
					</div>
				</div>
				<div class="backorder">
					<p></p>
				</div>
			</form>
			<div itemprop="description" class="description show-for-medium-down colored-links rte-content hide-for-ie9-down hidden" style="text-align: justify;">
				{{ $product['description'] }}
			</div>
	</div>
	

</article>
@if( !empty($product['similar_products']) )
<section class="similar-products">
	<h2 class="title">Similar Products</h2>
	<div class="product-grid">
		<div class="clearfix"></div>
		@foreach($product['similar_products'] as $similar)
		<div class="product-item col-md-3 col-xs-4">

			<div class="image-wrapper" style="opacity: 1;">
				<a href="{{ URL.'/collections/'.$product['categories'][0]['short_name'].'/'.$similar['short_name'] }}">
					<img src="{{ $similar['image'] }}" alt="{{ $similar['name'] }}" title="{{ $similar['name'] }}" />
				</a>
			</div>
			<div class="caption">
				<p class="title">
					<a href="{{ URL.'/collections/'.$product['categories'][0]['short_name'].'/'.$similar['short_name'] }}">
					{{ $similar['name'] }}
					</a>
				</p>
				<p class="price">
					$ {{ Product::viFormat($similar['sell_price']) }}
				</p>
			</div>
		</div>
		@endforeach
	</div>
</section>
@endif
@section('pageCSS')

<style id="css-ddslick" type="text/css">
.dd-select{ border-radius:2px; border:solid 1px #ebebeb; position:relative; cursor:pointer;}.dd-desc { color:#aaa; display:none !important; overflow: hidden; font-weight:normal; line-height: 1.2em; }.dd-selected{ overflow:hidden; display:block; padding:3px 10px;font-weight:bold!important;}.dd-pointer{ width:0; height:0; position:absolute; right:10px; top:50%; margin-top:-3px;}.dd-pointer-down{ border:solid 5px transparent; border-top:solid 5px #000; }.dd-pointer-up{border:solid 5px transparent !important; border-bottom:solid 5px #000 !important; margin-top:-8px;}.dd-options{ border:solid 1px #ebebeb; border-top:none; list-style:none; box-shadow:0px 1px 5px #ddd; display:none; position:absolute; z-index:2000; margin:0; padding:0;background:#fff; overflow:auto;}.dd-option{ padding:3px 10px; display:block; border-bottom:solid 1px #ddd; overflow:hidden; text-decoration:none; color:#333; cursor:pointer;-webkit-transition: all 0.25s ease-in-out; -moz-transition: all 0.25s ease-in-out;-o-transition: all 0.25s ease-in-out;-ms-transition: all 0.25s ease-in-out; }.dd-options > li:last-child > .dd-option{ border-bottom:none;}.dd-option:hover{ background:rgb(247, 220, 200); color:#000;}.dd-selected-description-truncated { text-overflow: ellipsis; white-space:nowrap; }.dd-option-selected { background:#f6f6f6; }.dd-option-image, .dd-selected-image { vertical-align:middle; float:left; margin-right:5px; max-width:64px;}.dd-image-right { float:right; margin-right:15px; margin-left:5px;}.dd-container{ position:relative;}​ .dd-selected-text { font-weight:bold}​
</style>
<style type="text/css">
	.hover_div{
		border:1px solid #333;
	}
</style>
@stop
@section('pageJS')


<script src="{{ URL::asset( 'assets/js/jquery.ddslick.js') }}" type="text/javascript"></script>
<script type="text/javascript">
var defaultRatio = {{ $defaultRatio }};
Array.prototype.equals = function (array) {
	// if the other array is a falsy value, return
	if (!array)
		return false;

	// compare lengths - can save a lot of time
	if (this.length != array.length)
		return false;

	for (var i = 0, l=this.length; i < l; i++) {
		// Check if we have nested arrays
		if (this[i] instanceof Array && array[i] instanceof Array) {
			// recurse into the nested arrays
			if (!this[i].equals(array[i]))
				return false;
		}
		else if (this[i] != array[i]) {
			// Warning - two different object instances will never be equal: {x:20} != {x:20}
			return false;
		}
	}
	return true;
}
$(".show_image").fancybox({
	'transitionIn'	:	'gallery',
	'transitionOut'	:	'gallery',
	'speedIn'		:	600, 
	'speedOut'		:	200, 
	'overlayShow'	:	false
});

var images = {{ json_encode($product['images']) }};
$('.image-item').hover(function(){
	$('.image-item').removeClass('hover_div');
	$(this).addClass('hover_div');
	var _image_ = $(this).find('a img').attr('src');
	_image_
	$("#main-image").attr('src',_image_);
	$("#a-main-image").attr('href',_image_);
});
$(".single-option-selector").each(function(){
	$(this).ddslick({
		onSelected: function(data){
			var id = data.original[0].id;
			var text = data.selectedData.text;
			var value = data.selectedData.value;
			if( id == "size-select" ) {
				if( data.selectedData.value == "custom-size" ) {
					value = 0;
					$("#custom-size").show();
					$("#width").val("");
					$("#height").val("");
				} else {
					size = text.split("x");
					$("#width").val( parseFloat(size[0]) );
					$("#height").val( parseFloat(size[1]) ).trigger("change");
					$("#custom-size").hide();
				}
			} else if( id == "depth-select" ) {
				$("#bleed").val( parseFloat(value) ).trigger("change");
			}
			if( data.selectedData.description ) {
				$("#"+ id + "-value").attr("data-id", data.selectedData.description);
			}
			$("#"+ id + "-value").val(value).trigger("change");
		}
	});
});

$(".calculate-price").change(function(){
	var quantity = $("#quantity").val();
	var width = $("#width").val() || 0;
	var height = $("#height").val() || 0;
	var bleed = $("#bleed").val() || 0;
	@if( $product['product_type_id'] >= 1 && $product['product_type_id'] <=5 )
	if( $(this).attr('name') ) {
		if( defaultRatio ) {
			var name = $(this).attr('name').replace('custom-', '');
			if( name == 'width' ) {
				width = parseFloat(width);
				height = width / defaultRatio;
				$("#height").val( height.toFixed(2) );
			} else {
				height = parseFloat(height);
				width = height * defaultRatio;
				$("#width").val( width.toFixed(2) );
			}
		}
	}
	@endif

	calculatePrice({ width: width, height: height, bleed: bleed, quantity: quantity, product_id : {{ $product['id'] or 0 }} }, function(result){
		$(".actual-price").text("$ "+ result.amount);
		if( result.bigger_price ) {
			$('.compare-price').text('Was $ '+ result.bigger_price)
								.show();
		}
	});
});
$("#quantity").trigger("change");

$(".options").change(function(){
	var arr = [], i = 0;
	var imgSrc = $("#main-image").attr("main-src");
	$(".options").each(function(){
		arr[i] = $(this).attr("data-id");
		i++;
	});
	arr = arr.sort();
	for(var i = 0; i < images.length; i++) {
		var view = images[i].view;
		if( view.sort().equals(arr) ) {
			imgSrc = images[i].path;
			break;
		}
	}

	$("#main-image").attr("src", imgSrc);
});
$(".options:first").trigger("change");

function calculatePrice(data, callBack)
{
	$('.compare-price').hide();
	$.ajax({
		url: "{{ URL.'/cal-price' }}",
		data: data,
		type: "POST",
		success: function(result){
			callBack(result)
		}
	});
}

$("#quantity").keydown(function(e){
	// Allow: backspace, delete, tab, escape, enter and .
	if ($.inArray(e.keyCode, [46, 8,13]) !== -1 ||
		 // Allow: Ctrl+A
		(e.keyCode == 65 && e.ctrlKey === true) ||
		 // Allow: home, end, left, right, down, up
		(e.keyCode >= 35 && e.keyCode <= 40)) {
			 // let it happen, don't do anything
			 return;
	}
	// Ensure that it is a number and stop the keypress
	if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		e.preventDefault();
	}
})
</script>
@stop