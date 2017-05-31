@section('body')
<body class="page-wristwear template-collection">
@stop
<header class="row">
    <div class="left columns large-6">
        <ul class="breadcrumbs colored-links">
            <li><a href="{{ URL }}">Home</a></li>
            <li><a href="{{ URL }}/collections">Shop</a></li>
            <li>{{ $collection['name'] }}</li>
        </ul>
    </div>
</header>
<div class="row">
    <div class="columns">
        <div class="divider"></div>
    </div>
</div>
<div class="row">
	@if( isset($collection['images'][0]['path']) )
	<div style="text-align:center">
	    <img src="{{ URL::asset($collection['images'][0]['path']) }}" alt="{{ $collection['name'] }}" title="{{ $collection['name'] }}" />
	</div>
	@endif
	<div class="product-grid clearfix">
	    <div class="clearfix"></div>
	    @foreach($collection['products'] as $product)
	    <div class="product-item columns large-3">
	        <div class="image-wrapper" style="opacity: 1;">
	            <a href="{{ URL.'/collections/'.$collection['short_name'].'/'.$product['short_name'] }}">
	            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" />
	            </a>
	        </div>
	        <div class="caption">
	            <p class="title">
	                <a href="{{ URL.'/collections/'.$collection['short_name'].'/'.$product['short_name'] }}">
	                {{ $product['name'] }}
	                </a>
	            </p>
	            <p class="price" style="">
	                From $ {{ Product::viFormat($product['sell_price']) }}
                    @if( isset($product['bigger_price']) )
                    <em class="marked-down-from">Was $ {{ Product::viFormat($product['bigger_price']) }}</em>
                    @endif
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
            <li><a href="{{ URL }}/collections">Shop</a></li>
            <li>{{ $collection['name'] }}</li>
        </ul>
    </div>
    @if( $collection['totalPage'] > 1 )
    <div class="right columns large-6">
        <ul class="pagination ">
        	@if( $collection['pageNum'] - 1 > 0 )
        	<li class="arrow left">
        		<a href="{{ URL.'/collections/'.$collection['short_name'].'/p/'.($collection['pageNum'] - 1) }}">
        			Previous
        			<span class="glyph arrow-left" aria-hidden="true"></span>
        		</a>
        	</li>
        	@endif
        	<?php
        		$start = $collection['pageNum'] - 3 >= 1 ? $collection['pageNum'] - 3 : 1;
        		$to    = $collection['pageNum'] + 3 <= $collection['totalPage'] ? $collection['pageNum'] + 3 : $collection['totalPage'];
        	?>
        	@for($i = $start; $i <= $to; $i++)
        	@if( $i == $collection['pageNum'] )
            	<li class="current"><a href="#">{{ $i }}</a></li>
            @else
            	<li><a href="{{ URL.'/collections/'.$collection['short_name'].'/p/'.$i }}">{{ $i }}</a></li>
        	@endif
        	@endfor
            @if( $collection['pageNum'] + 1 <= $collection['totalPage'] )
        	<li class="arrow right">
        		<a href="{{ URL.'/collections/'.$collection['short_name'].'/p/'.($collection['pageNum'] + 1) }}">
        			Next
        			<span class="glyph arrow-right" aria-hidden="true"></span>
        		</a>
        	</li>
        	@endif
		</ul>
	</div>
    @endif
</footer>