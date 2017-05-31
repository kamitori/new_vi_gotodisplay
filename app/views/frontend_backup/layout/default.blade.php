<!DOCTYPE html>
<html class="js no-touch svg inlinesvg svgclippaths no-ie8compat wf-lato-n4-active wf-active">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<meta name="google-site-verification" content="3CCgD3cD6e1nTfFqy3p-cadeDBYd9I7_rIjxNTn-L3c" />
		@if( isset($metaInfo['favicon']) )
		<link href="{{ URL::asset($metaInfo['favicon']) }}" rel="shortcut icon" type="image/x-icon" />
		@endif
		<title>{{ isset($metaInfo['meta_title']) && !empty($metaInfo['meta_title']) ? $metaInfo['meta_title'] : (isset($metaInfo['title_site']) ? $metaInfo['title_site'] : '') }}</title>
		<meta name="description" content="{{ $metaInfo['meta_description'] or '' }}" />
		@yield('pageCSS')
		<link href="http://fonts.googleapis.com/css?family=Comfortaa:400,700,300" rel="stylesheet" type="text/css" />
		<link href="{{ URL::asset('assets/css/style.css') }}" type="text/css" rel="stylesheet" />
		<link href="{{ URL::asset('assets/css/style2.css') }}" type="text/css" rel="stylesheet" />
	</head>
	@yield('body', '<body class="page-home template-index" data-twttr-rendered="true">')
		@include('frontend.layout.header')
		<section class="main-content">
			{{ $content or '' }}
			@yield('content')
		</section>
		<footer class="main-footer">
			<div class="row bottom">
				@include('frontend.layout.footer')
			</div>
			<div class="row">
				<div class="columns"></div>
			</div>
		</footer>
		<script src="{{URL::asset( 'assets/global/plugins/jquery.min.js') }}" type="text/javascript"></script>
		<script src="{{ URL::asset( 'assets/js/custom.modernizr.js') }}" type="text/javascript"></script>
		<script src="{{ URL::asset( 'assets/js/plugins.js') }}" type="text/javascript"></script>
		<script src="{{ URL::asset( 'assets/js/shop.js') }}" type="text/javascript"></script>
		@yield('pageJS')

	</body>
</html>