@section('body')
<body class="page-wristwear template-collection">
@stop
<div class="">{{ $small_banner or '' }}</div>
<div class="breadcrumbs" style="margin-bottom: 15px;">
    <img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
    @if($collection['name'] =='Your Collection')
        <ul class="unstyled-list">
        <li><a href="{{ URL }}">Home</a></li>
        <li><a href="{{ URL }}/user/addresses">User</a></li>
        <li>Your Collection</li>
    </ul>
    @else
    <ul class="unstyled-list">
        <li><a href="{{ URL }}">Home</a></li>
        <li><a href="{{ URL }}/collections">Shop</a></li>
        <li>{{ $collection['name'] }}</li>
    </ul>
    @endif
</div>
<div class="col-md-12">
    <div class="columns">
        <div class="divider"></div>
    </div>
</div>



<div class="col-md-12">
	@if( isset($collection['images'][0]['path']) )
	<!-- <div style="text-align:center; margin-top: 15px; margin-bottom: 15px;">
	    <img src="{{ URL::asset($collection['images'][0]['path']) }}" alt="{{ $collection['name'] }}" title="{{ $collection['name'] }}" />
	</div> -->
	@endif
	<div class="product-grid clearfix">
	    <div class="clearfix"></div>
	    @foreach($collection['products'] as $product)
            @if(isset($product['categories'][0]))
                {{ '';$collection['short_name'] = $product['categories'][0]['short_name'] }}
            @endif
	    <div class="product-item col-md-3 col-xs-4">
            @if(in_array($product['id'],$yourcollection))
                <div class="onoflike likered like" data-product-id="{{ $product['id'] }}" data-status="1" title="Remove this product in your collection">
            @else
                <div class="onoflike like" data-product-id="{{ $product['id'] }}" data-status="0" title="Add this product in your collection">
            @endif
                <i class="fa fa-heart"></i>
            </div>
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

@if( $collection['totalPage'] > 1 )
<div class="col-md-12 text-center">
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
        	<li class="active"><a href="{{ URL.'/collections/'.$collection['short_name'].'/p/'.$i }}">{{ $i }}</a></li>
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

<div class="breadcrumbs bottom">
    <img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
    <ul class="unstyled-list">
        <li><a href="{{ URL }}">Home</a></li>
        <li><a href="{{ URL }}/collections">Shop</a></li>
        <li>{{ $collection['name'] }}</li>
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