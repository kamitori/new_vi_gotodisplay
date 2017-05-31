<?php
class UserController extends BaseController {

	public function login()
	{
		$imgs = $this->loadGallery();
		$this->layout->content = View::make('frontend.login');

	}
	public function testing(){
        $this->layout->content = View::make('frontend.test');
    }
	public function signup()
	{
		$imgs = $this->loadGallery();
		$this->layout->content =  View::make('frontend.signup');
	}

	public function create()
	{
		$user = new User;
		$user->email = Input::has("email")?Input::get("email"):'';
		$arr_data = Input::all();
		$JTContact = new JTContact;
		$jt_id = $JTContact->create_contact($arr_data);
		if($jt_id && is_object($jt_id)){
			if( Input::has('password') ) {
				$password = Input::get('password');
				if( !empty($password) ) {
					$user->password = $password;
					$user->password_confirmation = Input::get('password_confirmation');
				}
			}
			$user->active = 1;
			$user->jt_id = (string)$jt_id;
			$pass = $user->valid();
			if($pass->passes()) {
				if( isset($user->password_confirmation) ) {
					unset($user->password_confirmation);
				}
				$user->password = Hash::make($user->password);
				$user->save();
				Auth::user()->loginUsingId($user->id);
				return Redirect::to('/user/addresses');
			}
			$imgs = $this->loadGallery();
			return Redirect::to('/user/signup')->with('error',$pass->messages()->all())->withInput();
		}else{
			return Redirect::to('/user/signup')->with('error',['The email has already been taken.'])->withInput();
		}
	}

	public function logout()
	{
		Auth::user()->logout();
        $imgs = $this->loadGallery();

		return Redirect::to('/');
	}
	public function trackingurl($tracking_numner){
		// echo 123;die;
	    $str_return = '';    
	    $responese_delivery = getDeliveryDetai($tracking_numner);

	    $responese_shipping = getShippingDocument($tracking_numner);
	    $responese = getPuralatorCourrierTrackPackage($tracking_numner);
	    
	    $arr_tracking_information = $responese->TrackingInformationList;
	    if(empty($arr_tracking_information) || !$arr_tracking_information || !is_object($arr_tracking_information)) {
	    	$this->layout->content = View::make('frontend.tracking')->with([
				'error'=>1,
				'img_'=>2,
				'tracking_number'=>$tracking_numner
			]);
	    } else {
	    	$arr_scaninformation = $responese->TrackingInformationList->TrackingInformation->Scans->Scan;
	    
		    $str_return .='<h3 style="text-align:left">Package Tracking Number: '.$tracking_numner.' </h3>';
		    
		    $str_return .='<h3 style="text-align:left">Delivered: <span> '.$responese_delivery->DeliveryDetails->ScanDate .' - '.gmdate("H:i:s",$responese_delivery->DeliveryDetails->ScanTime) .' </h3></span></h3>';
		    
		    $str_return .='<h3 style="text-align:left">Received By: <span> '.$responese_delivery->DeliveryDetails->ScanDetails->DeliverySignature.' </span></h3>';  
		    
		    $arr_data = array();
		    $str_return .='<h1 class="popup_sweet" style="text-align:left">Details</h1>';
		    $str_return .='<table align="center" width="100%" border="1" class="table table-striped" cellpadding="3" cellspacing="0">';
		    $str_return .='<tr>';
		        $str_return .='<th>Scan type</th>';
		        $str_return .='<th>Date</th>';
		        $str_return .='<th>Local Time</th>';
		        $str_return .='<th>City</th>';
		        $str_return .='<th>Description</th>';
		    $str_return .='</tr>';
		    $_shipping_create = 1;
		    for($i=0;$i<count($arr_scaninformation);$i++){
		    	$__scan = $_shipping_create;
		        $arr_data[$i]['ScanType'] = $arr_scaninformation[$i]->ScanType;
		        if($arr_scaninformation[$i]->ScanType=='ProofOfPickUp') $__scan = 2;
		        if($arr_scaninformation[$i]->ScanType=='Other' ) $__scan = 3;
		        if($arr_scaninformation[$i]->ScanType=='Undeliverable' ) $__scan = 4;
		        if($arr_scaninformation[$i]->ScanType=='Delivery' || $arr_scaninformation[$i]->ScanType=='OnDelivery') $__scan = 5;	        
		        if($_shipping_create<$__scan) $_shipping_create = $__scan;
		        $arr_data[$i]['ScanDate'] = $arr_scaninformation[$i]->ScanDate;
		        $arr_data[$i]['ScanTime'] = $arr_scaninformation[$i]->ScanTime;
		        $arr_data[$i]['Depot']['Name'] = $arr_scaninformation[$i]->Depot->Name;
		        $arr_data[$i]['Description'] = $arr_scaninformation[$i]->Description;

		        $str_return .= '<tr>';
		            $str_return .= '<td>'.$arr_scaninformation[$i]->ScanType.'</td>';
		            $str_return .= '<td>'.$arr_scaninformation[$i]->ScanDate.'</td>';
		            $str_return .= '<td>'.gmdate("H:i:s",$arr_scaninformation[$i]->ScanTime).'</td>';
		            $str_return .= '<td>'.$arr_scaninformation[$i]->Depot->Name.'</td>';
		            $str_return .= '<td style="text-align:left">'.'<span >'.$arr_scaninformation[$i]->Description.'</span>';
		            if($arr_scaninformation[$i]->ScanType == 'ProofOfPickUp')
		            { 
		                $arr_data_scan_detail = array();
		                $scandetail = $arr_scaninformation[$i]->ScanDetails;
		                $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>PickUp Confirmation Number: </span> '.$scandetail->PickUpConfirmationNumber.' </p>';
		                $arr_data_scan_detail['PickUpConfirmationNumber'] = $scandetail->PickUpConfirmationNumber;
		                $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>PickUp Contact Name: </span> '.$scandetail->PickUpContactName.' </p>';
		                $arr_data_scan_detail['PickUpContactName'] = $scandetail->PickUpContactName;
		                $str_return .= '<h4>Pickup Address</h4>';                
		                if($scandetail->PickUpAddress->Name !="") {
		                    $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>Name: </span> '.$scandetail->PickUpAddress->Name.' </p>';
		                    $arr_data_scan_detail['PickUpAddress']['Name'] = $scandetail->PickUpAddress->Name;
		                }                
		                if($scandetail->PickUpAddress->Company !="") {
		                    $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>Company: </span> '.$scandetail->PickUpAddress->Company.' </p>';                              
		                    $arr_data_scan_detail['PickUpAddress']['Company'] = $scandetail->PickUpAddress->Company;
		                }
		                if($scandetail->PickUpAddress->Department !="") {
		                    $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>Department: </span>'.$scandetail->PickUpAddress->Department.'</p>';
		                    $arr_data_scan_detail['PickUpAddress']['Department'] = $scandetail->PickUpAddress->Department;
		                }
		                if($scandetail->PickUpAddress->StreetNumber !="") {
		                    $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>Street Number: </span> '.$scandetail->PickUpAddress->StreetNumber.' </p>';
		                    $arr_data_scan_detail['PickUpAddress']['StreetNumber'] = $scandetail->PickUpAddress->StreetNumber;
		                }

		                if($scandetail->PickUpAddress->StreetSuffix !="") {
		                    $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>Street Suffix: </span> '.$scandetail->PickUpAddress->StreetSuffix.' </p>';
		                    $arr_data_scan_detail['PickUpAddress']['StreetSuffix'] = $scandetail->PickUpAddress->StreetSuffix;
		                }

		                if($scandetail->PickUpAddress->StreetName !="") {
		                    $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>Street Name: </span> '.$scandetail->PickUpAddress->StreetName.' </p>';
		                    $arr_data_scan_detail['PickUpAddress']['StreetName'] = $scandetail->PickUpAddress->StreetName;
		                }

		                if($scandetail->PickUpAddress->Floor !="") {
		                    $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>Floor: </span> '.$scandetail->PickUpAddress->Floor.' </p>';
		                    $arr_data_scan_detail['PickUpAddress']['Floor'] = $scandetail->PickUpAddress->Floor;
		                }

		                if($scandetail->PickUpAddress->City !="") {
		                    $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>City: </span> '.$scandetail->PickUpAddress->City.' </p>';
		                    $arr_data_scan_detail['PickUpAddress']['City'] = $scandetail->PickUpAddress->City;
		                }

		                if($scandetail->PickUpAddress->Province !="") {
		                    $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>Province: </span> '.$scandetail->PickUpAddress->Province.' </p>';
		                    $arr_data_scan_detail['PickUpAddress']['Province'] = $scandetail->PickUpAddress->Province;
		                }

		                if($scandetail->PickUpAddress->PostalCode !="") {
		                    $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>Postal Code: </span> '.$scandetail->PickUpAddress->PostalCode.' </p>';
		                    $arr_data_scan_detail['PickUpAddress']['PostalCode'] = $scandetail->PickUpAddress->PostalCode;
		                }

		                if($scandetail->PickUpAddress->PhoneNumber !="") {
		                    $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>Phone Number: </span> '.$scandetail->PickUpAddress->PhoneNumber.' </p>';
		                    $arr_data_scan_detail['PickUpAddress']['PhoneNumber'] = $scandetail->PickUpAddress->PhoneNumber;
		                }

		                if($scandetail->PickUpAddress->FaxNumber !="") 
		                {
		                    $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>Fax Number: </span> '.$scandetail->PickUpAddress->FaxNumber.' </p>';
		                    $arr_data_scan_detail['PickUpAddress']['FaxNumber'] = $scandetail->PickUpAddress->FaxNumber;
		                }
		                $arr_data[$i]['ScanDetails'] = $arr_data_scan_detail;
		            }
		            if($arr_scaninformation[$i]->ScanType == 'Delivery')
		            { 
		                $scandetail = $arr_scaninformation[$i]->ScanDetails;
		                $arr_scan_detail = array();
		                $arr_scan_detail['DeliverySignature'] = $scandetail->DeliverySignature;
		                if($scandetail->DeliverySignature !="") {
		                    $str_return .= '<p style="margin-left:20px;margin-top:10px;"><span>Delivery Signature: </span> '.$scandetail->DeliverySignature.' </p>';
		                    $arr_scan_detail['DeliverySignature'] = $scandetail->DeliverySignature;
		                }
		                $arr_data[$i]['ScanDetails'] = $arr_scan_detail;
		            }
		            $str_return .='</td>';
		        $str_return .='</tr>';
		    }
		    $str_return .='</table>';
			$this->layout->content = View::make('frontend.tracking')->with([
				'img_'=>$_shipping_create,
				'html_'=>$str_return,
				'tracking_number'=>$tracking_numner
			]);
	    }	    
	}
	public function shippingtracking(){
		$arr_list = JTShipping::where('deleted',false)->get();
		
		$this->layout->content = View::make('frontend.shippingtracking')->with([
			'arr_list'=>$arr_list
		]);
	}
	public function updatepassword_ver2(){
		$str = Input::has('order')?Input::get('order'):0;
		$arr_return = array('status'=>'error','message'=>'Invalid data');
		if($str) {
			$user_data = Auth::user()->get();        

			$v_user_id = (int)$user_data->id;

			$one = User::find($v_user_id);
			$one->password = Hash::make($str);
			if($one->save()){
				$user = [
			            'email' => $one->email,
			            'password' => $one->password
			        ];
				Auth::user()->attempt($user, 0);

				$arr_return = array('status'=>'ok','message'=>' has been updated');
			}else{
				$arr_return = array('status'=>'error','message'=>'Cannot update password');
			}
		}
		$response = Response::json($arr_return);
		$response->header('Content-Type', 'application/json');
		return $response;
	}
	public function checkpassword(){
		$str = Input::has('order')?Input::get('order'):0;
		
		$arr_return = array('status'=>'error','message'=>'Invalid data');

		if($str) {
			$user_data = Auth::user()->get();        

			$v_user_id = (int)$user_data->id;
			
			$one = User::find($v_user_id)->first();

			$hashedPassword = $user_data['password'];
			if(!empty($one)){
				if(Hash::check($str, $hashedPassword))
				{
				    $arr_return = array('status'=>'ok','message'=>'');
				}else{
					$arr_return = array('status'=>'erro','message'=>' not correct');
				}
			}
		}
		$response = Response::json($arr_return);
		$response->header('Content-Type', 'application/json');
		return $response;
	}
	public function profile(){
		$all_product = Cart::content();

		$countries = JTCountry::getSource();
		$this->layout->content = View::make('frontend.profile')->with([
			'addresses'  => Address::where('user_id',"=",Auth::user()->get()->id)
									->where('first_name','!=','')
									->where('last_name','!=','')
									->orderBy('default','desc')
									->get(),
			'countries'	=> $countries,
			'total_cart'=>count($all_product)
		]);
	}
	public function changepassword(){
		$all_product = Cart::content();
		
		$this->layout->content = View::make('frontend.changepassword')->with([			
			'total_cart'=>count($all_product)
		]);	
	}
	public function addresses()
	{		
		$imgs = $this->loadGallery();		
		$countries = JTCountry::getSource();
		$this->layout->content = View::make('frontend.address')->with(
			[
				'addresses'  => Address::where('user_id',"=",Auth::user()->get()->id)
											->orderBy('default','dsc')
											->get(),
				'countries'	=> $countries,
				'email'		=> Auth::user()->get()->email
			]);
	}
	public function set_primary_address(){
		$arr_return['status'] = 'error';
		$id = Input::has('order')?Input::get('order'):0;
		if($id) {
			$address = Address::find($id);
			if(isset($address->user_id)){
				$v_user_id = (int)$address->user_id;
				if($v_user_id==(int)Auth::user()->get()->id){

					Address::where('user_id',"=",$v_user_id)->update(array('default'=>0));

					$address->default = 1;
				
					$check = $address->save();
					if($check) {
						$arr_return['status'] = 'ok';
					}
				}				
			}			
		}
		
		$response = Response::json($arr_return);
		$response->header('Content-Type', 'application/json');
		return $response;
	}
	public function updateAddress()
	{
		$arr_return['status'] = 'ok';
		$id = Input::has('id')?Input::get('id'):0;
		if($id) {
			$address = Address::find($id);
		} else {
			$address = new Address;
		}
		$count = Address::where('user_id',Auth::user()->get()->id)->count();
		if($count>=5){
			$arr_return['status'] = 'error';
			$arr_return['message'] = 'You have reach the limit of addresses. Please delete some and try again';
			$response = Response::json($arr_return);
			$response->header('Content-Type', 'application/json');
			return $response;
		}
		$address->first_name = Input::has('first_name')?Input::get('first_name'):'';
		$address->last_name = Input::has('last_name')?Input::get('last_name'):'';
		$address->company = Input::has('company')?Input::get('company'):'';
		$address->address1 = Input::has('address1')?Input::get('address1'):'';
		$address->address2 = Input::has('address2')?Input::get('address2'):'';
		$address->city = Input::has('city')?Input::get('city'):'';
		$address->country_id = Input::has('country')?Input::get('country'):0;
		$address->province_id = Input::has('province')?Input::get('province'):0;
		$address->zipcode = Input::has('zipcode')?Input::get('zipcode'):'';
		$address->phone = Input::has('phone')?Input::get('phone'):'';
		$address->email = Input::has('email')?Input::get('email'):'';
		$address->note = Input::has('note')?Input::get('note'):'';
		$address->note = Input::has('note')?Input::get('note'):'';
		$address->billing_address = Input::has('billing_address')?Input::get('billing_address'):0;
		$address->billing_address = (int) $address->billing_address;

		if($address->billing_address==1){
			$count = Address::where('user_id',Auth::user()->get()->id)->where('billing_address',1)->count();
			if($count){
				$arr_return['status'] = 'error';
				$arr_return['message'] = 'You can only add 1 billing address. Please delete current address and try again!';
				$response = Response::json($arr_return);
				$response->header('Content-Type', 'application/json');
				return $response;
			}
		}

		if(Input::has('default')) {
			$address->default = Input::get('default')=="true"?1:0;
		} else {
			$address->default = 0;
		}
		if($id==0) {
			  $address->user_id = Auth::user()->get()->id;
			  $address->type = 'User';
		}
		$check = $address->save();
		if($check) {
			$arr_return['status'] = 'ok';
			$arr_return['order'] = $address->id;
		}
		$response = Response::json($arr_return);
		$response->header('Content-Type', 'application/json');
		return $response;
	}

	public function deleteAddress()
	{
		$user_id = Auth::user()->get()->id;
		$id = Input::has('id')?Input::get('id'):0;
		$address = Address::find($id);
		if($address) {
			if($address->user_id == $user_id) {
				if($address->delete()) {
					$arr_return['status']='ok';
				} else {
					$arr_return['status']='error';
					$arr_return['message']='Error delete address';
				}
			} else {
				$arr_return['status']='error';
				$arr_return['message']='You do not have permission to delete this address';
			}
		} else {
			$arr_return['status']='error';
			$arr_return['message']='Can not find address';
		}

		$response = Response::json($arr_return);
		$response->header('Content-Type', 'application/json');
		return $response;
	}



	public function forgotPassword()
	{
		$response = Password::user()->remind(Input::only('email'), function($message) {
									  $message->subject('Password reminder');
								  });
		switch ($response ) {
			case Password::INVALID_USER:
					  return Redirect::to('/user/login')
								->with('error', 'Not found email.')
								->with('forgot',true);

			case Password::REMINDER_SENT:
					  return Redirect::to('/user/login')
								->with('error', 'We sent you an email to reset your password.')
								->with('forgot',true);
		}

	}

	public function resetPassword($token)
	{
		$this->layout->content =  View::make('frontend.reset_password')->with('token',$token);
	}

	public function updatePassword()
	{
		$credentials = array(
							  'email' => Input::get('email'),
							  'password' => Input::get('password'),
							  'password_confirmation' => Input::get('password_confirmation'),
							  'token' => Input::get('token')
						  );

		$response = Password::user()->reset($credentials, function($user, $password) {
			  $user->password = Hash::make($password);
			  $user->save();

		 });
		switch ($response) {
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::to('/password/reset/'.Input::get('token'))
											->with('error', Lang::get($response))->withInput();

			case Password::PASSWORD_RESET:
			 	return Redirect::to('/user/login')
										->with('error', 'Your password has been reset.')->withInput();
		}
	}

	
	public function yourCollection()
	{
		$user_data = Auth::user()->get();
		$arr_product_id = array();
		
		if($user_data) {
			$user = User::find($user_data->id);
			if(isset($user->yourcollection) && !empty($user->yourcollection!='')){
				$tmp = json_decode($user->yourcollection,true);
				foreach ($tmp as $key => $value) {
					$arr_product_id[] = $key;
				}
			}
		}else{
			return Redirect::to('/user/login')
										->with('error', '')->withInput();
		}

		$collection = ProductCategory::with(['images' => function($query) {
								$query->select('path');
								$query->first();
							}])
							->where('active', 1)
							->first();
		$collection->products = Collection::getProductsCollection($arr_product_id);
		$collection->pageNum = 1;
		$collection->totalPage = Collection::getTotalPage($collection, 15);
		$collection = $collection->toArray();
		if(isset($collection['images']))
			unset($collection['images']);

		$this->layout->metaInfo['meta_title'] = $collection['name'] = 'Your Collection';
		$this->layout->metaInfo['meta_description'] = $collection['meta_description'];
		$this->layout->content = View::make('frontend.collections-one')->with([
																			'collection' => $collection,
																			'yourcollection'=>$arr_product_id
																		]);
	}

	public  function addRemoveCollection()
	{
		$product_id = Input::has('product_id')?Input::get('product_id'):'';
		$status = Input::has('status')?Input::get('status'):'';
		$arr_return['status'] = 'false';

		$user_data = Auth::user()->get();
		if($user_data)
			$user = User::find($user_data->id);
		else{
			$arr_return['status'] = 'nologin';
			$response = Response::json($arr_return);
			$response->header('Content-Type', 'application/json');
			return $response;
		}

		if(isset($user->yourcollection) && !empty($user->yourcollection!=''))
			$tmp = json_decode($user->yourcollection,true);
		else
			$tmp = array();

			
		if($status==0)
			$tmp[$product_id] = date("Y-m-d H:i:s");
		else if(isset($tmp[$product_id])){
			unset($tmp[$product_id]);
		}
		$user->yourcollection = json_encode($tmp);

		$check = $user->save();
		if($check) {
			$arr_return['status'] = 'ok';
		}
		$response = Response::json($arr_return);
		$response->header('Content-Type', 'application/json');
		return $response;
	}

	 public function checkLogin(){
        $email = Input::get('email');
        $fb_id = Input::get('fb_id');
        $arr_return = [
            "login" => 0,
            "message"=>""
        ];        
        $user = User::where("email","=",$email)->first();
        if($user){
            $user->fb_id = $fb_id;
            $user->save();
            if($user->active == 1){
                Auth::user()->loginUsingId($user->id);
                $arr_return["login"]=1;
            }else{
                $arr_return["message"]="Your account has not been activated yet.";
            }
        }else{
        	$user = User::where("email","=",$email)->where('fb_id','=',$fb_id)->first();
        	if($user){
        		Auth::user()->loginUsingId($user->id);
                $arr_return["login"]=1;
        	}else{
        		$f_name = Input::get('first_name');
        		$l_name = Input::get('last_name');
        		$user = new User;
        		$user->first_name = $f_name;
        		$user->last_name = $l_name;
        		$user->password = Hash::make('');
        		$user->fb_id = $fb_id;
        		$user->email = $email;
        		$user->active = 1;
        		if($user->save()){
        			Auth::user()->loginUsingId($user->id);
                	$arr_return["login"]=1;	
        		}
        	}
        }
        return $arr_return;
    }
    public function loadGallery(){
		$images = $arr_img = array();
    	$user_ip = User::getFolderKey();
        $uploaddir = public_path().DS.'assets'.DS.'upload'.DS.'themes'.DS.$user_ip.DS.'gallery'.DS;
        $images = glob($uploaddir.'*.*');
        if(!empty($images)){
	    	foreach ($images as $key => $img) {
	    		if((strpos($img,'.png') !== false || strpos($img,'.jpg') !== false || strpos($img,'.jpeg') !== false) && (strpos($img,'bottom.png') === false && strpos($img,'top.png') === false && strpos($img,'left.png') === false && strpos($img,'right.png') === false && strpos($img,'center.png') === false)){
	    			$str = str_replace([public_path(), 'assets'.DS.'upload'.DS.'themes'.DS.$user_ip.DS.'gallery', DS], '', $img);
	    			$arr_img[$user_ip][] = '/assets/upload/themes/'.$user_ip.'/gallery/'.$str;
	    		}
	    	}
	    	if(isset($arr_img[$user_ip])) $images = $arr_img[$user_ip];
    	}
    	Session::set('user_ip', $user_ip);
	    Session::set('user_images', $arr_img);
    	return $images;
    }

    public function yourGallery(){
    	$user_data = Auth::user()->get();
		if($user_data)
			$this->layout->content = View::make('frontend.user-gallery')->with([
																		'images' => $this->loadGallery(),
																		'max_storage' => 300,
																		'device' => $this->device
																	]);
		else
			return Redirect::to('/user/login')->with('error', '')->withInput();
    }

    public function removeImageGallery(){

     	$user_ip = Session::has('user_ip')?Session::get('user_ip'):User::getFolderKey();
     	$arr_return = array();
     	$arr_return['status'] = 'false';
     	$uploaddir = public_path().DS.'assets'.DS.'upload'.DS.'themes'.DS.$user_ip.DS.'gallery'.DS;
     	$filename = Input::has('file')?Input::get('file'):'';
     	$filename = str_replace('/assets/upload/themes/'.$user_ip.'/gallery/', $uploaddir, $filename);

     	if (!unlink($filename))
		  $arr_return['status'] = 'Can not remove';
		else
		  $arr_return['status'] = 'ok';

     	return $arr_return;
     }

    public function saveDeviceSize(){
    	$w = Input::has('w')?Input::get('w'):'800';
    	$h = Input::has('h')?Input::get('h'):'600';
    	$arr_device = Session::has('device')?Session::get('device'):array();
    	$arr_device['device_width'] = $w;
    	$arr_device['device_height'] = $h;
    	Session::set('device',$arr_device);
    	$arr_return = array();
    	$arr_return['status'] = 'ok';
    	return $arr_return;
    }

}