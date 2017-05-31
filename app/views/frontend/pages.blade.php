@section('body')
<body class="page-the-profound-aesthetic-academy customer-logged-in template-page">
@stop
<div class="full-width">
	<div class="columns">
		<div class="page-content">
			<div class="breadcrumbs">
				<img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs" />
				<ul class="unstyled-list">
					<li><a href="{{ URL }}">Home</a></li>
					<li><a href="{{ URL }}/{{strtolower(trim($page['short_name']))}}">{{ $page['name'] }}</a></li>
				</ul>
			</div>
			@if( $page['type'] == 'Default' )
			<div class="rte-content colored-links">
				{{ HTML::decode($page['content']) }}
    		</div>
    		@endif
		</div>
	</div>
</div>
@if( $page['type'] == 'Contact' )
<div class="full-width">
	{{ HTML::decode($page['content']) }}
</div>
@endif
