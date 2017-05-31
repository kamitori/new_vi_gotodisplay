@section('body')
<body class="page-the-profound-aesthetic-academy customer-logged-in template-page">
@stop
<header class="row">
    <div class="left columns large-6">
        <h1 class="page-title">Reset password</h1>
    </div>
</header>
<div class="row account-content">
	<div class="large-4 columns">
			<?php
				$login = Session::has('_old_input')?Session::get('_old_input'):array();
			?>
			<form method="POST" action="{{ URL }}/password/reset" accept-charset="UTF-8">
				<div class="form-group">
					<label for   ="email">Email</label>
					<input class ="form-control" placeholder="Email" type="text" name="email" id="email" value="{{ isset($login['email']) ? $login['email'] : '' }}" autocomplete="off">
			           </div>
				<div class="form-group">
					<label for="email">New password</label>
					<input class="form-control" placeholder="New password" type="password" name="password" id="password" value="" autocomplete="off">
				</div>
				<div class="form-group">
					<label for="email">Confirm new password</label>
					<input class="form-control" placeholder="Confirm new password" type="password" name="password_confirmation" id="password_confirmation" value="" autocomplete="off">
				</div>
				<input type="hidden" name="token" value="{{$token}}">
				<div class="form-actions form-group">
					<button type="submit" class="btn btn-primary">Update</button>
				</div>
			</form>
			<div class="error">
			       		{{ Session::has('error')?Session::pull('error'):'' }}
			</div>
	</div>
</div>