@section('body')
<body class="page-the-profound-aesthetic-academy customer-logged-in template-page">
@stop
<div class="row full-width">
	<div class="columns">
		<div class="page-content">
			<ul class="breadcrumbs colored-links">
				<li><a href="{{ URL }}">Home</a></li>
				<li>{{ strtoupper($page['name']) }}</li>
			</ul>
			@if( $page['type'] == 'Default' )
			<div class="rte-content colored-links">
				{{ HTML::decode($page['content']) }}
    		</div>
    		@endif
		</div>
	</div>
</div>
@if( $page['type'] == 'Contact' )
<div class="row full-width">
	{{ HTML::decode($page['content']) }}
</div>
@endif
