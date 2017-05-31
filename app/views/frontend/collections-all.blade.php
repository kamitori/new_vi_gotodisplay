@section('pageCSS')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/vitheme/plugins/css-image/css/normalize.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/vitheme/plugins/css-image/css/demo.css') }}" />
<link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/vitheme/plugins/css-image/css/set1.css') }}" />
@stop
@section('body')
<body class="page-collections template-list-collections" data-twttr-rendered="true">
@stop
<div class="breadcrumbs">
    <img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
    <ul class="unstyled-list">
        <li>Shop</li>
    </ul>
</div>
<div class="row">
    <div class="collections-grid clearfix">
    	@foreach($collections as $collection)
        <div class="collection-item col-md-4 col-xs-10 col-sm-10" style="height:270px" >
            <a href="{{ URL.'/collections/'.$collection['short_name'] }}">
                <div class="grid">                     
                    <figure class="effect-oscar">
                        <img src="{{ $collection['image'] }}" alt="{{ $collection['name'] }}" title="{{ $collection['name'] }}"/>
                        <figcaption>
                            <h2 style="word-wrap: break-word;">{{ $collection['name'] }}</h2>
                            <p>{{ $collection['totalProduct'] }} product{{ $collection['totalProduct'] > 0 ? 's' : '' }}
                            </p>
                        </figcaption>       
                    </figure>
                </div>
            </a>
        </div>
        @endforeach
    </div>
</div>
<style type="text/css">
    figure.effect-oscar figcaption{
        padding:0 !important;
    }
    figure.effect-oscar figcaption::before {
        top: 10px !important;
        right: 10px !important;
        bottom: 10px !important;
        left: 10px !important;
    }
    figure.effect-oscar h2{
        margin-top: 10% !important;
        margin-bottom: 0 !important;
    }
</style>
<div class="breadcrumbs bottom">
    <img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
    <ul class="unstyled-list">
        <li>Shop</li>
    </ul>
</div>