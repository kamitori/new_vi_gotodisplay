<?php
class WallCollage {

	public static function getProduct($productNameOrId, $collectionName, $input)
	{
		$product = Product::with([
								'type',
								'images',
								'layout',
								'sizeLists',
								'optionGroups' => function($query) {
									$query->select('option_groups.id', 'name', 'key');
								},
								'options' => function($query) {
									$query->select('options.id', 'name', 'key', 'option_group_id');
								},
								'categories' => function($query) use ($collectionName){
									$query->select('short_name', 'name');
									$query->where('categories.short_name', $collectionName);
								}
							])
							->whereHas('categories', function($query) use($collectionName) {
								$query->where('categories.short_name', $collectionName);
							});
		if( is_numeric($productNameOrId) ) {
			$product->where('id', $productNameOrId);
		} else if( is_string($productNameOrId) ) {
			$product->where('short_name', $productNameOrId);
		}
		$product = $product->where('active', 1)
					->first();
		if( is_object($product) ) {
			if( is_object($product->type) ) {
				$product->type_name = $product->type->name;
			} else {
				$product->type_name = '';
			}
			$product->wrap_option = false;
			if( is_object($product->optionGroups) ) {
				foreach($product->optionGroups as $key => $optionGroups) {
					if( $optionGroups->key == 'wrap_option' ) {
						$product->wrap_option = $optionGroups->id;
						break;
					}
				}
			}

			if( !empty($product->categories) ) {
				$category = [];
				$category['name'] = $product->categories[0]->name;
				$category['short_name'] = $product->categories[0]->short_name;
				unset($product->categories);
				$product->category = $category;
			}
			$product->shapes = [];
			if( is_object($product->layout) ) {
				$layout = [];
				$dpi = 72;
				$max_w = 1000;//pt
				$max_h= 500;//pt
				$svg_bleed = 1;
				$svg_w = ($product->layout->wall_size_w + 2*$svg_bleed) * $dpi;
				$svg_h = ($product->layout->wall_size_h + 2*$svg_bleed) * $dpi;

				$view_dpi = DesignOnline::getDPIOption($svg_w,$svg_h,$max_w,$max_h);
				$svg_bleed_pt = $svg_bleed*$dpi*$view_dpi; //pt
				unset($product->layout);
				$layout = [];
				$layout['width'] = ($svg_w*$view_dpi + $svg_bleed_pt);
				$layout['height'] = ($svg_h*$view_dpi + $svg_bleed_pt);
				$layout['view_dpi'] = $view_dpi;
				$layout['bleed'] = $svg_bleed_pt;
				$wall_w =  $product->layout->wall_size_w * $dpi;
				$wall_h =  $product->layout->wall_size_h * $dpi;
				if( $wall_w / $max_w > $wall_h / $max_h ) {
					$w = $max_w;
					$view_dpi  = $wall_w / $w;
					$h = $wall_h / $view_dpi;
				} else {
					$h = $max_h;
					$view_dpi = $wall_h / $h;
					$w = $wall_w / $view_dpi;
				}
				$layout['real_width'] = $w;
				$layout['real_height'] = $h;
				$layout['view_dpi'] = $view_dpi;
				if( $product->wrap_option !== false ) {
					$layout['wrap'] = 'natural';
				}
				if( isset($input['option'][7]) ) {
					$layout['wrap'] = $input['option'][7];
				}
				unset( $product->layout );
				$product->layout = $layout;
				$product->shapes = LayoutDetail::where('layout_id', '=', $product->svg_layout_id)
															->orderBy('coor_x', 'asc')
															->get();
				if(  !$product->shapes->isEmpty() ) {
					foreach($product->shapes as $shape) {
						$shape->bleed = ($svg_bleed*$dpi*$view_dpi);
						$shape->width = ($shape->width*$dpi*$view_dpi + 2*$shape->bleed) ;
						$shape->height = ($shape->height*$dpi*$view_dpi + 2*$shape->bleed) ;
						$shape->coor_x = ($shape->coor_x*$dpi*$view_dpi);
						$shape->coor_y = ($shape->coor_y*$dpi*$view_dpi);
					}
					$product->shapes = $product->shapes->toArray();
				}
			}
			$price = Product::getPrice($product);
			$product->sell_price = $price['sub_total'];
			$product->quantity = 1;
			if( isset($input['quantity']) ) {
				$product->quantity = $input['quantity'];
			}
			$product->sell_price *= $product->quantity;
			$product->similar_products = self::getSimilarProduct($product->id, $collectionName);
		}
		return $product;
	}

	public static function getSimilarProduct($id, $collectionName, $limit = 4, $orderBy = '')
	{
		if( empty($orderBy) ) {
			$orderBy = DB::raw('RAND()');
		}
		$products = Product::select('id', 'short_name', 'name', 'sku')
					->with(['images'=> function($query){
						$query->select('path');
						$query->where('imageables.option', 'like', '%"main":1%');
					}])
					->where('products.id', '<>', $id)
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
					$product->image = URL.'/'.str_replace('/images/products/', '/images/products/thumbs/', $product->images[0]->path);
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