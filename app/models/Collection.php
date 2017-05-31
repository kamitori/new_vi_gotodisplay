<?php

class Collection {

	public static function getCollection($arrData)
	{
		$collection = $arrData['collection'];
		$skip = isset($arrData['skip']) ? $arrData['skip'] : 0;
		$take = isset($arrData['take']) ? $arrData['take'] : 0;
		$products = Product::select('id', 'sku', 'short_name', 'name', 'margin_up')
					->with(['images'=> function($query){
						$query->select('path');
						$query->where('imageables.option', 'like', '%"main":1%');
					}])
					->whereHas('categories', function($query) use($collection) {
						$query->where('categories.id', $collection->id);
					})
					->where('products.active', 1)
					->take($take)
					->skip($skip)
					->orderBy('order_no', 'asc')
					->orderBy('id', 'desc')
					->get();
		if( !$products->isEmpty() ) {
			foreach($products as $key => $product) {
				$product->sell_price = Product::getSmallestPrice($product);
				if( $product->margin_up ) {
					$product->bigger_price = $product->sell_price * (1 + $product->margin_up /100);
				}
				if( isset($product->images[0]) ) {
					$product->image = URL.'/'.$product->images[0]->path;
					//server bi loi chmod, tam tat khi nao het thi mo lai // URL.'/'.str_replace('/images/products/', '/images/products/thumbs/', $product->images[0]->path);
				} else {
					$product->image = URL.'/assets/images/noimage/213x213.gif';
				}
				unset($product->images);
			}
			$products = $products->toArray();
			return $products;
		}
		return [];
	}

	public static function getTotalPage($collection, $take)
	{
		$total = Product::whereHas('categories', function($query) use($collection) {
						$query->where('categories.id', $collection->id);
					})
					->where('products.active', 1)
					->count('id');
		return ceil( $total / $take );
	}

	public static function getProduct($productNameOrId, $collectionName = '')
	{
		$product = Product::with([
								'type',
								'sizeLists',
								'images'=> function($query){
									$query->select('path');
								},
								'optionGroups' => function($query) {
									$query->select('option_groups.id', 'name', 'key');
								},
								'options' => function($query) {
									$query->select('options.id', 'name', 'key', 'option_group_id')->orderBy('name','asc');
								}
							]);
		if( !empty($collectionName) ) {
			$product->with(['categories' => function($query) use ($collectionName){
								$query->select('short_name', 'name');
								$query->where('categories.short_name', $collectionName);
							}])
							->whereHas('categories', function($query) use($collectionName) {
								$query->where('categories.short_name', $collectionName);
							});
		}
		if( is_numeric($productNameOrId) ) {
			$product->where('id', $productNameOrId);
		} else if( is_string($productNameOrId) ) {
			$product->where('short_name', $productNameOrId);
		}
		$arr_image_list = array();
		$product = $product->where('active', 1)
					->first();
		if( is_object($product) ) {
			if( !empty($product->images) ) {
				foreach($product->images as $key => $img) {
					$img->path = URL.'/'.$img->path;
					$option = json_decode($img->pivot->option, true);
					$img->main = $option['main'];
					$img->view = $option['view'];
					$back = isset($option['back']) ?$option['back'] : 0;
					$square = isset($option['square']) ?$option['square'] : 0;
					$bg_2d = isset($option['2d']) ?$option['2d'] : 0;
					settype($back, 'int');
					if( isset($img->main) && $img->main ) {
						$product->image = $img->path;
					}
					unset($img->pivot);
					if(!$back && !$square && !$bg_2d) $arr_image_list [] = $img->path;
				}
				if( !isset($product->image) ) {
					$product->image = URL.'/assets/images/noimage/213x213.gif';
				}
			}
			$product->image_list = $arr_image_list;
			if( !empty($product->default_view) ) {
				$product->default_view = json_decode($product->default_view, true);
			} else {
				$product->default_view = array();
			}
			if( is_object($product->type) ) {
				$product->type_name = $product->type->name;
			} else {
				$product->type_name = '';
			}

			$product->link_design = 'quick-design';
			if ( $product->type_name == 'Wall Collage' ) {
		    	$product->link_design = 'cluster-design';
			}
			if( in_array($product->id, [202, 199, 197, 201, 196, 195, 191, 189]) || $product->product_type_id == 6) {
			   	$product->link_design = 'wall-collage-design';
			}
		}

		return $product;

	}

	public static function getTotalProduct($collection)
	{
		return Product::whereHas('categories', function($query) use($collection) {
						$query->where('categories.id', $collection->id);
					})
					->where('products.active', 1)
					->count('id');
	}

	public static function getSimilarProduct($product, $collectionName,$limit=4,$orderBy='')
	{
		if($orderBy=='')
			$orderBy = DB::raw('RAND()');
		$products = Product::select('id', 'short_name', 'name', 'sku')
					->with(['images'=> function($query){
						$query->select('path');
						$query->where('imageables.option', 'like', '%"main":1%');
					}])
					->whereHas('categories', function($query) use($collectionName) {
						$query->where('categories.short_name', $collectionName);
					})
					->where('active', 1)
					->take($limit)
					->orderBy($orderBy)
					->get();
		if( !$products->isEmpty() ) {
			foreach($products as $key => $product) {
				$product->sell_price = Product::getSmallestPrice($product);
				if( isset($product->images[0]) ) {
					$product->image = URL.'/'.$product->images[0]->path;//
					//.str_replace('/images/products/', '/images/products/thumbs/', $product->images[0]->path);
				} else {
					$product->image = URL.'/assets/images/noimage/213x213.gif';
				}
				unset($product->images);
			}
			$products = $products->toArray();
			return $products;
		}
		return [];
	}

	public static function getPrevProduct($product, $collectionName)
	{
		$product = Product::select( 'id', 'short_name', 'name')
							->with('mainImage')
							->where('id', '<', $product->id)
							->where('active', 1)
							->whereHas('categories', function($query) use ($collectionName) {
								$query->where('categories.short_name', $collectionName);
							})
							->orderBy('id', 'desc')
							->remember(30)
							->first();
		if( is_object($product) ) {
			$product->sell_price = Product::getSmallestPrice($product);
			$product = $product->toArray();
			$product['main_image'] = isset($product['main_image'][0]['path']) ? URL.'/'.$product['main_image'][0]['path'] : URL.'/assets/images/noimage/213x213.gif';
			return $product;
		}
		return [];
	}

	public static function getNextProduct($product, $collectionName)
	{
		$product = Product::select( 'id', 'short_name', 'name')
							->with('mainImage')
							->where('id', '>', $product->id)
							->where('active', 1)
							->whereHas('categories', function($query) use ($collectionName) {
								$query->where('categories.short_name', $collectionName);
							})
							->orderBy('id', 'asc')
							->remember(30)
							->first();
		if( is_object($product) ) {
			$product->sell_price = Product::getSmallestPrice($product);
			$product = $product->toArray();
			$product['main_image'] = isset($product['main_image'][0]['path']) ? URL.'/'.$product['main_image'][0]['path'] : URL.'/assets/images/noimage/213x213.gif';
			return $product;
		}
		return [];
	}

	public static function getProductsCollection($arrProductId)
	{
		$products = Product::select('id', 'sku',  'name', 'margin_up','short_name')
					->with([
							'images'=> function($query){
								$query->select('path');
								$query->where('imageables.option', 'like', '%"main":1%');
								},
							'categories' => function($query){
								$query->select('short_name', 'name');
							}
					])
					->where('active', 1)
					->whereIn('id', $arrProductId)
					->orderBy('order_no', 'asc')
					->orderBy('id', 'desc')
					->get();

		if( !$products->isEmpty() ) {
			foreach($products as $key => $product) {
				$product->sell_price = Product::getSmallestPrice($product);
				if( $product->margin_up ) {
					$product->bigger_price = $product->sell_price * (1 + $product->margin_up /100);
				}
				if( isset($product->images[0]) ) {
					$product->image = URL.'/'.$product->images[0]->path;
					//server bi loi chmod, tam tat khi nao het thi mo lai // URL.'/'.str_replace('/images/products/', '/images/products/thumbs/', $product->images[0]->path);
				} else {
					$product->image = URL.'/assets/images/noimage/213x213.gif';
				}
				unset($product->images);
			}
			$products = $products->toArray();
			return $products;
		}
		return [];
	}

}
