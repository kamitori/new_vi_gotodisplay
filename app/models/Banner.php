<?php

class Banner extends BaseModel {

	protected $table = 'banners';

	public function images()
	{
		return $this->morphToMany('VIImage', 'imageable', 'imageables', 'imageable_id', 'image_id')
						->withPivot('option')
						->orderBy('imageables.id', 'desc');
	}

	public function afterSave($banner)
	{
		Cache::forget('banners');
	}

	public function beforeDelete($banner)
    {
		$banner->images()->detach();
		Cache::forget('banners');
    }
}