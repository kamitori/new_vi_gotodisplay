@section('body')
<body class="page-the-profound-aesthetic-academy customer-logged-in template-page">
@stop
<header class="row">
	<div class="left columns large-6">
		<h1 class="page-title">Account Addresses</h1>
	</div>
	<div class="row show-for-medium-up">
		<div class="columns">
			<ul class="breadcrumbs colored-links">
				<li><a href="{{ URL }}">Home</a></li>
				<li><a href="javascript:void(0)">User</a></li>
				<li><a href="{{ URL }}/user/addresses">Addresses</a></li>
			</ul>
		</div>
	</div>
</header>

<div class="row">
	<div class="columns large-6"><a href="javascript:add_address()" title="Add new address">Add new address</a></div>
	<div class="columns large-6"><a href="{{URL}}/orders" title="">View all order</a></div>
	
</div>
<br/>
<div class="row account-content" id="address_div">
		@if(isset($addresses))
			@foreach($addresses as $address)
				<div class='columns large-3' data-id="{{ $address->id }}">
					<strong>{{ $address->default?'Default Addresses':'&nbsp;' }}</strong>
					<h2>{{ isset($address->first_name)?$address->first_name:''}} {{ isset($address->last_name)?$address->last_name:''}}</h2>
					<p>{{ isset($address->phone)?$address->phone:''}} </p>
					<p>{{ isset($address->company)?$address->company:''}} </p>
					<p>{{ isset($address->address1)?$address->address1:''}} </p>
					<p>{{ isset($address->address2)?$address->address2:''}} </p>
					<p>{{ isset($address->city)?$address->city:''}} </p>
					@foreach($countries as $country)
						@if($address->country_id == $country['value'])
							@foreach($country['provinces'] as $province)
								@if($address->province_id == $province['value'])
									<p>{{ $province['text'] }}</p>
								@endif
							@endforeach
							<p>{{ $country['text'] }}</p>
						@endif
					@endforeach
					<p>{{ isset($address->zipcode)?$address->zipcode:''}} </p>
					<p>
						<button type="button" onclick="edit_address({{$address->id}})">Edit</button>
						<button type="button" onclick="delete_address({{$address->id}})">Delete</button>
					</p>
				</div>
			@endforeach
		@else
		<div class="error">You have not added any address</div>
		@endif
</div>
<div id="edit_address" style="display:none">
	<table class="customer_address_table">
                    <tbody>
                        <tr>
                            <td class="label"><label for="address_first_name">First Name</label></td>
                            <td class="value">
                            		<input type="hidden" id="address_id" class="address_form" name="address[id]" value="" size="40">
                            		<input type="text" id="address_first_name" class="address_form" name="address[first_name]" value="123123" size="40">
                            </td>
                        </tr>
                        <tr>
                            <td class="label"><label for="address_last_name">Last Name</label></td>
                            <td class="value"><input type="text" id="address_last_name" class="address_form" name="address[last_name]" value="123123" size="40"></td>
                        </tr>
                        <tr>
                            <td class="label"><label for="address_company">Company</label></td>
                            <td class="value"><input type="text" id="address_company" class="address_form" name="address[company]" value="13123" size="40"></td>
                        </tr>
                        <tr>
                            <td class="label"><label for="address_address1">Address1</label></td>
                            <td class="value"><input type="text" id="address_address1" class="address_form" name="address[address_1]" value="12321321" size="40"></td>
                        </tr>
                        <tr>
                            <td class="label"><label for="address_address2">Address2</label></td>
                            <td class="value"><input type="text" id="address_address2" class="address_form" name="address[address_2]" value="31231231" size="40"></td>
                        </tr>
                        <tr>
                            <td class="label"><label for="address_city">City</label></td>
                            <td class="value"><input type="text" id="address_city" class="address_form" name="address[city]" value="2321312321" size="40"></td>
                        </tr>
                        <tr>
                            <td class="label"><label for="address_country">Country</label></td>
                            <td class="value">
                                <select id="address_country" onchange="changeCountry(this)" name="address[country]">
                                    <option value="">-- Please Select --</option>
                                    @foreach ($countries as $key => $country)
                                    		<option value="{{ $country['value'] }}">{{ $country['text'] }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr class="address_province_container">
                            <td class="label"><label for="address_province">Province</label></td>
                            <td class="value">
                                <select id="address_province" class="address_province" name="address[province]">
                                            
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class="label"><label for="address_zip">Zip</label></td>
                            <td class="value"><input type="text" id="address_zip" class="address_form" name="address[zip]" value="123123" size="40"></td>
                        </tr>
                        <tr>
                            <td class="label"><label for="address_phone">Phone</label></td>
                            <td class="value"><input type="text" id="address_phone" class="address_form" name="address[phone]" value="1312321" size="40"></td>
                        </tr>
                        <tr>
                            <td class="label"></td>
                            <td class="value"><input type="checkbox" id="address_default" name="address[default]" value="0"> Set as Default Address?</td>
                        </tr>
                    </tbody>
                </table>
                <div class="action_bottom">
                    <button type="button" id="update_address" onclick="update_address()">Update Address</button> &nbsp;or&nbsp; 
                    <button type="button" id="cancel" onclick="cancel_edit();">Cancel</button>
                </div>
</div>
@section('pageJS')
	<script>
		var json_address={{ json_encode($addresses) }}
		var json_countries={{ json_encode($countries) }}
		$(function(){

		});	
		function edit_address(id){
			$("#update_address").text("Update Address");
			$("#address_id").val(id);
			$("#address_div").hide(50);
			$.each(json_address,function(key,value){
				if(value.id == id){
					console.log(value);
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

		function update_address(){
			$.ajax({
				url:'{{ URL }}/user/update_address',
				type:"POST",
				data:{
					id : $("#address_id").val(),
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
					'default':  $("#address_default").is(":checked"),
				},
				success:function(data){
					if(data.status == 'ok'){
						if($("#address_id").val()){
							alert("Update success");
						}else{
							alert("Add new success");
						}
						window.location.reload()
					}
				}
			})
		}
		function delete_address(id){
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

		function add_address(){
			edit_address(0);
			$("#update_address").text("Add New Address");
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

