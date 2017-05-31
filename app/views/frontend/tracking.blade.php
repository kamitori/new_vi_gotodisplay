@section('pageCSS')
<link rel="stylesheet" type="text/css" href="{{URL}}/assets/vitheme/plugins/css-image/css/taskbar.css">
@stop
<div class="breadcrumbs">
    <img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
    <ul class="unstyled-list">
        <li><a href="{{ URL }}">Home</a></li>
        <li><a href="javascript:void(0)">User</a></li>
        <li><a href="{{ URL }}/user/shipping-tracking"> Shipping and tracking</a></li>
        <li>Tracking Orders #{{$tracking_number}}</li>
    </ul>
</div>
<div class="co-md-12 col-sm-12 col-xs-12" style="padding:0;margin-bottom: 60px;">
    <div class="checkout-wrap">
      <ul class="checkout-bar">
        <li class="visited first"><a href="#">Login</a></li>        
        <li class="previous visited">Shipping & Billing</li>        
        <li class="{{$img_ >= 3 ? 'active' : '' }}">Picked Up</li>        
        <li class="{{$img_ >= 4 ? 'active' : '' }}">Transit</li>        
        <li class="last {{$img_ == 5 ? 'active' : '' }}" style="width:17%;">Complete</li>           
      </ul>
    </div>
</div>
@if ( isset($error) )
    <div class="co-md-12 col-sm-12 col-xs-12" style="clear:both;margin-top: 30px">
        <div class="table-responsive" style="width:100%;font-size:16px;">
           Tracking number #<strong>{{$tracking_number}}</strong> not found. <br />Please click <a href="https://www.purolator.com/en/ship-track/tracking-summary.page?">Here </a> to try again!!!
        </div>
    </div>
@else
    <form action="{{URL}}/cart" method="post" class="custom">
        <div class="">
            <div class="table-responsive" style="width:100%">
               {{$html_}}
            </div>
        </div>
    </form>
@endif