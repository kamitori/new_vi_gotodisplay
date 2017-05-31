<?php

class Imageable extends BaseModel {

	protected $table = 'imageables';

	public $timestamps = false;

	public function user()
	{
		return $this->belongsTo('User');
	}
}