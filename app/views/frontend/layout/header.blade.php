<header id="header">
	<div class="container">
		<div id="navbar" class="navbar-collapse">
			<ul class="nav navbar-nav" id="logo_menu">
				<li class="nav-item ">
					<a class="nav-item-link c-button" href="javascript:void(0)" title="Home" id="menu_list">
						<i class="fa fa-list"></i>
					</a>
				</li>
			</ul>
			<a class="navbar-brand" href="{{ URL }}" role="banner" title="Visual Impact">
					<?php $logoSrc = isset($metaInfo['main_logo']) ? URL::asset($metaInfo['main_logo']) : '';  ?>
				<img id="logo" src="{{ $logoSrc }}" data-retina="{{ $logoSrc }}" alt="{{ $metaInfo['title_site'] }}"/>
			</a>
			<ul class="nav navbar-nav navbar-right " id="logo_icon">
				<li class="searchbar-container" style="display:none;">
					<form action="javascript:void(0)"  method="get" role="search">	
						<input name="query" id="query_search_product" type="text" placeholder="Search store..." class="search-box hint text form-control">
						<button type="button" class="glyph search btn btn-1 btn-white" id="btn_search_product"><i class="fa fa-search"></i></button>
					</form>
				</li>
				<li class="account-container " style="display:none;">

					@if(isset(Auth::user()->get()->id))
						<a href="{{ URL }}/user/profile">View Account </a>
						<span class="separator">|</span>
						<a href="{{ URL }}/user/logout" id="customer_logout_link" style="margin-right: 5px;">Logout</a>
						<i class="fa fa-close account-close"></i>
					@else
						<a href="{{ URL }}/user/login" id="customer_login_link">Log in</a> or <a href="{{ URL }}/user/signup" id="customer_register_link" style="margin-right: 5px;">Sign Up</a>
						<!-- <i class="fa fa-close account-close"></i> -->
					@endif
				</li>
				<li>
					<a class="searchbar-open" href="#"><i class="fa fa-search"></i></a>
				</li>
				<li>
					<a href="{{URL}}/cart">
						<i class="fa fa-shopping-cart"></i>
					</a>
				</li>
				
				<li>
					<a href="{{URL}}/user/your-collection">
						<i class="fa fa-heart-o"></i>
					</a>
				</li>
				
				<li>
					<a href="#" class="account-open">
						<i class="fa fa-user"></i>
					</a>
				</li>
			</ul>
		</div>
		<nav id="c-menu--slide-left" class="c-menu c-menu--slide-left">
			<button class="c-menu__close">&larr; Close Menu</button>
			<ul class="c-menu__items list-unstyled nav nav-list">
				{{ $headerMenu['mobile'] or '' }}
			</ul>
		</nav><!-- /c-menu slide-left -->
		<div id="c-mask" class="c-mask"></div>
		<!-- <div id="banner" class="carousel slide mobile-hide" data-ride="carousel"> -->
		<div id="banner">
			<div id="banner-marquee" class="marqueeWrapper col-md-12 col-xs-12 col-sm-12">
                  <ul id="marquee">                    
                  </ul>
              </div>
		</div>
	</div>
</header>
<div id="mobile_menu_left">
	<div class="item bg-1"><a href="{{URL}}/user/shipping-tracking" class="upper-text">Shipping &amp; return</a></div>
	<div class="item bg-4"><a href="{{URL}}/user/profile" class="upper-text">Your Profile</a></div>
	<div class="item bg-2"><a href="{{URL}}/orders" class="upper-text">Your order</a></div>
	<div class="item bg-5"><a href="{{URL}}/user/your-gallery" class="upper-text">Your gallery</a></div>
</div>