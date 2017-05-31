@section('body')
<body class="page-the-profound-aesthetic-academy customer-logged-in template-page">
@stop
<header class="row">
    <div class="left columns large-6">
        <h1 class="page-title">Sign up</h1>
    </div>
    <div class="row show-for-medium-up">
        <div class="columns">
            <ul class="breadcrumbs colored-links">
                <li><a href="{{ URL }}">Home</a></li>
                <li><a href="javascript:void(0)">User</a></li>
                <li><a href="{{ URL }}/user/signup">Sign up</a></li>
            </ul>
        </div>
    </div>
</header>
<div class="row account-content">
	<div class="large-4 columns">
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
		          <button type="submit" class="btn btn-primary">Sign up</button>&nbsp; or &nbsp; <a href="{{ URL }}/user/login"  title="Back to login">Back to login</a>
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
