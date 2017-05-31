@section('body')
<body class="page-collections template-list-collections" data-twttr-rendered="true">
@stop
<header class="row">
    <div class="left columns large-6">
        <h1 class="page-title">Shop</h1>
    </div>
</header>
<div class="row">
    <div class="collections-grid clearfix">
    	@foreach($collections as $collection)
        <div class="collection-item columns large-4">
            <a href="{{ URL.'/collections/'.$collection['short_name'] }}">
                <div class="image-wrapper">
                    <div class="hover"></div>
                    <img src="{{ $collection['image'] }}" alt="{{ $collection['name'] }}" title="{{ $collection['name'] }}" />
                </div>
                <div class="caption" style="display: none;">
                    <div class="bg"></div>
                    <div class="inner">
                        <div class="title">
                            {{ $collection['name'] }}
                        </div>
                        <p class="product-count">{{ $collection['totalProduct'] }} product{{ $collection['totalProduct'] > 0 ? 's' : '' }}</p>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>