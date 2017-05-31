<div id="banners">
	{{ $banner }}
</div>
@section('pageCSS')
<link rel="Stylesheet" type="text/css" href="{{ URL::asset( 'assets/css/smoothDivScroll.css') }}" />
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
</style>
@stop
@section('pageJS')
<script src="{{ URL::asset( 'assets/js/jquery-ui.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/js/jquery.mousewheel.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/js/jquery.kinetic.min.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/js/jquery.smoothdivscroll-1.3-min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
// setTimeout(function(){
    $("div#banners").smoothDivScroll({
        autoScrollingMode: "onStart",
        touchScrolling: true,
        manualContinuousScrolling: true
    });
// }, 100);
</script>
@stop