@section('pageCSS')
<style type="text/css">
    #email{
        line-height: 100%;
        background: #f7f7f7;
        border: 1px solid #e6e6e6;
        padding: 10px;
        margin: 0 auto;
        /*text-align: center;*/
        float:left;
        width:100%;
    }
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
           if(isset($user->address)){
           	$address = $user->address->toArray();
           	if(count($address)){
           		$tax_per = $taxs[$address[0]['province_id']];
           	}
           }
           else{
           	$address=array(array( ));
           }
           $tax_price = $total_price * $tax_per/100;
 ?>
<body class="page-your-shopping-cart template-cart">
@stop

	    @section('content')
	    <section class="main-content">
	    <a name="detail"></a>
	    <header>
	        <div class="row show-for-medium-up">
	            <div class="columns">
	                <ul class="breadcrumbs colored-links">
	                    <li><a href="{{ URL }}">Home</a></li>
	                    <li><a href="#">Checkout</a></li>
	                </ul>
	            </div>
	        </div>
	    </header>

	    <article class="row" itemscope="" itemtype="">

	        <p style="clear:both">&nbsp;</p>

	        <form action="{{URL}}/checkout" method="post" class="custom">
	            <div class="row">
	                <div class="columns">
	                    <table width="100%" class="cart-table">
	                        <thead>
	                            <tr>
	                                <th class="title">Image</th>
	                                <th class="title">Name</th>
	                                <!-- <th class="title">Style</th>
	                                <th class="title">Depth</th> -->
	                                <th class="quantity">Quantity</th>
	                                <th class="total">Total</th>
	                                <th class="remove">&nbsp;</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            @foreach($carts as $rowId=>$cart)
	                            <tr>
	                                <td class="image">
	                                    <a class="image_product" href="{{$cart['url']}}">
	                                    <img class="item-cart-image"  style="width: 150px" src="{{$cart['image']}}" alt="{{$cart['title']}}">
	                                    </a>
	                                    <p class="mobile-title"><a href="{{$cart['url']}}">{{$cart['title']}}</a></p>
	                                </td>
	                                <td class="title">
	                                    <p><a href="{{$cart['url']}}" style="line-height: 20px;">{{$cart['title']}}</a></p>
	                                </td>
	                                <td class="quantity"><input type="text" class="field styled-input" name="quantity[{{$rowId}}]" id="{{$rowId}}" value="{{$cart['quantity']}}"></td>
	                                <td class="total">$ {{Product::viFormat($cart['subtotal'])}}</td>
	                                <td class="remove"><a title="Remove" onclick="deleteCartOrder('{{$rowId}}');" aria-hidden="true" class="glyph cross"></a></td>
	                            </tr>
	                            @endforeach
	                        </tbody>
	                    </table>
	                </div>
	            </div>
	            <!-- .row -->
	            <div class="row">
	                <div class="columns large-5 show-for-medium-up">
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
	                <div class="columns large-7">
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

	                 <!-- .right-column -->
	        <div class="left-column right columns large-5">
	            <form class="custom" action="{{URL}}/process-order" method="post" enctype="multipart/form-data">
	            	<input type="hidden" name="sum_sub_total" id="sum_sub_total" value='{{$total_price}}'>
	            	<input type="hidden" name="tax_per" id="tax_per" value='{{isset($tax_per)?$tax_per:0}}'>
	            	<input type="hidden" name="tax_price" id="tax_price" value='{{isset($tax_price)?$tax_price:0}}'>
	            	<input type="hidden" name="total" id="total" value='{{$total}}'>

	                <h1 class="page-title" itemprop="name">Shipping information</h1>
	                <div class="quanity-cart-row clearfix">
	                   <div class="variants vifull">
	                      <div class="selector-wrapper">
	                         <label for="shipping_first_name">First name</label>
	                         <input name="shipping_first_name" id="shipping_first_name" type="text" value="" placeholder="First name" required>
	                      </div>
	                      <div class="selector-wrapper">
	                         <label for="shipping_last_name">Last name</label>
	                         <input name="shipping_last_name" id="shipping_last_name" type="text" value="" placeholder="Last name" required>
	                      </div>
	                      <div class="selector-wrapper">
	                         <label for="shipping_address">Address</label>
	                         <input name="shipping_address" id="shipping_address" type="text" value="" placeholder="Address" required>
	                      </div>
	                      <div class="selector-wrapper">
	                         <label for="shipping_city">City</label>
	                         <input name="shipping_city" id="shipping_city" type="text" value="" placeholder="City" required>
	                      </div>
	                      <div class="selector-wrapper">
	                                    <label for="address_country">Country</label>
	                                    <select id="address_country" name="shipping_country">
	                                       <option value=""  selected="">-- Please Select --</option>
							@foreach($countries as $key => $country)
								<option value="{{$country['value']}}" <?php  if(isset($address[0]['country_id']) && $address[0]['country_id']==$country['value']) {echo 'selected=""'; $country_selected = $key;} else { if($country['value']=='CA') echo  'selected=""'; $country_selected =$key;}?>>{{$country['text']}}</option>
							@endforeach
	                                    </select>
	                       </div>
			<div class="selector-wrapper">
				<label for="shipping_province">Province / State</label>
				<select name="shipping_province" id="shipping_province">
					<option value=""  selected="">-- Please Select --</option>
					@if(isset($country_selected) && $country_selected !='')
					@foreach($countries[$country_selected]['provinces'] as $key => $province)
						<option value="{{$province['value']}}" <?php  if(isset($address[0]['province_id']) && $address[0]['province_id']==$province['value']) {echo 'selected=""'; $province_selected = $key;} else { if($province['value']=='AB') echo  'selected=""';}?>>{{$province['text']}}</option>
					@endforeach
					@endif
				</select>
			</div>
			<div class="selector-wrapper" style="margin-top:9px;">
				<label for="shippping_zipcode">Zipcode</label>
				<input name="shipping_zipcode" id="shipping_zipcode" type="text" maxlength="10" placeholder="Zipcode">
				</select>
			</div>
			<div class="selector-wrapper" style="margin-top:9px;">
				<label for="shippping_note">Note</label>
				<textarea name="shippping_note" id="shippping_note" maxlength="120" placeholder="Notice" style="width:200px;height:85px;resize: none;"></textarea>
				</select>
			</div>
	                            <div id="country-shipping-price" style="clear: both;display:block;">
	                          
	                            </div>
	                   </div>
	                </div>

	            <div itemprop="description" class="description show-for-medium-down colored-links rte-content hide-for-ie9-down" style="text-align: justify;">
	                <div style="box-sizing:border-box;color:#444444;font-family:HelveticaNeue-Light, 'Helvetica Neue Light', 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;letter-spacing:1px;">
	                   <p style="box-sizing:border-box;color:#000000;font-size:11px;margin-bottom:20px;"><br></p>
	                </div>
	            </div>
	        </div>

	                <!-- .left-column -->

	        <div class="middle-column photos right columns large-6 show-for-medium-up show-for-ie9-down">
	                <h1 class="page-title" itemprop="name">Billing information</h1>
	                <div class="quanity-cart-row clearfix">
	                    <div class="variants vifull">
	                      <div class="selector-wrapper">
	                         <label for="email">Email</label>
	                         <input name="email" id="email" type="email" value="{{  isset($user->email)?$user->email:'' }}" placeholder="example@live.ca" required title="Input email">
	                      </div>
	                      <div class="selector-wrapper">
	                         <label for="phone">Phone</label>
	                         <input name="phone" id="phone" type="text" value="{{ isset($address[0]['phone'])?$address[0]['phone']:'' }}" placeholder="Phone number" required  pattern="[0-9]*" title="input only number">
	                      </div>
	                      <div class="selector-wrapper">
	                         <label for="first_name">First name</label>
	                         <input name="billing_first_name" id="first_name" type="text" value="{{ isset($address[0]['first_name'])?$address[0]['first_name']:'' }}" placeholder="First name" required>
	                      </div>
	                      <div class="selector-wrapper">
	                         <label for="last_name">Last name</label>
	                         <input name="billing_last_name" id="last_name" type="text" value="{{ isset($address[0]['last_name'])?$address[0]['last_name']:'' }}" placeholder="Last name" required>
	                      </div>
	                      <div class="selector-wrapper">
	                         <label for="billing_address">Address</label>
	                         <?php 
	                         	$address_string = isset($address[0]['address1'])?$address[0]['address1']:'';
	                         	$address_string .=' ';
	                         	$address_string .=isset($address[0]['address2'])?$address[0]['address2']:'';
	                         ?>
	                         <input name="billing_address" id="billing_address" type="text" value="{{ $address_string }}" placeholder="Your address" required>
	                      </div>
	                      <div class="selector-wrapper">
	                         <label for="billing_city">City</label>
	                         <input name="billing_city" id="billing_city" type="text" value="{{ isset($address[0]['city'])?$address[0]['city']:'' }}" placeholder="City" required>
	                      </div>
	                      <div class="selector-wrapper">
						<label for="billing_country">Country</label>
						<select name="billing_country" id="billing_country">
						<option value=""  selected="">-- Please Select --</option>
							@foreach($countries as $key => $country)
								<option value="{{$country['value']}}" <?php  if(isset($address[0]['country_id']) && $address[0]['country_id']==$country['value']) {echo 'selected=""'; $country_selected = $key;} else { if($country['value']=='CA') echo  'selected=""';$country_selected =$key;}?>>{{$country['text']}}</option>
							@endforeach
						</select>
					</div>
	                      <div class="selector-wrapper">
						<label for="billing_province">Province / State</label>
						<select name="billing_province" id="billing_province">
							<option value=""  selected="">-- Please Select --</option>
							@if(isset($country_selected) && $country_selected !='')
							@foreach($countries[$country_selected]['provinces'] as $key => $province)
								<option value="{{$province['value']}}" <?php  if(isset($address[0]['province_id']) && $address[0]['province_id']==$province['value']) {echo 'selected=""'; $province_selected = $key;} else { if($province['value']=='AB') echo  'selected=""';}?>>{{$province['text']}}</option>
							@endforeach
							@endif
						</select>
					</div>
					<div class="selector-wrapper" style="clear:both;margin-top:-8px;">
						<label for="billing_zipcode">Zipcode</label>
						<input name="billing_zipcode" id="billing_zipcode" type="text" maxlength="10" placeholder="Zipcode"  value="{{ isset($address[0]['zipcode'])?$address[0]['zipcode']:'' }}" >
					</div>
	                      <div class="selector-wrapper">
	                         <label for="use_for_shipping">Use as shipping information</label>
	                         <input name="use_for_shipping" id="use_for_shipping" type="checkbox" value="1" >
	                      </div>
	                    </div>
	                 </div>
	                <input id="submit_billing" name="submit_billing" tabindex="40" type="submit" class="button_submit" value="Finish order">
	                or <a href="{{URL}}/collections/">return to store</a>

	            </form>
	        </div>

	    </article>
	    @stop
		
		@section('pageJS')
	    <script type="text/javascript">
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
    				$("#shipping_first_name").val($('#first_name').val());
    				$("#shipping_last_name").val($('#last_name').val());
    				$("#shipping_address").val($('#billing_address').val());
    				$("#shipping_city").val($('#billing_city').val());
    				$("#shipping_zipcode").val($('#billing_zipcode').val());
    				$("#address_country option[value="+$("#billing_country").val()+"]").prop("selected",true);
    				$("#address_country").trigger("change");
    				setTimeout(function(){
    					$("#shipping_province option[value="+$("#billing_province").val()+"]").prop("selected",true);
    					},50);
        		}else{
    				$("#shipping_first_name").val('');
    				$("#shipping_last_name").val('');
    				$("#shipping_address").val('');
    				$("#shipping_city").val('');
    				$("#shipping_zipcode").val('');
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