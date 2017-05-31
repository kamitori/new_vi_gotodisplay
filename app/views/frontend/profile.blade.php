@section('body')
<body class="page-the-profound-aesthetic-academy customer-logged-in template-page">
@stop
<div class="breadcrumbs">
	<img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
	<ul class="unstyled-list">
		<li><a href="{{ URL }}">Home</a></li>
		<li><a href="javascript:void(0)">User</a></li>
		<li><a href="{{ URL }}/user/profile">Profile</a></li>
	</ul>
</div>		
<br/>
<div class="account-content col-md-12" style="clear:both;margin-top:10px;padding:0;border:1px solid">
	<div class='col-md-4 mobile-hide'>
		<h4 style="font-size: 18px;"><b>PRIMARY ADDRESS</b></h4>
	</div>
	<div class='col-md-4 mobile-hide'>
		<h4 style="font-size: 18px;"><b>DELIVERY ADDRESS</b></h4>
	</div>
	<div class='col-md-4 mobile-hide'>
		<h4 style="font-size: 18px;"><b>BILLING INFORMATION</b></h4>
	</div>
	
	@if(isset($addresses) && count($addresses)>0)
		<div class='col-md-4'>
			<h4 class="mobile-show"><b>PRIMARY ADDRESS</b></h4>
			@foreach($addresses as $address)	
				<div data-id = '{{$address->id}}'>
					@if($address->default)
						<p>{{ isset($address->first_name)?$address->first_name:''}} {{ isset($address->last_name)?$address->last_name:''}} - {{ isset($address->company)? $address->company:'N/A'}}</p>						
						<p>
							@if(isset($address->address1))
								{{ $address->address1}}
							@endif						
							@if(isset($address->address2) && $address->address2!='')
								, {{ $address->address2}} (address 2) <br />
							@endif
							@if(isset($address->zipcode))
								,{{ $address->zipcode}}
							@endif						
							@foreach($countries as $country)
								@if($address->country_id == $country['value'])
									@foreach($country['provinces'] as $province)
										@if($address->province_id == $province['value'])
											<p>, {{ $province['text'] }}</p>
										@endif
									@endforeach
									@if(isset($address->city))
										,{{ $address->city}}
									@endif
									,{{ $country['text'] }}
								@endif
							@endforeach
						</p>											
						<p>{{ isset($address->email)?$address->email:''}} </p>
						<p>{{ isset($address->phone)?$address->phone:''}} </p>							
					@endif
				</div>
			@endforeach
		</div>
		<div class='col-md-4'>
			<h4 class="mobile-show"><b>DELIVERY ADDRESS</b></h4>
			@foreach($addresses as $address)
				<div data-id = '{{$address->id}}'>					
					@if($address->billing_address==0 && $address->first_name!='')
						<p>{{ isset($address->first_name)?$address->first_name:''}} {{ isset($address->last_name)?$address->last_name:''}} - {{ isset($address->company)? $address->company:'N/A'}}</p>						
						<p>
							@if(isset($address->address1))
								{{ $address->address1}}
							@endif						
							@if(isset($address->address2) && $address->address2!='')
								, {{ $address->address2}} (address 2) <br />
							@endif
							@if(isset($address->zipcode))
								,{{ $address->zipcode}}
							@endif						
							@foreach($countries as $country)
								@if($address->country_id == $country['value'])
									@foreach($country['provinces'] as $province)
										@if($address->province_id == $province['value'])
											<p>, {{ $province['text'] }}</p>
										@endif
									@endforeach
									@if(isset($address->city))
										,{{ $address->city}}
									@endif
									,{{ $country['text'] }}
								@endif
							@endforeach
						</p>											
						<p>{{ isset($address->email)?$address->email:''}} </p>
						<p>{{ isset($address->phone)?$address->phone:''}} </p>
						<p><b>Note:</b> </p>
						<p>{{ isset($address->note)?$address->note:''}} </p>
					@endif
				</div>
			@endforeach
		</div>
		<div class='col-md-4'>				
			<h4 class="mobile-show"><b>BILLING INFORMATION</b></h4>
			@foreach($addresses as $address)
				<div data-id = '{{$address->id}}'>					
					@if($address->billing_address==1)
						<p>{{ isset($address->first_name)?$address->first_name:''}} {{ isset($address->last_name)?$address->last_name:''}} - {{ isset($address->company)? $address->company:'N/A'}}</p>						
						<p>
							@if(isset($address->address1))
								{{ $address->address1}}
							@endif						
							@if(isset($address->address2) && $address->address2!='')
								, {{ $address->address2}} (address 2) <br />
							@endif
							@if(isset($address->zipcode))
								,{{ $address->zipcode}}
							@endif						
							@foreach($countries as $country)
								@if($address->country_id == $country['value'])
									@foreach($country['provinces'] as $province)
										@if($address->province_id == $province['value'])
											<p>, {{ $province['text'] }}</p>
										@endif
									@endforeach
									@if(isset($address->city))
										,{{ $address->city}}
									@endif
									,{{ $country['text'] }}
								@endif
							@endforeach
						</p>											
						<p>{{ isset($address->email)?$address->email:''}} </p>
						<p>{{ isset($address->phone)?$address->phone:''}} </p>
					@endif
				</div>							
			@endforeach				
		</div>
	@else
		<div class='col-md-12 text-center' style="margin-top:30px;margin-bottom:30px">
			You don't have any address. Click <a style="color:blue" href="{{url()}}/user/addresses">here</a> to add a new one !
		</div>
	@endif
</div>
<div class="col-md-12" style="padding:0;margin-top:30px">
	<div class="col-md-3" style="padding:0">
		<ul class="unstyled-list2" style="list-style-type:none;padding-left:15px;color:black">
			<li><a href="{{url()}}/user/addresses">Your address book</a></li>
			<li><a href="{{url()}}/checkout">Orders</a></li>
			<li><a href="{{url()}}/orders">Order History</a></li>
			<li><a href="{{url()}}/cart">Your Cart ({{$total_cart}})</a></li>
		</ul>
	</div>
	<div class="col-md-3" style="padding:0">
		<ul class="unstyled-list2" style="list-style-type:none;padding-left:15px;color:black">
			<li><a href="{{url()}}/user/changepassword">Edit Your Password</a></li>
			<li><a href="{{url()}}/user/your-gallery">Your Library</a></li>
			<li><a href="{{url()}}/user/your-collection">Your Collection</a></li>
			<li><a href="{{url()}}/user/shipping-tracking">Shipping & Tracking</a></li>
		</ul>
	</div>
	<div class="col-md-3">
		&nbsp;
	</div>
</div>
<style type="text/css">
	.main-content .breadcrumbs:first-child {
    	margin-bottom: 0px; 
	}
	ul.unstyled-list2 li{
		padding-bottom: 10px;
	}
	ul.unstyled-list2 a{
		color:#333;		
	}
	@media (min-width: 900px) {	
		.mobile-show{
			display: none;
		}
	}
	@media (max-width: 890px) {
		.mobile-hide{
			display: none;
		}
		.mobile-show{
			display: block;
		}
	}
</style>

