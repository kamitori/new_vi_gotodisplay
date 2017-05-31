
@section('body')
<body class="page-your-shopping-cart template-cart">
	@stop
@section('content')
<div class="breadcrumbs">
	<img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
	<ul class="unstyled-list">
		<li><a href="{{ URL }}">Home</a></li>
		<li><a >Order completed</a></li>
	</ul>
</div>
@if(isset($pages) && !empty($pages))
	{{ html_entity_decode($pages['content']) }}
@endif
@stop