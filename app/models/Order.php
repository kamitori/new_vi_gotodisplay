<?php

class Order extends BaseModel {

	protected $table = 'orders';

	protected $rules = array(
		'status' 		=> 'required',
	);

	public static function get($arr = [], $arrReturn = [])
	{
	}

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function billingAddress()
	{
		return $this->belongsTo('Address', 'billing_address_id');
	}

	public function shippingAddress()
	{
		return $this->belongsTo('Address', 'shipping_address_id');
	}

	public function orderDetails()
	{
		return $this->hasMany('OrderDetail')
					->addSelect('order_details.*', 'products.name', 'products.short_name', 'products.sku', 'categories.short_name as category')
					->join('products', 'products.id', '=', 'order_details.product_id')
					->join('products_categories', 'products_categories.product_id', '=', 'products.id')
					->join('categories', 'categories.id', '=', 'products_categories.category_id');
					// ->groupBy('products.id');
	}

	public function afterCreate($order)
	{
		Notification::add($order->id, 'Order');
	}


}