@section('body')
<body class="page-wristwear template-collection">
@stop

<div class="breadcrumbs">
	<img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
	<ul class="unstyled-list">
		<li><a href="{{ URL }}">Home</a></li>
		<li><a href="{{ URL }}/search/{{$search['key']}}">Search</a></li>
		<li>{{ str_replace("+"," ",$search['key']) }}</li>
	</ul>
</div>
<div class="row">
	<div class="columns">
		<div class="divider"></div>
	</div>
</div>
<div class="">
	<div class="product-grid clearfix">
		<div class="clearfix"></div>
		@foreach($search['products'] as $product)
	    <div class="product-item col-md-3 col-xs-4">
            @if(in_array($product['id'],$arr_your_collection))
                <div class="onoflike likered like" data-product-id="{{ $product['id'] }}" data-status="1" title="Remove this product in your collection">
            @else
                <div class="onoflike like" data-product-id="{{ $product['id'] }}" data-status="0" title="Add this product in your collection">
            @endif
                <i class="fa fa-heart"></i>
            </div>
	        <div class="image-wrapper" style="opacity: 1;">
	            <a href="{{ URL.'/collections/'.$product['category'].'/'.$product['short_name'] }}">
	            <img src="{{ $product['image'] }}" alt="{{ $product['name'] }}" />
	            </a>
	        </div>
	        <div class="caption">
	            <p class="title">
	                <a href="{{ URL.'/collections/'.$product['category'].'/'.$product['short_name'] }}">
	                {{ $product['name'] }}
	                </a>
	            </p>
	            <div class="price" style="">
	                From $ {{ Product::viFormat($product['sell_price']) }}
                    @if( isset($product['bigger_price']) )
                    <em class="marked-down-from">Was $ {{ Product::viFormat($product['bigger_price']) }}</em>
                    @endif
	            </div>
	        </div>
	    </div>
	    @endforeach
		<div class="clearfix"></div>
	</div>
</div>


@if( $search['totalPage'] > 1 )
<div class="col-md-12 text-center">
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
			<li class="active"><a href="#">{{ $i }}</a></li>
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
<div class="breadcrumbs">
	<img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
	<ul class="unstyled-list">
		<li><a href="{{ URL }}">Home</a></li>
		<li><a href="{{ URL }}/search/{{$search['key']}}">Search</a></li>
		<li>{{ str_replace("+"," ",$search['key']) }}</li>
	</ul>
</div>

@section('pageJS')
<script type="text/javascript">
    $(".onoflike").click(function(){
       var product_id = $(this).data("product-id");
       var status = $(this).data("status");
       var item = $(this);
       
       $.ajax({
            url: "{{ URL.'/user/add_remove_collecttion' }}",
            data: {product_id:product_id,status:status},
            type: "POST",
            success: function(result){
                if(result.status=='nologin'){
                    window.location.assign("{{ URL }}/user/login");
                }
                if(status==0 && result.status=='ok'){
                    item.addClass("likered");
                    item.data("status",1);
                    toastr.success('Added to the collection successful');
                }    
                if(status==1 && result.status=='ok'){
                    item.removeClass("likered");
                    item.data("status",0);
                    toastr.warning('Remove from the collection successful');
                    if(document.URL.indexOf("user/your-collection")!=-1){
                        window.location.assign("{{ URL }}/user/your-collection");
                    }
                }
            }
        });
       
    });

</script>
@stop