<form action="{{URL}}/cart" method="post" class="custom">
    <div class="row">
    	<h2>Order #{{ $order['id'] }} created at {{date('M d, Y h:i:s',strtotime($order['created_at']))}}</h2>
    	<a style="float:right;margin-right:10px;" href="{{URL}}/orders">View all order</a>
        <div class="columns">
            <table width="100%" id="cart-table">
                <thead>
                    <tr>
                    	<th>Name</th>
                        <th class="image">Preview</th>
                        <th>Option</th>
                        <th class="number">Quantity</th>
                        <th class="number">Sub total</th>
                        <th class="number">Tax</th>
                        <th class="number">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order['order_details'] as $detail)
                    <tr>
                        <td><a href="{{ $detail['option']['url'] }}" target="_blank">{{ $detail['option']['name'] }}</a></td>
                    	<td class="image">
                            <img class="item-cart-image" style="height: 110px;" src="{{ URL.$detail['option']['image'] }}" />
                        </td>
                        <td>{{
                            ( isset($detail['option']['size']) && !empty($detail['option']['size']) ? '<b>'.$detail['option']['size'].'</b>, ' : '' ).
                            ( isset($detail['option']['bleed_title']) && !empty($detail['option']['bleed_title']) ? '<b>'.$detail['option']['bleed_title'].'</b>" depth, ' : '' ).
                            ( isset($detail['option']['border_title']) && !empty($detail['option']['border_title']) ? '<b>'.$detail['option']['border_title'].'</b>" border, ' : '' ).
                            ( isset($detail['option']['wrap_title']) && !empty($detail['option']['wrap_title']) ? '<b>'.$detail['option']['wrap_title'].'</b> wrap ' : '' )
                        }}</td>
                        <td class="number">{{ number_format($detail['quantity']) }}</td>
                        <td class="number">$ {{ Product::viFormat($detail['sum_sub_total']) }}</td>
                        <td class="number">$ {{ Product::viFormat($detail['sum_tax'])}}</td>
                        <td class="number">$ {{ Product::viFormat($detail['sum_amount'] )}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-right" style="font-size: 1.2em;font-weight: bold;padding-right: 50px;">Totals</td>
                        <td class="number">$ {{ Product::viFormat($order['sum_sub_total']) }}</td>
                        <td class="number">$ {{ Product::viFormat($order['sum_tax']) }}</td>
                        <td class="number">$ {{ Product::viFormat($order['sum_amount']) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</form>

<article class="row" >
        <!-- .right-column -->
    @foreach( ['left' => 'billing', 'right' => 'shipping'] as $position => $address )
    <div class="left-column {{ $position }} columns large-5">
        <h1 class="page-title" itemprop="name">{{ ucfirst($address) }} information</h1>
        <div class="quanity-cart-row clearfix">
           <div class="variants vifull">
                <div class="selector-wrapper">
                    <label for="shipping_first_name">First name</label>
                    <input readonly type="text" value="{{ isset($order[$address.'_address']['first_name']) ? $order[$address.'_address']['first_name'] : '' }}" />
                </div>
                <div class="selector-wrapper">
                    <label for="shipping_last_name">Last name</label>
                    <input readonly type="text" value="{{ isset($order[$address.'_address']['last_name']) ? $order[$address.'_address']['last_name'] : '' }}" />
                </div>
                <div class="selector-wrapper">
                    <label for="shipping_address">Address</label>
                    <input readonly type="text" value="{{ isset($order[$address.'_address']['address1']) ? $order[$address.'_address']['address1'] : '' }}" />
                </div>
                <div class="selector-wrapper">
                    <label for="shipping_city">City</label>
                    <input readonly type="text" value="{{ isset($order[$address.'_address']['city']) ? $order[$address.'_address']['city'] : ''  }}" />
                </div>
                <div class="selector-wrapper">
                    <label for="address_country">Country</label>
                    <input readonly type="text" value="{{ isset($order[$address.'_address']['country']) ? $order[$address.'_address']['country'] : '' }}" />
                </div>
                <div class="selector-wrapper">
                    <label for="shipping_province">Province / State</label>
                    <input readonly type="text" value="{{ isset($order[$address.'_address']['province']) ? $order[$address.'_address']['province'] : '' }}" />
                </div>
           </div>
        </div>
    </div>
    @endforeach
</article>

@section('pageCSS')
<style type="text/css">
#cart-table .number {
    text-align: right;
}
#cart-table .image {
    text-align: center;
}
</style>
@stop