<header class="main-header">
    <div class="bg"></div>
    <div class="row top">
        <!-- Logo -->
        <div class="large-2 columns">
            <h1 class="title clearfix" role="banner">
                <a href="{{ URL }}" role="banner" title="Visual Impact">
                <?php $logoSrc = isset($metaInfo['main_logo']) ? URL::asset($metaInfo['main_logo']) : '';  ?>
                <img src="{{ $logoSrc }}" data-retina="{{ $logoSrc }}" alt="{{ $metaInfo['title_site'] }}" style="margin-top:10%;width:100%;" />
                </a>
            </h1>
        </div>
        <div class="large-8 columns">
            <div class="bottom-row">
                <div class="row">
                    <div class="columns menu-container">
                        <!--============= MAIN MENU ================-->
                        <div class="main-menu" style="text-align:center !important;">
                            <nav role="navigation" class="widescreen clearfix" >
                                <ul class="inline-list font-nav" style="display:inline-block; padding:0;">
                                    {{ $headerMenu['default'] or '' }}
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="mobile-tools">
                        <a class="glyph menu" href=""></a>
                        <a href="{{ URL }}/search" class="glyph search"></a>
                        <a href="{{ URL }}/account" class="glyph account"></a>
                        <a href="{{ URL }}/cart" class="glyph cart"></a>
                    </div>
                </div>
            </div>
            <div class="main-menu-dropdown-panel">
                <div class="row"></div>
            </div>
            <!--============= MAIN MENU ================-->
            <div class="mobile-menu">
                <nav role="navigation">
                    <ul class="font-nav">
                        {{ $headerMenu['mobile'] or '' }}
                    </ul>
                </nav>
            </div>
            <div class="row">
                <div class="header-divider"></div>
            </div>
        </div>
        <div class="large-2 columns">
            <div class="search-account ">
                <!-- TOP LEFT -->
                <div class="inline-list font-nav" role="navigation" style="float:left;margin-left: 1px">
                </div>
                <!-- TOP RIGHT -->
                <div class="menu">
                    <a class="searchbar-open" href="#">
                    Search			                            	<span aria-hidden="true" class="glyph search"></span>
                    </a>
                    <a class="account-open" href="#">
                    Account			                            	<span aria-hidden="true" class="glyph account"></span>
                    </a>
                </div>
                <div class="searchbar-container">
                    <form action="javascript:void(0)"  method="get" role="search">
                        <input name="query" id="query_search_product" type="text" placeholder="Search store..." class="search-box hint text">
                        <button type="submit" class="glyph search" id="btn_search_product"></button>
                    </form>
                    <a href="{{ URL }}#" aria-hidden="true" class="searchbar-close glyph cross"></a>
                </div>
                <div class="account-container">

                    @if(isset(Auth::user()->get()->id))
                        <a href="{{ URL }}/user/addresses">View Account </a>
                        <span class="separator">|</span>
                        <a href="{{ URL }}/user/logout" id="customer_logout_link" style="margin-right: 5px;">Logout</a>
                        <a href="#" aria-hidden="true" class="account-close glyph cross"  style="margin-right: -3px;"></a>
                    @else
                        <a href="{{ URL }}/user/login" id="customer_login_link">Log in</a> or <a href="{{ URL }}/user/signup" id="customer_register_link" style="margin-right: 5px;">Sign Up</a>
                        <a href="{{ URL }}#" aria-hidden="true" class="account-close glyph cross" style="margin-right: -3px;"></a>
                    @endif
                </div>
            </div>
            <!-- Cart -->

            <div class=" cart-container">
                <div class="cart">
                    <a class="cart-link" href="{{ URL }}/cart">
                    Cart&nbsp;
                    <span class="number">
                        {{ $cartQuantity ? number_format($cartQuantity) : '' }}
                    </span>
                    <span aria-hidden="true" class="glyph cart"></span>
                    </a>
                    <div class="recently-added">
                        <table width="100%">
                            <thead>
                                <tr>
                                    <td colspan="3">Recently Added</td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr>
                                    <td class="items-count">
                                        <a href="{{ URL }}/cart"><span class="number">{{ $cartQuantity ? number_format($cartQuantity) : '' }}</span> Items</a>
                                    </td>
                                    {{-- <td colspan="2" class="text-right">
                                        <strong>TOTAL <span class="total-price" style="margin-left:5px;">$ 0.00</span></strong>
                                    </td> --}}
                                </tr>
                            </tfoot>
                        </table>
                        <div class="row">
                            <div class="checkout columns">
                                <a class="button" href="{{ URL }}/cart">Go to Checkout</a>
                            </div>
                            <!-- #cart-meta -->
                        </div>
                        <div class="error">
                            <p>The item you just added is unavailable. Please select another product or variant.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #end cart -->
        </div>
    </div>
</header>
@section('pageJS')
	<script>
		$(function(){
			$("#btn_search_product").bind("click",function(){
				key = $("#query_search_product").val().trim();
				while(key.indexOf("  ") > 0){
					key = key.replace("  "," ");
				}
				key = key.replace(" ",'+');
				window.location = '/search/'+key;
			})
		});
	</script>
@append