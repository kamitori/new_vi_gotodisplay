<?php
use Jenssegers\Agent\Agent;
class CollectionsController extends BaseController {

	protected $take = 15;

	public function index()
	{
	}

	public function getAllCollections()
	{
		$collections = ProductCategory::with('images')
							->where('active', 1)
							->orderBy('order_no', 'asc')
							->get();
		foreach($collections as $collection) {
			if( isset($collection->images[0]) ) {
				$collection->image = URL.'/'.$collection->images[0]->path;
			} else {
				$collection->firstProduct();
				if( isset($collection->products[0]) && isset($collection->products[0]->main_image[0]) ) {
					$collection->image = URL.'/'.$collection->products[0]->main_image[0]->path;
				} else {
					$collection->image = URL.'/assets/images/noimage/247x185.gif';
				}
			}
			$collection->totalProduct = Collection::getTotalProduct($collection);
		}
		

		$this->layout->metaInfo['meta_title'] = 'Collections';
		$this->layout->content = View::make('frontend.collections-all')->with([
																			'collections' => $collections->toArray()
																		]);
	}

	public function getCollection($collectionName, $pageNum = 1)
	{
		$take = $this->take;
		$skip = floor( ($pageNum -1) * $take );
		$collection = ProductCategory::with(['images' => function($query) {
								$query->select('path');
								$query->first();
							}])
							->where('short_name', $collectionName)
							->where('active', 1)
							->first();

		if( !is_object($collection) ) {
			return App::abort(404);
		}
		
		$collection->products = Collection::getCollection([
														'collection' 	=> $collection,
														'take'			=> $take,
														'skip'			=> $skip
													]);
		$collection->pageNum = $pageNum;
		$collection->totalPage = Collection::getTotalPage($collection, $take);
		$collection = $collection->toArray();

		$user_data = Auth::user()->get();
		$yourcollection = array();
		
		if($user_data) {
			$user = User::find($user_data->id);
			if(isset($user->yourcollection) && !empty($user->yourcollection!='')){
				$tmp = json_decode($user->yourcollection,true);
				foreach ($tmp as $key => $value) {
					$yourcollection[] = $key;
				}
			}
		}
		// $
		if(isset($collection['pinerest_url']) && $collection['pinerest_url']!='') $this->layout->pinerest_url = $collection['pinerest_url'];
		$this->layout->metaInfo['meta_title'] = $collection['meta_title'];
		$this->layout->metaInfo['meta_description'] = $collection['meta_description'];
		$this->layout->content = View::make('frontend.collections-one')->with([
																			'collection' => $collection,
																			'yourcollection' =>$yourcollection,
																			'small_banner' => $this->layout->small_banner
																		]);


	}

	public function getProduct($collectionName, $productName)
	{
		$product = Collection::getProduct($productName, $collectionName);
		
		if( !is_object($product) ) {
			return App::abort(404);
		}
		$agent = new Agent();
		$v_is_mobile = false;
		if($agent->isMobile()){
            $v_is_mobile = true;
        }
		$product->similar_products = Collection::getSimilarProduct($product, $collectionName);
		$product->prev_product = Collection::getPrevProduct($product, $collectionName);
		$product->next_product = Collection::getNextProduct($product, $collectionName);
		$product = $product->toArray();
		$defaultRatio = 0;
		if( isset($product['size_lists']) && !empty($product['size_lists']) ) {
			foreach($product['size_lists'] as $size) {
				if( $size['default'] ) {
					$defaultRatio = $size['sizew'] / $size['sizeh'];
				}
			}
			if( !$defaultRatio && isset($product['size_lists'][0]) ) {
				$defaultRatio = $product['size_lists'][0]['sizew'] / $product['size_lists'][0]['sizeh'];
			}

			$defaultRatio = round($defaultRatio, 2);
		}

		$this->layout->metaInfo['meta_title'] = $product['meta_title'];
		$this->layout->metaInfo['meta_description'] = $product['meta_description'];
		$this->layout->content = View::make('frontend.product')->with([
																	'product' => $product,
																	'defaultRatio' => $defaultRatio,
																	'is_mobile'=>$v_is_mobile
																]);
	}

	public function calculatePrice($arr_input=array())
	{
		$sizew 		= Input::has('width') 	? (float)Input::get('width') 	: 0;
		$sizeh 		= Input::has('height') 	? (float)Input::get('height') 	: 0;
		$bleed 		= Input::has('bleed') 	? (float)Input::get('bleed') 	: 0;
		$quantity 	= Input::has('quantity') 	? (int)Input::get('quantity') 	: 1;
		$product_id = Input::has('product_id') 	? Input::get('product_id') 		: 0;
		$all 		= Input::has('all') ? true : false;
		$sizes 		= Input::has('size') ? Input::get('size') : [];
		
		//Load tu ham php
		$sizew 		= isset($arr_input['width'])? $arr_input['width']:$sizew;
		$sizeh 		= isset($arr_input['height'])? $arr_input['height']:$sizeh;
		$bleed 		= isset($arr_input['bleed'])? $arr_input['bleed']:$bleed ;
		$quantity 	= isset($arr_input['quantity'])? $arr_input['quantity']:$quantity;
		$product_id = isset($arr_input['product_id'])? $arr_input['product_id']:$product_id;
		$all 		= isset($arr_input['all'])? $arr_input['width']:$all;
		$sizes 		= isset($arr_input['size'])? $arr_input['size']:$sizes;
		
		$product = Product::select('id', 'sku', 'margin_up')
							->where('id','=', $product_id)
							->first();
		
		$sellPrice = 0;
		if( is_object($product) ) {
			if( $all ) {
				foreach($sizes as $size) {
					$p = $product;
					$p->sizew = (float)$size['w'];
					$p->sizeh = (float)$size['h'];
					$p->bleed = $bleed;
					$p->quantity = $quantity;
					$price = JTProduct::getPrice($p);
					$arrUnitPrices[] = $price['sell_price'];
				}
				$arrReturn = ['unit_prices' => $arrUnitPrices ];
			} else {
				$product->sizew = $sizew;
				$product->sizeh = $sizeh;
				$product->bleed = $bleed;
				$product->quantity = $quantity;

				$price = JTProduct::getPrice($product);
				if( $product->margin_up ) {
					$biggerPrice = $price['sell_price'] * (1 + $product->margin_up / 100);
					$arrReturn = ['unit_price' => $price['sell_price'], 'bigger_price' => Product::viFormat($biggerPrice), 'amount' => $price['sub_total'] ];
				} else {
					$arrReturn = ['unit_price' => $price['sell_price'], 'amount' => $price['sub_total'] ];
				}
			}
		} else {
			if( $all ) {
				$arrUnitPrices = [];
				foreach($sizes as $size) {
					$arrUnitPrices[] = Product::viFormat(0);
				}
				$arrReturn = ['unit_prices' => $arrUnitPrices ];
			} else {
				$arrReturn = ['unit_price' => Product::viFormat(0), 'amount' => Product::viFormat(0) ];
			}
		}

		if(isset($arr_input['product_id']))
			return $arrReturn['amount'];
		
		$response = Response::json($arrReturn);
		$response->header('Content-Type', 'application/json');
		return $response;
	}





	public function searchProduct($key, $pageNum = 1){
		$pageNum = (int)$pageNum?(int)$pageNum:1;
		$take =	15;
		$skip = floor( ($pageNum -1) * $take );
		$products = Product::getProductByKey([
											'key' 	=> $key,
											'take'	=> $take,
											'skip'	=> $skip
										]);
		$search['products'] = $products;
		$search['pageNum'] = $pageNum;
		$search['key'] = $key;
		$search['totalPage'] = Product::getTotalPageByKey($key, $take);

		$arr_user_data = Auth::user()->get();
		$arr_your_collection = array();
		
		if($arr_user_data) {
			$user = User::find($arr_user_data->id);
			if(isset($user->yourcollection) && !empty($user->yourcollection!='')){
				$tmp = json_decode($user->yourcollection,true);
				foreach ($tmp as $key => $value) {
					$arr_your_collection[] = $key;
				}
			}
		}

		$this->layout->content = View::make('frontend.search')->with([
																	'search' => $search,
																	'arr_your_collection'=>$arr_your_collection
																]);
	}
}
