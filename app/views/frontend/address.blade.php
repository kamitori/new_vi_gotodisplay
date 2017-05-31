@section('body')
<body class="page-the-profound-aesthetic-academy customer-logged-in template-page">
@stop
<div class="breadcrumbs">
	<img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
	<ul class="unstyled-list">
		<li><a href="{{ URL }}">Home</a></li>
		<li><a href="javascript:void(0)">User</a></li>
		<li><a href="{{ URL }}/user/addresses">Addresses</a></li>
	</ul>
</div>
{{--*/ $default_adress = '' /*--}}
@foreach($addresses as $address)	
@if($address->default)
	{{--*/ $default_adress = $address /*--}}
@endif					
@endforeach
<div id="edit_address" >
	<div class="customer_address_table col-md-6">
		<form class="">
			<h3>DELIVERY ADDRESS</h3>
			<div class="form-group">
				<label class="control-label" for="address_first_name">First Name (*)</label>
				<input type="hidden" id="address_id" class="form-control" name="address[id]" value="" size="40">
				<input type="text" id="address_first_name" required class="form-control" name="address[first_name]" value="{{isset($default_adress->first_name) ? $default_adress->first_name : '' }}" size="40"> 
			</div>
			<div class="form-group">
				<label class="control-label" for="address_last_name">Last Name </label>
				<input type="text" id="address_last_name" required class="form-control" name="address[last_name]" value="{{isset($default_adress->last_name) ? $default_adress->last_name : '' }}" size="40">
			</div>
			<div class="form-group">
				<label class="control-label" for="address_company">Company (optional)</label>
				<input type="text" id="address_company" class="form-control" name="address[company]" value="{{isset($default_adress->company) ? $default_adress->company : '' }}" size="40">
			</div>
			<div class="form-group">
				<label class="control-label" for="address_address1">Address1 (*)</label>
				<input type="text" id="address_address1" required class="form-control" name="address[address_1]" value="{{isset($default_adress->address1) ? $default_adress->address1 : '' }}" size="40">
			</div>
			<div class="form-group">
				<label class="control-label" for="address_address2">Address2</label>
				<input type="text" id="address_address2" class="form-control" name="address[address_2]" value="{{isset($default_adress->address2) ? $default_adress->address2 : '' }}" size="40">
			</div>
			<div class="form-group">
				<label class="control-label" for="address_phone">Phone (*)</label>
				<input type="text" id="address_phone" class="form-control" name="address[phone]" value="{{isset($default_adress->phone) ? $default_adress->phone : '' }}" size="40">
			</div>
			<div class="form-group" style="display: none">
				<label class="control-label" for="address_phone">Email (*)</label>
				<input type="text" id="address_email" class="form-control" name="address[email]" value="{{$email}}" size="40">
			</div>
			<div class="form-group">
				<label class="control-label" for="address_country">Country</label>
				
					<select class="form-control" id="address_country" onchange="changeCountry(this)" name="address[country]">
						<option value="">-- Please Select --</option>
						@foreach ($countries as $key => $country)
							<option {{isset($default_adress->country_id) && $default_adress->country_id == $country['value'] ? 'selected' : '' }} value="{{ $country['value'] }}">{{ $country['text'] }}</option>
						@endforeach
					</select>
				
			</div>
			<div class="form-group">
				<label class="control-label" for="address_province">Province</label>
				<select class="form-control" id="address_province" class="address_province" name="address[province]">
					<option value="">-- Please Select --</option>
				</select>
				
			</div>
			<div class="form-group">
				<label class="control-label" for="address_city">City</label>
				<input type="text" id="address_city" class="form-control" name="address[city]" value="{{isset($default_adress->city) ? $default_adress->city : '' }}" size="40">
			</div>
			
			<div class="form-group">
				<label class="control-label" for="address_zip">ZipCode</label>
				<input type="text" id="address_zip" class="form-control" name="address[zip]" value="{{isset($default_adress->zipcode) ? $default_adress->zipcode : '' }}" size="40">
			</div>
			<div class="form-group">
				<label class="control-label" for="address_zip">Note</label>
				<textarea class="form-control" id="note" name="address[note]"></textarea>
			</div>
			
			<div class="action_bottom form-group text-right">
				<button class="btn btn-3 btn-white" type="button" onclick="update_address();">Add New Address</button>				
			</div>
		</form>
	</div>
	<div class="customer_address_table col-md-6">
		<form class="">
			<h3>BILLING INFORMATION</h3>
			<div class="form-group">
				<label class="control-label" for="billing_address_first_name">First Name (*)</label>				
				<input type="text" id="billing_address_first_name" class="form-control" name="billing_address[first_name]" value="" size="40">				
			</div>
			<div class="form-group">
				<label class="control-label" for="billing_address_last_name">Last Name </label>
				<input type="text" id="billing_address_last_name" class="form-control" name="billing_address[last_name]" value="" size="40">
			</div>
			<div class="form-group">
				<label class="control-label" for="billing_address_company">Company (optional)</label>
				<input type="text" id="billing_address_company" class="form-control" name="billing_address[company]" value="" size="40">
			</div>
			<div class="form-group">
				<label class="control-label" for="billing_address_address1">Address1 (*)</label>
				<input type="text" id="billing_address_address1" class="form-control" name="billing_address[address_1]" value="" size="40">
			</div>
			<div class="form-group">
				<label class="control-label" for="billing_address_address2">Address2</label>
				<input type="text" id="billing_address_address2" class="form-control" name="billing_address[address_2]" value="" size="40">
			</div>
			<div class="form-group">
				<label class="control-label" for="billing_address_phone">Phone (*)</label>
				<input type="text" id="billing_address_phone" class="form-control" name="billing_address[phone]" value="" size="40">
			</div>
			<div class="form-group" style="display: none">
				<label class="control-label" for="billing_address_phone">Email (*)</label>
				<input type="text" id="billing_address_email" class="form-control" name="billing_address[email]" value="{{$email}}" size="40">
			</div>
			<div class="form-group">
				<label class="control-label" for="billing_address_country">Country</label>
				
					<select class="form-control" id="billing_address_country" onchange="changeCountry(this)" name="billing_address[country]">
						<option value="">-- Please Select --</option>
						@foreach ($countries as $key => $country)
								<option value="{{ $country['value'] }}">{{ $country['text'] }}</option>
						@endforeach
					</select>
				
			</div>
			<div class="form-group">
				<label class="control-label" for="billing_address_province">Province</label>
				<select class="form-control" id="billing_address_province" class="billing_address_province" name="billing_address[province]">
					<option value="">-- Please Select --</option>
				</select>
				
			</div>
			<div class="form-group">
				<label class="control-label" for="billing_address_city">City</label>
				<input type="text" id="billing_address_city" class="form-control" name="billing_address[city]" value="" size="40">
			</div>
			
			<div class="form-group">
				<label class="control-label" for="billing_address_zip">ZipCode</label>
				<input type="text" id="billing_address_zip" class="form-control" name="billing_address[zip]" value="" size="40">
			</div>
			
			<div class="action_bottom form-group text-right">
				<button class="btn btn-3 btn-white" type="button" onclick="update_address(1);">Add New Address</button>				
			</div>
		</form>
	</div>
</div>
<br/>
<div class="account-content" style="clear:both;margin-top:20px">
	<div class='col-md-4 mobile-hide'>
		<h4><b>PRIMARY ADDRESS</b></h4>
	</div>
	<div class='col-md-4 mobile-hide'>
		<h4><b>DELIVERY ADDRESS</b></h4>
	</div>
	<div class='col-md-4 mobile-hide'>
		<h4><b>BILLING INFORMATION</b></h4>
	</div>
		@if(isset($addresses))
			<div id="primary_address_div" class='col-md-4'>
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
							<p>
								<!-- <button type="button" class="btn btn-3 btn-white" onclick="delete_address({{$address->id}})">delete</button> -->
							</p>
						@endif
					</div>
				@endforeach
			</div>
			<div id="delivery_address_div" class='col-md-4'>
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
							<p>
								<button type="button" class="btn btn-3 btn-white" onclick="set_primary({{$address->id}})">Set as primary</button>
								<button type="button" class="btn btn-3 btn-white" onclick="delete_address({{$address->id}})">delete</button>
							</p>	
						@endif
					</div>
				@endforeach
			</div>
			<div id="billing_address_div" class='col-md-4'>
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
							<p>
								<button type="button" class="btn btn-3 btn-white" onclick="set_primary({{$address->id}})">Set as primary</button>
								<button type="button" class="btn btn-3 btn-white" onclick="delete_address({{$address->id}})">delete</button>
								
							</p>	
						@endif
					</div>							
				@endforeach				
			</div>
		
		@endif
		<?php $arr_address = $addresses->toArray() ;?>
		<?php if(empty($arr_address)) { ?>
			<div id="primary_address_div" class='col-md-4'></div>
			<div id="delivery_address_div" class='col-md-4'></div>
			<div id="billing_address_div" class='col-md-4'></div>
		<?php }?>
	<div></div>
</div>
<style type="text/css">
	.main-content .breadcrumbs:first-child {
     margin-bottom: 0px; 
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
@section('pageJS')
	<script>
		var json_address={{ json_encode($addresses) }}
		var json_countries={{ json_encode($countries) }}


		function edit_address(id){
			$("#update_address").text("Update Address");
			$("#address_id").val(id);
			$("#address_div").hide(50);
			$.each(json_address,function(key,value){
				if(value.id == id){
					$("#address_first_name").val(value.first_name);
					$("#address_last_name").val(value.last_name);
					$("#address_company").val(value.company);
					$("#address_address1").val(value.address1);
					$("#address_address2").val(value.address2);
					$("#address_city").val(value.city);
					$("#address_country").val(value.country_id);
					$("#address_country").trigger("change");
					$("#address_province").val(value.province_id);
					$("#address_zip").val(value.zipcode);
					$("#address_phone").val(value.phone);
					if(value['default'])
						$("#address_default").attr("checked",true);
					else
						$("#address_default").attr("checked",false);

				}
			})
			if(id==0){
				$(".address_form").val('');
				$("#address_country").val('');
				$("#address_province").val('');
			}
			//Get data

			$("#edit_address").show(50);
		}

		function cancel_edit(){
			$("#edit_address").hide(50);
			$("#address_div").show(50);
		}		
		function set_primary(id){
			$.ajax({
				url:'{{ URL }}/user/set-primary-address',
				type:"POST",
				data:{
					order:id
				},
				success:function(data){
					if(data.status == 'ok'){
						alert("Update success");
						window.location.reload();
					}
				}
			})
		}
		function validateEmail($email) {
		  var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		  return emailReg.test( $email );
		}
		function update_address(type){
			var _temp = 0;
			if(type) _temp = type;
			_temp = parseInt(_temp,10);
			var _data = {};
			if(!_temp){
				if($("#address_first_name").val()==''){
					alert('Delivery first name cannot empty');
					$("#address_first_name").trigger('focus');
					return;
				}
				if($("#address_phone").val()==''){
					alert('Delievey phone cannot empty');
					$("#address_phone").trigger('focus');
					return;
				}
				if($("#address_email").val()==''){
					alert('Delievey email cannot empty');
					$("#address_email").trigger('focus');
					return;
				}
				if(!validateEmail($("#address_email").val() ) ){
					alert('Email not valid. Please try again');
					$("#address_email").trigger('focus');
					return;
				}
				if($("#address_address1").val()==''){
					alert('Delievey address cannot empty');
					$("#address_address1").trigger('focus');
					return;
				}				
			}else{
				if($("#billing_address_first_name").val()==''){
					alert('Billing first name cannot empty');
					$("#billing_address_first_name").trigger('focus');
					return;
				}
				if($("#billing_address_phone").val()==''){
					alert('Billing phone cannot empty');
					$("#billing_address_phone").trigger('focus');
					return;
				}
				if($("#billing_address_address1").val()==''){
					alert('Billing address cannot empty');
					$("#billing_address_address1").trigger('focus');
					return;
				}
				if($("#billing_address_email").val()==''){
					alert('Billing email cannot empty');
					$("#billing_address_email").trigger('focus');
					return;
				}
				if(!validateEmail($("#billing_address_email").val() ) ){
					alert('Billing not valid. Please try again');
					$("#billing_address_email").trigger('focus');
					return;
				}
			}
			if(!_temp){
				_data = {
					id : $("#address_id").val(),
					billing_address:0,
					first_name:  $("#address_first_name").val(),
					last_name:  $("#address_last_name").val(),
					company:  $("#address_company").val(),
					address1:  $("#address_address1").val(),
					address2:  $("#address_address2").val(),
					city:  $("#address_city").val(),
					country:  $("#address_country").val(),
					province:  $("#address_province").val(),
					zipcode:  $("#address_zip").val(),
					phone:  $("#address_phone").val(),
					note: $("#note").val(),
					email: $("#address_email").val(),
					'default':  0
				};
				var _seprate = '-';
				var temp_data = '<p>';
					temp_data += $("#address_first_name").val() + ' ' + $("#address_last_name").val() + _seprate;
					temp_data += $("#address_company").val() + _seprate;
				temp_data += '</p>';
				temp_data += '<p>';
					temp_data += $("#address_address1").val() + _seprate;
					temp_data += ','+$("#address_zip").val();
					temp_data += ','+$("#address_city").val();
				temp_data += '</p>';
				temp_data += '<p>';
					temp_data += $("#address_email").val() ;
				temp_data += '</p>';
				temp_data += '<p>';
					temp_data += $("#address_phone").val() ;
				temp_data += '</p>';
			}else{
				_data = {
					id : $("#billing_address_id").val(),
					billing_address:1,
					first_name:  $("#billing_address_first_name").val(),
					last_name:  $("#billing_address_last_name").val(),
					company:  $("#billing_address_company").val(),
					address1:  $("#billing_address_address1").val(),
					address2:  $("#billing_address_address2").val(),
					city:  $("#billing_address_city").val(),
					country:  $("#billing_address_country").val(),
					province:  $("#billing_address_province").val(),
					zipcode:  $("#billing_address_zip").val(),
					phone:  $("#billing_address_phone").val(),
					email: $("#billing_address_email").val(),
					'default':  0
				};

				var _seprate = '-';
				var temp_data = '<p>';
					temp_data += $("#billing_address_first_name").val() + ' ' + $("#billing_address_last_name").val() + _seprate;
					temp_data += $("#billing_address_company").val() + _seprate;
				temp_data += '</p>';
				temp_data += '<p>';
					temp_data += $("#billing_address_address1").val() + _seprate;
					temp_data += ','+$("#billing_address_zip").val();
					temp_data += ','+$("#billing_address_city").val();
				temp_data += '</p>';
				temp_data += '<p>';
					temp_data += $("#billing_address_email").val() ;
				temp_data += '</p>';
				temp_data += '<p>';
					temp_data += $("#billing_address_phone").val() ;
				temp_data += '</p>';				
			}
				
			$.ajax({
				url:'{{ URL }}/user/update_address',
				type:"POST",
				data:_data,
				success:function(data){
					if(data.status == 'ok'){
						if($("#address_id").val()){
							alert("Update success");
						}else{
							alert("Add new success");
						}
						// window.location.reload();
						var temp_data2 = '<div data-id = '+data.order+'>';
						temp_data += '<p>';
							temp_data += '<button type="button" class="btn btn-3 btn-white"';
							temp_data +='onclick="set_primary('+data.order+')">Set as primary</button>    ';
							temp_data +='<button type="button" class="btn btn-3 btn-white" ';
							temp_data +='onclick="delete_address('+data.order+')">delete</button>';		
						temp_data += '</p>';
						temp_data2 = temp_data2 + temp_data + '</div>';

						if(!_temp){
							//delivery_address_div
							$("#delivery_address_div").append(temp_data2);
						}else{
							//billing_address_div
							$("#billing_address_div").append(temp_data2);
						}
						//primary_address_div--
					}else{
						alert(data.message);
					}
				}
			})
		}
		function delete_address(id){
			if(confirm("Do you want to delete this address?")){
				$("div [data-id="+id+"]").remove();

				$.ajax({
					url:'{{ URL }}/user/delete_address',
					type:"POST",
					data:{
						id : id
					},
					success:function(data){
						if(data.status == 'ok'){
							$("div data-id=['"+id+"']").remove();
						}else{
							alert(data.message);
						}
					}
				})
			}
		}

		function add_address(){
			edit_address(0);
			
		}

		function changeCountry(obj){
			country_key = $(obj).val();
			$.each(json_countries,function(key,value){
				if(country_key == value['value']){
					province = value['provinces'];
					$("#address_province").html('');
					if(province.length>0){
						option='';
						$.each(province,function(key2,value2){
							option+='<option value="'+value2['value']+'">'+value2['text']+'</option>';
						})
						$("#address_province").html(option);
					}
				}
			})
		}
	</script>
@stop

