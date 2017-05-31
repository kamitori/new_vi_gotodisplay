@section('body')
<body class="page-the-profound-aesthetic-academy customer-logged-in template-page">
@stop
<div class="breadcrumbs">
	<img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
	<ul class="unstyled-list">
		<li><a href="{{ URL }}">Home</a></li>
		<li><a href="javascript:void(0)">User</a></li>
		<li><a href="{{ URL }}/user/profile">Chang password</a></li>
	</ul>
</div>		
<br/>
<div class="account-content col-md-12" style="clear:both;margin-top:10px;padding:0;border:1px solid">
	<div class='col-md-12'>
		<h4 style="font-size: 18px;"><b>OLD PASSWORD  </b><span style="color:red" id="error_old"></span></h4>
	</div>
	<div class='col-md-12'>
		<input class="form-control" onchange="check(this);" placeholder="Old password" id="old_password" type="password" />
	</div>
	<div class='col-md-12'>
		<h4 style="font-size: 18px;"><b>NEW PASSWORD  <span style="color:red" id="new_old"></span></b></h4>
	</div>
	<div class='col-md-12'>
		<input class="form-control" placeholder="New password" id="new_password" type="password" />
	</div>
	<div class='col-md-12' style="margin-top: 15px; margin-bottom: 15px;">
		<div class='col-md-2'></div>
		<div class='col-md-8'>
			<input type="button" onclick="changepassword();" value="Change password" class="form-control btn btn-3" />
		</div>
		<div class='col-md-2'></div>
	</div>

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
	<div class="col-md-3">
		<ul class="unstyled-list2" style="list-style-type:none;padding-left:15px;color:black">
			<li>Edit Your Password</li>
			<li><a href="{{url()}}/user/your-gallery">Your Library</a></li>
			<li><a href="{{url()}}/user/your-collection">Your Collection</a></li>
			<li><a href="{{url()}}/user/shipping-tracking">Shipping & Tracking</a></li>
		</ul>
	</div>
	<div class="col-md-3">
		&nbsp;
	</div>
</div>
@section('pageJS')
<script type="text/javascript">
	var _old = false;
	function check(obj){
		$.ajax({
			url:'{{ URL }}/user/check-password',
			type:"POST",
			data:{
				order:$(obj).val()
			},
			success:function(data){
				$("#error_old").html(data.message);
				if(data.status=='ok') _old = true;
				else _old = false;
			}
		});
	}
	function changepassword(){
		var _new = $("#new_password").val();
		if(_old && _new !='' && _new.length >=6){

			$.ajax({
				url:'{{ URL }}/user/update-password',
				type:"POST",
				data:{
					order:_new
				},
				success:function(data){
					$("#new_old").html(data.message);
					if(data.status=='ok'){
						$("#new_password").val('');
						$("#old_password").val('');
					}
				}
			});		
		}else{
			if(_new ==''){
				alert('New password cannot be empty');
				$("#new_password").trigger('focus');
			}else{
				if(_new.length<6){
					alert('New password to short. Please try again');
					$("#new_password").trigger('focus');
				}else{
					alert('Please fill old password with correct information');
					$("#old_password").trigger('focus');
				}				
			}			
		}
	}	
</script>
@stop
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

</style>

