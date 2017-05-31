<?php
use Jenssegers\Agent\Agent;
class WallCollageDesignsController extends BaseController {

	public function wallCollageDesign($collectionName, $productName)
	{
		$product = WallCollage::getProduct($productName, $collectionName, Input::all());
		if( !is_object($product) ) {
			return App::abort(404);
		}
		$v_background_image = '';

        if(isset($product->images) && is_object($product->images)){
            $arr_images_list = $product->images;
            foreach($arr_images_list as $image){
                if( !empty($image['pivot']['option']) ) {
                    $image['pivot']['option'] = json_decode($image['pivot']['option'], true);                
                    if( isset($image['pivot']['option']['back']) && $image['pivot']['option']['back'] ) {
                        $v_background_image = $image['path'];
                    }               
                }
            }
        }
		$svgInfo = [];
		if( $cart_id = Input::get('cart_id') ) {
			if( empty($svgInfo = Cart::get($cart_id)) ) {
				return Redirect::to(URL.'/collections/'.$collectionName.'/quick-design/'.$product_name.'#quick_design');
			}
			$arrSVGInfos = Session::has('svginfos') ? Session::get('svginfos') : [];
			$svgInfo = isset($arrSVGInfos[$cart_id]) ? $arrSVGInfos[$cart_id] : [];
			$product->cart_id = $cart_id;
		}
		$ip = User::getFolderKey();
		$arrBackground 	= Session::has('user_backgrounds')	?Session::get('user_backgrounds'):[];
		$arrImages 		= Session::has('user_images')		?Session::get('user_images')	 :[];
		if( isset($arrImages[$ip]) ) {
			$arrImages = $arrImages[$ip];
		}
		//collection
		$collection = ProductCategory::select('id','short_name','name')
						->where('short_name','=',"$collectionName")
						->first();
		if(is_null($collection))
			return App::abort(404);
		$collection = $collection->toArray();
		$agent = new Agent();
		$view = 'frontend.wall_collage_design.index';
		$isMobile = false;
        if($agent->isMobile()){
            $view = 'frontend.wall_collage_design.mobile';
            $isMobile = true;
        }
		$this->layout->content = View::make($view)->with([
			'collection'=>$collection,
			'product' => $product->toArray(),
			'filters' => [
						'original' 	=> 'Original',
							'sepia'		=> 'Sepia',
							'grayscale'	=> 'GrayScale'
						],
			'arrBackground' => $arrBackground,
			'arrImages'     => $arrImages,
			'isMobile'      => $isMobile,
			'background'	=> Configure::getBackground(),
			'svgInfo'		=> $svgInfo,
			'background_image'  => $v_background_image
		]);
	}

	public function preview3D()
	{
		if( !Request::ajax() ) {
			return App::abort(404);
		}

		$info = Input::get('info');
		$svg = Input::get('svg');
		$svgSetup = Input::get('svg_setup');
		$productId = Input::get('id');

		$svg = preg_replace("/NS[0-9]:/i",'',$svg);
		$svg = preg_replace("/:NS[0-9]/i",'',$svg);
		$IE = strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false
								|| strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false ? true : false;
		if( $IE ) {
				$svg1 = substr($svg, 0, strpos($svg, 'xmlns="http://www.w3.org/2000/svg"') );
				$svg2 = substr($svg, strpos($svg, 'xmlns="http://www.w3.org/2000/svg"') );
				$svg2 = preg_replace('/xmlns="http:\/\/www.w3.org\/2000\/svg"/', '', $svg2, 1 );
				$svg2 = preg_replace('/xmlns=""/', '', $svg2 );
				$svg = $svg1.$svg2;
		}

		$ip = Session::has('user_ip')?Session::get('user_ip'):User::getFolderKey();
		$path = public_path().DS.'assets'.DS.'upload'.DS.'themes'.DS.$ip;
		if( !File::exists($path) ) {
			File::makeDirectory($path, 0777, true);
		}
		file_put_contents($path.DS.'svg_'.$productId.'.svg', $svg);
		$width = $svgSetup['width'];
		$height = $svgSetup['height'];
		$cmd = PHAMTOM_CONVERT.' "'.URL.'/get-svg?path=assets/upload/themes/'.$ip.'/'.'svg_'.$productId.'.svg" '.$path.DS.'png_'.$productId.".png {$width}*{$height}";
		exec($cmd);
		$arrReturn = [
			'width' 	 => $width,
			'height' 	 => $height,
			'bleed' 	 => $svgSetup['bleed'],
			'imageTotal' => 0,
		];
		$imageURL = URL.'/assets/upload/themes/'.$ip;
		$imageWrap = in_array($svgSetup['wrap'], ['natural', 'm_wrap']) ? true : false;

		if( !$imageWrap ) {
			if( $svgSetup['wrap'] == 'white' ) {
				$color = '#ffffff';
			} else if( $svgSetup['wrap'] == 'black' ) {
				$color = '#000000';
			} else if(  strpos($svgSetup['wrap'], '#') !== false ) {
				$color = $svgSetup['wrap'];
			} else {
				$color = '#ffffff';
			}
			$arrReturn['color'] = $color;
		}
		foreach($info as $shapePosition => $shape) {
			foreach($shape as $shapeName => $shapeInfo) {
				$arrReturn['shapes'][$shapePosition][$shapeName]['points'] = $shapeInfo['points'];
				if( /* !$imageWrap && */ $shapeName != 'center' ) {
					continue;
				}
				$mask = new Imagick();
				$mask->newimage($width, $height, new ImagickPixel('transparent'));
				$mask->setimageformat('png');
				$polygon = new ImagickDraw();
				$polygon->setFillColor(new ImagickPixel('black'));
				$polygon->polygon($shapeInfo['points']);
				$mask->drawimage($polygon);
				$image = new Imagick();
				$image->readimage($path.DS.'png_'.$productId.'.png');
				$image->setImageFormat('png');
				$image->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
				$image->setImageMatte(true);
				$image->compositeimage($mask, Imagick::COMPOSITE_DSTIN, 0, 0, Imagick::CHANNEL_ALPHA);
				/*if( isset($shapeInfo['angle']) ) {
					if( $shapeInfo['angle'] == 0 ) {
						$shapeInfo['angle'] = 180;
					}
					$image->rotateImage(new ImagickPixel('none'), (float)$shapeInfo['angle']);
				}*/
				$image->trimimage(0);
				$image->setImagePage(0, 0, 0, 0);
				$imgPath = $path.DS.'png_'.$productId.'_'.$shapePosition.'_'.$shapeName.'.png';
				$image->writeImage($imgPath);
				/*if( isset($shapeInfo['angle']) && DS == '/' ) {
					$cmd = escapeshellcmd('/var/www/vi/app/libs/unrotate -f 40 "'.$imgPath.'" "'.$imgPath.'"');
					exec($cmd.' > /dev/null &');
					$arrReturn['shapes'][$shapePosition][$shapeName]['cmd'] = $cmd;
				}*/
				$arrReturn['shapes'][$shapePosition][$shapeName]['image'] = $imageURL.'/png_'.$productId.'_'.$shapePosition.'_'.$shapeName.'.png?t='.time();
				$arrReturn['imageTotal']++;
			}
		}
		return $arrReturn;
	}

}
