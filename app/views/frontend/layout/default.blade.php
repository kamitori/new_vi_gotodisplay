<!DOCTYPE html>
<html class="js no-touch svg inlinesvg svgclippaths no-ie8compat wf-lato-n4-active wf-active">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
		<base url="{{url()}}"/>
		@if( isset($metaInfo['favicon']) )
		<link href="{{ URL::asset($metaInfo['favicon']) }}" rel="shortcut icon" type="image/x-icon" />
		@endif
		<title>{{ isset($metaInfo['meta_title']) && !empty($metaInfo['meta_title']) ? $metaInfo['meta_title'] : (isset($metaInfo['title_site']) ? $metaInfo['title_site'] : '') }}</title>
		<meta name="description" content="{{ $metaInfo['meta_description'] or '' }}" />
		@yield('head')
		
		@yield('pageCSS')
		<link href="http://fonts.googleapis.com/css?family=Comfortaa:400,700,300" rel="stylesheet" type="text/css" />
		<link href="{{ URL::asset('assets/global/plugins/bootstrap/css/bootstrap.min.css') }}" type="text/css" rel="stylesheet" />
		<link href="{{ URL::asset('assets/global/plugins/font-awesome/css/font-awesome.min.css') }}" type="text/css" rel="stylesheet" />
		<link href="{{ URL::asset('assets/vitheme/plugins/leftmenu/css/style.css') }}" type="text/css" rel="stylesheet" />
		<link href="{{ URL::asset('assets/vitheme/plugins/fancybox/dist/jquery.fancybox.css') }}" type="text/css" rel="stylesheet" />
		<link href="{{ URL::asset('assets/global/plugins/bootstrap-toastr/toastr.css') }}" type="text/css" rel="stylesheet" />
		<link href="{{ URL::asset('assets/vitheme/style.css') }}" type="text/css" rel="stylesheet" />
		<link href="{{ URL::asset('assets/vitheme/mobile.css') }}" type="text/css" rel="stylesheet" />
		<link href="{{ URL::asset('assets/vitheme/tablet.css') }}" type="text/css" rel="stylesheet" />

		

	</head>

	@yield('body', '<body class="page-home template-index" data-twttr-rendered="true">')
		<div id="loading_wait" style="position: fixed;width: 100%;height: 100%;background: rgba(12,34,36,.5); z-index: 9999999;color:white;display:none;">
			<div class="container text-center" style="align-items: center;  display: flex;  justify-content: center;height:100%;">
				<img src="{{URL}}/assets/images/others/ajax-loader.gif" alt="title" style="max-width: 50px;"/>
		    	<span style="font-size: 150%;margin-left:5px;"> Loading ...</span>
			</div>
		</div>
		<div id="loading_wait_2" style="position: fixed;width: 100%;height: 100%;background: rgba(12,34,36,.5); z-index: 9999999;color:white;display:none;">
			<div class="container text-center" style="align-items: center;  display: flex;  justify-content: center;height:100%;">
				<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="40" id="loading_p_bar" aria-valuemin="0" aria-valuemax="100" style="width:40%;height:30px">40%</div>
			</div>
		</div>
		
		@include('frontend.layout.header')

		<!-- Main content -->
		<div class="container site_content">
			@if($route=='/')
				@yield('subBanner')
				<section class="">
					<!-- <div class="container"> -->
						{{ $content or '' }}
						@yield('content')
					<!-- </div> -->
				</section>
			@else
				<?php 
					$arr_route = explode('/', $route);
					$check_route = false;
					if(isset($arr_route[2])){
						$check_route = in_array($arr_route[2], ['quick-design','cluster-design','wall-collage-design']);
					}
				?>
				@if($check_route)
					<section class="main-content container">
						{{ $content or '' }}
						@yield('content')
					</section>
				@else
					<section class="main-content container ">
						<div class="row" style="display: flex;">
							<div class="col-md-9" style="flex:1;padding-bottom: 40px; min-height: 900px;">
								{{ $content or '' }}
								@yield('content')
							</div>
							<div class="col-md-3" >
								@include('frontend.layout.right_menu')
							</div>
						</div>
					</section>
				@endif
			@endif
		
			<footer id="footer">
					<div class="container main-footer">
						<div class="col-md-12 ">
							@include('frontend.layout.footer')
						</div>
					</div>
			</footer>
		</div>
		<!-- JS Plugin -->
		<script src="{{URL::asset('assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
		<script src="{{URL::asset( 'assets/global/plugins/bootstrap/js/bootstrap.min.js') }}" type="text/javascript"></script>
		<script src="{{ URL::asset('assets/vitheme/plugins/fancybox/dist/jquery.fancybox.js') }}" type="text/javascript"></script>
		<script src="{{ URL::asset( 'assets/global/plugins/bootstrap-toastr/toastr.min.js') }}" type="text/javascript"></script>
		<script src="{{ URL::asset( 'assets/js/custom.modernizr.js') }}" type="text/javascript"></script>
		<script src="{{ URL::asset( 'assets/js/plugins.js') }}" type="text/javascript"></script>
		<!-- <script src="{{ URL::asset( 'assets/js/shop.js') }}" type="text/javascript"></script> -->

		<!-- JS manual -->
		<script src="{{URL::asset('assets/vitheme/plugins/leftmenu/js/src/menu.js') }}" type="text/javascript"></script>
		<script src="{{ URL::asset( 'assets/js/main.js') }}" type="text/javascript"></script>
		<script src="{{ URL::asset( 'assets/js/facebook-login.js') }}" type="text/javascript"></script>

		<!-- JS in the page -->
		@yield('pageJS')
	</body>
</html>