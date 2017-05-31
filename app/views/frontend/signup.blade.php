@section('body')
<body class="page-the-profound-aesthetic-academy customer-logged-in template-page">
@stop
<div class="breadcrumbs">
	<img src="/assets/vitheme/images/anvy.png" alt="" class="icon-breadcrumbs">
	<ul class="unstyled-list">
		<li><a href="{{ URL }}">Home</a></li>
		<li><a href="javascript:void(0)">User</a></li>
		<li><a href="{{ URL }}/user/signup">Sign up</a></li>
	</ul>
</div>
<div class="account-content">
	<div class="col-md-6 col-md-offset-3">
		<form method="POST" action="{{ URL }}/user/signup" accept-charset="UTF-8">
		    <input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
		<?php
			$signup = Session::has('_old_input')?Session::get('_old_input'):array();
		?>
		        <div class="form-group">
		            <label for="email">Email</label>
		            <input class="form-control" placeholder="Email" type="text" name="email" id="email" value="{{ isset($signup['email'])?$signup['email']:'' }}" autocomplete="off">
		        </div>
		        <div class="form-group">
		            <label for="email">Password</label>
		            <input class="form-control" placeholder="Password" type="password" name="password" id="password" value="" autocomplete="off">
		        </div>
		        <div class="form-group">
		            <label for="email">Confirm password</label>
		            <input class="form-control" placeholder="Confirm password" type="password" name="password_confirmation" id="password_confirmation" value="" autocomplete="off">
		        </div>
		        
		        <div class="form-actions form-group">
					<p class="text-center"><button type ="submit" class="btn btn-4 btn-block">Sign up</button></p>
					<p class="text-center">&nbsp; or &nbsp;</p>
					<p class="text-center"><a href      ="{{ URL }}/user/login"  title="Back to login">Back to login</a></p>
		        </div>
			<div class="error">
			<br/>
		            	@if(Session::has('error'))
					@foreach(Session::get('error') as $key => $error)
						- {{ $error }}<br />
					@endforeach
		            	@endif
		            </div>

		</form>
	</div>
</div>
<style type="text/css">
	@media (max-width: 900px) {
	
		#mobile_menu_left{
			display: none !important;
		}
	section.main-content{
		margin-top: 2px;
		margin-bottom: 5px;
	}

}
</style>
