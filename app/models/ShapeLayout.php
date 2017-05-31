<?php
class ShapeLayout extends BaseModel {

	protected $table = 'shape_layouts';

	public $rules = [
		'name' => 'required',
	];

	public function shapes()
	{
		return $this->hasMany('ShapeLayoutDetail', 'shape_layout_id')
					->orderBy('shape_layout_details.coor_x', 'asc');
	}

	public function beforeDelete($layout)
	{
		$layout->shapes()->delete();
	}
}
