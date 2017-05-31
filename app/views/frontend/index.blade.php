<!-- Content index -->
{{$content_home}}
@section('pageCSS')
<link rel="Stylesheet" type="text/css" href="{{ URL::asset( 'assets/css/smoothDivScroll.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/vitheme/plugins/css-image/css/normalize.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/vitheme/plugins/css-image/css/demo.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/vitheme/plugins/css-image/css/set1.css') }}" />
<style type="text/css">
#banners
{
	width:100%;
	height: 330px;
	position: relative;
}
#banners div.scrollableArea img
{
	height: 330px;
	position: relative;
	float: left;
	margin: 0;
	padding: 0;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-o-user-select: none;
	user-select: none;
}
@media (min-width: 900px) {
	#header{
		height: 685px !important;
    	margin-bottom: -215px !important;
	}
}
@media (max-width: 1024px) {
	section.main-content{
		/*margin-top: 120px;*/
	}
	.ckeditor-html5-video {
		display: none;
	}
}
</style>
@stop
@section('pageJS')
<script src="{{ URL::asset( 'assets/js/jquery-ui.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/js/jquery.mousewheel.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/js/jquery.kinetic.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/js/jquery.smoothdivscroll-1.3-min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/js/facebook-login.js') }}" type="text/javascript"></script>


<script type="text/javascript" src="{{ URL::asset( 'assets/vitheme/plugins/css-image/js/slider-img.js') }}"></script>

<script type="text/javascript">
	$("video")[0].play();	
// setTimeout(function(){
	$("div#banners").smoothDivScroll({
		autoScrollingMode: "onStart",
		touchScrolling: true,
		manualContinuousScrolling: true
	});
	function videoEnded(){
		$("video")[0].play();
	}
// }, 100);
	$('figure').on('click',function(){
		var tag_a = $(this).find('a');
		window.open(tag_a.attr('href'));
	});
	
	var t = {
        tabInterval: 5e3,
        imgroot: document.location.origin +"/",
        tabs: {{$banner['header']}},
        globalLayers: [{
            src: "P29019_sale_promo.png",
            top: (70 + 10 + 58 * parseInt({{$banner['total']-1}},10)),
            left: 0,
            height: 35,
            width: 188,
            href: "/collections/indoor-signage",
            id: "globalLayer1"
        }],
        tabsMarquee: {{$banner['data']}},
        };
        $("#marquee").marquee(t)

</script>

@stop



<link rel="stylesheet" type="text/css" href="{{ URL::asset( 'assets/vitheme/plugins/css-image/css/marquee.css') }}" charset="utf-8"/>
