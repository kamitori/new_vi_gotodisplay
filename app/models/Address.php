<?php

class Address extends BaseModel {
	protected $table = 'addresses';

	// public function user()
	// {
	// 	return $this->belongsTo('User', 'user_id');
	// }

	public function billing_order()
    {
        return $this->hasOne('Order, billing_address_id');
    }
}