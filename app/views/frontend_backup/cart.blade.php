
@section('body')
<body class="page-your-shopping-cart template-cart">
    @stop
@section('content')
<section class="main-content">
    <header class="row">
        <div class="columns">
            <h1 class="page-title">Your Cart</h1>
        </div>
    </header>
    @if(Session::has('order_message'))
    <section class="empty-cart row colored-links">
        <div class="columns">
            <h1>{{Session::get('order_message')}}</h1>
        </div>
        <script type="text/javascript">
            setTimeout(function(){
                location.reload();
            },3000);
        </script>
    </section>
    @elseif(Session::has('order_confirm'))
    <section class="empty-cart row colored-links">
        <div class="columns">
            <h1>{{Session::get('order_confirm')}}</h1>
            <h2>You can continue browsing <a href="{{URL}}/collections/">here</a>.</h2>
        </div>
    </section>
    @elseif(empty($carts))
    <section class="empty-cart row colored-links">
        @if(Session::has('order_success'))
        <div class="columns">
            <h1>{{Session::get('order_success')}}</h1>
            <h2>You can continue browsing <a href="{{URL}}/collections/">here</a>.</h2>
        </div>
        @else
        <div class="columns">
            <h1>It appears that your cart is currently empty!</h1>
            <h2>You can continue browsing <a href="{{URL}}/collections/">here</a>.</h2>
        </div>
        @endif
    </section>
    @else
    @if(Session::has('order_message'))
    <section class="empty-cart row colored-links">
        <div class="columns">
            <h1>{{Session::get('order_message')}}</h1>
        </div>
    </section>
    @endif
    <form action="{{URL}}/cart" method="post" class="custom">
        <div class="row">
            <div class="columns">
                <table width="100%" class="cart-table">
                    <thead>
                        <tr>
                            <th class="image">&nbsp;</th>
                            <th class="title">&nbsp;</th>
                            <th class="quantity">Quantity</th>
                            <th class="total">Total</th>
                            <th class="remove">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carts as $rowId=>$cart)
                        <tr>
                            <td class="image">
                                <a class="image_product" href="{{ substr($cart['url'], 0, strrpos($cart['url'], '/')).'/'.$cart['type_design'].'/'.substr($cart['url'], strrpos($cart['url'], '/')+1).'?cart_id='.$rowId }}">
                                <img class="item-cart-image"  style="max-width: 400px;max-height: 150px;" src="{{$cart['image']}}" alt="{{$cart['title']}}">
                                </a>
                                <p class="mobile-title"><a href="{{$cart['url']}}">{{$cart['title']}}</a></p>
                            </td>
                            <td class="title">
                                <p><a href="{{$cart['url']}}" style="line-height: 20px;">{{$cart['title']}}</a></p>
                            </td>
                            <td class="quantity"><input type="text" class="field styled-input" name="quantity[{{$rowId}}]" id="{{$rowId}}" value="{{$cart['quantity']}}"></td>
                            <td class="total">$ {{  Product::viFormat($cart['subtotal']) }}</td>
                            <td class="remove"><a title="Remove" href="{{URL}}/cart/delete-cart/{{$rowId}}" aria-hidden="true" class="glyph cross"></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- .row -->
        <div class="row">
            <div class="columns large-5 show-for-medium-up">
                <div class="continue-shopping show-for-medium-up">
                    <span><a href="{{URL}}/collections/"><span aria-hidden="true" class="glyph arrow-left"></span> Continue Shopping</a></span>
                </div>
            </div>
            <div class="columns large-7">
                <div class="totals columns">
                    <input type="submit" name="update" class="button" value="Update cart">
                    <input type="button" name="update" class="button" onclick="window.location.replace('{{URL}}/checkout')" value="Add to order">
                    <!-- <p class="additional-checkout-buttons">
                        <img id="paypal_express" style="cursor: pointer;" src="https://www.paypal.com/en_US/i/btn/btn_xpressCheckoutsm.gif">
                        <script type="text/javascript">
                            $("#paypal_express").click(function(){
                                location.assign("{{URL}}/checkout");
                            })
                        </script>
                    </p> -->
                </div>
            </div>
            <div class="continue-shopping columns show-for-small columns">
                <span><a onclick="{{URL}}/collections/"><span aria-hidden="true" class="glyph arrow-left"></span> Continue Shopping</a></span>
            </div>
        </div>
    </form>
        <!-- .row -->
    @section('pageJS')
    <script type="text/javascript">
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
                            window.location = '{{URL}}/cart';
                        }else{
                            alert(data.data);
                        }
                    }
                });
            }
        }
    </script>
    @stop
    @endif
</section>
@stop