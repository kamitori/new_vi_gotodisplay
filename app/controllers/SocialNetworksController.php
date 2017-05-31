<?php

class SocialNetworksController extends BaseController {

	public function flickrAuth(){
		$request_token_url = "http://www.flickr.com/services/oauth/request_token";
		$oauth_nonce       =md5(microtime().mt_rand());
		$timestamp         = time();
		$consumer_key      =  Configure::where('ckey','=','flickr_app_id')->pluck("cvalue");
		$consumer_secret   = Configure::where('ckey','=','flickr_app_secret')->pluck("cvalue");
		Session::put('flickr_app_id',$consumer_key);
		Session::put('flickr_app_secret',$consumer_secret);
		$sig_method        = "HMAC-SHA1";
		$oauth_version     = "1.0";
		$callback_url      = Input::get('url_callback');
		$callback_url = str_replace("#quick_design","",$callback_url);

		$basestring = "oauth_callback=".str_replace('%7E', '~', rawurlencode(($callback_url)))."&oauth_consumer_key=".$consumer_key."&oauth_nonce=".$oauth_nonce."&oauth_signature_method=".$sig_method."&oauth_timestamp=".$timestamp."&oauth_version=".$oauth_version;
		$basestring = "GET&".str_replace('%7E', '~', rawurlencode(($request_token_url)))."&".str_replace('%7E', '~', rawurlencode(($basestring)));
		$hash_key = $consumer_secret."&";
		$oauth_signature = base64_encode(hash_hmac('sha1', $basestring, $hash_key, true));
		$url = $request_token_url."?oauth_nonce=".$oauth_nonce."&oauth_timestamp=".$timestamp."&oauth_consumer_key=".$consumer_key."&oauth_signature_method=".$sig_method."&oauth_version=".$oauth_version."&oauth_signature=".$oauth_signature."&oauth_callback=".$callback_url;
		// echo $url;die;
		if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			$out = curl_exec($curl);
			$aOut = array();
			if($out!=""){
				$arr_out = explode("&", $out);
				if(in_array('oauth_callback_confirmed=true',$arr_out)){
					foreach($arr_out  as $key=>$value){
						$arr_tmp = explode("=",$value);
						$aOut[$arr_tmp[0]] = $arr_tmp[1];
						if($arr_tmp[0] == "oauth_token_secret"){
							Session::set($arr_tmp[0], $arr_tmp[1]);
						}
					}
				}
				if(in_array('oauth_problem=signature_invalid',$arr_out)){
					$arr_tmp = explode("=",$arr_out[0]);
					$aOut[$arr_tmp[0]] = $arr_tmp[1];
				}
			}
			curl_close($curl);
			return $aOut;
		}
	}

	public function flickrGetUserID(){
		if(!Session::has('user_nsid')){
			if(Session::has('oauth_token_secret')){
				$request_token_url = "http://www.flickr.com/services/oauth/access_token";
				$oauth_nonce       =md5(microtime().mt_rand());
				$timestamp         = time();
				$consumer_key      =  Configure::where('ckey','=','flickr_app_id')->pluck("cvalue");
				$consumer_secret   = Configure::where('ckey','=','flickr_app_secret')->pluck("cvalue");;
				$sig_method        = "HMAC-SHA1";
				$oauth_version     = "1.0";
				$oauth_token      = Input::get('oauth_token');
				$oauth_verifier      = Input::get('oauth_verifier');
				$oauth_token_secret      = Session::get('oauth_token_secret');

				$basestring = "oauth_consumer_key=".$consumer_key;
					$basestring.="&oauth_nonce=".$oauth_nonce;
					$basestring.="&oauth_signature_method=".$sig_method;
					$basestring.="&oauth_timestamp=".$timestamp;
					$basestring.="&oauth_token=".$oauth_token;
					$basestring.="&oauth_verifier=".$oauth_verifier;
					$basestring.="&oauth_version=".$oauth_version;
				$basestring = "GET&".urlencode($request_token_url)."&".urlencode($basestring);
				$hash_key = $consumer_secret."&".$oauth_token_secret;
				$oauth_signature = base64_encode(hash_hmac('sha1', $basestring, $hash_key, true));
				$url = $request_token_url;
					$url .="?oauth_nonce=".$oauth_nonce;
					$url .="&oauth_timestamp=".$timestamp;
					$url .="&oauth_verifier=".$oauth_verifier;
					$url .="&oauth_consumer_key=".$consumer_key;
					$url .="&oauth_signature_method=".$sig_method;
					$url .="&oauth_version=".$oauth_version;
					$url .="&oauth_token=".$oauth_token;
					$url .="&oauth_signature=".$oauth_signature;
				if( $curl = curl_init() ) {
					curl_setopt($curl, CURLOPT_URL, $url);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
					$out = urldecode(curl_exec($curl));
					// echo $out;die;
					$aOut = array();
					if($out!=""){
						$arr_out = explode("&", $out);
						if(in_array('oauth_problem=signature_invalid',$arr_out)){
							$arr_tmp = explode("=",$arr_out[0]);
							$aOut[$arr_tmp[0]] = $arr_tmp[1];
						}else{
							foreach( $arr_out as $key=>$value){
								$arr_tmp = explode("=",$value);
								$aOut[$arr_tmp[0]] = $arr_tmp[1];
								if(isset($aOut['user_nsid'])){
									Session::set('user_nsid',$aOut['user_nsid']);
								}
							}
						}

					}
					echo json_encode($aOut);
					curl_close($curl);
				}
			}else{
				return [];
			}
		}else{
			return array('user_nsid'=>Session::get('user_nsid'));
		}
	}



    public function getImage(){
        if( Request::ajax() && Input::has('link')){
            $arr_return = array(
                "error" => 1,
                "data" =>'Error write file'
                );
            $arr_img = Session::has('user_images')?Session::get('user_images'):array();
            $user_ip = User::getFolderKey();
            $uploaddir = app_path().'/../public_html/assets/upload/themes/'.$user_ip.'/gallery/';
	        if( !File::exists($uploaddir) ) {
	        	File::makeDirectory($uploaddir, 777, true);
	        }
		    $link = Input::get('link');
		    if(Input::get('ext') != ''){
			    $ext = explode("/",Input::get('ext'));
			    $ext = $ext[1];
	 	    }else{
	 	        $ext = 'jpg';
	 	    }
		    $name_image = md5(time()).'.'.$ext;
		    $data = Input::has('data') ? Input::get('data') : [];
		    if( isset($data['store']) && $data['store'] == 'google-drive') {
	        	$check_write = GoogleDrive::downloadFile($link, $uploaddir.$name_image);
		    } else {
		    	$content_file = file_get_contents($link);
		    	$check_write = File::put($uploaddir.$name_image, $content_file);
		    }
	        if($check_write){
	            $arr_return = array(
	                "error" => 0,
	                "data" =>'/assets/upload/themes/'.$user_ip.'/gallery/'.$name_image
	                );
	            $arr_img[$user_ip][] = '/assets/upload/themes/'.$user_ip.'/gallery/'.$name_image;
	            Session::set('user_images', $arr_img);
	            $image = Image::make($uploaddir.$name_image);
	            // $image->resize(250, null);
	            if( !File::exists($uploaddir.'thumbs') ) {
	        		File::makeDirectory($uploaddir.'thumbs', 777, true);
	            }
	            $image->save($uploaddir .'thumbs/thumb_'.$name_image);
	        	return $arr_return;
	        }
	        return [];
	    } else{
            return Redirect::to('/');
        }
    }

    public function importSkyDrive()
    {
    	return View::make('frontend.quick_design.image_from_skydrive');
    }

    public function callBackInstagram()
    {
    	return View::make('frontend.quick_design.callback_instagram');
    }

    public function callBackPinterest()
    {
    	return View::make('frontend.quick_design.callback_pinterest');
    }
}