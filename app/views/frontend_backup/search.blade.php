@section('body')
<body class="page-wristwear template-collection">
@stop
<header class="row">
    <div class="left columns large-6">
        <ul class="breadcrumbs colored-links">
            <li><a href="{{ URL }}">Home</a></li>
            <li><a href="{{ URL }}/search/{{$search['key']}}">Search</a></li>
            <li>{{ str_replace("+"," ",$search['key']) }}</li>
        </ul>
    </div>
</header>
<div class="row">
    <div class="columns">
        <div class="divider"></div>
    </div>
</div>
<div class="row">
	<div class="product-grid clearfix">
	    <div class="clearfix"></div>
	    @foreach($search['products'] as $product)
	    <div class="product-item columns large-3">
	        <div class="image-wrapper" style="opacity: 1;">
	            <a href="{{ URL.'/collections/'.$product['category'].'/'.$product['short_name'] }}">
	            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" title="{{ $product['name'] }}" />
	            </a>
	        </div>
	        <div class="caption">
	            <p class="title">
	                <a href="{{ URL.'/collections/'.$product['category'].'/'.$product['short_name'] }}">
	                {{ $product['name'] }}
	                </a>
	            </p>
	            <p class="price" style="">
	                From $ {{ Product::viFormat($product['sell_price']) }}
	            </p>
	        </div>
	    </div>
	    @endforeach
	    <div class="clearfix"></div>
	</div>
</div>
<div class="row">
    <div class="columns">
        <div class="divider bottom-margin"></div>
    </div>
</div>
<footer class="row">
    <div class="left columns large-6">
        <ul class="breadcrumbs colored-links">
            <li><a href="{{ URL }}">Home</a></li>
            <li><a href="{{ URL }}/search/{{$search['key']}}">Search</a></li>
            <li>{{ str_replace("+"," ",$search['key']) }}</li>
        </ul>
    </div>
    @if( $search['totalPage'] > 1 )
    <div class="right columns large-6">
        <ul class="pagination ">
        	@if( $search['pageNum'] - 1 > 0 )
        	<li class="arrow left">
        		<a href="{{ URL.'/search/'.$search['key'].'/'.($search['pageNum'] - 1) }}">
        			Previous
        			<span class="glyph arrow-left" aria-hidden="true"></span>
        		</a>
        	</li>
        	@endif
        	<?php
        		$start = $search['pageNum'] - 3 >= 1 ? $search['pageNum'] - 3 : 1;
        		$to    = $search['pageNum'] + 3 <= $search['totalPage'] ? $search['pageNum'] + 3 : $search['totalPage'];
        	?>
        	@for($i = $start; $i <= $to; $i++)
        	@if( $i == $search['pageNum'] )
            	<li class="current"><a href="#">{{ $i }}</a></li>
            @else
            	<li><a href="{{ URL.'/search/'.$search['key'].'/'.$i }}">{{ $i }}</a></li>
        	@endif
        	@endfor
            @if( $search['pageNum'] + 1 <= $search['totalPage'] )
        	<li class="arrow right">
        		<a href="{{ URL.'/search/'.$search['key'].'/'.($search['pageNum'] + 1) }}">
        			Next
        			<span class="glyph arrow-right" aria-hidden="true"></span>
        		</a>
        	</li>
        	@endif
		</ul>
	</div>
    @endif
</footer>