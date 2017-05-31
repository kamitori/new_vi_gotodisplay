<?php

class HomeController extends BaseController {

	public function index()
	{
		$view_home = View::make('admin.layout.home');
		$content_home = $view_home->render();		
		
		$this->layout->content = View::make('frontend.index')->with([
												'banner' => Home::getBanner(),
												'content_home'=>$content_home
											]);
	}

	public function getVIImages()
	{
		if( Request::ajax() ){
			$tags = Input::has('tags') ? Input::get('tags') : '';
			$image = VIImage::getOthers(['tags' => $tags]);
			$arrReturn = [];
			if( $image['total'] ) {
				try {
					$service = GoogleDrive::connect();
				} catch(Exception $e) {
					$service = false;
				}
				foreach($image['images'] as $image) {
					if( $image['store'] == 'google-drive' && $image['file_id'] ) {
						if( !$service ) {
							continue;
						}
						$key = md5('150x150jpg');
						$file = GoogleDrive::getFile($image['file_id'], $service);
						if( Cache::tags(['images', $image['id']])->has($key) ) {
							$thumb = URL.'/thumb/'.$image['id'].'/600x450.jpg';
						} else {
							$thumb = URL.'/thumb/'.$image['id'].'/600x450.jpg?path='.urlencode($file->thumbnailLink);
						}
						$ext = $file->mimeType;
						$link = $file->downloadUrl;
					} else {
						$thumb = URL.'/thumb/'.$image['id'].'/150x150.jpg';
						$link = URL.'/'.$image['path'];
						$ext = 'image/'.substr($image['path'], strrpos($image['path'], '.') + 1);
					}
					$arrReturn[] = [
									'id' => $image['id'],
									'thumb' => $thumb,
									'link'	=> $link,
									'ext'	=> $ext,
									'store' => $image['store'],
								];
				}
			}
			return $arrReturn;
		}
		return App::abort(404);
	}

	public function saveSessionImgs(){
		$arr_img = array();
		if( Request::ajax() && Input::has('arrImgs')){
			$arrImgs = Input::get('arrImgs');
			$user_ip = User::getFolderKey();
	        Session::set('user_ip', $user_ip);
	        $arr_img = Session::has('user_images')?Session::get('user_images'):array();
	        foreach ($arrImgs as $value) {
	        	$arr_img[$user_ip][] = str_replace(URL, "", $value);
	        }
	        Session::set('user_images', $arr_img);
	     }
	     return $arr_img;

	}

	public function deleteSessionImgs(){
		if( !Request::ajax() ) {
			return App::abort(404);
		}
		$arrReturn 	= ['status' => 'error'];
		$src 		= Input::has('src') ? Input::get('src') : '';
		if( !empty($src) ) {
			$userIp = User::getFolderKey();
			if( $src == 'all' ) {
				$arrImgs = [$userIp => []];
			} else {
				$src = str_replace(URL.'/', '', $src);
				$arrImgs = Session::has('user_images')?Session::get('user_images'):array();
				foreach ($arrImgs[$userIp] as $key => $value) {
					if( strpos($value, $src) !== false ) continue;
					unset($arrImgs[$userIp][$key]);
				}
			}
	        Session::set('user_images', $arrImgs);
			$arrReturn 	= ['status' => 'ok'];
		}

		return $arrReturn;
	}

	public function submitSubscribe()
    {
        $email = Input::get('email');
        // $name = Input::get('name');
        // $arr_name = explode(" ", $name);
        // $first_name = ucwords($arr_name[0]);
        // $last_name = ucwords(str_replace($first_name." ", "", $name));
        $subscribe_at = date("Y-m-d H:i:s");
        if( filter_var($email, FILTER_VALIDATE_EMAIL) ) {
            $notification = 0;
            $objUser = User::select('id', 'subscribe')->where('email', $email)->first();
            // echo 'objUser->subscribe:'.$objUser->subscribe;exit;
            if($objUser){
            	if(!$objUser->subscribe){
            		$objUser->subscribe = 1;
            		$objUser->save();
            		$notification = 1;
            	}
            } else {
            	try{
	                DB::statement('INSERT INTO `users`(`first_name`,`last_name`, `email`, `password`, `subscribe`, `active`) VALUES ("","", "'. $email .'", "'.Hash::make(str_random(15)).'", 1, 0)');
	                $notification = 1;
	            } catch(Exception $e) {
	                $notification = 0;
	            }
            }
	            
            $arrReturn = ['status' => 'ok', 'message' => 'Thank you for subscribing to our newsletter.<br /> You have been successfully added to our mailing list, keeping you up-to-date with our latest news.'];

            if($notification == 1)
            {
                BackgroundProcess::newsletterMail([
                        'email' => $email,
                        'name' => ''
                    ]);       
            }
    
        } else {
            $arrReturn = ['status' => 'error', 'message' => 'Please enter valid email.'];
        }


        return $arrReturn;
    }

    public function testMail($userId=''){
        /*BackgroundProcess::waitactiveMail([
                        'user_id' => $userId
                    ]);*/

        /*$user = User::find($userId);
        if (!$user) {
            return $this->error('Missing user_id field.');
        }
        $user = $user->toArray();
        unset($user['password'],
            $user['created_by'],
            $user['updated_by'],
            $user['company_id'],
            $user['jt_id']);
        $user['token'] = str_rot13(base64_encode($user['email'].'--'.$user['id']));
        $arrData = ['user' => $user];
        $subject = '[ANVYDIGITAL]You have been registered.';

        // $signupNotificationEmails = Configure::getSignupNotificationEmails();
        // $arrEmailsCC = explode(",", $signupNotificationEmails);
        // $arrEmailsCC = array_map('trim',$arrEmailsCC);

        Mail::send('emails.auth.waitactivate', $arrData, function($message) use($user, $subject) {
            $message->to($user['email'])->subject($subject);
        });*/

        BackgroundProcess::newsletterMail([
                        'subject' => 'test minh',
                        'email' => 'lqminhdev@gmail.com',
                        'name' => 'mmm',
                        'unsubscribe' => '0'
                    ]);

        /*$subject = '[ANVYDIGITAL]minh test.';
        $arrData = [
                        'subject' => 'test minh',
                        'email' => 'lqminhdev@gmail.com',
                        'name' => 'mmm',
                        'unsubscribe' => 0
                    ];
        Mail::send('emails.auth.newsletter', $arrData, function($message) use($subject, $arrData) {
            $message->to($arrData['email'])->subject($subject);
        });*/
        
    }
}
