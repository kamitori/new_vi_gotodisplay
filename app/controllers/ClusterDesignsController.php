<?php
use Jenssegers\Agent\Agent;
class ClusterDesignsController extends BaseController {

	public function clusterDesign($collection_name, $product_name,$zoom = 1)
	{
        $agent = new Agent();
        $cartEdit = false;
        $svgInfo = ['elements' => []];
        if( $cart_id = Input::get('cart_id') ) {
            if( empty($svgInfo = Cart::get($cart_id)) ) {
                return Redirect::to(URL.'/collections/'.$collection_name.'/quick-design/'.$product_name.'#quick_design');
            }
            $arrSVGInfos = Session::has('svginfos') ? Session::get('svginfos') : [];
            $svgInfo = array_merge($svgInfo->toArray(), ['elements' =>(isset($arrSVGInfos[$cart_id]) ? $arrSVGInfos[$cart_id] : [])]);
            $svgInfo['cart_id'] = $cart_id;
            $cartEdit = true;
        }
        $arr_img = Session::has('user_images')?Session::get('user_images'):array();
        $user_ip = Session::has('user_ip')?Session::get('user_ip'):User::getFolderKey();
        if( isset($arr_img[$user_ip]) ) {
            $arr_img = $arr_img[$user_ip];
        } else {
            $arr_img = [];
        }
        krsort($arr_img);
        $arr_img = array_values($arr_img);
        //query this product
        $product = Product::with('images')
                ->with('optionGroups')
                ->with('options')
                ->where('short_name','=',$product_name)
                ->where('active','=',1)
                ->first();                
        if(is_null($product))
            return App::abort(404);
        $product->quantity = Input::has('quantity') ? Input::get('quantity') : 1;
        $price = Product::getPrice($product);
        $product->sell_price = $price['sub_total'];
        $v_background_image = '';
        $v_square_background_image = '';
        $v_2dbg_image = '';

        $v_horizontal_image = '';
        $v_vertical_image = '';

        $arr_option_group = ProductOptionGroup::getSource(false, true);

        $arr_temp_option_group = array();
        $arr_orientaion_value = array();
        foreach(['option_groups', 'options'] as $value) {
            $tmpData = [];
            if( !empty($product->$value) ){
                foreach($product->$value as $v) {
                    $tmpData[] = $v['id'];
                }
            }
            $arr_temp_option_group[$value] = $tmpData;
            unset($tmpData);
        }

        foreach($arr_option_group as $group){
            if(isset($group['key']) && $group['key'] == 'orientation' && in_array($group['value'], $arr_temp_option_group['option_groups']) ){
                $arr_orientation_option = $group['options'];
                foreach($arr_orientation_option as $opt){
                    
                    if( in_array($opt['value'], $arr_temp_option_group['options']) ){
                        $arr_orientaion_value[$opt['value']] = $opt;
                    }
                }
            }            
        }


        if(isset($product->images) && is_object($product->images)){
            $arr_images_list = $product->images;
            $v_view = 0;
            foreach($arr_images_list as $image){
                if( !empty($image['pivot']['option']) ) {
                    $image['pivot']['option'] = json_decode($image['pivot']['option'], true);                
                    if( isset($image['pivot']['option']['back']) && $image['pivot']['option']['back'] ) {
                        $v_background_image = $image['path'];
                        $v_view = $image['pivot']['option']['view'];
                    }
                    
                    if( isset($image['pivot']['option']['square']) && $image['pivot']['option']['square'] ) {
                        $v_square_background_image = $image['path'];
                    }
                    
                    if( isset($image['pivot']['option']['2d']) && $image['pivot']['option']['2d'] ) {
                        $v_2dbg_image = $image['path'];
                    }

                    if(!empty($v_view)){
                        $v_orientation_value = (int)$v_view[0];
                        if(isset($arr_orientaion_value[$v_orientation_value])){
                            if($arr_orientaion_value[$v_orientation_value]['text'] == 'Horizontal'){
                                $v_horizontal_image = $image['path'];     
                            }else if($arr_orientaion_value[$v_orientation_value]['text'] == 'Vertical'){
                                $v_vertical_image = $image['path'];                
                            }
                        }
                    }
                }
            }
        }
        
        //collection
        $collection = ProductCategory::select('id','short_name','name')
                        ->where('short_name','=',"$collection_name")
                        ->first();

        if(is_null($collection))
            return App::abort(404);
        $collection = $collection->toArray();


		$similar_products = Collection::getSimilarProduct($product, $collection_name,999,'order_no');
		$product = $product->toArray();

        $product['category_id'] = $collection['id'];
        $product['product_type'] = $product['product_type_id'];

        //Get option of product

        //default view
        if(isset($product['default_view'])){
            $default_view  = json_decode($product['default_view']);
        } else {
        	$default_view = [];
        }
        $arr_option_detail = $option_key = array();
        $arr_option = QuickDesign::getOptionProductLink($product['id']); //option cua product id
        if(count($arr_option)>0){
            $option =  QuickDesign::getOptionArray();
            foreach ($arr_option as $option_id) {
                if(isset($option[$option_id])){
                  $kk = $option[$option_id];
                  $option_key[$option_id] =  $option[$option_id];
                  $option_key_id[$kk] =  $option_id;
                }
            }
            $arr_where = array();
            $option_detail = ProductOption::get()->toArray();
            foreach ($option_detail as $key => $value) {
               if(isset($option_key[$value['option_group_id']]))
                    $arr_option_detail[$value['option_group_id']][$value['key']] = $value['name'];
                if( $value['option_group_id'] == 7 ) {
                	if( in_array($value['id'], $default_view) ) {
                        $default_opt[7] =  $value['key'];
                	}
                }
            }
        }

        //Product sizes
        $product_sizes = QuickDesign::getOptionPrice($product['id']);
        $size_default = array(10,10);
        foreach ($product_sizes as $key => $value) {
           if($value['default']==1)
            $size_default = explode("x", str_replace("&nbsp;","",$value['size']));
        }
        if(count($product_sizes)==0)
            $product_sizes = array();

        $filter = array();
        $filter['original'] = array('name'=>'Original');
        $filter['sepia'] = array('name'=>'Sepia');
        $filter['grayscale'] = array('name'=>'GrayScale');

        //reset product field

        $product['option'] = Input::has('option')?Input::get('option'):array();
        $product['border_in'] = isset($product['option'][8])?floatval(str_replace("border", "", $product['option'][8])):'';
        $product['bleed'] = isset($product['option'][6])?$product['option'][6]:'';
        $product['wrap'] = 'natural';
        if( isset($svgInfo['elements'][0]['wrap']) ) {
            $product['wrap'] = $svgInfo['elements'][0]['wrap'];
        } if(isset($product['option'][7]))
            $product['wrap'] = $product['option'][7];
        else if(isset($default_opt[7]))
            $product['wrap'] = $default_opt[7];

        $product['rotate_frame'] = 0;
        $border_frame = isset($product['option'][2])?$product['option'][2]:'';

        if(Input::has('size') && Input::has('id')){
            $sid = Input::get('size');
            $size = QuickDesign::getPriceList(Input::get('id'));
            $size_default[0] = isset($size[$sid]['sizew'])?$size[$sid]['sizew']:$size_default[0];
            $size_default[1] = isset($size[$sid]['sizeh'])?$size[$sid]['sizeh']:$size_default[1];
        }
        if(Input::has('custom-width') && Input::has('custom-height')){
            $size_default[0] = Input::get('custom-width');
            $size_default[1] = Input::get('custom-height');
        }
        $arr_where = $arrbleeb = array();
        $product['bleed'] = 1;

        //Data layout
        $arr_list_layout = $input_data = $svg_setup = array();$content = '';
        if(!isset($product['svg_layout_id']))
            $product['svg_layout_id'] = '[1]'; //set default svg_layout_id
        $arr_list_layout = QuickDesignsController::getProductLayout($product['svg_layout_id']);

        //Layout
        if(isset($arr_list_layout[0])){
            $arr_list_layout[0]['bleed'] = $product['bleed'];
            $svg_setup = DesignOnline::DrawClusterDesign($arr_list_layout[0],1);

        }

        //sort theo thu tu huong x+
        $svg_setup['data'] = aasort($svg_setup['data'], $key='coor_x',$order=1,true);
        if( !empty($svgInfo['elements']) ) {
            foreach($svg_setup['data'] as $k => $v) {
                $svg_setup['data'][$k] = array_merge($v, isset($svgInfo['elements'][$k]) ? $svgInfo['elements'][$k] : []);
            }
        }
        if(Request::ajax()) return $svg_setup;
        //socail
        $arr_socail_id = array();
         $fb_app_id = Configure::where('ckey','=','facebook_app_id')->pluck("cvalue");
        $flickr_app_id= Configure::where('ckey','=','flickr_app_id')->pluck("cvalue");
        $dropbox_app_id= Configure::where('ckey','=','dropbox_app_id')->pluck("cvalue");
        $googledrive_app_id= Configure::where('ckey','=','googledrive_app_id')->pluck("cvalue");
        $skydrive_app_id= Configure::where('ckey','=','skydrive_app_id')->pluck("cvalue");
        $instagram_app_id= Configure::where('ckey','=','instagram_app_id')->pluck("cvalue");
        $arr_socail_id['facebook'] = $fb_app_id;
        $arr_socail_id['flickr'] = $fb_app_id;
        $arr_socail_id['dropbox'] = $dropbox_app_id;
        $arr_socail_id['googledrive'] = $googledrive_app_id;
        $arr_socail_id['skydrive'] = $skydrive_app_id;
        $arr_socail_id['instagram'] = $instagram_app_id;

        $v_multi_piece = 1;

        $view = 'frontend.cluster_design.index';
        $isMobile = false;
        if($agent->isMobile()){
            $view = 'frontend.cluster_design.mobile';
            $isMobile = true;
        }
        $this->layout->content = View::make($view)
                        ->with(array(
                                'collection'        => $collection,
                                'product'           => $product,
                                'isMobile'          => $isMobile,
                                'multi_piece'       => $v_multi_piece,
                                'arr_list_layout'   => $arr_list_layout,
                                'similar_products'  => $similar_products,
                                'product_option'    => $arr_option_detail,
                                'option_key'        => $option_key,
                                'size_default'      => $size_default,
                                'product_sizes'     => $product_sizes,
                                'svg_setup'         => $svg_setup,
                                'arr_img'           => $arr_img,
                                'collection_name'   => $collection_name,
                                'product_name'      => $product_name,
                                'filter'            => $filter,
                                'border_frame'      => $border_frame,
                                'max_w'             => 40,
                                'max_h'             => 40,
                                'arr_socail_id'     => $arr_socail_id,
                                'theme'             => 'frontend',
                                'jt_options'        => (strlen($product['jt_id']) == 24 ? JTProduct::getOptions($product['jt_id']) : []),
                                'arrBackground'     => Session::has('user_backgrounds')?Session::get('user_backgrounds'):array(),
                                'background'        => Configure::getBackground(),
                                'cartEdit'          => $cartEdit,
                                'background_image'  => $v_background_image,
                                'square_background_image' =>$v_square_background_image,
                                'horizontal_image' =>$v_horizontal_image,
                                'vertical_image' =>$v_vertical_image,
                                '2d_bg_image'=>$v_2dbg_image,
                               ));

    }

    public function zoomclusterDesign(){
        $arr_return = array('error'=>1,'data'=>'Invalid data');
        if(Request::ajax()){
            $zoom = Input::has('txt_zoom')?Input::get('txt_zoom'):"";
            $collection_name = Input::has('txt_collection')?Input::get('txt_collection'):"";
            $product_id = Input::has('txt_product')?Input::get('txt_product'):"";
            $data_return = self::clusterDesign($collection_name, $product_id,$zoom);
            $arr_return = array('error'=>0,'data'=>$data_return);
        }
        $response = Response::json($arr_return);
        $response->header('Content-Type', 'application/json');
        return $response;
    }
}