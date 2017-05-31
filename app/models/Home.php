<?php

class Home {

	public static function getMetaInfo()
	{
		$arrData = [];
		if( Cache::has('meta_info') ) {
			$arrData = Cache::get('meta_info');
		} else {
			$arrMeta = ['title_site', 'meta_description', 'main_logo', 'favicon'];
			$configures = Configure::select('ckey', 'cvalue')->whereIn('ckey', ['title_site', 'meta_description', 'main_logo', 'favicon'])->get();
			foreach($configures as $configure) {
				$arrData[$configure['ckey']] = $configure['cvalue'];
			}
			foreach($arrMeta as $key) {
				if( !isset($arrData[$key]) ) $arrData[$key] = '';
			}
			Cache::forever('meta_info', $arrData);
		}

		return $arrData;
	}

	public static function getBanner_carorsell()
	{
		$link = Request::path();
		if($link == '/') $link = '';
		if($link != ''){
			$pos = Home::strposX($link, '/', 2);
			// echo 'pos:'.$pos.'<br/>';
			if($pos){
				$link = substr($link, 0, $pos);	
			}
			// echo 'link:'.$link;exit;
		}

		$html = '';
		if( Cache::has('banners-'.$link) ) {
			$html = Cache::get('banners-'.$link);
		} else {
			$menu = Menu::select('big_banners')->where('link', $link)->where('type', 'frontend');
			if($link == ''){
				$menu->where('name', 'Home');
			}
			$menu = $menu->get()->toArray();
			$arr_used_banners = array();
			if(!empty($menu)){
				if($menu[0]['big_banners'] != null){
					$arr_used_banners = explode(',', $menu[0]['big_banners']);
				}
			}
			$query = Banner::select('id', 'name','description')->where('active', 1);
			$query->where(function($q) {
							$q->orWhere('type', null);
							$q->orWhere('type', '');
						});
			$banners = $query->orderBy('order_no', 'asc')->with('images')->get()->toArray();
			// $arr_banners = $banners;
			// pr($arr_banners);exit;
			$arr_banners = array();
			if(empty($arr_used_banners)){
				// $arr_banners = $banners;
			} else {
				foreach ($banners as $value) {
					if(in_array($value['id'], $arr_used_banners)){
						$arr_banners[] = $value;
					}
				}
			}

			$indicators = '<ol class="carousel-indicators">';
			$inner = '<div class="carousel-inner" role="listbox">';
			$control = '<a class="left carousel-control" href="#banner" role="button" data-slide="prev">
							<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="right carousel-control" href="#banner" role="button" data-slide="next">
							<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</a>';
			$i = 0;
			foreach($arr_banners as $banner) {
				$image = '';
				if( !empty($banner['images']) ) {
					$image = reset($banner['images']);
					$image = $image['path'];
				}
				if( empty($image) ) {
					continue;
				}if($i == 0){					

					$indicators .= '<li data-target="#banner" data-slide-to="'.$i.'" class="active"></li>';
					$inner .= '<div class="item active grid2">						
						<figure class="effect-oscar2">
							<img src="'.URL.'/'.$image.'" alt="'.$banner['name'].'" title="'.$banner['name'].'" />
							<figcaption>
								<h2>'.$banner['name'].'</h2>
								<p>
									'.$banner['description'].'
								</p>
							</figcaption>		
						</figure>
					</div>';
				}else{
					$indicators .= '<li data-target="#banner" data-slide-to="'.$i.'"></li>';
					$inner .= '<div class="item grid2">						
						<figure class="effect-oscar2">
							<img src="'.URL.'/'.$image.'" alt="'.$banner['name'].'" title="'.$banner['name'].'" />
							<figcaption>
								<h2>'.$banner['name'].'</h2>
								<p>
									'.$banner['description'].'
								</p>
							</figcaption>	
						</figure>
					</div>';
				}
				$i++;
			}
			$indicators.='</ol>';
			$inner.='</div>';
			$html = $indicators.$inner.$control;
			// Cache::forever('banners-'.$link, $html);
		}		
		return $html;
	}
	public static function getBanner()
	{
		$link = Request::path();
		if($link == '/') $link = '';
		if($link != ''){
			$pos = Home::strposX($link, '/', 2);
			// echo 'pos:'.$pos.'<br/>';
			if($pos){
				$link = substr($link, 0, $pos);	
			}
			// echo 'link:'.$link;exit;
		}

		$html = '';
		if( Cache::has('banners-'.$link) ) {
			$html = Cache::get('banners-'.$link);
		} else {
			$menu = Menu::select('big_banners')->where('link', $link)->where('type', 'frontend');
			if($link == ''){
				$menu->where('name', 'Home');
			}
			$menu = $menu->get()->toArray();
			$arr_used_banners = array();
			if(!empty($menu)){
				if($menu[0]['big_banners'] != null){
					$arr_used_banners = explode(',', $menu[0]['big_banners']);
				}
			}
			$query = Banner::select('id', 'name','description','link')->where('active', 1);
			$query->where(function($q) {
							$q->orWhere('type', null);
							$q->orWhere('type', '');
						});
			$banners = $query->orderBy('order_no', 'asc')->with('images')->get()->toArray();
			
			$arr_banners = array();
			if(empty($arr_used_banners)){
				
			} else {
				foreach ($banners as $value) {
					if(in_array($value['id'], $arr_used_banners)){
						$arr_banners[] = $value;
					}
				}
			}

			$arr_banner_list_header = array();
			$arr_banner_list_data = array();

			
			

			
			$i = 1;
			foreach($arr_banners as $banner) {
				$link = $banner['link']=="" ? "/":$banner['link'];
				$image = '';
				if( !empty($banner['images']) ) {
					$image = reset($banner['images']);
					$image = $image['path'];
				}
				if( empty($image) ) {
					continue;
				}
				$arr_banner_list_header[] = array(
					"id"=>"tab".$i,
					"tabHeader"=> $banner['name'],
        			"tabdescription"=> ($banner['description']=='' || $banner['description']==null ? '' : $banner['description'])
				);
				$arr_banner_list_data["tab".$i] = array(
					array(
						'src'=>$image,
						'layerImages'=>array(
							array(
								"top"=> 0,
			                    "left"=> 270,
			                    "height"=> 570,
			                    "width"=> 800,
			                    "href"=>  $link,
			                    "id"=> "t".$i
							)
						)
					)
				);		
				$i++;
			}
			
			$html = array(
				'header'=>json_encode($arr_banner_list_header),
				'data'=>json_encode($arr_banner_list_data),
				'total'=>$i
			);
			Cache::forever('banners-'.$link, $html);
			
		}		
		return $html;
	}
	public static function getSmallBanner()
	{
		$link = Request::path();
		if($link == '/') $link = '';
		if($link != ''){
			$pos = Home::strposX($link, '/', 2);
			if($pos){
				$link = substr($link, 0, $pos);	
			}
		}	

		$menu = Menu::select('small_banners')->where('link', $link)->where('type', 'frontend');
		if($link == ''){
			$menu->where('name', 'Home');
		}
		$menu = $menu->get()->toArray();
		$arr_used_banners = array();
		if(!empty($menu)){
			if($menu[0]['small_banners'] != null){
				$arr_used_banners = explode(',', $menu[0]['small_banners']);
			}
		}
		$query = Banner::select('id', 'name', 'link','description')->where('active', 1)->where('type', 'small banner');
		$banners = $query->orderBy('order_no', 'asc')->with('images')->get()->toArray();
		// pr($arr_banners);exit;
		$arr_banners = array();
		foreach ($banners as $value) {
			if(in_array($value['id'], $arr_used_banners)){
				$arr_banners[] = $value;
			}
		}

		if(empty($arr_banners)) return '';

		$banner = $arr_banners[0];
		$image = '';
		if( !empty($banner['images']) ) {
			$image = reset($banner['images']);
			$image = $image['path'];
		}
		if( empty($image) ) {
			return '';
		}
		/*
			<figcaption>
								<h2>'.$banner['name'].'</h2>
								<p>
									'.$banner['description'].'
								</p>
							</figcaption>	
		*/		
		if(empty($banner['link'])) $banner['link'] = '#';
		$html = '<div style="margin-bottom:15px;" class="grid">
					<figure class="effect-oscar">
						<a href="'.$banner['link'].'" target="_blank">
							<img src="'.URL.'/'.$image.'" alt="'.$banner['name'].'" title="'.$banner['name'].'" style="width:100%" />
							
						</a>
					</figure>					
				</div>';
		return $html;
	}

	/**
     * Find the position of the Xth occurrence of a substring in a string
     * @param $haystack
     * @param $needle
     * @param $number integer > 0
     * @return int
     */
    public static function strposX($haystack, $needle, $number){
        if($number == '1'){
            return strpos($haystack, $needle);
        }elseif($number > '1'){
            return strpos($haystack, $needle, Home::strposX($haystack, $needle, $number - 1) + strlen($needle));
        }else{
            return error_log('Error: Value for parameter $number is out of range');
        }
    }

}