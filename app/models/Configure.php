<?php

class Configure extends BaseModel {

	protected $table = 'configures';

	protected $rules = array(
			'cname' 	=> 'required',
			'ckey' 		=> 'required',
			'cvalue' 	=> 'required',

		);

	public static function getFormat()
	{
		if( !Cache::has('vi_format') ) {
			$decimals = self::where('ckey', 'vi_format')->pluck('cvalue');
			if( $decimals ) {
				Cache::forever('vi_format', $decimals);
			} else {
				$decimals = 2;
			}
		} else {
			$decimals = Cache::get('vi_format');
		}
		return $decimals;
	}

	public static function getMargin()
	{
		if( !Cache::has('margin') ) {
			$margin = self::where('ckey', 'margin')->pluck('cvalue');
			if( $margin ) {
				Cache::forever('margin', $margin);
			} else {
				$margin = 5;
			}
		} else {
			$margin = Cache::get('margin');
		}
		return $margin;
	}

	public static function getBackground()
	{
		if( !Cache::has('background') ) {
			$backgrounds = self::select('cvalue')
								->where('active', 1)
								->where('ckey', 'background')
								->get();
			if( !$backgrounds->isEmpty() ) {
				$arrBackgound = [];
				foreach($backgrounds as $bg) {
					$arrBackgound[] = $bg->cvalue;
				}
				Cache::forever('background', $arrBackgound);
			} else {
				$arrBackgound = [URL.'/assets/images/background/bg_wall5.png'];
			}
		} else {
			$arrBackgound = Cache::get('background');
		}
		return $arrBackgound;
	}

	public static function getGoogleDrive()
	{
		if( Cache::has('googleDrive') ) {
			return Cache::get('googleDrive');
		} else {
			$googleDrive = self::select('ckey', 'cvalue')
							->where('active', 1)
							->where('ckey', 'like', 'google_drive_%')
							->get();
			if( !$googleDrive->isEmpty() ) {
				$arrData = [];
				foreach($googleDrive as $key) {
					$arrData[$key->ckey] = $key->cvalue;
				}
				Cache::forever('googleDrive', $arrData);
				return $arrData;
 			}
 			return [];
		}
	}

	public function images()
	{
		return $this->morphToMany('VIImage', 'imageable', 'imageables', 'imageable_id', 'image_id')
						->withPivot('option')
						->orderBy('imageables.id', 'desc');
	}

	public static function getSignupNewsletterEmails()
	{
		if( Cache::has('signup-newsletter-emails') ) {
			return Cache::get('signup-newsletter-emails');
		} else {
			$mail = self::where('ckey', 'signup-newsletter-emails')->pluck('cvalue');
			if( $mail ) {
				// Cache::forever('signup-newsletter-emails', $mail);
			} else {
				$mail = 'info@anvydigital.com';
			}
			return $mail;
		}
	}

	private static function deleteCache($configure)
	{
		switch ($configure->ckey) {
			case 'meta_title':
			case 'meta_description':
			case 'main_logo':
			case 'favicon':
				Cache::forget('meta_info');
				break;
			case 'vi_format':
				Cache::forget('vi_format');
				break;
			case 'margin':
				Cache::forget('margin');
				break;
			case 'google_drive_email':
			case 'google_drive_key_file':
				Cache::flush('googleDrive');
				break;
			case 'background':
				Cache::flush('background');
				break;
			default:
				break;
		}
	}

	public function afterSave($configure)
	{
		self::deleteCache($configure);
	}

	public function beforeDelete($configure)
    {
		self::deleteCache($configure);
    }
}
