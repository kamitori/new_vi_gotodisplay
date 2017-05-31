@section('pageCSS')
<style type="text/css">
	/*#email{
		line-height: 100%;
		background: #f7f7f7;
		border: 1px solid #e6e6e6;
		padding: 10px;
		margin: 0 auto;
		/*text-align: center;*/
		float:left;
		width:100%;
	}*/
	.button_submit{
		background: #444444;
		color: #ffffff;
		-webkit-box-shadow: none;
		-moz-box-shadow: none;
		box-shadow: none;
		font-family: "lato";
		font-weight: 300 !important;
		font-size: emCalc(12px) !important;
		text-transform: uppercase;
		border: 0;
		-webkit-border-radius: 2px;
		-moz-border-radius: 2px;
		-ms-border-radius: 2px;
		-o-border-radius: 2px;
		border-radius: 2px;
		padding: 12px 24px !important;
	}
	.remove a{
		   cursor: pointer;
	}
</style>

@stop

@section('body')
<?php
	if(isset($taxs) && isset($taxs['AB']))
		$tax_per = $taxs['AB'];
	else
	$tax_per = 0;
   	$list_country = json_encode($countries);
   	$arr_billing_information = array();
   	$arr_primary_address = array();
   	if(isset($user->address)){
		$address = $user->address->toArray();
		if(count($address)){
			if(!empty($taxs)){
				if(isset($taxs[$address[0]['province_id']])) $tax_per = $taxs[$address[0]['province_id']];
			}
		}
		if(!empty($address)){
			for($i=0;$i<count($address);$i++){
				if((int)$address[$i]['billing_address'] == 1){
					$arr_billing_information[0] = $address[$i];
				}elseif((int)$address[$i]['default'] == 1){
					$arr_primary_address[0] = $address[$i];
				}
			}
		}
    }
   	else{
		$address = array(array( ));
   	}
   	$tax_price = $total_price * $tax_per/100;
?>
<body class="page-your-shopping-cart template-cart">
@stop

		@section('content')
		<div class="breadcrumbs">
			<img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
			<ul class="unstyled-list">
				<li><a href="{{ URL }}">Home</a></li>
				<li><a href="{{ URL }}/cart">Your Cart</a></li>
				<li><a href="{{ URL }}/checkout">Checkout</a></li>
			</ul>
		</div>
		<section class="main-content">
		<!-- <a name="detail"></a>
		<header>
			<div class="row show-for-medium-up">
				<div class="columns">
					<ul class="breadcrumbs colored-links">
						<li><a href="{{ URL }}">Home</a></li>
						<li><a href="#">Checkout</a></li>
					</ul>
				</div>
			</div>
		</header> -->

		<article class="" itemscope="" itemtype="">
			<form action="{{URL}}/checkout"  method="post" class="custom">
				<div class="">
					<div class="table-responsive" style="width:100%">
						<table width="100%" class="cart-table table table-striped">
							<thead>
								<tr>
									<th class="order">#</th>
									<th class="image text-center">Your image</th>
									<th class="quantity">Quantity</th>
									<th class="total text-right">Total</th>
								</tr>
							</thead>
							<tbody>
								<?php $i=1; ?>
								@foreach($carts as $rowId=>$cart)
								<tr>
									<td class="order">{{$i}}<?php $i++; ?></td>
									<td class="image text-center">
										<a class="image_product" href="{{$cart['url']}}">
											<img class="item-cart-image"  style="max-width: 400px;max-height: 150px;" src="{{$cart['image']}}" alt="{{$cart['title']}}">
										</a>
										<p><a href="{{$cart['url']}}" style="line-height: 20px;">{{$cart['title']}}</a></p>
									</td>
									<!-- <td class="title">
										<p><a href="{{$cart['url']}}" style="line-height: 20px;">{{$cart['title']}}</a></p>
									</td> -->
									<td class="quantity"><input type="text" class="field styled-input" name="quantity[{{$rowId}}]" id="{{$rowId}}" value="{{$cart['quantity']}}"></td>
									<td class="total text-right">$ {{Product::viFormat($cart['subtotal'])}}</td>
									<!-- <td class="remove"><a title="Remove" onclick="deleteCartOrder('{{$rowId}}');" aria-hidden="true" class="glyph cross"></a></td> -->
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
				<!-- .row -->
				<div class="row">
					<div class="col-md-5 show-for-medium-up">
						<div class="shipping-rates-calculator">
							<div id="shipping-calculator" class="shipping-calculator">
								<h3>&nbsp;</h3>


								<div id="wrapper-response" class="hidden-field">
									<p id="shipping-rates-feedback" class="error"></p>
									<p id="discount-promo-code" class="error"></p>
								</div>
							</div>
						</div>
						<!-- <div class="continue-shopping show-for-medium-up">
							<span><a href="{{URL}}/collections/"><span aria-hidden="true" class="glyph arrow-left"></span> Continue Shopping</a></span>
						</div> -->
					</div>
					<div class="col-md-7 text-right">
						<div class="totals columns">
							<?php $total =  $total_price + $tax_price; ?>
							<h3><strong>SUBTOTAL $ {{Product::viFormat($total_price)}}</strong></h3>
							<h3 style="{{ $tax_per ? '' : 'display:none' }};" id="tax-line"><strong>+ TAX  <span id="tax-per">{{$tax_per}}</span>%: $ <span id="tax-price">{{Product::viFormat($tax_price)}}</span></strong></h3>
							<h3><strong>TOTAL $ <span id="total-cart">{{Product::viFormat($total)}}</span></strong></h3>
						</div>
					</div>
					<!-- <div class="continue-shopping columns show-for-small columns">
						<span><a onclick="{{URL}}/collections/"><span aria-hidden="true" class="glyph arrow-left"></span> Continue Shopping</a></span>
					</div> -->
				</div>
			</form>
			<hr class="divider">
			<h3 class="upper-text">
				* Filling in the form below for billing and delivery
			</h3>
					 <!-- .right-column -->
			<div class="left-column col-md-12">
				<form id="form_submit" class="custom form-inlilne" action="{{URL}}/process-order" method="post" onsubmit="return check_safari();" enctype="multipart/form-data">
					<input type="hidden" name="sum_sub_total" id="sum_sub_total" value='{{$total_price}}'>
					<input type="hidden" name="tax_per" id="tax_per" value='{{isset($tax_per)?$tax_per:0}}'>
					<input type="hidden" name="tax_price" id="tax_price" value='{{isset($tax_price)?$tax_price:0}}'>
					<input type="hidden" name="total" id="total" value='{{$total}}'>
					<div class="col-md-6">
						<h3 itemprop="name">Delivery address &nbsp;</h3>
					</div>
					<div class="col-md-6" style="margin-top: 15px;padding:0">
						@if(!empty($address[0]))
							<select class="form-control" id="change_address_delivery" onchange="change_address_delivery();">
								<option>---Select---</option>
								@foreach($address as $key=> $val)
									<option value="{{$key}}" {{$val['default'] == 1 ? "selected" :"" }}>
										@if($val['default']==1)
											(PRIMARY)
										@elseif($val['billing_address'] == 1)
											(BILLING)
										@else
											(DELIVERY)
										@endif
										{{$val['first_name']}} - 
										{{$val['last_name']}} - {{$val['company']}}
										-
										@if(isset($val['address1']))
											{{ $val['address1']}} -
										@endif
										{{$val['zipcode']}} -
										{{$val['email']}} - {{$val['phone']}}
									</option>
								@endforeach
							</select>
						@endif
					</div>

					
					<div class="quanity-cart-row clearfix" style="clear:both">
					   <div class="variants vifull">
						  <div class="form-group col-md-6">
							 <label class="label-control" for="shipping_first_name">First name</label>
							 <input class="form-control frequired" name="shipping_first_name" id="shipping_first_name" type="text" value="{{ isset($arr_primary_address[0]['first_name'])?$arr_primary_address[0]['first_name']:'' }}" placeholder="First name" required onchange="change_delivery()">
						  </div>
						  <div class="form-group col-md-6">
							 <label class="label-control" for="shipping_last_name">Last name</label>
							 <input class="form-control frequired" name="shipping_last_name" id="shipping_last_name" type="text" value="{{ isset($arr_primary_address[0]['last_name'])?$arr_primary_address[0]['last_name']:'' }}" placeholder="Last name" required onchange="change_delivery()">
						  </div>
						  <div class="form-group col-md-6">
							 <label class="label-control" for="shipping_address">Address</label>
							 <input class="form-control frequired" name="shipping_address" id="shipping_address" type="text" value="{{ isset($arr_primary_address[0]['address1'])?$arr_primary_address[0]['address1']:'' }}" placeholder="Address" required onchange="change_delivery()">
						  </div>
						  <div class="form-group col-md-6">
							 <label class="label-control" for="shipping_city">City</label>
							 <input class="form-control frequired" name="shipping_city" id="shipping_city" type="text" value="{{ isset($arr_primary_address[0]['city'])?$arr_primary_address[0]['city']:'' }}" placeholder="City" required onchange="change_delivery()">
						  </div>
						  <div class="form-group col-md-6">
										<label class="label-control" for="address_country">Country</label>
										<select class="form-control" id="address_country" name="shipping_country" onchange="change_delivery()">
										   <option value="0"  selected="">-- Please Select --</option>
							@foreach($countries as $key => $country)
								<option value="{{$country['value']}}" <?php  if(isset($arr_primary_address[0]['country_id']) && $arr_primary_address[0]['country_id']==$country['value']) {echo 'selected=""'; $country_selected = $key;} else { if($country['value']=='CA') echo  'selected=""'; $country_selected =$key;}?>>{{$country['text']}}</option>
							@endforeach
										</select>
						   </div>
							<div class="form-group col-md-6">
								<label class="label-control" for="shipping_province">Province / State</label>
								<select class="form-control" name="shipping_province" id="shipping_province" onchange="change_delivery()">
									<option value="0"  selected="">-- Please Select --</option>
									@if(isset($country_selected) && $country_selected !='')
									@foreach($countries[$country_selected]['provinces'] as $key => $province)
										<option value="{{$province['value']}}" <?php  if(isset($arr_primary_address[0]['province_id']) && $arr_primary_address[0]['province_id']==$province['value']) {echo 'selected=""'; $province_selected = $key;} else { if($province['value']=='AB') echo  'selected=""';}?>>{{$province['text']}}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div class="form-group col-md-6">
								<label class="label-control" for="shippping_zipcode">Zipcode</label>
								<input class="form-control" name="shipping_zipcode" id="shipping_zipcode" type="text" maxlength="10" value="{{ isset($arr_primary_address[0]['zipcode'])?$arr_primary_address[0]['zipcode']:'' }}" placeholder="Zipcode" onchange="change_delivery()">
								</select>
							</div>
							<div class="form-group col-md-6">
								<label class="label-control" for="shippping_note">Note</label>
								<textarea class="form-control" name="shippping_note" id="shippping_note" maxlength="120" placeholder="Notice" style="resize: none;height:80px;" onchange="change_delivery()">{{ isset($arr_primary_address[0]['note'])?$arr_primary_address[0]['note']:'' }}</textarea>
								</select>
							</div>
								<div id="country-shipping-price" style="clear: both;display:block;">
							  
								</div>
					   </div>
					</div>
			</div>

					<!-- .left-column -->

			<div class="middle-column photos col-md-12 show-for-medium-up show-for-ie9-down">
					<h3 itemprop="name" style="display: inline-block;">Billing information</h3>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<span class="form-group inline">
							 <label class="label-control" for="use_for_shipping">Same delivery address</label>
							 <input name="use_for_shipping" id="use_for_shipping" type="checkbox" value="1" >
					</span>
					<div class="quanity-cart-row clearfix">
						<div class="variants vifull">
						  <div class="form-group col-md-6">
							 <label class="label-control" for="email">Email</label>
							 <input class="form-control frequired" name="email" id="email" type="email" value="{{ isset($arr_billing_information[0]['email'])?$arr_billing_information[0]['email']:'' }}" placeholder="example@live.ca" required title="Input email" onchange="change_billing()">
						  </div>
						  <div class="form-group col-md-6">
							 <label class="label-control" for="phone">Phone</label>
							 <input class="form-control frequired" name="phone" id="phone" type="text" value="{{ isset($arr_billing_information[0]['phone'])?$arr_billing_information[0]['phone']:'' }}" placeholder="Phone number" required  pattern="[0-9]*" title="input only number" onchange="change_billing()">
						  </div>
						  <div class="form-group col-md-6">
							 <label class="label-control" for="first_name">First name</label>
							 <input class="form-control frequired" name="billing_first_name" id="first_name" type="text" value="{{ isset($arr_billing_information[0]['first_name'])?$arr_billing_information[0]['first_name']:'' }}" placeholder="First name" required onchange="change_billing()">
						  </div>
						  <div class="form-group col-md-6">
							 <label class="label-control" for="last_name">Last name</label>
							 <input class="form-control frequired" name="billing_last_name" id="last_name" type="text" value="{{ isset($arr_billing_information[0]['last_name'])?$arr_billing_information[0]['last_name']:'' }}" placeholder="Last name" required onchange="change_billing()">
						  </div>
						  <div class="form-group col-md-6">
							 <label class="label-control" for="billing_address">Address</label>
							 <?php 
								$address_string = isset($arr_billing_information[0]['address1'])?$arr_billing_information[0]['address1']:'';
								$address_string .= $address_string !='' ? $address_string .' ' : '';
								$address_string .=isset($arr_billing_information[0]['address2'])?$arr_billing_information[0]['address2']:'';
							 ?>
							 <input class="form-control frequired" name="billing_address" id="billing_address" type="text" value="{{ $address_string }}" placeholder="Your address" required onchange="change_billing()">
						  </div>
						  <div class="form-group col-md-6">
							 <label class="label-control" for="billing_city">City</label>
							 <input class="form-control frequired" name="billing_city" id="billing_city" type="text" value="{{ isset($arr_billing_information[0]['city'])?$arr_billing_information[0]['city']:'' }}" placeholder="City" required onchange="change_billing()">
						  </div>
						  <div class="form-group col-md-6">
						<label class="label-control" for="billing_country">Country</label>
						<select class="form-control" name="billing_country" id="billing_country" onchange="change_billing()">
						<option value="0"  selected="">-- Please Select --</option>
							@foreach($countries as $key => $country)
								<option value="{{$country['value']}}" <?php  if(isset($arr_billing_information[0]['country_id']) && $arr_billing_information[0]['country_id']==$country['value']) {echo 'selected=""'; $country_selected = $key;} else { if($country['value']=='CA') echo  'selected=""';$country_selected =$key;}?>>{{$country['text']}}</option>
							@endforeach
						</select>
					</div>
						  <div class="form-group col-md-6">
						<label class="label-control" for="billing_province">Province / State</label>
						<select class="form-control" name="billing_province" onchange="change_billing()" id="billing_province">
							<option value="0"  selected="">-- Please Select --</option>
							@if(isset($country_selected) && $country_selected !='')
							@foreach($countries[$country_selected]['provinces'] as $key => $province)
								<option value="{{$province['value']}}" <?php  if(isset($arr_billing_information[0]['province_id']) && $arr_billing_information[0]['province_id']==$province['value']) {echo 'selected=""'; $province_selected = $key;} else { if($province['value']=='AB') echo  'selected=""';}?>>{{$province['text']}}</option>
							@endforeach
							@endif
						</select>
					</div>
					<div class="form-group col-md-6" style="clear:both;margin-top:-8px;">
						<label class="label-control" for="billing_zipcode">Zipcode</label>
						<input class="form-control" name="billing_zipcode" id="billing_zipcode" type="text" maxlength="10" placeholder="Zipcode"  value="{{ isset($arr_billing_information[0]['zipcode'])?$arr_billing_information[0]['zipcode']:'' }}" onchange="change_billing()" >
					</div>
					<div class="form-group col-md-10">
						<a class="btn btn-3 btn-white" data-fancybox="modal" href="#view_invoices">
							View invoice
						</a>
						<input id="submit_billing" name="submit_billing" tabindex="40" type="submit" class="button_submit btn btn-4 btn-white" value="Place and order">
						or <a href="{{URL}}/collections/">return to store</a>
					</div>
						</div>
					 </div>
				</form>
			</div>

		</article>
		<div id="view_invoices" class="content_list" style="display:none;width:80%">
			<h3>INVOICE</h3>
			<div class="table-responsive" style="width:100%">
				<table width="100%" class="cart-table table table-striped">
					<thead>
						<tr>
							<th class="order">#</th>
							<th class="image text-center">Your image</th>
							<th class="quantity">Quantity</th>
							<th class="total text-right">Total</th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; ?>
						@foreach($carts as $rowId=>$cart)
						<tr>
							<td class="order">{{$i}}<?php $i++; ?></td>
							<td class="image text-center">
								<a class="image_product" href="{{$cart['url']}}">
									<img class="item-cart-image"  style="max-width: 150px;max-height: 100px;" src="{{$cart['image']}}" alt="{{$cart['title']}}">
								</a>
								<p><a href="{{$cart['url']}}" style="line-height: 20px;">{{$cart['title']}}</a></p>
							</td>
							<td class="quantity">
								<span id="view_invoice_{{$i}}">{{$cart['quantity']}}</span>
							</td>
							<td class="total text-right">$ {{Product::viFormat($cart['subtotal'])}}</td>
							
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			<div class="col-md-5 show-for-medium-up">
				<div class="shipping-rates-calculator">
					<div id="shipping-calculator" class="shipping-calculator">
						<h3>&nbsp;</h3>
						<div id="wrapper-response" class="hidden-field">
							<p id="shipping-rates-feedback" class="error"></p>
							<p id="discount-promo-code" class="error"></p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-7 col-sm-12 col-xs-12 text-right">
				<div class="totals columns">
					<?php $total =  $total_price + $tax_price; ?>
					<h3>
						<strong>SUBTOTAL $ {{Product::viFormat($total_price)}}</strong>
					</h3>
					<h3 style="{{ $tax_per ? '' : 'display:none' }};" id="invoice-tax-line">
						<strong>
							+ TAX  <span id="invoice-tax-per">{{$tax_per}}</span>%: $ 
							<span id="invoice-tax-price">{{Product::viFormat($tax_price)}}</span>
						</strong>
					</h3>
					<h3><strong>TOTAL $ <span id="invoice-total-cart">{{Product::viFormat($total)}}</span></strong></h3>
				</div>
			</div>

			<div class="col-sm-12 col-xs-12 col-md-6">
				<h4>Deviery Address</h4>
				<div class="col-sm-12 col-xs-12 col-md-12">
					<div class="col-sm-6 col-xs-6 col-md-4">
						Fullname:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_d_fullname">
						N/A
					</div>
					<div class="col-sm-6 col-xs-6 col-md-4">
						Address:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_d_address">
						N/A
					</div>
					<div class="col-sm-6 col-xs-6 col-md-4">
						City:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_d_city">
						N/A
					</div>
					<div class="col-sm-6 col-xs-6 col-md-4">
						Country:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_d_country">
						N/A
					</div>
					<div class="col-sm-6 col-xs-6 col-md-4">
						Province:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_d_province">
						N/A
					</div>
					<div class="col-sm-6 col-xs-6 col-md-4">
						Zipcode:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_d_zipcode">
						N/A
					</div>
					<div class="col-sm-6 col-xs-6 col-md-4">
						Note:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_d_note">
						N/A
					</div>
				</div>
			</div>

			<div class="col-sm-12 col-xs-12 col-md-6">
				<h4>Billing Address</h4>
				<div class="col-sm-12 col-xs-12 col-md-12">
					<div class="col-sm-6 col-xs-6 col-md-4">
						Fullname:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_b_fullname">
						N/A
					</div>
					<div class="col-sm-6 col-xs-6 col-md-4">
						Email:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_b_email">
						N/A
					</div>
					<div class="col-sm-6 col-xs-6 col-md-4">
						Phone:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_b_phone">
						N/A
					</div>
					<div class="col-sm-6 col-xs-6 col-md-4">
						Address:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_b_address">
						N/A
					</div>
					<div class="col-sm-6 col-xs-6 col-md-4">
						City:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_b_city">
						N/A
					</div>
					<div class="col-sm-6 col-xs-6 col-md-4">
						Country:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_b_country">
						N/A
					</div>
					<div class="col-sm-6 col-xs-6 col-md-4">
						Province:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_b_province">
						N/A
					</div>
					<div class="col-sm-6 col-xs-6 col-md-4">
						Zipcode:
					</div>
					<div class="col-sm-6 col-xs-6 col-md-8" id="invoice_b_zipcode">
						N/A
					</div>					
				</div>
			</div>
			<div class="col-sm-12 col-xs-12 col-md-12 text-right">
				<a class="btn btn-3 btn-white" id="close_button" onclick="close_modal_2()" href="#">
					Closed
				</a>
			</div>
		</div>
		@stop
		
		@section('pageJS')
		<script type="text/javascript">
		var list_provice = {};
		function validateEmail($email) {
		  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		  return emailReg.test( $email );
		}
		function close_modal_2(){
			$('.fancybox-close-small').trigger('click')
		}
		var _default_address = $.parseJSON('{{json_encode($address)}}');
		function change_billing(){
			$("#invoice_b_fullname").html(($("#first_name").val() + ' ' + $("#last_name").val())==' '?'N/A':($("#first_name").val() + ' ' + $("#last_name").val()));
			
			$("#invoice_b_email").html($("#email").val()=='' ? 'N/A' : $("#email").val());
			$("#invoice_b_phone").html($("#phone").val()=='' ? 'N/A' : $("#phone").val());
			$("#invoice_b_address").html($("#billing_address").val()=='' ? 'N/A' : $("#billing_address").val());
			$("#invoice_b_city").html($("#billing_city").val()=='' ? 'N/A' : $("#billing_city").val());
			$("#invoice_b_country").html($("#billing_country").val()==0 ? 'N/A' : $("#billing_country  option:selected").text());
			$("#invoice_b_province").html($("#billing_province").val()==0 || $("#billing_province").val()==null ? 'N/A' : $("#billing_province option:selected").text());
			$("#invoice_b_zipcode").html($("#billing_zipcode").val()=='' ? 'N/A' : $("#billing_zipcode").val());			
		}
		function change_delivery(){
			$("#invoice_d_fullname").html(($("#shipping_first_name").val() + ' ' + $("#shipping_last_name").val())==' '?'N/A' : ($("#shipping_first_name").val() + ' ' + $("#shipping_last_name").val()) );
			$("#invoice_d_address").html($("#shipping_address").val()=='' ? 'N/A' : $("#shipping_address").val());
			$("#invoice_d_city").html($("#shipping_city").val()=='' ? 'N/A' : $("#shipping_address").val() );
			$("#invoice_d_country").html($("#address_country").val() ==0 ? 'N/A' : $("#address_country option:selected").text());
			$("#invoice_d_province").html($("#shipping_province").val() ==0 ? 'N/A' : $("#shipping_province option:selected").text());
			$("#invoice_d_zipcode").html($("#shipping_zipcode").val() =='' ? 'N/A' : $("#shipping_address").val());
			$("#invoice_d_note").html($("#shippping_note").val() =='' ? 'N/A' : $("#shipping_address").val());
		}
		function check_safari(){
			if(isSafari()){
				var _stop = false;
				$('.frequired').each(function(){
					if($(this).val()==''){
						if($(this).attr('id')=='email'){
							alert('Email cannot be empty !');
						}
						else {
							alert($(this).attr('placeholder') + ' cannot be empty !');
						}
						$(this).trigger('focus');
						_stop = true;
						return false;
					}else if($(this).attr('id')=='email'){
						//frequired
						if(!validateEmail($(this).val())) {
							alert('Email not valid. Please try again !');
							_stop = true;
							$(this).trigger('focus');
							return false;
						}
					}
				});				
			}
			if(_stop) return false;
			return true;
		}
		function isSafari() {
		      return /^((?!chrome).)*safari/i.test(navigator.userAgent);
		  }
		function change_address_delivery(){
			var current = $("#change_address_delivery").val();
			$("#shipping_first_name").val(_default_address[current]['first_name']);
			$("#shipping_last_name").val(_default_address[current]['last_name']);
			$("#shipping_address").val(_default_address[current]['address1']);
			$("#shipping_city").val(_default_address[current]['city']);
			$("#address_country").val(_default_address[current]['country_id']);
			$("#shipping_province").val(_default_address[current]['province_id']);
			$("#shipping_zipcode").val(_default_address[current]['zipcode']);
			change_delivery();
		}
		var list_country = $.parseJSON('{{ $list_country }}');
		var list_tax = $.parseJSON('{{ json_encode($taxs) }}');

			// $("#cart-promo-code").mask('AAAA-AAAA-AAAA');
			$("input",".quantity").keydown(function(e){
				if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
					 // Allow: Ctrl+A
					(e.keyCode == 65 && e.ctrlKey === true) ||
					 // Allow: home, end, left, right
					(e.keyCode >= 35 && e.keyCode <= 39)) {
						 // let it happen, don't do anything
						 return;
				}
				// Ensure that it is a number and stop the keypress
				if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
					e.preventDefault();
				}
			});
			$("input",".quantity").focus(function(){
				$(this).attr("data-old-value", $(this).val() );
			});
			$("input",".quantity").change(function(){
				if( $(this).val() <= 0){
					$(this).val( $(this).attr("data-old-value") );
					event.preventDefault();
				}else{
					updateCartOrder(this);
				}
			});
			$("#check-promo-code").click(function(){

				$("#estimated-discount").hide();
				$("em","#estimated-discount").html("");
				$(this).val("Searching...");
				$.ajax({
					url: "{{URL}}/cart/get-promo-code",
					type: "POST",
					data: {promo_code : $("#cart-promo-code").val()},
					success: function(result){
						$("input[type=button]#check-promo-code").val("Apply");
						if(result.discount != undefined){
							$("#estimated-discount").show();
							if(result.is_shipping_discount != undefined)
								$("#discount-label","#estimated-discount").html("Shipping Discount");
							else
								$("#discount-label","#estimated-discount").html("Discount");
							$("em#discount-percent","#estimated-discount").html(result.discount);
							$("em#discount-price","#estimated-discount").html(result.discount_price);
							console.log(result.discount_value);
							$('#discount').val(parseFloat(result.discount_value).toFixed(2));
						} else{
							  $('#discount').val(0)
							alert(result.message);
						 }
						if(result.total_price != undefined){
			   sub_total = $("#sum_sub_total").val();

			   tax = $("#tax").val();
			
			   shipping_price = 0;
			   data_shipping = JSON.parse($("#address_country option:selected").attr('data-value'));
			   $.each(data_shipping,function(key,value){
				shipping_price = value.shipping_price;
			   })
			   discount = $("#discount").val();
			   
			   total_price = parseFloat(sub_total) + parseFloat(shipping_price) - parseFloat(discount)+parseFloat(tax);
			   total_price = total_price.toFixed(2);

							$("#total-cart").html(total_price);
							$("#total").val(total_price);

						}
					}
				});
			});
		   
		   /* $("#country-shipping-price").on("click",".shipping_method",function(){
				changeShipping();
			})
			function changeShipping()
			{
				var radio = $(".shipping_method:checked","#country-shipping-price");
				var id = radio.val();
				$("em","#estimated-shipping").html("").parent().hide();
				// $("#total-cart").html("");
				if(id == undefined)
					id = 0;
				$.ajax({
					url : "{{URL}}/cart/change-shipping-method",
					type: "POST",
					data: {method : id},
					success: function(shipping){
						if(shipping.status == "ok"){
							$("em","#estimated-shipping").html("$ "+shipping.shipping_price).parent().show();
							$("#shipping_price").val(shipping.shipping_price);
							total_price = parseFloat(parseFloat(shipping.cost_value)+parseFloat($("#tax").val())).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
							$("#total-cart").html(total_price);
							$("#total").val(total_price);
							$("em#discount-price","#estimated-discount").html(shipping.discount_price);
						}
					}
				})
			}*/
			change_address_delivery();
			function deleteCartOrder(row_id){
			   if(confirm('Do you want to remove this item ?')){
					$.ajax({
						url:'{{ URL }}/cart/delete-cart-order',
						type: 'POST',
						data:{
							row_id:row_id
						},
						success:function(data){
							if(data.error==0){
								window.location.reload();
							}else{
								alert(data.data);
							}
						}
					});
			   }
			}

			function updateCartOrder(obj){
				row_id = $(obj).attr("id");
				quantity = $(obj).val();
				if(quantity>0){
					$.ajax({
						url:'{{ URL }}/cart/update-cart-order',
						type: 'POST',
						data:{
							row_id:row_id,
							quantity:quantity
						},
						success:function(data){
							if(data.error==0){
								window.location.reload();
							}else{
								alert(data.data);
							}
						}
					});

				}
			}
			
			$("#billing_country").on("change",function(){
				id = $(this).val();
				$.each(list_country,function(key,elem){
					if(elem.value == id)
						list_provice = elem.provinces
				})
				html='';
				$.each(list_provice,function(key,value){
					html+='<option value="'+value['value']+'">'+value['text']+'</option>'
				});
				$('#billing_province').html(html);
			})

			$("#address_country").on("change",function(){
				id = $(this).val();
				$.each(list_country,function(key,elem){
					if(elem.value == id)
						list_provice = elem.provinces
				})
				html='';
				$.each(list_provice,function(key,value){
					html+='<option value="'+value['value']+'">'+value['text']+'</option>'
				});
				$('#shipping_province').html(html);
			})

			$("#use_for_shipping").on("click",function(){
				if($(this).is(":checked")){
					$("#first_name").val($('#shipping_first_name').val());
					$("#last_name").val($('#shipping_last_name').val());
					$("#billing_address").val($('#shipping_address').val());
					$("#billing_city").val($('#shipping_city').val());
					$("#billing_zipcode").val($('#shipping_zipcode').val());
					$("#billing_country option[value="+$("#address_country").val()+"]").prop("selected",true);
					$("#billing_country").trigger("change");					
					setTimeout(function(){
						$("#billing_province option[value="+$("#shipping_province").val()+"]").prop("selected",true);
						},50);
				}else{
					$("#first_name").val('');
					$("#last_name").val('');
					$("#billing_address").val('');
					$("#billing_city").val('');
					$("#billing_zipcode").val('');

					$("#invoice_b_fullname").html('N/A');
					$("#invoice_b_email").html('N/A');
					$("#invoice_b_phone").html('N/A');
					$("#invoice_b_address").html('N/A');
					$("#invoice_b_city").html('N/A');
					$("#invoice_b_country").html('N/A');
					$("#invoice_b_province").html('N/A');
					$("#invoice_b_zipcode").html('N/A');
				}
			})

			$("#billing_province").on("change",function(){
					if(list_tax[$(this).val()]){
						$("#tax-line").show();
						tax_per = list_tax[$(this).val()];
						$("#tax_per").val(tax_per)
						tax_price = parseFloat($("#sum_sub_total").val())*tax_per/100;

						$("#tax_price").val(tax_price.toFixed(2));

						total = parseFloat($("#sum_sub_total").val()) + parseFloat(tax_price)
						$("#total").val(total.toFixed(2));
						

						$("#tax-per").text(tax_per);
						tax_price_text = format_number(tax_price);
						$("#tax-price").text(tax_price_text);
						total_text = format_number(total);
						$("#total-cart").text(total_text);
					}else{
						$("#tax_per").val(0);
						$("#tax_price").val(0);
						$("#tax-line").hide();
						total = parseFloat($("#sum_sub_total").val());
						$("#total").val(total.toFixed(2));
						total_text = format_number(total);
						$("#total-cart").text(total_text);

					}
			})
			$("#billing_country").on("change",function(){
					$("#billing_province").trigger("change");
			})
			$("#billing_province").trigger("change");
			function format_number(number){
					number = number.toFixed(2);
					number = number.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
					return number;
			}
		
		</script>
		@stop
		<style type="text/css">
	@media (max-width: 890px) {	
	input.styled-input{
		width:75px;
	}
}
</style>