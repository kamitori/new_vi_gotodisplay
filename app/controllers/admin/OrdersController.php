<?php

class OrdersController extends AdminController {

	public static $table = 'orders';

	public static $status = [
						'New',
						'Submitted',
						'In production',
						'Partly shipped',
						'Completed',
						'Cancelled'
					];

	public function index()
	{
		$this->layout->title = 'Orders';
		$this->layout->content = View::make('admin.orders-all');
	}

	public function listOrder()
	{
		if( !Request::ajax() ) {
            return App::abort(404);
        }
		$admin_id = Auth::admin()->get()->id;

		$start = Input::has('start') ? (int)Input::get('start') : 0;
		$length = Input::has('length') ? Input::get('length') : 10;
		$search = Input::has('search') ? Input::get('search') : [];
		$orders = Order::with('billingAddress')
						->with('shippingAddress')
						->with('user')
						->select(DB::raw('id, user_id, billing_address_id, shipping_address_id, status, sum_sub_total, discount, tax, sum_tax, note,
											(SELECT COUNT(*)
												FROM notifications
									         	WHERE notifications.item_id = orders.id
									         		AND notifications.item_type = "Order"
													AND notifications.admin_id = '.$admin_id.'
													AND notifications.read = 0 ) as new'));
		if(!empty($search)){
			foreach($search as $key => $value){
				if(empty($value)) continue;
				if( $key == 'status' ) {
	        		$orders->where($key, $value);
				} else if ($key == 'full_name') {
					$orders->whereHas('user',function($query) use ($value) {
						$query->where(function($q)  use ($value) {
							$value = trim($value);
							$arr_value = explode(' ', $value);
							foreach ($arr_value as $key2 => $value2) {
								$q->orWhere('first_name','like', '%'.$value2.'%');
								$q->orWhere('last_name','like', '%'.$value2.'%');
							}
						});
					});
				} else if ($key == 'billing_address_id') {
					$orders->whereHas('billing_address',function($query) use ($value) {
						$query->where(function($q)  use ($value) {
							$value = trim($value);
							$arr_value = explode(' ', $value);
							foreach ($arr_value as $key2 => $value2) {
								$q->orWhere('address1','like', '%'.$value2.'%');
								$q->orWhere('address2','like', '%'.$value2.'%');
							}
						});
					});
				} else if ($key == 'shipping_address_id') {
					$orders->whereHas('shipping_address',function($query) use ($value) {
						$query->where(function($q)  use ($value) {
							$value = trim($value);
							$arr_value = explode(' ', $value);
							foreach ($arr_value as $key2 => $value2) {
								$q->orWhere('address1','like', '%'.$value2.'%');
								$q->orWhere('address2','like', '%'.$value2.'%');
							}
						});
					});
				} else {
	                $value = ltrim(rtrim($value));
	        		$orders->where($key,'like', '%'.$value.'%');
				}
			}
		}
		$order = Input::has('order') ? Input::get('order') : [];
		if(!empty($order)){
			$columns = Input::has('columns') ? Input::get('columns') : [];
			foreach($order as $value){
				$column = $value['column'];
				if( !isset($columns[$column]['name']) || empty($columns[$column]['name']) )continue;
				$orders->orderBy($columns[$column]['name'], ($value['dir'] == 'asc' ? 'asc' : 'desc'));
			}
		}
        $count = $orders->count();
        if($length > 0) {
			$orders = $orders->skip($start)->take($length);
		}
		$arrOrders = $orders->get()->toArray();
		$arrReturn = ['draw' => Input::has('draw') ? Input::get('draw') : 1, 'recordsTotal' => Order::count(),'recordsFiltered' => $count, 'data' => []];
		$arrRemoveNew = [];
		if(!empty($arrOrders)){
			foreach($arrOrders as $key => $order){
				$order['full_name'] = $order['user']['first_name'].' '.$order['user']['last_name'];
				if ( $order['new'] ) {
					$order['full_name'] .= '| <span class="badge badge-danger">new</span>';
					$arrRemoveNew[] = $order['id'];
				}
				$order['billing_address'] = $order['billing_address']['address1'].' '.$order['billing_address']['address2'];
				$order['shipping_address'] = $order['shipping_address']['address1'].' '.$order['shipping_address']['address2'];
				$arrReturn['data'][] = array(
	                              ++$start,
	                              $order['id'],
	                              $order['full_name'],
	                              $order['billing_address'],
	                              $order['shipping_address'],
	                              $order['status'],
	                              $order['sum_sub_total'],
	                              $order['discount'],
	                              $order['sum_tax'],
	                              $order['note'],
	                              htmlentities(nl2br($order['billing_address'])),
	                              htmlentities(nl2br($order['shipping_address'])),
	                              );
			}
		}
		if( !empty($arrRemoveNew) ) {
			Notification::whereIn('item_id', $arrRemoveNew)
						->where('item_type', 'Order')
						->where('admin_id', $admin_id)
						->update(['read' => 1]);
		}
		$response = Response::json($arrReturn);
		$response->header('Content-Type', 'application/json');
		return $response;
	}

	public function updateOrder()
	{
		if( Input::has('pk') ) {
   			if( !Request::ajax() ) {
	   			return App::abort(404);
	   		}
	   		return self::updateQuickEdit();
		}
	}
	public function updateQuickEdit()
	{
   		$arrReturn = ['status' => 'error'];
   		$id = (int)Input::get('pk');
   		$name = (string)Input::get('name');
   		$value = Input::get('value');
   		try {
			$layout = Order::findorFail($id);
			$layout->$name = $value;
	    } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
	        return App::abort(404);
	    }
	    $pass = $layout->valid();
        if($pass->passes()) {
        	$layout->save();
   			$arrReturn = ['status' => 'ok'];
        	$arrReturn['message'] = $layout->name.'Update has been saved';
        } else {
        	$arrReturn['message'] = '';
        	$arrErr = $pass->messages()->all();
        	foreach($arrErr as $value)
        	    $arrReturn['message'] .= "$value\n";
        }
        $response = Response::json($arrReturn);
		$response->header('Content-Type', 'application/json');
		return $response;
	}
	public function addOrder()
	{
   		$this->layout->title = 'Add Order';
		$this->layout->content = View::make('admin.orders-one');
	}

	public function editOrder($orderId)
	{
   		try {
   			$order = Order::with('images')
   								->findorFail($orderId);
	    } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
	        return App::abort(404);
	    }
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

   		$order['images'] = reset($order['images']);
   		$this->layout->title = 'Edit Order';
		$this->layout->content = View::make('admin.orders-one')->with([
															'order' 		=> $order,
															]);
	}
	public function viewOrder($orderId) {
		try {
   			$order = Order::with('billingAddress')
				            ->with('shippingAddress')
				            ->with('orderDetails')
				            ->with('user')
							->findorFail($orderId);
	    } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
	        return App::abort(404);
	    }
	    $order = $order->toArray();

	    $arr_order_detail_temps = $order['order_details'];
	    $arr_options = Options::select('name','key')->get();
		$arr_options_data = array();
		foreach($arr_options as $val){
			$arr_options_data[$val['key']] = $val['name'];
		}
		$arr_existed_product_order_detail = array();
		$arr_new_order_detail = array();
		for($i=0;$i<count($arr_order_detail_temps);$i++){
			if(!in_array($arr_order_detail_temps[$i]['id'], $arr_existed_product_order_detail)){
				$arr_existed_product_order_detail[] = $arr_order_detail_temps[$i]['id'];
				$arr_new_order_detail[] =  $arr_order_detail_temps[$i];
			}
		}
		$order['order_details'] = $arr_new_order_detail;

	    foreach(['billing_address', 'shipping_address'] as $address) {
	    	$order[$address]['country'] = JTCountry::getName($order[$address]['country_id']);
	    	$order[$address]['province'] = JTProvince::getName($order[$address]['province_id']);

	    }
        $this->layout->content = View::make('admin.orders-one')->with([
													'order' => $order,
													'options'=>$arr_options_data
												]);
	}

	public function exportPdf($detailId)
	{
		$svg = OrderDetail::select('svg_file', 'sizew', 'sizeh', 'option')
								->where('id', $detailId)
								->first();

		if( !is_object($svg) ) {
			return 'File does not exist';
		}

		$sizew = $svg->sizew;
		$sizeh = $svg->sizeh;
		if($sizew==0 || $sizeh==0){
			$option = json_decode($svg->option, true);
			
			if(isset($option['size'])){
				// list($w, $h) = split('x', $option['size']);
				$size = explode("x", $option['size']);
				$sizew = (float)$size[0];
				$sizeh = (float)$size[1];
			} else {
				$sizew = $sizeh = 12;	
			}
		}

		$svg_path = str_replace(DS, '/', str_replace(public_path(), '', $svg->svg_file));
		$pdf_name = 'svg_'.$detailId.time().'.pdf';
		$pdf_path = public_path().DS.'assets'.DS.'upload'.DS.'pdfs'.DS;
		if( !file_exists($pdf_path) ) {
			mkdir($pdf_path, 0777);
		}
		$pdf_path .= $pdf_name;
		$svg->sizew = (float)$sizew;
		$svg->sizeh = (float)$sizeh;
		$width = $svg->sizew * 72;
		$height = $svg->sizeh * 72;
		$cmd = PHAMTOM_CONVERT.' "'.URL.'/get-svg?path='.$svg_path."&width={$width}&height={$height}\" ".$pdf_path." {$svg->sizew}in*{$svg->sizeh}in";
		// $cmd = PHAMTOM_CONVERT.' "'.URL.'/get-svg?path='.$svg_path."\" ".$pdf_path." 72in*72in";
		// echo $cmd;die;
		exec($cmd);
		return Redirect::to(URL.'/assets/upload/pdfs/'.$pdf_name);
	}
}