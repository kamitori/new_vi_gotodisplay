<?php

class OrderDetail extends BaseModel {

	protected $table = 'order_details';

	protected $rules = array(
	);

	public static function get($arr = [], $arrReturn = [])
	{
	}
	public function product()
	{
		return $this->belongsTo('Product', 'product_id');
	}
	public function order()
	{
		return $this->belongsTo('Order','order_id');
	}


}