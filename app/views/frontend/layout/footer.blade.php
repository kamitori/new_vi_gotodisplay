{{ $footerMenu or '' }}
@if(isset(Auth::user()->get()->id))
<div class="col-xs-12 col-sm-12 col-md-2">
	<b class="title">My account</b>
	<ul class="list-unstyled" role="navigation">
		<li><a href="{{ URL }}/orders">Your order</a></li>
		<li>Shipping history</li>
		<li><a href="{{ URL }}/user">Your profile</a></li>
		<li>Your collection</li>
	</ul>
</div>
@else
<div class="col-xs-12 col-sm-12 col-md-2">
	<b class="title">Account</b>
	<ul class="list-unstyled" role="navigation">
		<li><a href="{{ URL }}/user/login">Login</a></li>
		<li><a href="{{ URL }}/user/signup">Signup</a></li>
	</ul>
</div>
@endif
<div class="col-xs-12 col-sm-12 col-md-4">
	<b class="title">Offers & promotions</b>
	<div class="content subscribe_box" id="mailing-list-module">
		<p>Subscribe to our newsletter to receive the latest news and offers from gotoDisplay</p>
		<form accept-charset="UTF-8" action="javascript:void(0)" class="contact-form" method="post">
			<p class="success feedback"></p>
			<p class="error feedback"></p>
			<!-- <input type="input" id="newsletter-first-name" name="contact[firstname]" value="Subscriber">
			<input type="input" id="newsletter-last-name" name="contact[lastname]" value="Newsletter"> -->
			<div class="row">
				<div class="col-xs-8" style="padding-right: 10px;">
					<input type="email" placeholder="Your Email" name="email_address" id="email_address" class="form-control">
				</div>
				<div class="col-xs-4">
					<input type="submit" class="btn btn-2 btn-white" value="Subscribe" name="subscribe" id="email-submit">
				</div>
			</div>
		</form>
	</div>
</div>
<div class="col-md-12 text-right" style="z-index:999;clear:both">
	<img src="{{url()}}/assets/vitheme/images/pay.png" alt="" class="full-height">
</div>
<script async defer src="//assets.pinterest.com/js/pinit.js"></script>

