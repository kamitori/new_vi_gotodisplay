<?php

class CartController extends BaseController { //HomeController {

	static $_discount_price = 0;
	/*public function __construct()
	{
		parent::__construct();
	}*/


	

	public static function getShippingMethod()
	{
		$arr_shipping_price = array();
		$shippings = ShipPrice::select(
									   'ship_price.*',
									   'country.name AS country','country.id AS country_id',
									   'ship_price_detail.id AS detail_id','ship_price_detail.shipping_price','ship_price_detail.shipping_method')
									->where('ship_price.active','=',1)
									->leftJoin('country', 'ship_price.country_id', '=', 'country.id')
									->leftJoin('ship_price_detail', 'ship_price.id', '=', 'ship_price_detail.ship_price_id')
									->get();
		foreach($shippings as $ship){
			$arr_shipping_price[$ship['id']]['country'] = $ship->country;
			$arr_shipping_price[$ship['id']]['country_id'] = $ship->country_id;
			$arr_shipping_price[$ship['id']]['shipping_price'][$ship->detail_id] = array(
																					'shipping_method' => $ship->shipping_method,
																					'shipping_price' => number_format($ship->shipping_price,2),
													   );
		}
		return $arr_shipping_price;
	}

	public function cart() {
		if(Input::has('update'))
			self::updateCartOrder();
		else if(Input::has('checkout'))
			return self::updateCartOrder();
		else if(Input::has('paypal_express')){
			$cart = self::getCart(true);
			if(!empty($cart['items']))
				return PaypalController::doPaypalExpressPayment( $cart);
		} else if(Input::has('token') && Input::has('PayerID') )
			return PaypalController::paymentSuccess();
		$carts = array();
		$all_product = Cart::content();

		$quantity = 0;
		foreach($all_product as $key=>$product){
			if($product->id == 'promo_code') continue;
			if($product->id == 'shipping_method') continue;
			$carts[$key] = array(
									'title'=>$product->name,
									'image'=>$product->options->image,
									'price'=>$product->price,
									'url'   =>$product->options->url,
									'quantity'=>(int)$product->qty,
									'subtotal'=>$product->subtotal,
									'type_design'=>$product->options->type_design,
								);
			$quantity += (int)$product->qty;
		}
		$discount = 0;
		$promo_code = '';
		$is_shipping_discount = 0;
		$ship_price = -1;
		//$address = array();
		 $row_id = Cart::search(array('id'=>'promo_code'));
		if($row_id){
			$discount = Cart::get($row_id[0])->options->discount;
			$promo_code = Cart::get($row_id[0])->options->promo_code;
			$is_shipping_discount = Cart::get($row_id[0])->options->is_shipping_discount;
		}
		/*$row_id = Cart::search(array('id'=>'address'));
		if($row_id){
			$ship_price = Cart::get($row_id[0])->options->price;
			$address = Cart::get($row_id[0])->options->address;
		}*/
		$this->layout->content = View::make('frontend.cart')
						->with(array(
							   'carts'   => $carts,
							   // 'countries'   => $countries,
							   'discount'   => $discount,
							   'promo_code'   => $promo_code,
							   'is_shipping_discount'   => $is_shipping_discount,
							   'ship_price'   => $ship_price,
							   //'arr_shipping_price'=>self::getShippingMethod(),
							   // 'address'   => $address,
							   'quantity'   => $quantity,
							   'total_price'    => self::calculatePriceCart()
							   ));
	}

	public function add(){
		// Cart::destroy();
		$input = Input::all();
		$product = Product::select('name', 'id', 'sku', 'sell_price', 'jt_id')
					->where('id','=',Input::get('id'))
					->where('active','=',1)
					->first();
		$options = array();
		$rowId = '';
		foreach(array('color','material') as $property){
			if(Input::has($property)){
			   $property_data = ProductPropertyController::getPropertyById((int)Input::get($property));
			   $options[$property] = $property_data['name'];
			   $rowId .= Input::get($property);
		   }
		}
		$arr_input = array();
		
		
		$arr_input['quantity'] = (int)Input::get('quantity');
		$arr_input['product_id'] = Input::get('id');

		if(Input::has('sizes')){
			$sizes = Input::get('sizes');
			$sizes = explode("x",$sizes);
			$options['size'] = $sizes[0].'"x'.$sizes[1].'"';
			$arr_input['width'] = (float)$sizes[0];
			$arr_input['height'] = (float)$sizes[1];
		}
		if(Input::has('bleed')){
			$bleed = Input::get('bleed');
			$options['bleed'] = $bleed;
			$arr_input['bleed'] = $bleed;
		}
		if(Input::has('bleed_title')){
			$options['bleed_title'] = Input::get('bleed_title');
		}

		if(Input::has('border')){
			$border = Input::get('border');
			$options['border'] = $border;
		}
		if(Input::has('border_title')){
			$options['border_title'] = Input::get('border_title');
		}

		if(Input::has('wrap')){
			$wrap = Input::get('wrap');
			$options['wrap'] = $wrap;
		}
		if(Input::has('wrap_title')){
			$options['wrap_title'] = Input::get('wrap_title');
		}
		if(Input::has('origin_image')){
			$options['origin_image'] = Input::get('origin_image');
		}
		$options['name'] = $product->name;
		$options['type'] = Input::has('type')?Input::get('type'):'';
		$options['image'] = Request::root().Input::get('img_link');
		$options['url'] = Input::get('url');
		$product_name = $product->name;
		$product_name .='<br/>'.(isset($options['size']) ? ' '.$options['size']: '').(isset($options['color']) ? ' / ' .$options['color'] : '');
		if( isset($option['wrap']) ) {
			$product_name .= ' - '.$option['wrap'];
		}

		if( isset($option['bleed']) ) {
			$product_name .= ' - '.$option['bleed'].'" thickness)';
		}

		if( isset($option['border']) ) {
			$product_name .= ' - '.$option['border'].'" border)';
		}
		// pr(Input::has('price'));die;
		// here 1
		if(Input::has('price'))
			$sell_price = Input::get('price');
		else
			$sell_price = $product->sell_price;

		if($sell_price=='custom'){
			$sell_price = 0;
			$product_des = '<br /><i>Custom price</i>';
		}

		$cart_id = md5($product->id.$rowId.$product_name.$options['image']);

		$user_ip = Session::has('user_ip')?Session::get('user_ip'):User::getFolderKey();
		$path = public_path().DS.'assets'.DS.'upload'.DS.'themes'.DS.$user_ip;
		$svg_path = $path.DS.'orders'.DS.'svg_'.$product->id.'_'.$cart_id.'.svg';

		$image_link = Input::get('img_link');
		$image_path = public_path().DS.str_replace('/', DS, $image_link );
		if( !Cart::get($cart_id) ) {
			if( !file_exists($path.DS.'orders') ) {
				mkdir($path.DS.'orders', 0777);
			}
			if( Input::has('svg') ) {
				$svg = Input::get('svg');
				$svg = preg_replace("/NS[0-9]:/i",'',$svg);
				$svg = preg_replace("/:NS[0-9]/i",'',$svg);
				$IE = strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false
										|| strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false ? true : false;
				if( $IE ) {
					$svg1 = substr($svg, 0, strpos($svg, 'xmlns="http://www.w3.org/2000/svg"') );
					$svg2 = substr($svg, strpos($svg, 'xmlns="http://www.w3.org/2000/svg"') );
					$svg2 = preg_replace('/xmlns="http:\/\/www.w3.org\/2000\/svg"/', '', $svg2, 1 );
					$svg2 = preg_replace('/xmlns=""/', '', $svg2 );
					$svg = $svg1.$svg2;
				}
				$ip = Session::has('user_ip')?Session::get('user_ip'):User::getFolderKey();
				$path = public_path().DS.'assets'.DS.'upload'.DS.'themes'.DS.$ip;
				if( !File::exists($path) ) {
					File::makeDirectory($path, 0777, true);
				}
				file_put_contents($path.DS.'svg_'.$product->id.'.svg', $svg);
			}
			copy($path.DS.'svg_'.$product->id.'.svg', $svg_path);
			$image_link = Input::get('img_link');
			$new_image_path = str_replace('.png', '_'.$cart_id.'.png', $image_path);
			if( !file_exists($new_image_path) ) {
				copy($image_path, $new_image_path);
			}
			$options['image'] = str_replace('.png', '_'.$cart_id.'.png', $options['image']);
			$options['image'] = str_replace(URL,'', $options['image']);
			$options['image_path'] = $new_image_path;
		}
		$options['type_design'] = Input::get('type');
		$options['product_id'] = $product->id;
		$options['svg'] = '/assets/upload/themes/'.$user_ip.'/orders/svg_'.$product->id.'_'.$cart_id.'.svg';
		$options['jt_id'] = isset($product->jt_id)?$product->jt_id:'';
		$options['sku'] = isset($product->sku)?$product->sku:'';
		$cartRowId = Input::has('cart_id') ? Input::get('cart_id') : 0;
		if( $cartRowId && Cart::get($cartRowId) ) {
			
			Cart::update($cartRowId, ['name' => $product_name, 'qty' =>(int)Input::get('quantity'), 'price' => (float)$sell_price, 'options' => $options]);
			copy($path.DS.'svg_'.$product->id.'.svg', $svg_path);
			copy($image_path, $options['image_path']);
			$cart_id = $cartRowId;
		} else {
			Cart::add($cart_id, $product_name, (int)Input::get('quantity'), (float)$sell_price, $options);
			$cart_id = Cart::search(['id' => $cart_id])[0];
		}
		$arrSVGInfos = Session::has('svginfos') ? Session::get('svginfos') : [];
		$arrSVGInfos[$cart_id] = Input::get('svg_info');
		Session::put('svginfos', $arrSVGInfos);
		if($options['type'] =='')
			$total_price = self::calculatePriceCart();

		if (!Request::ajax())
			return Redirect::to('/cart');
		$arr_return = array(
							'title'=>$product_name,
							'image'=> $options['image'],
							'price'=> $sell_price,
							'url'   =>Input::get('url'),
							'quantity'=>(float)Input::get('quantity'),
							'token'=>$cart_id
							);
		$response = Response::json($arr_return);
		$response->header('Content-Type', 'application/json');
		return $response;
	}

	public static function getCart($isReturnArray = false)
	{
		$arr_return = array();
		$all_product = Cart::content();
		$quantity = 0;
		$arr_return['items'] = array();
		foreach($all_product as $row_id=>$product){
			if($product->id == 'promo_code') continue;
			if($product->id == 'shipping_method') continue;
			$arr_return['items'][] = array(
									'title'=>$product->name,
									'image'=>$product->options->image,
									'price'=>$product->price,
									'url'   =>$product->options->url,
									'quantity'=>(int)$product->qty
								);
			$quantity+= (int)$product->qty;
		}
		$arr_return['items'] = array_reverse($arr_return['items']);
		$arr_return['item_count'] = $quantity;
		$arr_return['note'] = '';
		$arr_return['requires_shipping'] = true;
		$arr_return['total_price'] = self::calculatePriceCart();
		if($isReturnArray)
			return $arr_return;
		$response = Response::json($arr_return);
		$response->header('Content-Type', 'application/json');
		return $response;
	}

	public function deleteCart($row_id)
	{
		if($row = Cart::get($row_id)) {
			if( file_exists($row->options->svg) ) {
				@unlink($row->options->svg);
			}
			if( file_exists($row->options->image_path) ) {
				@unlink($row->options->image_path);
			}
			Cart::remove($row_id);
			$arrSVGInfos = Session::get('svgInfos');
			if( isset($arrSVGInfos[$row_id]) ) {
				unset($arrSVGInfos[$row_id]);
			}
			Session::put('svgInfos', $arrSVGInfos);
		}
		$all_product = Cart::content();
		$i = 0;
		foreach($all_product as $product){
			if($product->id == 'promo_code') continue;
			if($product->id == 'shipping_method') continue;
			$i++;
		}
		if($i == 0 )
			Cart::destroy();
		return Redirect::to(URL.'/cart');
	}

	 public function deleteCartOrder(){
			$arr_return = array(
						"error" => 1,
						"data" =>'Error delete item'
						);
			if(Request::ajax() && Input::has('row_id')){
				$row_id = Input::get('row_id');
				if($row = Cart::get($row_id)){
					if( file_exists($row->options->svg) ) {
						@unlink($row->options->svg);
					}
					if( file_exists($row->options->image_path) ) {
						@unlink($row->options->image_path);
					}
					Cart::remove($row_id);
					$arr_return = array(
						"error" => 0,
						"data" =>''
						);
				}
				$all_product = Cart::content();
				$i = 0;
				foreach($all_product as $product){
					if($product->id == 'promo_code') continue;
					if($product->id == 'shipping_method') continue;
					$i++;
				}
				if($i == 0 ){
					Cart::destroy();
				}
			}
			$response = Response::json($arr_return);
			$response->header('Content-Type', 'application/json');
			return $response;
	}


	public static function updateCartOrder(){
		 $arr_return = array(
						"error" => 1,
						"data" =>'Error update item'
						);
			if(Request::ajax() && Input::has('row_id') && Input::has('quantity') ){
				$row_id = Input::get('row_id');
				$quantity = Input::get('quantity');
				if( $row = Cart::get($row_id) ){
					if( !$quantity ) {
						if( file_exists($row->options->svg) ) {
							@unlink($row->options->svg);
						}
						if( file_exists($row->options->image_path) ) {
							@unlink($row->options->image_path);
						}
					}
					Cart::update($row_id,(int)$quantity);
					$total_price = self::calculatePriceCart();
					$arr_return = array(
						"error" => 0,
						"data" =>''
						);
				}
			}
			$response = Response::json($arr_return);
			$response->header('Content-Type', 'application/json');
			return $response;
	}

	public static function getCartQuantity()
	{
		$all_product = Cart::content();
		$quantity = 0;
		foreach($all_product as $key=>$product){
			if($product->id == 'promo_code') continue;
			if($product->id == 'shipping_method') continue;
			$quantity += (int)$product->qty;
		}
		return $quantity;
	}
	public static function updateCart()
	{

		$arr_post = Input::all();
		if(isset($arr_post['quantity']) && !empty($arr_post['quantity'])){
			foreach($arr_post['quantity'] as $row_id => $quantity){
				if(Cart::get($row_id)){
					Cart::update($row_id,(int)$quantity);
				}
			}
		}
		if(isset($arr_post['checkout']))
			return Redirect::to('/checkout');
		View::share('cart_quantity',self::getCartQuantity());
	}

	public function changeShippingMethod()
	{
		$arr_return = array('status' => 'error', 'message' => 'Invalid Shipping Method');
		$id = Input::has("method") ? Input::get("method") : 0;
		if( $id ){
			$ship_price = ShipPriceDetail::select('ship_price_detail.*','ship_price.country_id')
											->where('ship_price_detail.id','=',$id)
											->leftJoin('ship_price','ship_price.id','=','ship_price_detail.ship_price_id')
											->get()
											->first();
			if( !is_null($ship_price) ){
				$ship_price->shipping_price = round( $ship_price->shipping_price ,2);
				$row_id = Cart::search(array('id'=>'shipping_method'));
				if($row_id)
					Cart::update($row_id[0],array('options'=>array(
														 'shipping_price'=>$ship_price->shipping_price,
														 'country_id'=>$ship_price->country_id,
														 'ship_price_detail_id'=>$ship_price->id,
														 'ship_price_id'=>$ship_price->ship_price_id)
												));
				else
					Cart::add('shipping_method','shipping_method',1,0,array(
																		'shipping_price'=>$ship_price->shipping_price,
																		'country_id'=>$ship_price->country_id,
																		'ship_price_detail_id'=>$ship_price->id,
																		'ship_price_id'=>$ship_price->ship_price_id
																		));
				$arr_return = array('status'=> 'ok', 'shipping_price'=> number_format($ship_price->shipping_price,2),'cost_value'=>self::getSubTotal($ship_price->shipping_price) + $ship_price->shipping_price,'cost'=> number_format( self::getSubTotal($ship_price->shipping_price) + $ship_price->shipping_price,2), 'discount_price' => number_format(self::$_discount_price,2));
			}
		} else {
			$row_id = Cart::search(array('id'=>'shipping_method'));
			if($row_id)
			   Cart::remove($row_id[0]);
		}
		$response = Response::json($arr_return);
		$response->header('Content-Type', 'application/json');
		return $response;
	}

	public function changeBillingProvince(){
		$arr_return = array();
		$id =  Input::has("province_id") ? Input::get("province_id") : 0;
		if($id){
			$city = City::where('id','=',$id)->get()->first();
			 if($city){
				$arr_return['status']='ok';
				$arr_return['tax_per']= $city->taxval;
			 }else{
				$arr_return['status']='error';
			 }
		}else{
				$arr_return['status']='error';
				$city = array();
		}
		$response = Response::json($arr_return);
		$response->header('Content-Type', 'application/json');
		return $response;
	}

	public function getPromoCode()
	{
		$arr_return = array();
		$arr_post = Input::all();
		$row_id = Cart::search(array('id'=>'promo_code'));
		if(!isset($arr_post['promo_code']) || strlen($arr_post['promo_code']) == 0){
			if($row_id)
				Cart::remove($row_id[0]);
			$message = 'This promo code can not be blank.';
			$arr_return['total_price_value'] = self::getShippingPrice() + self::calculatePriceCart();
			$arr_return['total_price'] = number_format(self::getShippingPrice() + self::calculatePriceCart(),2);
		}
		else{
			$voucher = Voucher::select('value','is_shipping_discount')
					->where('key','=',$arr_post['promo_code'])
					->where('active','=',1)
					->where('used','<>',1)
					->where('valid_from','<=',date('Y-m-d'))
					->where('valid_to','>=',date('Y-m-d'))
					->get()
					->first();
			if(is_null($voucher)){
				if($row_id)
					Cart::remove($row_id[0]);
				$message = 'This promo code is not existed or was used or was expired.';
				$arr_return['total_price_value'] = self::getShippingPrice() + self::calculatePriceCart();
				$arr_return['total_price'] = number_format(self::getShippingPrice() + self::calculatePriceCart(),2);
			}
			else{
				$message = 'Valid code!';
				$arr_return['discount'] = $voucher['value'].' %';
				if($voucher->is_shipping_discount){
					$arr_return['discount_price'] = round(self::getShippingPrice() * ($voucher['value']/100),2);
					$options = array('discount'=>$voucher['value'].'%','promo_code'=>$arr_post['promo_code'],'is_shipping_discount' => 1);
					$arr_return['is_shipping_discount'] = 1;
				} else {
					$arr_return['discount_price'] = round(self::calculatePriceCart() * ($voucher['value']/100),2);
					$options = array('discount'=>$voucher['value'].'%','promo_code'=>$arr_post['promo_code'],'is_shipping_discount' => 0);
				}
				$arr_return['total_price'] = self::getShippingPrice() + self::calculatePriceCart() - $arr_return['discount_price'];
				$arr_return['total_price_value'] = $arr_return['total_price'];
				$arr_return['total_price'] = number_format($arr_return['total_price'],2);
				$arr_return['discount_value'] = number_format($arr_return['discount_price'],2);
				$arr_return['discount_price'] = '- $ '.number_format($arr_return['discount_price'],2);

				if($row_id)
					Cart::update($row_id[0],array('options'=>$options));
				else
					Cart::add('promo_code','promo_code',1,0,$options);
			}
		}
		$arr_return['message'] = $message;
		$response = Response::json($arr_return);
		$response->header('Content-Type', 'application/json');
		return $response;
	}



	public function addDesign()
	{
		$design_id = Input::has('design_id') ? Input::get('design_id') : 0;
		$product_id = Input::has('product_id') ? Input::get('product_id') : 0;
		$quantity = Input::has('quantity') ? Input::get('quantity') : 1;
		$color_id = Input::has('color') &&  Input::get('color') != 'undefined' ? Input::get('color') : 0;
		$size_id = Input::has('size')  &&  Input::get('size') != 'undefined' ? Input::get('size') : 0;
		$material_id = Input::has('material') ? Input::get('material') : 0;
		$url = Input::has('url') ? Input::get('url') : '';
		if($product_id == 0 || $design_id == 0)
			return Redirect::to('/');
		$product = Product::select('name','short_name','id','material','color')
					->where('active','=',1)
					->where('id','=',$product_id)
					->first();

		if(is_null( $product))
			return Redirect::to('/');
		$api = new ImageStylor_Api(IMAGE_STYLOR_KEY);
		$design_info = $api->get_design_info($design_id);
		$design_image = Request::root().'/assets/upload/'.$product->main_image;
		if(isset($design_info['image']))
			$design_image = $design_info['image'];
		$options = array();
		$rowId = '';
		foreach(array('color','material') as $property){
			$var = $property.'_id';
			if($$var){
				if(strpos($product->$property, $$var)!==false){
				   $property_data = ProductPropertyController::getPropertyById($$var);
				   $options[$property] = $property_data['name'];
				   $rowId .= $$var;
				}
		   }
		}
		$product->sell_price = 0;
		if($size_id){
			$price = ProductOptionPrice::select('sell_price','sizew','sizeh')
								->where('id','=',(int)$size_id)
								->get()
								->first();
		   $product->sell_price = isset($price->sell_price) ? $price->sell_price : 0;
		   $options['size'] = $price->sizew. ' x '.$price->sizeh;
		}
		$options['image'] = $design_image;
		$rowId .= $options['image'];
		$options['url'] = base64_decode($url);
		$product_name = $product->name.' '.(isset($options['size']) ? ' - '.$options['size'] : '').(isset($options['color']) ? ' / '.$options['color'] : '');
		Cart::add(md5($product->id.$rowId), $product_name, $quantity, $product->sell_price,$options);
		return Redirect::to('/cart');
	}

	private static function getProductForCheckout()
	{
		$all_product = Cart::content();
		$arr_items = array();
		$i = $j = 0;
		foreach($all_product as $row_id=>$product){
			if($product->id == 'promo_code') continue;
			if($product->id == 'shipping_method') continue;
			$arr_items[$i][$j] = array(
									'title'=>$product->name,
									'image'=>$product->options->image,
									'options'=>$product->options,
									'price'=>$product->price,
									'quantity'=>(int)$product->qty
								);
			$j++;
			if($j != 0 && $j %2 == 0)
				$i++;
		}
		return $arr_items;
	}

	public static function getSubTotal($shipping_price = 0)
	{
		$total = self::calculatePriceCart();
		$row_id = Cart::search(array('id'=>'promo_code'));
		if($row_id){
			$discount = Cart::get($row_id[0])->options->discount;
			$is_shipping_discount = Cart::get($row_id[0])->options->is_shipping_discount;
			$discount = (float)str_replace('%','',$discount);
			if($is_shipping_discount)
				$discount = round($shipping_price * $discount / 100,2);
			else
				$discount = round($total * $discount / 100,2);
			self::$_discount_price = $discount;
			$total -= $discount;
		}
		return $total;
	}

	public static function getShippingPrice()
	{
		$shipping_price = 0;
		$row_id = Cart::search(array('id'=>'shipping_method'));
		if($row_id){
			$shipping_price = Cart::get($row_id[0])->options->shipping_price;
		}
		return $shipping_price;
	}

	public function checkout(){
		$carts = array();
		$all_product = Cart::content();

		$quantity = 0;
		$discount = 0;
		$promo_code = '';
		$is_shipping_discount = 0;
		$ship_price = -1;
		$row_id = Cart::search(array('id'=>'promo_code'));
		if($row_id){
			$discount = Cart::get($row_id[0])->options->discount;
			$promo_code = Cart::get($row_id[0])->options->promo_code;
			$is_shipping_discount = Cart::get($row_id[0])->options->is_shipping_discount;
		}
		foreach($all_product as $key=>$product){
			if($product->id == 'promo_code') continue;
			if($product->id == 'shipping_method') continue;
			$carts[$key] = array(
									'name' => $product->options->name,
									'title'=>$product->name,
									'image'=>$product->options->image,
									'bleed'=>$product->options->bleed,
									'wrap'=>$product->options->wrap,
									'price'=>$product->price,
									'url'   =>$product->options->url,
									'quantity'=>(int)$product->qty,
									'subtotal'=>$product->subtotal,
								);
			$quantity += (int)$product->qty;
		}
		$user = Auth::user()->get();
		if(isset($user)){
			$address = Address::where('user_id','=',$user->id)->orderBy('default','desc')->get();
			if(isset($address)){
				$user->address = $address;
			}else{
				$user->address=array(array());
			}
		}
		$countries = JTCountry::getSource();
		$taxs = JTTax::getSource();
		if(count($carts)>0)
				//return View::make(self::$theme.'.checkout_information')
				$this->layout->content = View::make('frontend.checkout_information')
								->with(array(
										'user' => $user,
										'carts' => $carts,
										'countries'=>$countries,
										'arr_items'=>self::getProductForCheckout(),
										'total'=>self::calculatePriceCart(),
										//'arr_shipping_price'=>self::getShippingMethod(),
										'total_price'    => self::calculatePriceCart(),
										'is_shipping_discount'   => $is_shipping_discount,
										'discount'   => $discount,
										'promo_code'   => $promo_code,
										'taxs' => $taxs,
									   ));
		else
				return Redirect::to('/cart');

	}
	public static function calculatePriceCart()
	{
		$total_price = 0;
		$all_product = Cart::content();

		foreach($all_product as $key=>$product_cart){
			if($product_cart->id == 'promo_code') continue;
			if($product_cart->id == 'shipping_method') continue;
			if($product_cart->options->type==''){
				$sizew = $sizeh = 0;
				if( $product_cart->options->size  ) {
					$size_product = explode('x',$product_cart->options->size);
					$sizew = Floatval($size_product[0]);
					$sizeh = Floatval($size_product[1]);
				}
				$bleed      = $product_cart->options->bleed;
				$quantity   =(int)$product_cart->qty;
				$product_id =$product_cart->options->product_id;

				$product = Product::select('id', 'sku')
									->where('id', $product_id)
									->first();
				$sizes = Input::has('size') ? Input::get('size') : [];
				$sellPrice = 0;
				if( is_object($product) ) {
					$product->sizew = $sizew;
					$product->sizeh = $sizeh;
					$product->bleed = $bleed;
					$product->quantity = $quantity;

					$price = JTProduct::getPrice($product);
					$unitPrice = (float)str_replace(',', '', $price['sell_price']);
					$subTotal = (float)str_replace(',', '', $price['sub_total']);
					Cart::update($product_cart->rowid,array('subtotal'=> $subTotal,'price'=> $unitPrice ));
					$total_price += floatval(str_replace(",","",$price['sub_total']));
				}
			}else{
				$total_price += floatval($product_cart->price) *floatval($product_cart->qty);
			}
		}
		return $total_price;

	}
	public function ordercomplete(){
		$pages = Page::where('short_name','order-complete')->first()->toArray();		
		$this->layout->content = View::make('frontend.order-complete')
												->with([
														   'pages' => $pages,
														]);
	}
	public function create_jt_order($arr_data){
        
       		$data = $arr_data;
            $items = Cart::content();
            $method_pay = 'cash';
            
           	$invoice_address = isset($arr_data['billing_address'])?$arr_data['billing_address']:'';
           	$invoice_zipcode = isset($arr_data['billing_zipcode'])?$arr_data['billing_zipcode']:'';

           	$invoice_province = isset($arr_data['billing_province'])?$arr_data['billing_province']:'';

           	$invoice_country = isset($arr_data['billing_country'])?$arr_data['billing_country']:'CA';
           	$invoice_city = isset($arr_data['billing_city'])?$arr_data['billing_city']:'';
           	$invoice_first_name = isset($arr_data['billing_first_name'])?$arr_data['billing_first_name']:'';
           	$invoice_last_name = isset($arr_data['billing_last_name'])?$arr_data['billing_last_name']:'';
           	$invoice_phone = isset($arr_data['phone'])?$arr_data['phone']:'';
           	$invoice_email = isset($arr_data['email'])?$arr_data['email']:'';

           	// shipping information

           	$shipping_address = isset($arr_data['shipping_address'])?$arr_data['shipping_address']:'';
           	$shipping_zipcode = isset($arr_data['shipping_zipcode'])?$arr_data['shipping_zipcode']:'';

           	$shipping_province = isset($arr_data['shipping_province'])?$arr_data['shipping_province']:'';

           	$shipping_country = isset($arr_data['shipping_country'])?$arr_data['shipping_country']:'CA';
           	$shipping_city = isset($arr_data['shipping_city'])?$arr_data['shipping_city']:'';
           	$shipping_first_name = isset($arr_data['shipping_first_name'])?$arr_data['shipping_first_name']:'';
           	$shipping_last_name = isset($arr_data['shipping_last_name'])?$arr_data['shipping_last_name']:'';
           	$notes = isset($arr_data['shippping_note'])?$arr_data['shippping_note']:'';

           	$total = isset($arr_data['total'])?$arr_data['total']:0;
           	$tax_price = isset($arr_data['tax_price'])?$arr_data['tax_price']:0;
           	$tax_per = isset($arr_data['tax_per'])?$arr_data['tax_per']:0;
           	$sum_sub_total = isset($arr_data['sum_sub_total'])?$arr_data['sum_sub_total']:0;

            
            $user_data = Auth::user()->get();
            
            if ($user_data && $user_data->email ) $emailInfo = trim($user_data->email);
            else $emailInfo = '';

            $phone = '';
            
            $pickup_option = '';

            $provinceId = '';

           	$cash_tend = $sum_sub_total;

            
            
            $datetime_pickup = '';
            $datetime_delivery = '';
            
            $Paidby = 'Cash';

            $cash_tend = floatval($cash_tend);

            $payment_method = array('On Account'=>$cash_tend);

            $soStatus = 'New';

            $orderType = 2; //Online

            $create_from = 'Vi1 Online';
            $status = 'In production';            

            $pos_delay = 0;

            $pos_delay = 0;
            $somongo = new JTOrder;
            
            
            $first_name = trim($invoice_first_name);
            $last_name = trim($invoice_last_name);
            $nameInfo = $last_name . ' '.$first_name;
            

            $previous_order_status = 'No';

            $contact = new JTContact;
            $contact = $contact::where('email',$emailInfo)->first();
            if(!$contact){
                $contact = new JTContact;
                $contact->email = $emailInfo;
                $contact->fullname = $nameInfo;
                $contact->first_name = $first_name;
                $contact->last_name = $last_name;
                $contact->phone = $phone;
                try {
                    	$contact->save();
                	} catch (Exception $e) {
                }
            }           

            $contact = $contact->toArray();

            $invoiceAddress =[[
                'deleted'   => false,
                'shipping_address_1' => $invoice_address,
                'shipping_address_2' => '',
                'shipping_address_3' => '',
                'shipping_town_city' => $invoice_city,
                'shipping_zip_postcode' => $invoice_zipcode,
                'shipping_province_state_id' => $invoice_province,
                'shipping_province_state' => $invoice_province,
                'shipping_country'    => $invoice_country,
                'shipping_country_id' => $invoice_country,
                'phones'=>$phone,
                'emails'=>$emailInfo,
                'full_name'=>$nameInfo,
                'notes'=>$notes
            ]];
            $shippingAddress =[[
                'deleted'   => false,
                'shipping_address_1' => $shipping_address,
                'shipping_address_2' => '',
                'shipping_address_3' => '',
                'shipping_town_city' => $shipping_city,
                'shipping_zip_postcode' => $shipping_zipcode,
                'shipping_province_state_id' => $shipping_province,
                'shipping_province_state' => $shipping_province,
                'shipping_country'    => $shipping_country,
                'shipping_country_id' => $shipping_country,
                'phones'=>$phone,
                'emails'=>$emailInfo,
                'full_name'=>$shipping_last_name . ' ' .$shipping_first_name,
                'notes'=>$notes
            ]];
            $payment_data = "";
            $had_paid = 0;
            $had_paid_amount = 0;
           
            $deliveryMethod = $pickup_option;
            
            $voucher = '';
            if($contact){
                $order_id = $somongo->add($contact, [
                    'delivery_method' => $deliveryMethod,
                    'invoice_address'  => $invoiceAddress,
                    'shipping_address'  => $shippingAddress,
                    'cash_tend' => $cash_tend,
                    'paid_by' => $Paidby,
                    'had_paid'=>$had_paid,
                    'had_paid_amount'=>$had_paid_amount,
                    'pos_delay'=>$pos_delay,
                    'create_from'=>$create_from,
                    'datetime_pickup'=>$datetime_pickup,
                    'payment_due_date'=>$datetime_pickup,
                    'payment_data'=>$payment_data,
                    'datetime_delivery'=>$datetime_delivery,
                    'time_delivery'=>new \MongoDate(time()),
                    'status'=>$status,
                    'asset_status'=>$status,
                    'phones'=>$phone,
                    'emails'=>$emailInfo,
                    'full_name'=>$nameInfo,
                    'voucher'=>$voucher
                ]);

            }else{
                $user = array('_id'=>new \MongoId('564694b0124dca8603f4d46f'));
                $order_id = $somongo->add($user, [
                    'delivery_method' => $deliveryMethod,
                    'invoice_address'  => $invoiceAddress,
                    'shipping_address'  => $shippingAddress,
                    'paid_by' => $Paidby,
                    'had_paid'=>$had_paid,
                    'had_paid_amount'=>$had_paid_amount,
                    'cash_tend' => $cash_tend, //payment
                    'pos_delay'=>$pos_delay,
                    'create_from'=>$create_from,
                    'status'=>$status,
                    'payment_due_date'=>$datetime_pickup,
                    'payment_data'=>$payment_data,
                    'asset_status'=>$status,
                    'phones'=>$phone,
                    'emails'=>$emailInfo,
                    'full_name'=>$nameInfo,
                    'voucher'=>$voucher
                ]);

            }

    }
	public function processOrder(){
			$arr_data = Input::all();

			// self::create_jt_order($arr_data);
			
			
			$order = array();
			$email = Input::has('email')?Input::get('email'):'';
			$phone = Input::has('phone')?Input::get('phone'):'';
			$tax_per = Input::has('tax_per')?Input::get('tax_per'):0;
			$tax_price = Input::has('tax_price')?Input::get('tax_price'):0;
			$note =  Input::has('shippping_note')?Input::get('shippping_note'):'';

			$billing_first_name = Input::has('billing_first_name')?Input::get('billing_first_name'):'';
			$billing_last_name = Input::has('billing_last_name')?Input::get('billing_last_name'):'';
			$billing_address = Input::has('billing_address')?Input::get('billing_address'):'';
			$billing_city = Input::has('billing_city')?Input::get('billing_city'):'';
			$billing_country = Input::has('billing_country')?Input::get('billing_country'):0;
			$billing_province = Input::has('billing_province')?Input::get('billing_province'):0;
			$billing_zipcode = Input::has('billing_zipcode')?Input::get('billing_zipcode'):'';

			$shipping_first_name = Input::has('shipping_first_name')?Input::get('shipping_first_name'):'';
			$shipping_last_name = Input::has('shipping_last_name')?Input::get('shipping_last_name'):'';
			$shipping_address = Input::has('shipping_address')?Input::get('shipping_address'):'';
			$shipping_city = Input::has('shipping_city')?Input::get('shipping_city'):'';
			$shipping_country = Input::has('shipping_country')?Input::get('shipping_country'):'';
			$shipping_province = Input::has('shipping_province')?Input::get('shipping_province'):'';
			$shipping_zipcode = Input::has('shipping_zipcode')?Input::get('shipping_zipcode'):'';
			$shipping_method = Input::has('shipping_method')?Input::get('shipping_method'):'';

			$sum_sub_total = Input::has('sum_sub_total')?Input::get('sum_sub_total'):'';
			$discount = Input::has('discount')?Input::get('discount'):'';
			$total = Input::has('total')?Input::get('total'):'';


			$arr_billing = new Address;
			$arr_billing->first_name = $billing_first_name;
			$arr_billing->last_name = $billing_last_name;
			$arr_billing->company = '';
			$arr_billing->address1 = $billing_address;
			$arr_billing->address2 = '';
			$arr_billing->city = $billing_city;
			$arr_billing->country_id = $billing_country;
			$arr_billing->province_id = $billing_province;
			$arr_billing->zipcode = $billing_zipcode;
			$arr_billing->phone = $phone;



			$arr_shipping = new Address;
			$arr_shipping->first_name = $shipping_first_name;
			$arr_shipping->last_name = $shipping_last_name;
			$arr_shipping->company = '';
			$arr_shipping->address1 = $shipping_address;
			$arr_shipping->address2 = '';
			$arr_shipping->city = $shipping_city;
			$arr_shipping->country_id = $shipping_country;
			$arr_shipping->province_id = $shipping_province;
			$arr_shipping->zipcode = $shipping_zipcode;
			$arr_shipping->phone = $phone;
			$arr_shipping->save();
			$shipping_address_id = $arr_shipping->id;

			$status_id=0;
			$arr_ss_user = Auth::user()->get();
			if($email!=''){
					if(!isset($arr_ss_user->id)) $user = User::where('email',$email)->get()->first();
					else $user = User::where('id',$arr_ss_user->id)->first();
					
					if(!$user){
						$new_user = new User;
						$new_user->email = $email;
						$new_user->password = Hash::make('123456'); // pass mac dinh
						$new_user->first_name = $billing_first_name;
						$new_user->last_name = $billing_last_name;
						$new_user->active = 1;
						$new_user->save();
						$contact_id = $new_user->id;
						Auth::user()->loginUsingId($contact_id);
						$arr_billing->user_id = $contact_id;
						$arr_billing->default = 0;
					}else{
						$contact_id = $user->id;
					}

					$arr_billing->save();
					$billing_address_id = $arr_billing->id;


					//$sum_tax = $sum_sub_total*$taxval/100;
					$cart = Cart::content();

					/*$shipping_price=0;
					foreach ($cart as $key => $value) {
						if($value->name=='shipping_method'){
							$shipping_price= $value->options->shipping_price;
						}
					}
					$voucher='';
					foreach ($cart as $key => $value) {
						if($value->name=='promo_code'){
							$voucher= $value->options->promo_code;
						}
					}*/

					$order = new Order;
					$order->user_id = $contact_id;
					$order->billing_address_id = $billing_address_id;
					$order->shipping_address_id = $shipping_address_id;
					$order->status = "New";
					$order->sum_sub_total = $sum_sub_total;
					$order->discount = $discount;
					$order->tax = $tax_per;
					$order->sum_tax = $tax_price;
					$order->note = $note;
					//$order->shipping_price = $shipping_price;
					//$order->phone = $phone;
					//$order->voucher=$voucher;
					$order->status = 'New';
					$order->sum_amount = $total;
					$order->created_at = date('Y-m-d H:i:s',time());
					$order->save();
					if($order->id){
							$order_id = $order->id;
							foreach ($cart as $key => $row) {
								if($row->name!='shipping_method'&&$row->name!='promo_code'){

										$product_id =  $row->options->product_id;
										$jt_id =  $row->options->jt_id;
										$sku =  $row->options->sku;
										$quantity  =  $row->qty;
										$sell_by = "unit";
										$sell_price = $row->price;
										$sizew = $sizeh = 0;
										if( !empty($row->options->size) && strpos($row->options->size, 'x') !== false ) {
											list($sizew, $sizeh) = explode('x', $row->options->size);
											$sizew = (float)$sizew;
											$sizeh = (float)$sizeh;
										}
										$sizew_unit = 'in';
										$sizeh_unit = 'in';
										$sub_total = $row->subtotal;
										$image = $row->options->svg;
										//$taxper=$taxval;
										//$tax = $sub_total*$taxval/100;
										//$amount = $sub_total + $tax;
										$order_detail = new OrderDetail;

										$order_detail->order_id = $order_id;
										$order_detail->product_id = $product_id;
										//$order_detail->jt_product_id = $jt_id;
										//$order_detail->sku = $sku;
										$order_detail->quantity = $quantity;
										$order_detail->svg_file = $image;
										$order_detail->sell_price = $sell_price;
										//$order_detail->sell_by = $sell_by;
										$order_detail->sizew = $sizew;
										//$order_detail->sizew_unit = $sizew_unit;
										$order_detail->sizeh = $sizeh;
										//$order_detail->sizeh_unit = $sizeh_unit;
										$order_detail->sum_sub_total = $sub_total;
										$order_detail->option = $row->options;
										
										$order_detail->tax = $tax_per;
										$order_detail->sum_tax = $tax_per * $sub_total /100;
										$order_detail->sum_amount = $sub_total + $order_detail->sum_tax;
										$order_detail->save();
								}
							}
							Cart::destroy();
							return Redirect::to("/order-complete");
					 }else{
						$message = 'Error create order';
						return App::abort(404);
					 }
			}else{
				return App::abort(404);
			}
	}
	public function orders(){
		$user = Auth::user()->get();
		if(! $user)
			return Redirect::to('/user/login');
		else
			$arr_order = DB::table('orders')
			->select('orders.id','orders.user_id' , 'billing_address_id' , 'shipping_address_id' , 'status' , 'sum_sub_total' , 'sum_tax' , 'sum_amount' , 'orders.created_at' , 'first_name' , 'last_name' , 'address1' , 'address2' , 'city' , 'country_id' , 'province_id' , 'zipcode' , 'phone')
			->join('addresses','addresses.id','=','orders.billing_address_id')
			->where('orders.user_id', '=', $user->id)
			->get();
		$countries = JTCountry::getSource();
		$this->layout->content = View::make('frontend.orders')
											->with(array(
											  'arr_order'  => $arr_order,
											  'user'      => $user,
											));
	}
	public function viewOrder($order_id){
		$user = Auth::user()->get();
		if(!$user) {
			return Redirect::to('/user/login');
		}
		$order = Order::where('id','=',$order_id)
						->where('user_id','=',$user->id)
						->with('billingAddress')
						->with('shippingAddress')
						->with('orderDetails')
						->first();
		if ($order) {
			$order = $order->toArray();
			$arr_order_detail_temps = $order['order_details'];
			$arr_existed_product_order_detail = array();
			$arr_new_order_detail = array();
			for($i=0;$i<count($arr_order_detail_temps);$i++){
				if(!in_array($arr_order_detail_temps[$i]['id'], $arr_existed_product_order_detail)){
					$arr_existed_product_order_detail[] = $arr_order_detail_temps[$i]['id'];
					$arr_new_order_detail[] =  $arr_order_detail_temps[$i];
				}
			}
			$order['order_details'] = $arr_new_order_detail;

			foreach($order['order_details'] as $key => $detail) {
				$order['order_details'][$key]['option'] = json_decode($detail['option'], true);
			}
			foreach(['shipping', 'billing'] as $address) {
				if( isset($order[$address.'_address']['country_id']) ) {
					$order[$address.'_address']['country'] = JTCountry::getName($order[$address.'_address']['country_id']);
				}
				if( isset($order[$address.'_address']['province_id']) ) {
					$order[$address.'_address']['province'] = JTProvince::getName($order[$address.'_address']['province_id']);
				}
			}
			$this->layout->content = View::make('frontend.view_order')
												->with([
														   'order' => $order,
														]);
		} else {
			return App::abort(404);
		}

	}


	public static function checkCheckout($arr_post)
	{
		$arr_check = array('last_name','address_1','city','zip','country');
		if(isset($arr_post['billing_is_shipping']))
			$arr_post['shipping_address'] = $arr_post['billing_address'];
		foreach(array('billing_address','shipping_address') as $address_key){
			foreach($arr_check as $check){
				if(!isset( $arr_post[$address_key][$check] ) || strlen($arr_post[$address_key][$check]) == 0)
					return false;
			}
			if( isset($arr_post[$address_key]['country']) && strlen($arr_post[$address_key]['country'])
					&& (!isset($arr_post[$address_key]['province']) || strlen($arr_post[$address_key]['province']) == 0) )
				return false;
			$state = ZipCode::where('zip','=',$arr_post[$address_key]['zip'])->pluck('state');
			$not_match_zip = false;
			if(is_numeric($arr_post[$address_key]['province'])){
				 if(is_null($state))
					$not_match_zip = true;
				else {
					$province = City::where('id','=',$arr_post[$address_key]['province'])->pluck('province_code');
					if($province != $state)
						$not_match_zip = true;
				}
			}
			if($not_match_zip){
				$arr_post[$address_key]['not_match_zip'] = true;
				Input::merge(array($address_key=>$arr_post[$address_key]));
				return false;
			}
		}

		return true;
	}

	/*public static function changeShippingPrice()
	{
	}*/

	public static function changeShippingPrice()
	{
		$method_id = Input::has('method_id') ? Input::get('method_id') : 0;
		$method = ShipPriceDetail::select('ship_price_detail.*','ship_price.country_id')
										->where('ship_price_detail.id','=',$method_id)
										->leftJoin('ship_price','ship_price.id','=','ship_price_detail.ship_price_id')
										->get()
										->first();
		$shipping_price = 0;
		if( !is_null($method) ){
			$method->shipping_price = round( $method->shipping_price ,2);
			$row_id = Cart::search(array('id'=>'shipping_method'));
			if($row_id)
				Cart::update($row_id[0],array('options'=>array(
													 'shipping_price'=>$method->shipping_price,
													 'country_id'=>$method->country_id,
													 'ship_price_detail_id'=>$method->id,
													 'ship_price_id'=>$method->ship_price_id)
											));
			else
				Cart::add('shipping_method','shipping_method',1,0,array(
																	'shipping_price'=>$method->shipping_price,
																	'country_id'=>$method->country_id,
																	'ship_price_detail_id'=>$method->id,
																	'ship_price_id'=>$method->ship_price_id
																	));
			$shipping_price = $method->shipping_price;
		}
		$total = self::getSubTotal($shipping_price);
		$arr_return = array(
							'cost'=>number_format(($total  + $shipping_price), 2),
							'shipping_price'=>number_format($shipping_price,2),
							'status'=>'ok'
							);
		$response = Response::json($arr_return);
		$response->header('Content-Type', 'application/json');
		return $response;

	}

	public static function doCheckout()
	{
		$arr_post = Input::all();
		if(self::checkCheckout($arr_post)){
			if(isset($arr_post['checkout']['email']) && ( !filter_var($arr_post['checkout']['email'], FILTER_VALIDATE_EMAIL) || !checkdnsrr(substr($arr_post['checkout']['email'], strpos($arr_post['checkout']['email'], '@') + 1))))
				return Redirect::to('/checkout')->withInput(Input::except('commit'))->with('email_error','Email not valid.');
			//Create Salesorder then send confirm email to customer
			if(isset($arr_post['billing_is_shipping']))
				$arr_post['shipping_address'] = $arr_post['billing_address'];
			/*$all_product = Cart::content();
			$weight = 0;
			foreach($all_product as $key=>$product){
				if($product->id == 'promo_code') continue;
				if($product->id == 'shipping_method') continue;
				$weight += $product->options->weight * (int)$product->qty;
			}
			$shipping_price = ShippingController::getRate($weight,$arr_post['shipping_address']['zip']);
			usort( $shipping_price, function($a,$b){
				return $a['price'] < $b['price'] ? 0 : 1;
			});
			Session::put('shipping_price',json_encode($shipping_price));
			Session::put('shipping_key',0);*/
			$user = Auth::user()->get();
			if( $user )
				$arr_post['email'] = $user->email;
			else
				$arr_post['email'] = $arr_post['checkout']['email'];
			unset($arr_post['_token'],$arr_post['checkout'],$arr_post['commit']);
			Session::put('order_info',json_encode($arr_post));
				return Redirect::to('/checkout');
			OrderController::createOrder($arr_post);
			Cart::destroy();
			return Redirect::to('/cart')->with('order_success','Thank you for shopping with Dolch Designs. Your order has been processed and you will receive a confirmation email shortly.');
		}
		return Redirect::to('/checkout')->withInput(Input::except('commit'));
	}

	public static function confirmCode($token)
	{
		$order = Order::select('id','status','email')
				->where('token','=',$token)
				->get()
				->first();
		if(is_null($order))
			return Redirect::to('/cart')->with('order_confirm','This order was not existed');
		if($order['status'] > 0)
			return Redirect::to('/cart')->with('order_confirm','Your order had been confirmed.');
		Order::where('id',$order['id'])
				->update(array('status'=>2));
		$config = EmailTemplate::where('type','=','order_success')
									->get()
									->first();
		if($config){
			$data = array(
						  'content' => $config['content'],
						  );
			$email = $order['email'];
			Mail::queue('emails.order_success', $data, function($message) use($email,$config)
			{
				$message->to($email, 'Guest')->subject($config['subject']);
			});
		}
		return Redirect::to('/cart')->with('order_confirm','Your order has been submitted');
	}

}