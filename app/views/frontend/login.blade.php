@section('body')
<body class="page-the-profound-aesthetic-academy customer-logged-in template-page">
@stop
<div class="breadcrumbs">
	<img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
	<ul class="unstyled-list">
		<li><a href="{{ URL }}">Home</a></li>
		<li><a href="javascript:void(0)">User</a></li>
		<li><a href="{{ URL }}/user/login">Login</a></li>
	</ul>
</div>
<div class="account-content">
	<div class="col-md-6 col-md-offset-3">
		<form action="{{ URL }}/user/login" method="post" accept-charset="utf-8" class="form-group">
			<?php
				$login = Session::has('_old_input')?Session::get('_old_input'):array();
			?>
			<div id="login_div" style="{{ Session::has('forgot')?'display:none':'' }}">
				   
					<div class="form-group">
						<label for="email" class="control-label">Username or Email:</label>
						<input type="text" name="email" class="form-control" id="email" value="{{ isset($login['email']) ? $login['email'] : '' }}" placeholder="Your email" autocomplete="off"/>
					</div>
					<div class="form-group">
						<label for="password" class="control-label">Password:</label>
						<input type="password" class="form-control" name="password" id="password" value="{{ isset($login['password']) ? $login['password'] : '' }}" placeholder="" autocomplete="off"/>
					</div>
				   
				   <input type="hidden" name="redirect_url" value="<?php
						if(Input::has('redirect_url')){
							echo Input::get('redirect_url');
						}else{
							if(isset($login['redirect_url'])){
								echo $login['redirect_url'];
							}else{
								echo '';
							}
						}
				   ?>">
				   <label for="remember"  class="control-label">
				   <input type="checkbox" id="remember" name="remember" {{ isset($login['remember']) ? 'checked' : '' }} value="1"/> Remember</label>
				   <p><a href="javascript:forgot_password();" title="Forgot your password">Forgot your password?</a></p>
				   <p class="text-center"><button type="submit" class="btn btn-4 btn-block">Login</button></p>
				   <p class="text-center">&nbsp; or &nbsp; </p>
				   <p class="text-center"><a href="{{ URL }}/user/signup" title="Register a new account">Register a new account</a></p>
				   <p class="text-center"><button onclick="checkLoginState()" type="button" class="btn btn-4 btn-block"><i class="fa fa-facebook-square"></i>&nbsp;&nbsp;Login with Facebook</button></p>
			</div>
		</form>
		<form action="{{ URL }}/user/forgot_password" method="post" accept-charset="utf-8">
			<div id="forgot_pass_div" style="{{ Session::has('forgot')?'':'display:none' }}">
				<label for="email" class="label">Username or Email</label>
					<input type="email" name="email" id="email_forgot" value="" placeholder="Your email" autocomplete="off" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" />
					<button type="submit" id="btn_forgot_password">Submit</button> &nbsp; or &nbsp; <a href="javascript:backto_login();" title="Back to login">Back to login</a>
			</div>
		</form>
			<div class="error">
						{{ Session::has('error')?Session::pull('error'):'' }}
			</div>
		
	</div>
</div>
@section('pageJS')
	<script>
							   function forgot_password(){
									$("#login_div").hide(50);
									$("#forgot_pass_div").show(50);
									$(".error").fadeOut(1000);
							   }

							   function backto_login(){
									$("#login_div").show(50);
									$("#forgot_pass_div").hide(50);
									$(".error").fadeOut(1000);
							   }
	function detectPopupBlocker() {
		var test = window.open(null,"","width=100,height=100");
		try {
			test.close();			
		} catch (e) {
			// window.location = "123.xml";
		}
	}
	function addLoadEvent(func) {
		var oldonload = window.onload;
		if (typeof window.onload != 'function') {
			window.onload = func;
		} else {
			window.onload = function() {
				if (oldonload) {
					oldonload();
				}
				func();
			}
		}
	}
	// addLoadEvent(detectPopupBlocker);
	</script>
@stop
<style type="text/css">
	@media (max-width: 900px) {
	
		#mobile_menu_left{
			display: none !important;
		}
		
}
</style>

