<?php
use Jenssegers\Agent\Agent;
class QuickDesignsController extends BaseController {

    public static function UploadImagesTemp(){
        $user_ip = User::getFolderKey();
        Session::set('user_ip', $user_ip);
        $arr_img = Session::has('user_images')?Session::get('user_images'):array();
        $data = array();
        $arr_return = array('error'=>'There was an error uploading your files','data'=>array());
        if(!empty($_FILES)){
            $error = false;
            $files = $size = array();
            $uploaddir = public_path().DS.'assets'.DS.'upload'.DS.'themes'.DS.$user_ip.DS.'gallery'.DS;
            if(!File::exists($uploaddir)) {
                // path does not exist
                File::makeDirectory($uploaddir, 0777, true);
            }
            $files_all = array();
            foreach($_FILES as $key=>$file)
            {
                $size = $files = array();
                $path_parts = pathinfo($file['name']);
                $ex = '.'.$path_parts['extension'];
                $filename = str_replace([$ex, ' '],'',$file['name']);
                $file['name'] = $filename.$ex;

                if(move_uploaded_file($file['tmp_name'], $uploaddir .$file['name'])){
                    // echo 123;die;
                    $arr = [ ['name' => $file['name'], 'size' => $file['size']]];
                    if( $ex == '.pdf' ) {
                        $name = md5(time()).'--';
                        if( DS == '\\' ) {
                            // $cmd = app_path('libs'.DS.'pdftopng').' -r 300 '.$uploaddir.$file['name'].' '.$uploaddir.$name;
                            $cmd = app_path('libs'.DS.'pdftopng').' '.$uploaddir.$file['name'].' '.$uploaddir.$name;
                        } else {
                            // $cmd = 'pdftoppm -r 300 -png '.$uploaddir.$file['name'].' '.$uploaddir.$name;
                            $cmd = 'pdftoppm -png '.$uploaddir.$file['name'].' '.$uploaddir.$name;
                        }
                        exec($cmd);
                        $images = glob($uploaddir.$name.'*.*');
                        if( !empty($images) ) {
                            $arr = [];
                            foreach($images as $img) {
                                $arr[] = [
                                        'name' => str_replace([public_path(), 'assets'.DS.'upload'.DS.'themes'.DS.$user_ip.DS.'gallery', DS], '', $img),
                                        'size' => filesize($img)
                                ];
                            }
                        } else {
                            continue;
                        }
                    }
                    foreach($arr as $f) {
                        $files[] = $f['name'];
                        $size[] = floor(((int)$f['size'])/1024);
                        $arr_img[$user_ip][] = '/assets/upload/themes/'.$user_ip.'/gallery/'.$f['name'];
                        list($width, $height) = getimagesize($uploaddir.$f['name']);
                        /*echo "width: " . $width . "<br />";
                        echo "height: " .  $height;*/
                        try {
                            if (!is_dir($uploaddir .'thumbs')) {
                                mkdir($uploaddir .'thumbs');
                            }
                            $image = Image::make($uploaddir.$f['name']);
                            $image->resize(250, null);
                            $image->save($uploaddir .'thumbs/thumb_'.$f['name']);
                        } catch(Exception $e) {

                        }
                        // edit 7/4/2015
                        // convert into multi array upload images
                        $files_all [] = array(
                            'files'=> $files
                            ,'size' => $size
                            ,'url'=>'/assets/upload/themes/'.$user_ip.'/gallery/'
                            ,'full_url'=>'/assets/upload/themes/'.$user_ip.'/gallery/'.$f['name']
                            , 'width' => $width
                            , 'height' => $height
                        );
                    }
                }else{
                    $error = true;
                }
            }
            // $arr_return = array('files' => $files, 'size' => $size, 'url'=>'/assets/upload/themes/'.$user_ip.'/', 'width' => $width, 'height' => $height);
            Session::set('user_images', $arr_img);
            if(!$error) $arr_return = array('error'=>'','data'=>$files_all);
        }
        $response = Response::json($arr_return);
        $response->header('Content-Type', 'application/json');
        return $response;
        // echo json_encode($data);
    }
    // public static function UploadImagesTemp(){
    //     $user_ip = User::getFolderKey();
    //     Session::set('user_ip', $user_ip);
    //     $arr_img = Session::has('user_images')?Session::get('user_images'):array();
    //     $data = array();

    //     if(count($_FILES)>0){
    //         $error = false;
    //         $files = $size = array();
    //         $uploaddir = './assets/upload/themes/'.$user_ip.'/';
    //         if(!File::exists($uploaddir)) {
    //             // path does not exist
    //             File::makeDirectory($uploaddir);
    //             chmod($uploaddir, 0777);
    //         }
    //         foreach($_FILES as $key=>$file)
    //         {
    //             $path_parts = pathinfo($file['name']);
    //             $ex = '.'.$path_parts['extension'];
    //             $filename = str_replace($ex,'',$file['name']);
    //             $filename = self::valid_input_string($filename);
    //             $file['name'] = $filename.$ex;

    //             if(move_uploaded_file($file['tmp_name'], $uploaddir .basename($file['name']))){
    //                 $files[] = $file['name'];
    //                 $size[] = floor(((int)$file['size'])/1024);
    //                 $arr_img[$user_ip][] = '/assets/upload/themes/'.$user_ip.'/'.basename($file['name']);
    //                 list($width, $height) = getimagesize($uploaddir.basename($file['name']));
    //                 /*echo "width: " . $width . "<br />";
    //                 echo "height: " .  $height;*/
    //                 if (!is_dir($uploaddir .'thumbs')) {
    //                     mkdir($uploaddir .'thumbs');
    //                 }

    //                 $image = new ImageController();
    //                 $image->load($uploaddir.basename($file['name']));
    //                 $image->resizeToWidth(250);
    //                 $image->save($uploaddir .'thumbs/thumb_'.basename($file['name']));
    //             }else{
    //                 $error = true;
    //             }
    //         }
    //         Session::set('user_images', $arr_img);
    //         $data = ($error) ? array('error' => 'There was an error uploading your files') : array('files' => $files, 'size' => $size, 'url'=>'/assets/upload/themes/'.$user_ip.'/', 'width' => $width, 'height' => $height);
    //     }

    //     echo json_encode($data);
    // }


    public static function ExportPDFWithInkScape($type='pdf',$path='1',$name='drawing',$bk = false){
        require_once app_path().'/functions/phpsvg/svglib/inkscape.php';
        if($bk){
            if(!file_exists($path)) @mkdir($path,0775);
            $content = File::get($path.$name);
            $v_server = (stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://').$_SERVER['HTTP_HOST'];
            $content = str_replace($v_server, public_path(), $content);
            $new_file = $path.'del-'.$name;
            File::put($new_file, $content);
            $inkscape = new Inkscape( $new_file);
            $inkscape->exportAreaSnap();
            $inkscape->exportTextToPath();
            if($name) $ok = $inkscape->export( $type, $path.md5($name).'.'.$type );
            else $ok = $inkscape->export( $type, $path.'.'.$type );
            @unlink($new_file);
            if ( $ok ){
                return md5($name).'.'.$type;
            }
        }else{
        if(file_exists(public_path() . '/svg/'.$path.'/'.$name.'.svg')){
                $inkscape = new Inkscape( public_path() . '/svg/'.$path.'/'.$name.'.svg' );
            $inkscape->exportAreaSnap();
            $inkscape->exportTextToPath();
            try{
                $ok = $inkscape->export( $type, public_path() . '/pdf/'.$name.'.'.$type );
                if ( $ok ){
                        return '/pdf/'.$name.'.'.$type;
                }
            }
                catch ( Exception $exc ){
            }
            }
    }
        return false;
    }
    public static function create_file_svg($v_content,$v_product_id,$_file_name = '',$p_path_only = false,$svg_path = 'svg'){
        // tam thoi chi ghi file svg ma thoi
        if(strpos($v_content,'<?xml')===false){
            $v_content = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>'.$v_content;
        }
        if(!file_exists(public_path().DS.$svg_path.DS)) @mkdir(public_path().DS.$svg_path.DS,0775);
        $v_path = public_path().DS.$svg_path.DS.$v_product_id.DS;
        if(!file_exists($v_path)){
            @mkdir($v_path,0775);
        }
        if($_file_name!='') $v_path = $v_path.$_file_name.'.svg';
        else $v_path = $v_path.$v_product_id.'.svg';
        $fp = fopen($v_path, 'w');
        fwrite($fp, $v_content, strlen($v_content));
        fclose($fp);
        if($p_path_only) return public_path().DS.$svg_path.DS.$v_product_id.DS;
        return $v_path;
    }
    /*
    * parameter 1 : source_ul : link to image
    * parameter 2 : quality of new image
    * use when upload image or import image/convert to base 64
    * reason: reduce image size , make more space on server and reduce pdf file size
    */
    function simple_compress_image($source_url='http://vi.anvyonline.com/assets/upload/themes/14.161.71.220/Music-image-music-36556275-1680-1050.jpg', $quality=80) {
        $v_server = (stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://').$_SERVER['HTTP_HOST'];
        $destination_url = str_replace($v_server, '', $source_url);
        $destination_url = str_replace('http://vi.anvyonline.com', '', $source_url);
        $destination_url = public_path().'/'.$destination_url;
        $info = getimagesize($source_url);

        if ($info['mime'] == 'image/jpeg'){
            $image = imagecreatefromjpeg($source_url);
            imagejpeg($image, $destination_url, $quality);
        }
        elseif ($info['mime'] == 'image/gif'){
            $image = imagecreatefromgif($source_url);
            imagegif($image, $destination_url, $quality);
        }
        elseif ($info['mime'] == 'image/png'){
            $image = imagecreatefrompng($source_url);
            imagepng($image, $destination_url, $quality);
        }
        return $destination_url;
    }
    function create_pdf_svg($v_content){
        $v_content = self::convert_image_link_to_base64decode($v_content);
        $v_path = self::create_file_svg($v_content,1);
        self::ExportPDFWithInkScape('pdf',1,1);
    }
    
    public function quickDesign($collection_name, $product_name){
        $box_svg_h = 400;
        $box_svg_w = 600;
        $box_svg_w2 = 800;
        if($this->device['mobile']){
            $box_svg_h = (int)((int)$this->device['device_width']*0.95);
            $box_svg_w = (int)((int)$this->device['device_width']*0.95);
            $box_svg_w2 = (int)((int)$this->device['device_width']*0.95);
        }

        $agent = new Agent();
        $cartEdit = false;
        $svgInfo = [];

        if( $cart_id = Input::get('cart_id') ) {
            if( empty($svgInfo = Cart::get($cart_id)) ) {
                return Redirect::to(URL.'/collections/'.$collection_name.'/quick-design/'.$product_name.'#quick_design');
            }
            $arrSVGInfos = Session::has('svginfos') ? Session::get('svginfos') : [];
            $svgInfo = array_merge($svgInfo->toArray(), (isset($arrSVGInfos[$cart_id]) ? $arrSVGInfos[$cart_id] : []));
            $svgInfo['cart_id'] = $cart_id;
        }
        // $arr_img = Session::has('user_images')?Session::get('user_images'):array();
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
        }
        $user_ip = Session::has('user_ip')?Session::get('user_ip'):User::getFolderKey();

        
        //ie up hinh
        if(Input::hasFile('upload_file')){
            $file   = Input::file('upload_file');
            $file_name = $file->getClientOriginalName();
            $uploaddir = './assets/upload/themes/'.$user_ip.'/gallery/';
            if(!File::exists($uploaddir)){
                File::makeDirectory($uploaddir, 0777, true);
            }
            $file->move($uploaddir,$file_name);
            if (!is_dir($uploaddir .'thumbs')) {
                File::makeDirectory($uploaddir.'thumbs', 0777);
            }
            $image = Image::make($uploaddir.$file_name);
            $image->resize(250, null);
            $image->save($uploaddir .'thumbs/thumb_'.$file_name);
            $arr_img[$user_ip][] = '/assets/upload/themes/'.$user_ip.'/gallery/'.$file_name;
            Session::set('user_images', $arr_img);
        }

        if(isset($arr_img[$user_ip]) && is_array($arr_img[$user_ip])){
            $arr_img = $arr_img[$user_ip];
        }
        else
            $arr_img = array();
        krsort($arr_img);
        $arr_img = array_values($arr_img);
        //product
        $product = Product::with('images')
                ->with('optionGroups')
                ->with('options')
                ->where('short_name','=',$product_name)
                ->where('active','=',1)
                ->first();
        if(is_null($product))
            return App::abort(404);
        $product = $product->toArray();
        $v_background_image = '';
        $v_square_background_image = '';
        $v_horizontal_image = '';
        $v_vertical_image = '';
        $v_2dbg_image = '';

        $arr_option_group = ProductOptionGroup::getSource(false, true);

        $arr_temp_option_group = array();
        $arr_orientaion_value = array();
        foreach(['option_groups', 'options'] as $value) {
            $tmpData = [];
            if( !empty($product[$value]) ){
                foreach($product[$value] as $v) {
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
        if(isset($product['images']) && is_array($product['images'])){
            $arr_images_list = $product['images'];
            foreach($arr_images_list as $image){
                $v_view = 0;
                if( !empty($image['pivot']['option']) ) {
                    $image['pivot']['option'] = json_decode($image['pivot']['option'], true);
                    if( isset($image['pivot']['option']['back']) && $image['pivot']['option']['back'] ) {
                        $v_background_image = $image['path'];
                    }
                    if( isset($image['pivot']['option']['square']) && $image['pivot']['option']['square'] ) {
                        $v_square_background_image = $image['path'];
                    }
                    $v_view = $image['pivot']['option']['view'];

                    if( isset($image['pivot']['option']['2d']) && $image['pivot']['option']['2d'] ) {
                        $v_2dbg_image = $image['path'];
                    }
                    
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

        //collection
        $collection = ProductCategory::select('id','short_name','name')
                        ->where('short_name','=',"$collection_name")
                        ->first();

        if(is_null($collection))
            return App::abort(404);
        $collection = $collection->toArray();
        $product['category_id'] = $collection['id'];
        $product['product_type'] = $product['product_type_id'];
        //get option of product
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
            }
        }
        //product_sizes
        $product_sizes = QuickDesign::getOptionPrice($product['id']);
        $size_default = array(10,10);
        $defaultRatio = 0;
        foreach ($product_sizes as $key => $value) {
            if($value['default']==1) {
                $size_default = explode("x", str_replace("&nbsp;","",$value['size']));
                $defaultRatio = $size_default[0] / $size_default[1];
            }
        }
        if( !$defaultRatio && isset($product_sizes[0]['size']) ) {
            $first = explode("x", str_replace("&nbsp;","",$product_sizes[0]['size']));
            $defaultRatio = $first[0] / $first[1];
        }
        if(count($product_sizes)==0)
            $product_sizes = array();
            //$product_sizes = array(1=>array('size'=>'10x10','sell_price'=>0));

        $filter = array();
        $filter['original'] = array('name'=>'Original');
        $filter['sepia'] = array('name'=>'Sepia');
        $filter['grayscale'] = array('name'=>'GrayScale');
	
	$options_data = ProductOption::select('options.key')
                            ->join('option_groups', 'option_groups.id', '=', 'options.option_group_id')
                            ->where('option_groups.name', 'Depth')
                            ->orderBy('options.key', 'asc')
                            ->get();

        $arrbleeb_trans = array();
        
	foreach($options_data as $key => $value){
		$arrbleeb_trans[strval($value->key)]=strval(floatval($value->key));
	}

        //reset product field
        $product['quantity'] = Input::has('quantity')?Input::get('quantity'):1;
        $product['option'] = Input::has('option')?Input::get('option'):array();
        $product['border_in']= isset($product['option'][8])?floatval(str_replace("border", "", $product['option'][8])):'';
        if( isset($svgInfo['options']['border']) ) {
            $product['border_in'] = $svgInfo['options']['border'];
        }
        $product['bleed'] = isset($product['option'][6])?$product['option'][6]:'';
        $product['canvasframe'] = isset($product['option'][5])?$product['option'][5]:'b_frame';

        $border_frame = isset($product['option'][7])?$product['option'][7]:'';
        $edge_color = isset($product['option'][2])?$product['option'][2]:'';
        if($product['id']==188)
            $border_frame = 'silver_edge';

        if(Input::has('size') && Input::has('id')){
            $sid = Input::get('size');
            $size = QuickDesign::getPriceList(Input::get('id'));
            $size_default[0] = isset($size[$sid]['sizew'])?$size[$sid]['sizew']:$size_default[0];
            $size_default[1] = isset($size[$sid]['sizeh'])?$size[$sid]['sizeh']:$size_default[1];
        }
        if(Input::has('custom-width') && Input::has('custom-height')){
            $size_default[0] = Input::get('custom-width');
            $size_default[1] = Input::get('custom-height');
        } else if( isset($svgInfo['options']['size']) ) {
            $size_default = explode('x', str_replace('"', '', $svgInfo['options']['size']));
        }

        $arr_where = $arrbleeb = array();
        $arr_where[] = array('field'=>'type','operator'=>'=','value'=>'Depth');
        $data = array();

        //Depth
        if(isset($option_key[6]))
        $data = ProductOption::select('options.key', 'options.name','options.thumb')
                    ->join('optionables', function($join) use($product){
                        $join->on('options.id', '=', 'optionables.optionable_id')
                            ->where('optionables.product_id', '=', $product['id'])
                            ->where('optionables.optionable_type', '=', 'ProductOption');
                    })
                    ->join('option_groups', 'option_groups.id', '=', 'options.option_group_id')
                    ->where('option_groups.name', 'Depth')
                    ->orderBy('options.key', 'asc')
                    ->get();
        if(empty($data) && isset($option_key[6])){
            $data = ProductOption::select('options.name', 'options.key','options.thumb')
                            ->join('option_groups', 'option_groups.id', '=', 'options.option_group_id')
                            ->where('option_groups.name', 'Depth')
                            ->orderBy('options.key', 'asc')
                            ->get();
        }
        $arr_dept_image = array();
        if(!empty($data)){
            foreach ($data as $value) {                
                $arrbleeb[$arrbleeb_trans[strval($value->key)]] = $value->name;
                if(file_exists(public_path().DS.$value->thumb)) $arr_dept_image[$arrbleeb_trans[strval($value->key)]] = $value->thumb;
            }
        }
        //sort $arrbleeb
        ksort($arrbleeb);
        if (isset($arrbleeb_trans[$product['bleed']]))
            $product['bleed'] = $arrbleeb_trans[$product['bleed']];
        else if(!empty($arrbleeb)){
            reset($arrbleeb);
            $product['bleed'] = key($arrbleeb);
        }else if($product['category_id']==37){
            $product['bleed'] = 0.375;
        }else if($product['category_id']==28){
            $product['bleed'] = 1;
        }else{
            $product['bleed'] = 0.5;
        }
        //Draw svg
        $arr_list_layout = $input_data = array();
        if(isset($product['svg_layout_id']))
            $arr_list_layout = self::getProductLayout($product['svg_layout_id']);
        $content = '';

                    //product option, vertical or horizontal
        $product['rotate_frame'] = 0;

        if(($product['category_id']==29 || $product['product_type']==7) && $product['svg_layout_id']!='0' && $product['svg_layout_id']!=''){
            $svg_setup = self::getProductLayoutSplit($product,$size_default,$arr_list_layout,$content);
            // echo '<pre>';
            // print_r($svg_setup);
            // die;
        }else{
            $input_data['bleed'] = (float)$product['bleed']>0?$product['bleed']:0; //inch
            if( isset($svgInfo['options']['bleed']) ) {
                $input_data['bleed'] = $svgInfo['options']['bleed'];
            } else if(Input::has('bleed')){
                $input_data['bleed'] = (float)Input::get('bleed');
            }
            if($product['border_in']!='')
                $input_data['border_in'] =  $product['border_in'];

            $input_data['width'] = $size_default[0]+$input_data['bleed']; //inch
            $input_data['height'] = $size_default[1]+$input_data['bleed']; //inch
            $input_data['fixed_box'] = array('width'=>$box_svg_w,'height'=>$box_svg_h);
            $input_data['big_resolution'] = 0;
            if($product['category_id']==29){
                $input_data['fixed_box'] = array('width'=>$box_svg_w2,'height'=>$box_svg_h);
                $input_data['big_resolution'] = 1;
            }
            $input_data['image_link'] = "http://vi.local/assets/upload/16x20-main.png";
            if( isset($svgInfo['url']) ) {
                $input_data['image_link'] = $svgInfo['url'];
            }
            $input_data['x_0'] = 0;
            $input_data['y_0'] = 0;
            // if( isset($svgInfo['x']) ) {
            //     $input_data['x_0'] = $svgInfo['x'];
            // }
            // if( isset($svgInfo['y']) ) {
            //     $input_data['y_0'] = $svgInfo['y'];
            // }

            if(Input::has('width')){
                $input_data['width'] = (float)Input::get('width')+$input_data['bleed'];//inch
            }
            if(Input::has('height')){
                $input_data['height'] = (float)Input::get('height')+$input_data['bleed'];//inch
            }
            if(Input::has('border')){
                $input_data['border_in'] = (float)Input::get('border');//inch
            }
            $content = '';

            //Wall splits
            if(isset($product['product_type']) && $product['product_type']==6 && isset($product['svg_layout']) && file_exists(public_path().$product['svg_layout'])&&!is_dir(public_path().$product['svg_layout'])){
                $content = File::get(public_path().$product['svg_layout']);
                $content = self::fullScaleSvg($content,2,array('w'=>$box_svg_w,'h'=>$box_svg_h));
            }

            $product['number_img'] = isset($product['number_img']) ? $product['number_img'] : 0;

            if(isset($product['option'][4]) && $product['option'][4] == 'vertical'
                    || isset($svgInfo['rotateFrame']) && ($svgInfo['rotateFrame'] == 90 || $svgInfo['rotateFrame'] == 270) ){
                $tmp = $input_data['width'];
                $input_data['width'] = $input_data['height'];
                $input_data['height'] =  $tmp;
                $product['rotate_frame'] = 90;
            }
            $svg_setup = DesignOnline::drawWrapFrame($input_data);
        }
        //ajax
        if(Input::has('width') || Input::has('bleed') || Input::has('border')){
            echo json_encode($svg_setup);
            die;
        }
        $arr_socail_id = array();
        $fb_app_id = Configure::where('ckey','=','facebook_app_id')->pluck("cvalue");
        $flickr_app_id= Configure::where('ckey','=','flickr_app_id')->pluck("cvalue");
        $dropbox_app_id= Configure::where('ckey','=','dropbox_app_id')->pluck("cvalue");
        $googledrive_app_id= Configure::where('ckey','=','googledrive_app_id')->pluck("cvalue");
        $skydrive_app_id= Configure::where('ckey','=','skydrive_app_id')->pluck("cvalue");
        $instagram_app_id= Configure::where('ckey','=','instagram_app_id')->pluck("cvalue");

        $arr_socail_id['facebook'] = $fb_app_id;
        $arr_socail_id['flickr'] = $flickr_app_id;
        $arr_socail_id['dropbox'] = $dropbox_app_id;
        $arr_socail_id['googledrive'] = $googledrive_app_id;
        $arr_socail_id['skydrive'] = $skydrive_app_id;
        $arr_socail_id['instagram'] = $instagram_app_id;
        $view = 'frontend.quick_design';
        $isMobile = false;
        if($agent->isMobile()){
            $view = 'frontend.quick_design_mobile';
            $isMobile = true;
        }
        $this->layout->content = View::make($view)
                        ->with(array(
                                'collection'        => $collection,
                                'product'           => $product,
                                'multi_piece'       => false,
                                'isMobile'          => $isMobile,
                                'contents'          => $content,
                                'arr_list_layout'   => $arr_list_layout,
                                'product_option'    => $arr_option_detail,
                                'option_key'        => $option_key,
                                'size_default'      => $size_default,
                                'product_sizes'     => $product_sizes,
                                'svg_setup'         => $svg_setup,
                                'arr_img'           => $arr_img,
                                'filter'            => $filter,
                                'arrbleeb'          => $arrbleeb,
                                'border_frame'      => $border_frame,
                                'edge_color'        =>  $edge_color,
                                'max_w'             => 40,
                                'max_h'             => 40,
                                'arr_socail_id'     => $arr_socail_id,
                                'jt_options'        => JTProduct::getOptions($product['jt_id'], $product['sku']),
                                'defaultRatio'      => $defaultRatio,
                                'svgInfo'           => $svgInfo,
                                'background_image'  => $v_background_image,
                                'square_background_image' =>$v_square_background_image,
                                'horizontal_image' =>$v_horizontal_image,
                                'vertical_image' =>$v_vertical_image,
                                'bg_2d_image'=>$v_2dbg_image,
                                'dept_image'=>$arr_dept_image
                               ));
    }

    public static function drawOnePiece($val,$size_default,$product,$dpi=0,$extra_x = 0,$extra_y=0){
        $input_data['bleed'] = (float)$product['bleed']>0?$product['bleed']:0; //inch
        if(Input::has('bleed')){
            $input_data['bleed'] = (float)Input::get('bleed');
        }
        if($product['border_in']!='')
            $input_data['border_in'] =  $product['border_in'];
        $input_data['bleed'] = 0.05;
        // dpi này là giua màn hinh và kich thuoc gốc của layout
        $input_data['width'] = (round($val['width']/($dpi) )+$input_data['bleed']) ;  //inch
        $input_data['height'] = (round($val['height']/($dpi) )+$input_data['bleed']) ; //inch
        $input_data['fixed_box'] = array('width'=>$box_svg_w2,'height'=>$box_svg_h);
        $input_data['big_resolution'] = 0;
        if($product['category_id']==29){
            $input_data['fixed_box'] = array('width'=>$box_svg_w2,'height'=>$box_svg_h);
            $input_data['big_resolution'] = 1;
        }
        $input_data['image_link'] = "";
        // $input_data['x_0'] = ($val['coor_x']/$dpi)+$extra_x;
        // $input_data['y_0'] = ($val['coor_y']/$dpi)+$extra_y;
        $input_data['x_0'] = (($val['coor_x']*72)/$dpi);
        $input_data['y_0'] = (($val['coor_y']*72)/$dpi);
        if(Input::has('border')){
            $input_data['border_in'] = (float)Input::get('border');//inch
        }
        //product option, vertical or horizontal
        $product['rotate_frame'] = 0;
        $product['svg_layout'] = json_decode(stripcslashes($product['svg_layout']),true);

        return $input_data;
    }

    public static function getProductLayoutSplit($product,$size_default,$arr_list_layout,&$content){
        $svg_setup = array();
        foreach($arr_list_layout as $key => $val){
            $v_extra_x = 0;
            $layout_width = isset($val['wall_size_w'])?$val['wall_size_w']:62;// inch
            $layout_height = isset($val['wall_size_h'])?$val['wall_size_h']:16;// inch
            for($i=0;$i<count($val['data']);$i++){
                $dpi = DesignOnline::getDPIOption($layout_width*72,$layout_height*72,$box_svg_w2+100,$box_svg_h-1);
                if($i>0){
                    $v_extra_x += ($val['data'][$i-1]['width']*72) / $dpi;
                    $svg_setup[] = DesignOnline::drawWrapFramePiece(self::drawOnePiece($val['data'][$i],$size_default,$product,$dpi, $v_extra_x,0));
                }
                else $svg_setup[] = DesignOnline::drawWrapFramePiece(self::drawOnePiece($val['data'][$i],$size_default,$product,$dpi));

            }

            break;// mac dinh ve svg cua thang dau tien thoi
        }

        return $svg_setup;
    }


    public static function getProductLayout($arr_layout_id,$zoom=1){
        $arr_layout = Layout::getListLayoutById($arr_layout_id);
        $arr_return = array();
        foreach($arr_layout as $key=>$val){
            $arr_box = LayoutDetail::getBoxInformationByLyoutID($val['id']);
            $arr_return [] = array(
                'piece'=>count($arr_box)
                ,'data'=>$arr_box
                ,'layout_id'=>$val['id']
                ,'layout_name'=>$val['name']

                ,'wall_size_w'=>isset($val['wall_size_w'])?$val['wall_size_w']*$zoom:1
                ,'wall_size_h'=>isset($val['wall_size_h'])?$val['wall_size_h']*$zoom:1
            );
        }
        return $arr_return;
    }
     // lay du lieu mau, xoa sau khi co backend
    function get_data_sample($number_piece=2,$v_width_piece = 2,$v_height_piece = 1,$v_space = 3,$width=10,$height=8){
        for($i=0;$i<$number_piece;$i++){
               $arr_items[] = array(
                'x'=>($i*10)
                ,'y'=>0
                ,'order'=>$i
                ,'isLandscape'=>false
                ,'space'=>$v_space
            );
        }

        $arr_json[] = array(
            'numberofpiece' => $number_piece
            ,'layout_id' => 71
            ,'width' => $width
            ,'height' =>$height
            ,'product_id' =>14
            ,'size_display'=>'19x10'
            ,'layouts'=>$arr_items
            ,'sku'=>''
            ,'name'=>$number_piece.($v_width_piece >$v_height_piece ?" Along" :"Across").'  8x10'
        );

        $v_width_each_piece = (100 - $v_space) / $v_width_piece;
        $v_height_each_piece = $v_height_piece > 1 ? ((100 - $v_space) / $v_height_piece) : 100;

        return array(
                'width'=>$v_width_each_piece
                ,'height'=>$v_height_each_piece
                ,'json'=>$arr_json
            );
    }
    public function moveImage(){
        $img=Input::file('img');
        if (!is_dir('assets/analyzer')) {
            mkdir('assets/analyzer');
            chmod('assets/analyzer', 0777);
        }
        if($img->move('assets/analyzer',$img->getClientOriginalName())){
            //$url=URL::to('/')."/analyzer/img/".$img->getClientOriginalName();
            //return "<img src=$url />";
            Session::put('img', $img->getClientOriginalName());
            return Redirect::to('collections/analyze_image');
        }else{
            return "Upload that bai";
        }
    }
    public function analyzeImage(){
        $img = Input::get('img');
        $img = str_replace(URL.'/', '', $img);
        $img = str_replace(['/', '\\'], DS, $img);
        $img = public_path($img);
        list($width, $height) = getimagesize($img);
        $size = filesize($img) / (1024 * 1024);
        $size = round($size,2);
        $f = $width/$height;
        $mp = round(($width*$height)/1000000,1);
        if ($f<1) {
            $dimensions = array(
                                array(12,16),
                                array(16,21),
                                array(24,32),
                                array(30,40),
                                array(36,48),
                                array(48,64),
                                array(72,96),
                                );
        } elseif ($f>1) {
            $dimensions = array(
                                array(16,12),
                                array(21,16),
                                array(32,24),
                                array(40,30),
                                array(48,36),
                                array(64,48),
                                array(96,72),
                                );
        } elseif ($f==1) {
            $dimensions = array(
                                array(12,12),
                                array(16,16),
                                array(24,24),
                                array(30,30),
                                array(36,36),
                                array(48,48),
                                array(72,72),
                                );
        }
        foreach ($dimensions as $key => $arr_inch) {
            $diagonal = sqrt($arr_inch[0]*$arr_inch[1]);
            $viewdis = 1.5*$diagonal;
            $ppineed = 3438/$viewdis;
            $ppi = ($width*$height)/($arr_inch[0]*$arr_inch[1]);
            $quantity = $ppi/$ppineed;
            $dimensions[$key][2] = $quantity;
            if ($quantity>95) $dimensions[$key][3] = '<b style="color:#197600">AMAZING</b>';
            elseif ($quantity>45) $dimensions[$key][3] = '<b style="color:#206026">GOOD</b>';
            elseif ($quantity>30) $dimensions[$key][3] = '<b style="color:#244327">ACCEPTABLE</b>';
            elseif ($quantity>22) $dimensions[$key][3] = '<b style="color:#594a30">OK but...</b>';
            elseif ($quantity>1.5) $dimensions[$key][3] = '<b style="color:#8d5b04">FAIR, WILL NEED OPTIMIZATION</b>';
            else $dimensions[$key][3] = '<b style="color:#9f0000">DON\'T EVEN THINK ABOUT IT.</b>';
        }
        if(!Request::ajax()){
            return View::make('frontend.analyze_image')->with(array(
                                                                    'image' => $img,
                                                                    'width' => $width,
                                                                    'height' => $height,
                                                                    'size' => $size,
                                                                    'f' => $f,
                                                                    'mp' => $mp,
                                                                    'dimensions' => $dimensions
                                                                ));
        }else{
            $arr_data = array('image' => $img,
                                'width' => $width,
                                'height' => $height,
                                'size' => $size,
                                'f' => $f,
                                'mp' => $mp,
                                'dimensions' => $dimensions,
                                'image' => Input::get('img')
                            );
            return json_encode($arr_data);
        }
    }

    public function exportSimple($type,$svghtml){
        if(Input::has('type'))
            $type = Input::get('type');
        if(Input::has('svghtml'))
            $svghtml = Input::get('svghtml');
    }

    public function preview3d($type,$product_id,$ext,$size){
        $image = 'png_'.$product_id.'_bleed';
        $arrsize = explode("x", $size);
        $user_ip = Session::has('user_ip')?Session::get('user_ip'):User::getFolderKey();
        return View::make(self::$theme .'.preview3d')->with(array(
                                'type'          => $type,
                                'image'         => $image,
                                'ext'           => $ext,
                                'user_ip'       => $user_ip,
                                'width'     => (float)$arrsize[0],
                                'height'     => (float)$arrsize[1],
                                'bleed'     => (float)$arrsize[2],
                               ));
    }

    public function newPreview3d(){

        if( Request::ajax() ) {
            $browser = Request::server('HTTP_USER_AGENT');
            $product_id = Input::get('product_id');
            $type = Input::get('type');
            $svg = Input::get('svghtml');
            $svg = preg_replace("/NS[0-9]:/i",'',$svg);
            $svg = preg_replace("/:NS[0-9]/i",'',$svg);
            $svg = preg_replace('/stroke="#ff8c00" stroke-width="2"/i','',$svg);
            $svg = preg_replace('/stroke="none" stroke-width="2"/i','',$svg);
            $IE = strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false
                        || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false ? true : false;
            $image_bleed = in_array($type, ['natural', 'm_wrap', 'm_frame']) ? true : false;
            $image_border = in_array($type, ['aluminum_border']) ? true : false;
            if( !Input::has('points') ) {
                if( $image_border ) {
                    if( $IE ) {
                        $svg = preg_replace("/polygon[^>]+fill=\"white\"(.*)\/>/",'fill-opacity="0" stroke-width="0" />',$svg);
                    }else {
                        $svg = preg_replace("/fill=\"white\"(.*)<\/polygon>/",'fill-opacity="0" stroke-width="0"> </polygon>',$svg);
                    }
                }
            }
            if( $image_bleed ) {
                if( $IE ) {
                    $svg = preg_replace("/<polygon[^>]+class=\"bleed\"[^>]+\>/i",'',$svg);
                    $svg = preg_replace("/<path[^>]+class=\"bleed\"[^>]+\>/i",'',$svg);
                    $svg = preg_replace("/<rect[^>]+class=\"bleed\"[^>]+\>/i",'',$svg);
                }else {
                    $svg = preg_replace("/<polygon[^>]+class=\"bleed\"[^>]*><\/polygon>/",'',$svg);
                    $svg = preg_replace("/<path[^>]+class=\"bleed\"[^>]*><\/path>/i", '', $svg);
                    $svg = preg_replace("/<rect[^>]+class=\"bleed\"[^>]*><\/rect>/i", '', $svg);
                }
            }

            if( $IE ) {
                $svg1 = substr($svg, 0, strpos($svg, 'xmlns="http://www.w3.org/2000/svg"') );
                $svg2 = substr($svg, strpos($svg, 'xmlns="http://www.w3.org/2000/svg"') );
                $svg2 = preg_replace('/xmlns="http:\/\/www.w3.org\/2000\/svg"/', '', $svg2, 1 );
                // $svg2 = preg_replace('/xmlns="http:\/\/www.w3.org\/2000\/svg"/', '', $svg2,2);
                $svg2 = preg_replace('/xmlns=""/', '', $svg2 );
                $svg = $svg1.$svg2;
            }
            $agent = new Agent();
            $isSafari = $agent->isSafari() && ($agent->is('OS X') || $agent->is('iPhone'));
            if( $isSafari ){
                $svg = preg_replace('/href/','xlink:href',$svg,1);
            }
            $tmp_svg = $svg;
            if( $image_bleed ) {
                if( $IE ) {
                    $tmp_svg = preg_replace("/<path[^>]+class=\"extra-polygon\"[^>]+\>/i",'',$tmp_svg);
                } else {
                    $tmp_svg = preg_replace("/<path[^>]+class=\"extra-polygon\"[^>]*><\/path>/",'',$tmp_svg);
                }
            }

            $user_ip = Session::has('user_ip')?Session::get('user_ip'):User::getFolderKey();
            $url = Request::root();
            $path = public_path().DS.'assets'.DS.'upload'.DS.'themes'.DS.$user_ip;
            $url_image = $url.'/assets/upload/themes/'.$user_ip;
            if( !File::exists($path) ) {
            	File::makeDirectory($path, 0777, true);
            }
            file_put_contents($path.DS.'svg_'.$product_id.'.svg', $tmp_svg);
            if( Input::has('bleed') ) {
                $bleed = (float)Input::get('bleed');
                $width = (float)Input::get('width');
                $height = (float)Input::get('height');
            } else {
                $svgfile = simplexml_load_file($path.DS.'svg_'.$product_id.'.svg');
                $bleed = (float)$svgfile->rect['width'];
                $width = (float)$svgfile['width'];
                $height = (float)$svgfile['height'];
            }
            $cmd = PHAMTOM_CONVERT.' '.$url.'/get-svg?path=assets/upload/themes/'.$user_ip.'/'.'svg_'.$product_id.'.svg'.'  '.$path.DS.'png_'.$product_id.".png {$width}*{$height}";
            exec($cmd);
            file_put_contents($path.DS.'svg_'.$product_id.'.svg', $svg);

            $time = time();
            $array = ['status' => 'success'];
            if( Input::has('points') ) {
                $points = Input::get('points');
                $points = json_decode($points, true);
                $arrPoints = [];
                foreach ($points as $pointName => $data) {
                    $i = 0;
                    foreach($data['points'] as $key => $point) {
                        $point = (float)$point;
                        if( $key % 2 == 1 ) {
                            $arrPoints[$pointName][$i]['y'] = $point;
                            ++$i;
                        } else {
                            $arrPoints[$pointName][$i]['x'] = $point;
                        }
                    }
                }
                if( !class_exists('Imagick') ) {
                    $array['status'] = 'error';
                    $array['message'] = 'Must install Imagick to use this feature.';
                } else {
                    foreach($arrPoints as $pointName => $point) {
                        if( !$image_bleed && $pointName != 'center' ) continue;
                        $mask = new Imagick();
                        $mask->newimage($width, $height, new ImagickPixel('transparent'));
                        $mask->setimageformat('png');
                        $polygon = new ImagickDraw();
                        $polygon->setFillColor(new ImagickPixel('black'));
                        $polygon->polygon($point);
                        $mask->drawimage($polygon);
                        $image = new Imagick();
                        $image->readimage($path.DS.'png_'.$product_id.'.png');
                        $image->setImageFormat('png');
                        $image->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
                        $image->setImageMatte(true);
                        $image->compositeimage($mask, Imagick::COMPOSITE_DSTIN, 0, 0, Imagick::CHANNEL_ALPHA);
                        if( isset($points[$pointName]['angle']) ) {
                            $angle =  (180 - (float)$points[$pointName]['angle']) / 2 - 90;
                            $image->rotateImage(new ImagickPixel('none'), $angle);
                        }
                        $image->trimimage(0);
                        $image->setImagePage(0, 0, 0, 0);
                        if( $pointName!= 'center' ) {
                            $x = $y = $subtractWidth = $subtractHeight = 0;
                            if( in_array($pointName, ['top', 'bottom']) ) {
                                $subtractWidth  = $image->width - ($width - $bleed * 2);
                                if( isset($points[$pointName]['length']) ){
                                    $subtractWidth  = $image->width - floor((float)$points[$pointName]['length']);
                                }
                                $subtractHeight = $image->height - $bleed;
                                $shapeWidth = $width - $bleed * 2;
                                $shapeHeight = $bleed;
                            } else if( isset($points[$pointName]['angle']) ) {
                                $shapeHeight = $image->height;
                                if( isset($points[$pointName]['length']) ){
                                    $shapeHeight = (float)$points[$pointName]['length'];
                                }
                                $subtractWidth = $image->width - $bleed;
                                $subtractHeight = $image->height - $shapeHeight;
                                $shapeWidth = $bleed;
                            }
                            if( $subtractWidth > 0 ) {
                                $x = round($subtractWidth/2);
                            }
                            if( $subtractHeight > 0 ) {
                                $y = round($subtractHeight/2);
                            }
                            if( $x > 0 || $y > 0 ) {
                                $image->cropImage($shapeWidth, $shapeHeight, $x, $y);
                                $image->trimimage(0);
                            }
                        }
                        $image->writeImage($path.DS.'png_'.$product_id.'_'.$pointName.'.png');
                        $array['images'][$pointName] = $url_image.'/png_'.$product_id.'_'.$pointName.'.png?'.$time;
                        unset($arrPoints[$pointName]);
                        $arrPoints[$pointName]['points'] = $point;
                        if( isset($points[$pointName]['angle']) ) {
                            $arrPoints[$pointName]['angle'] = true;
                        }
                    }
                    if( Input::has('color') ) {
                        $color = Input::get('color');
                        switch ($color) {
                            case 'white':
                            case 'w_frame':
                            case 'white_edge':
                                $color = '#fff';
                                break;
                            case 'blackedge':
                            case 'black':
                            case 'black_frame':
                                $color = '#000';
                                break;
                            case 'silver_edge':
                                $color = '#e3e4e5';
                                break;
                            default:
                                $color = $color;
                                break;
                        }
                        $array['color'] = $color;
                    }

                    $array['points'] = $arrPoints;
                }

            } else {
                $centerWidth = $width - ($bleed * 2);
                $centerHeight = $height - ($bleed * 2);

                if($image_bleed || $image_border) {

                    //top
                    $img = Image::make($path.DS.'png_'.$product_id.'.png');
                    $img->crop($centerWidth, $bleed, $bleed);
                    $img->save($path.DS.'png_'.$product_id.'_top.png');
                    $array['images']['top'] = $url_image.'/png_'.$product_id.'_top.png?'.$time;

                    //bottom
                    $img = Image::make($path.DS.'png_'.$product_id.'.png');
                    $img->crop($centerWidth, $bleed, $bleed, $centerHeight + $bleed);
                    $img->save($path.DS.'png_'.$product_id.'_bottom.png');
                    $array['images']['bottom'] = $url_image.'/png_'.$product_id.'_bottom.png?'.$time;

                    //left
                    $img = Image::make($path.DS.'png_'.$product_id.'.png');
                    $img->crop($bleed, $centerHeight, 0, $bleed);
                    $img->save($path.DS.'png_'.$product_id.'_left.png');
                    $array['images']['left'] = $url_image.'/png_'.$product_id.'_left.png?'.$time;

                    //right
                    $img = Image::make($path.DS.'png_'.$product_id.'.png');
                    $img->crop($bleed, $centerHeight, $bleed + $centerWidth, $bleed);
                    $img->save($path.DS.'png_'.$product_id.'_right.png');
                    $array['images']['right'] = $url_image.'/png_'.$product_id.'_right.png?'.$time;

                    $array['color'] = false;
                } else {
                    switch ($type) {
                        case 'red':
                            $color = '#f00';
                            break;
                        case 'black':
                        case 'black_frame':
                        case 'blackedge':
                            $color = '#000';
                            break;
                        case 'white':
                        case 'w_frame':
                        case 'white_edge':
                            $color = '#fff';
                            break;
                        case 'silver_edge':
                                $color = '#e3e4e5';
                                break;
                        default:
                            $color = $type;
                            break;
                    }
                    $array['color'] = $color;
                }

                $img = Image::make($path.DS.'png_'.$product_id.'.png');
                $img->crop($centerWidth, $centerHeight, $bleed, $bleed);
                $img->save($path.DS.'png_'.$product_id.'_center.png');
            }
            $array['images']['center'] = $url_image.'/png_'.$product_id.'_center.png?'.time();
            $array['bleed'] = $bleed;
            $array['width'] = $width;
            $array['height'] = $height;
            if( Input::has('product_type') && Input::get('product_type') == 10 ) {
                $array['float_canvas'] = true;
            }
            $response = Response::json($array);
            $response->header('Content-Type', 'application/json');
            return $response;
        }

    }
    public function capture3d(){
        $user_ip = Session::has('user_ip')?Session::get('user_ip'):User::getFolderKey();
        $name3d = $_POST['name3d'];
        $data   = $_POST['img'];

        $data = str_replace('data:image/png;base64,', '', $data);
        $data = base64_decode($data);
        $path_folder = public_path().DS.'/assets/upload/themes/'.$user_ip.'/3d';
        if (!is_dir($path_folder)) @mkdir($path_folder,0777);
        $path = $path_folder.DS.$name3d;
        file_put_contents($path, $data);
        $image = new Imagick();
        $image->readimage($path);
        $image->trimimage(0);
        $image->writeImage($path);

        return '/assets/upload/themes/'.$user_ip.'/3d/'. $name3d;
    }
    public function buildSVG($type){
        $browser = $_SERVER['HTTP_USER_AGENT'];
        if(Input::has('product_id'))
            $product_id = Input::get('product_id');
        $IE = strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false
                    || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false ? true : false;
        $agent = new Agent();
        $isSafari = $agent->isSafari() && ($agent->is('OS X') || $agent->is('iPhone'));
        if($type=='image' && Input::has('svghtml')){
            $svg = Input::get('svghtml');
            // $svg = self::beautyfullFormatSvg($svg);
            // $svg = self::fillWhitePolygonSvg($svg);
            if( $IE ) {
                $svg1 = substr($svg, 0, strpos($svg, 'xmlns="http://www.w3.org/2000/svg"') + 1);
                $svg2 = substr($svg, strpos($svg, 'xmlns="http://www.w3.org/2000/svg"') + 1);
                $svg2 = preg_replace('/xmlns="http:\/\/www.w3.org\/2000\/svg"/', '', $svg2, 1 );
                $svg = $svg1.$svg2;
            }
            if( $isSafari ){
                $svg = preg_replace('/NS\d+:href/', 'xlink:href',$svg,1);
            }
            $user_ip = Session::has('user_ip')?Session::get('user_ip'):User::getFolderKey();
            $url = Request::root();
            $path = public_path().DS.'assets'.DS.'upload'.DS.'themes'.DS.$user_ip;
            $url_image = '/assets/upload/themes/'.$user_ip;
            file_put_contents($path.DS.'svg_'.$product_id.'.svg', $svg);

            $svgfile = simplexml_load_file($path.DS.'svg_'.$product_id.'.svg');

            $bleed = (float)$svgfile->rect['width'];
            $width = (float)$svgfile['width'];
            $height = (float)$svgfile['height'];
            $cmd = PHAMTOM_CONVERT.' '.$url.'/get-svg?path=assets/upload/themes/'.$user_ip.'/'.'svg_'.$product_id.'.svg'.'  '.$path.DS.'png_'.$product_id.".png {$width}*{$height}";
            if(exec($cmd)){
                if( Input::has('points') ) {
                    $points = Input::get('points');
                    $points = json_decode($points, true);
                    $arrPoints = [];
                    foreach ($points as $pointName => $data) {
                        if( $pointName != 'center' ) continue;
                        $i = 0;
                        foreach($data['points'] as $key => $point) {
                            $point = (float)$point;
                            if( $key % 2 == 1 ) {
                                $arrPoints[$pointName][$i]['y'] = $point;
                                ++$i;
                            } else {
                                $arrPoints[$pointName][$i]['x'] = $point;
                            }
                        }
                    }
                    if( !class_exists('Imagick') ) {
                        echo 'Must install Imagick to use this feature.';
                        die;
                    } else {                        
                        foreach($arrPoints as $pointName => $point) {
                            $mask = new Imagick();
                            $mask->newimage($width, $height, new ImagickPixel('transparent'));
                            $mask->setimageformat('png');
                            $polygon = new ImagickDraw();
                            $polygon->setFillColor(new ImagickPixel('black'));
                            $polygon->polygon($point);
                            $mask->drawimage($polygon);
                            $image = new Imagick();
                            $image->readimage($path.DS.'png_'.$product_id.'.png');
                            $image->setImageFormat('png');
                            $image->setImageVirtualPixelMethod(Imagick::VIRTUALPIXELMETHOD_TRANSPARENT);
                            $image->setImageMatte(true);
                            $image->compositeimage($mask, Imagick::COMPOSITE_DSTIN, 0, 0, Imagick::CHANNEL_ALPHA);
                            $image->trimimage(0);
                            $image->writeImage($path.DS.'png_'.$product_id.'.png');
                        }
                    }
                } else {
                    $svgfile = simplexml_load_file($path.DS.'svg_'.$product_id.'.svg');
                    if( Input::has('bleed') ) {
                        $bleed = (float)Input::get('bleed');
                    } else {
                        $bleed = (float)$svgfile->rect['width'];
                    }
                    $width = (float)$svgfile['width'];
                    $height = (float)$svgfile['height'];
                    $centerWidth = $width - ($bleed * 2);
                    $centerHeight = $height - ($bleed * 2);
                    $img = Image::make($path.DS.'png_'.$product_id.'.png');

                    $img->crop($centerWidth, $centerHeight, $bleed, $bleed);
                    if(file_exists($path.DS.'png_'.$product_id.'.png')) @unlink($path.DS.'png_'.$product_id.'.png');
                    $img->save($path.DS.'png_'.$product_id.'.png');
                }
                return $url_image.'/'.'png_'.$product_id.".png?".date('d-m-y-h-m-s');

            }
       } else if($type=='double-image' && Input::has('imglink')){
            $img_source = Input::get('imglink');
            $img_dest = str_replace(".png", time().".png", $img_source);
            copy($_SERVER['DOCUMENT_ROOT'].$img_source, $_SERVER['DOCUMENT_ROOT'].$img_dest);
            chmod($_SERVER['DOCUMENT_ROOT'].$img_dest, 0777);
            return $img_dest;
       } else if($type=='fast-thums' || $type=='fill-white-fast'){
            $svg = Input::get('svghtml');
            if( $IE ) {
                $svg1 = substr($svg, 0, strpos($svg, 'xmlns="http://www.w3.org/2000/svg"') + 1);
                $svg2 = substr($svg, strpos($svg, 'xmlns="http://www.w3.org/2000/svg"') + 1);
                $svg2 = preg_replace('/xmlns="http:\/\/www.w3.org\/2000\/svg"/', '', $svg2, 1 );
                $svg = $svg1.$svg2;
            }
            if($type=='fill-white-fast')
                $svg = self::cluterFillWhitePolygon($svg);
            $user_ip = Session::has('user_ip')?Session::get('user_ip'):User::getFolderKey();
            $url = Request::root();
            $path = public_path().DS.'assets'.DS.'upload'.DS.'themes'.DS.$user_ip;
            if( !File::exists($path) ) {
                File::makeDirectory($path, 0777, true);
            }
            $url_image = $url.'/assets/upload/themes/'.$user_ip;
            file_put_contents($path.DS.'svg_'.$product_id.'.svg', $svg);
            $svgfile = simplexml_load_file($path.DS.'svg_'.$product_id.'.svg');
            $bleed = (float)$svgfile->rect['width'];
            $width = (float)$svgfile['width'];
            $height = (float)$svgfile['height'];
            $cmd = PHAMTOM_CONVERT.' '.$url.'/get-svg?path=assets/upload/themes/'.$user_ip.'/'.'svg_'.$product_id.'.svg'.'  '.$path.DS.'png_'.$product_id.".png {$width}*{$height}";
            if(exec($cmd))
                return $url_image.'/'.'png_'.$product_id.".png";
       }

    }

    public function createSvgFileFromString($content,$product_id,$mod='',$view_dpi=1){
        if($mod=='REMOVE_ID')
            $content = self::removeIdInSvg($content);
        $content = self::beautyfullFormatSvg($content);
        //$content = self::fillWhitePolygonSvg($content);
        $content = self::fullScaleSvg($content,(float)$view_dpi);
        $user_ip = Session::has('user_ip')?Session::get('user_ip'):User::getFolderKey();
        $outfile = "/assets/upload/themes/".$user_ip."/svg_".$product_id;
        if($mod=='RANDOM_KEY')
            $outfile .= (string)time();
        $outfile .= ".svg";
        $arr_cont = self::changeSize($content);
        $fp = fopen($_SERVER['DOCUMENT_ROOT'].$outfile ,"wb");
        fwrite($fp,$arr_cont['content']);
        fclose($fp);
        chmod($_SERVER['DOCUMENT_ROOT'].$outfile, 0777);

        if(Input::has('remove_poly'))
            $remove_poly = Input::get('remove_poly');
        else
            $remove_poly = 0;
        $img_bleed = array();

        $outfile = "/assets/upload/themes/".$user_ip."/svg_".$product_id.'_center.svg';
        $arr_cont = self::changeSize($content);
        $fp = fopen($_SERVER['DOCUMENT_ROOT'].$outfile ,"wb");
        fwrite($fp,$arr_cont['content']);
        fclose($fp);
        $arr_cont['link'] = $outfile;
        $img_bleed[0] = $arr_cont;

        return $img_bleed;
    }
    public function removeIdInSvg($content){
        $pattern = '/id="(.*?)"/i';
        $replacement = '';
        $content = preg_replace($pattern,$replacement,$content);
        return $content;
    }
    public function beautyfullFormatSvg($content){
        $patterns = array('/\t/','/</');
        $replaces  = array('',"\n<");
        $content = preg_replace($patterns,$replaces,$content);

        $content = preg_replace("/\n<\//","</",$content);//enter
        $content = preg_replace('/</',"\t<",$content);//tab all
        $content = preg_replace("/\t<\//","</",$content); //bsp

        $patterns = array("/\t<svg/","/<image/","/<\/g>/","/<\/svg>/","/\t<!--/");
        $replaces  = array("<svg","\t<image","\n\t</g>","\n</svg>","<!--");
        $content = preg_replace($patterns,$replaces,$content);

        return $content;
    }
    public function removePolygonSvg($content){
        $content = preg_replace("/<polygon(.*)<\/polygon>/","",$content);
        return $content;
    }
    public function fillWhitePolygonSvg($content){
        $IE = strpos($_SERVER['HTTP_USER_AGENT'], 'Trident') !== false
                    || strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false ? true : false;
        if( $IE ) {
            $content = preg_replace("/<polygon(.*)fill=\"(.*)\"(.*)fill-opacity=\"(\d*\.?\d+)\"(.*)>/i",'<polygon${1}fill="white"${3}fill-opacity="1"${5}>',$content);
        }else {
            $content = preg_replace("/<polygon(.*)fill=\"(.*)\"(.*)fill-opacity=\"(.*)\"(.*)<\/polygon>/",'<polygon${1}fill="white"${3}fill-opacity="1"${5}</polygon>',$content);
        }
        return $content;
    }
    //
    public function create_pdf_zip_image_file($content){

    }
    public function  cluterFillWhitePolygon($svg){
        $svg = preg_replace("/fill-opacity=\"0.4\"/si", "fill-opacity=\"1\"", $svg);
        return $svg;
    }
    public function convert_image_link_to_base64decode($content){
        $patterns = '/<image [^>]+ xlink:href=\"(.+?)\" [^>]+><\/image>/';
        preg_match_all($patterns, $content, $arr_math_path);
        if(!isset($arr_math_path[1]))   return $content;
        foreach ($arr_math_path[1] as $str) {
            if($_SERVER['HTTP_HOST']=='vi.anvyonline.com'){
                $v_server = (stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://').$_SERVER['HTTP_HOST'];
                $str = str_replace($v_server, '', $str);
                $v_new_link = base64_encode(public_path().$str);
            }else{
                $v_new_link = base64_encode(file_get_contents($str));
            }
            $content = str_replace($str, 'data:image/jpg;base64,'.$v_new_link, $content);
        }
        return $content;
    }
    function getLayoutInfoByProductSku($v_sku){
        $v_product = Product::select('layout_id')->where('sku', $v_sku)->first();
        $arr_layout = json_decode($v_product->layout_id,true);
        $v_layout = Layout::where('id', $arr_layout[0])->first();
        return array('w'=>$v_layout->wall_size_w*72,'h'=>$v_layout->wall_size_h*72);
    }
    public function ScalePreview($v_sku = ''){
        $arr_return = array('error'=>1,'message'=>'Invalid data','_data_'=>'');
        if(Request::ajax()){
            $v_product = Product::select('id')->where('sku', $v_sku)->first();
            $arr_box_size = self::getLayoutInfoByProductSku($v_sku);
            $v_data_svg = Input::has('svg_data') ? Input::get('svg_data'):"";
            $v_screen_width = Input::has('width') ? Input::get('width'):0;
            $v_screen_height = Input::has('height') ? Input::get('height'):0;
            $v_scale = DesignOnline::getDPIOption($arr_box_size['w'],$arr_box_size['h'],$v_screen_width,$v_screen_height);
            $v_background_image = Input::has('background_image') ? Input::get('background_image'):'';
            $v_new_content = self::fullScaleSvg($v_data_svg,$v_scale,array());
            $v_file_name = md5(date('d-m-y-h-m-s'));

            $v_path = self::create_file_svg($v_new_content,$v_product->id,$v_file_name,false,'/svg/'.$v_product->id.'/preview/');
            $v_file_image_name = HomeController::ExportPDFWithInkScape('png',$v_path,'',true);
            if($v_file_image_name) $v_file_image_name = str_replace(public_path(), url(), $v_path).'.png';

            $arr_data_position[0] = array(
                'id'=>1
                ,'width'=>($arr_box_size['w']) // inc
                ,'height'=>($arr_box_size['h']) // inc
                ,'coor_x'=>0
                ,'coor_y'=>0
                ,'layout_id'=>0
            );

            // $arr_data_position[1] = array(
            //     'id'=>2
            //     ,'width'=>250 // inc  center
            //     ,'height'=>150 // inc
            //     ,'coor_x'=>($v_screen_width/2) // pt center
            //     ,'coor_y'=>0
            //     ,'layout_id'=>0
            // );

            $arr_layout[0] = array(
                'piece'=>1
                ,'data'=>$arr_data_position
                ,'layout_name'=>''
                ,'layout_id'=>0
                ,'layout_imgae'=>''
                ,'bleed'=>0.01
                ,'wall_size_w'=>$arr_box_size['w']
                ,'wall_size_h'=>$arr_box_size['h']
            );
            $svg_setup = DesignOnline::DrawClusterDesign($arr_layout[0]);
            $v_svg_content = '
                <svg id="svg_svg_main" xmlns="http://www.w3.org/2000/svg" version="1.1" width="1000" height="281.25" xmlns:xlink="http://www.w3.org/1999/xlink">
                     <g>
                      <title>Layer 1</title>
                      <image x="38.582677" y="-52.755897" width="454.708671" height="598.708674" id="svg_8" xlink:href="'.$v_background_image.'" />
                      <image x="223.62203" y="218.897602" width="79.90554" height="47.527593" id="svg_9" xlink:href="'.$v_file_image_name.'" />
                     </g>
                    </svg>
            ';
            $v_path = self::create_file_svg($v_new_content,$v_product->id,$v_file_name);
            $v_file_image_name = HomeController::ExportPDFWithInkScape('png',$v_path,'',true);
            if($v_file_image_name) $v_file_image_name = str_replace(public_path(), url(), $v_path).'.png';

            $arr_data = array(
                'img'=>$v_file_image_name
                ,'svg_setup'=>$svg_setup
            );


            $arr_return = array('error'=>0,'message'=>'','_data_'=>$arr_data);
        }
        $response = Response::json($arr_return);
        $response->header('Content-Type', 'application/json');
        return $response;
    }

    public static function fullScaleSvg($content,$scale,$box_size=array()){

        $browser = $_SERVER['HTTP_USER_AGENT'];
        //$box_size = array('w'=>600,'h'=>400);//pt
        //tinh lai scale tu dong dua vao box_size
        if(!empty($box_size) && isset($box_size['w']) && isset($box_size['h'])){
            $file_size = array();
            $patterns = '/(.*<svg[^>]* width=")(-?[\d.]+)(.*)/si';
            preg_match($patterns, $content, $arr_math);
            if(isset($arr_math[2])){
                $file_size['w'] = floatval($arr_math[2]);
            }
            $patterns = '/(.*<svg[^>]* height=")(-?[\d.]+)(.*)/si';
            preg_match($patterns, $content, $arr_math);
            if(isset($arr_math[2])){
                $file_size['h'] = floatval($arr_math[2]);
            }
            if($box_size['w']/$file_size['w'] > $box_size['h']/$file_size['h']){
                $scale = $box_size['h']/$file_size['h'];
            }else{
                $scale = $box_size['w']/$file_size['w'];
            }
        }

        $svg['svg']=array('width','height');
        // $svg['image']=array('width','height','x','y');
        foreach ($svg as $element => $arr) {
            foreach ($arr as $attr) {
                $patterns = '/(.*<'.$element.'[^>]* '.$attr.'=")(-?[\d.]+)(.*)/si';
                preg_match($patterns, $content, $arr_math);
                if(isset($arr_math[2])){
                    $newsize = floatval($arr_math[2])*$scale;
                    $content = preg_replace($patterns, "\${1}{$newsize}\${3}", $content);
                }
            }
        }

        //rotate image
        $patterns = "/(.*<image[^>]* transform=\"rotate[(])([\d.]+[\s])([\d.]+[\s])([\d.]+)([)])(.*<\/image>)/si";
        $arr_math = array();
        preg_match($patterns, $content, $arr_math);
        if(count($arr_math)>0){
            $rotate = $arr_math[2].(float)$arr_math[3]*$scale." ".(float)$arr_math[4]*$scale;
            $content = preg_replace($patterns, "\${1}{$rotate}\${5}\${6}", $content);
        }
        // end image

        //rect
        $rect =array('width','height','x','y');
        $arr_rect = explode("</rect>",$content);
        $tmp_cont = '';
        foreach ($arr_rect as $str) {
            // echo '1===<br />';
            foreach ($rect as $attr) {
                $patterns = '/(.*<rect[^>]* '.$attr.'=")(-?[\d.]+)(.*)/si';

                preg_match($patterns, $str, $arr_math);
                if(isset($arr_math[2])){
                    $newsize = floatval($arr_math[2])*$scale;
                    $str = preg_replace($patterns, "\${1}{$newsize}\${3}", $str);
                }

            }
            $tmp_cont .= $str.'</rect>';
        }
        $tmp_cont = str_replace("</svg></rect>", "</svg>", $tmp_cont);

        $content = $tmp_cont;
        // image
             //rect
            $images =array('width','height','x','y');
            $arr_rect = explode("</image>",$content);
            $tmp_cont = '';
            foreach ($arr_rect as $str) {
                // echo '1===<br />';
                foreach ($rect as $attr) {
                    $patterns = '/(.*<image[^>]* '.$attr.'=")(-?[\d.]+)(.*)/si';

                    preg_match($patterns, $str, $arr_math);
                    if(isset($arr_math[2])){
                        $newsize = floatval($arr_math[2])*$scale;
                        $str = preg_replace($patterns, "\${1}{$newsize}\${3}", $str);
                    }

                }
                $tmp_cont .= $str.'</image>';
            }
            $tmp_cont = str_replace("</svg></image>", "</svg>", $tmp_cont);
            $content = $tmp_cont;
        // end image

        $arr_math_path = array();
        $patterns = '/d="(.*)"+>/i';
        preg_match_all($patterns, $content, $arr_math_path);
        if(isset($arr_math_path[2])){
            foreach($arr_math_path[2] as $key =>$val){
                $v_value = '';
                $v_temp2 = str_replace('M', ' M ', $val);
                $v_temp2 = str_replace('m', ' m ', $v_temp2);
                $v_temp2 = str_replace('L', ' L ', $v_temp2);
                $v_temp2 = str_replace('l', ' l ', $v_temp2);
                $v_temp2 = str_replace('Z', ' Z ', $v_temp2);
                $v_temp2 = str_replace('z', ' z ', $v_temp2);
                $v_temp2 = str_replace('C', ' C ', $v_temp2);
                $v_temp2 = str_replace('c', ' c ', $v_temp2);
                $v_temp2 = str_replace('H', ' H ', $v_temp2);
                $v_temp2 = str_replace('h', ' h ', $v_temp2);
                $v_temp2 = str_replace('S', ' S ', $v_temp2);
                $v_temp2 = str_replace('s', ' s ', $v_temp2);
                $v_temp2 = explode(' ', $v_temp2);
                $v_temp = '';
                for($j=0;$j<count($v_temp2);$j++){
                    if($v_temp2[$j]=='') continue;
                    if(!is_numeric($v_temp2[$j])){
                            $arr_expolode = explode(',', $v_temp2[$j]);
                            if(count($arr_expolode)==1){
                                $v_value .= $v_temp2[$j] .' ';
                    }else{
                                for($k=0;$k<count($arr_expolode);$k++){
                                    $v_value .= (float)$arr_expolode[$k]*$scale .' ';
                                }
                            }
                        }else{
                            $v_value .= ''.((float)$v_temp2[$j]*$scale) . ' ';
                    }
                    }
                $arr_math_path[3][] = $v_value;
            }
            foreach($arr_math_path[0] as $key =>$val){
                $new_val = str_replace($arr_math_path[2][$key],$arr_math_path[3][$key],$val);
                $content = str_replace($val, $new_val, $content);
            }
        }

        //translate
        $patterns = '/translate\((.*),+(.*)\)/i';
        preg_match_all($patterns, $content, $arr_math_path);
        $arr_change = array();
        foreach ($arr_math_path[1] as $key => $value) {
            $arr_change[1][$key] = (float)$value*$scale;
        }
        foreach ($arr_math_path[2] as $key => $value) {
            $arr_change[2][$key] = (float)$value*$scale;
        }
        foreach ($arr_math_path[0] as $key => $value) {
            $new_val = str_replace($arr_math_path[1][$key],$arr_change[1][$key],$value);
            $new_val = str_replace($arr_math_path[2][$key],$arr_change[2][$key],$new_val);
            $content = str_replace($value, $new_val, $content);
        }

        // update scale cua tag g field stranform cho truong hop ve = shape all
        /*
            $patterns = '/<g [^>]+ transform=\"(.+?)\" [^>]+><\/g>/';
            preg_match_all($patterns, $content, $arr_math_path);

                    var_dump ($arr_math_path);
                     $arr_rect = explode("</g>",$content);

             foreach ($arr_rect as $str) {
                    $patterns = '/(.*<g[^>]* transform=")(-?[\d.]+)(.*)/si';

                    preg_match($patterns, $str, $arr_math);
                    var_dump($arr_math)              ;die;
                    if(isset($arr_math[2])){
                        $newsize = floatval($arr_math[2])*$scale;
                        $str = preg_replace($patterns, "\${1}{$newsize}\${3}", $str);
                    }

                $tmp_cont .= $str.'</g>';
            }

            die;
        */
        //poligon
        if(strpos($browser,'MSIE') ){
           $patterns = '/(.*<polygon[^>]* points=")(.*,[\d.]+)(.*)>/si';
        }
        else{
            $patterns = '/(.*<polygon[^>]* points=")(.*,[\d.]+)(.*)polygon>/si';
        }
        $arr_math = array();
        preg_match($patterns, $content, $arr_math);
        if(isset($arr_math[2])){
            $arr_poli = explode(" ",$arr_math[2]);
            foreach ($arr_poli as $key => $value) {
               $polygon_point[$key] = explode(",",$value);
            }
            $polygon_point_str = '';
            foreach ($polygon_point as $key => $point) {
                foreach ($point as $k => $value) {
                   $polygon_point[$key][$k] = floatval($value)*$scale;
                   $polygon_point_str .= (string)$polygon_point[$key][$k].',';
                }
                $polygon_point_str .="@";
            }
            $polygon_point_str = str_replace(",@", " ", $polygon_point_str);
            if(strpos($browser,'MSIE')) $content = preg_replace($patterns, "\${1}{$polygon_point_str}\${3}>", $content);
            else $content = preg_replace($patterns, "\${1}{$polygon_point_str}\${3}polygon>", $content);
        }
        return $content;
    }


    public function changeSize($content,$type=0,$remove_poly=0){ //0:center,1:top,2:right,3:bottom,4:left
        $browser = $_SERVER['HTTP_USER_AGENT'];
        $arr_tmp = explode("</rect>",$content);
        $rect = $arr_tmp[0];
        preg_match('/(.*<rect[^>]* width=")([\d.]+)(.*")(.*>)/si', $rect, $arr_math);
        $move_x = (float)$arr_math[2];
        preg_match('/(.*<rect[^>]* height=")([\d.]+)(.*")(.*>)/si', $rect, $arr_math);
        $move_y = (float)$arr_math[2];

        //get rotate
        $rotate = 0;
        $patterns = "/(.*<image[^>]* transform=\"rotate[(])([\d.]+[\s])([\d.]+[\s])([\d.]+)([)])(.*<\/image>)/si";
        preg_match($patterns, $content, $arr_math);
        if(isset($arr_math[2]))
            $rotate = (float)$arr_math[2];
        //remove poligon
        if($type==0)
            $remove_poly = 0;
        if($remove_poly!=1){
            if(strpos($browser,'MSIE') )
                $patterns = '/(<polygon)(.*\/>)/si';
            else
                $patterns = '/(<polygon)(.*polygon>)/si';
            $content = preg_replace($patterns, "", $content);
        }else{
            if(strpos($browser,'MSIE'))
                $patterns = '/(<.*polygon[^>]* fill=")(.*")(.*\/>)/si';
            else
                $patterns = '/(<.*polygon[^>]* fill=")(.*")(.*polygon>)/si';
            preg_match($patterns, $content, $arr_math);
            $tmp = explode('"', $arr_math[2]);
            $background = $tmp[0];
        }


        //remove rect

         if(strpos($browser,'MSIE') )
            $patterns = '/(<rect)(.*>)/si';
        else
            $patterns = '/(<rect)(.*rect>)/si';
        $content = preg_replace($patterns, "", $content);


        //move image
         if(strpos($browser,'MSIE') ){
            $patterns0 = '/(.*<image[^>]* x=")(-?[\d.]+)(.*")(.*>)/si';
            $patterns1 = '/(.*<image[^>]* y=")(-?[\d.]+)(.*")(.*>)/si';
        }else{
            $patterns0 = '/(.*<image[^>]* x=")(-?[\d.]+)(.*")(.*image>)/si';
            $patterns1 = '/(.*<image[^>]* y=")(-?[\d.]+)(.*")(.*image>)/si';
        }
        preg_match($patterns0, $content, $arr_math);
        $original['x'] = (float)$arr_math[2];
        preg_match($patterns1, $content, $arr_math);
        $original['y'] = (float)$arr_math[2];
        $x = $original['x']-$move_x;
        $y = $original['y']-$move_y;


        //resize svg
        $patterns2 = '/(.*<svg[^>]* width=")(-?[\d.]+)(.*")(.*<g)/si';
        $patterns3 = '/(.*<svg[^>]* height=")(-?[\d.]+)(.*")(.*<g)/si';
        preg_match($patterns2, $content, $arr_math2);
        $width = round((float)$arr_math2[2]-2*$move_x);
        preg_match($patterns3, $content, $arr_math2);
        $height = round((float)$arr_math2[2]-2*$move_y);

        if($type==1){
            $y = $original['y'];
        }
        if($type==2){
            $x = $original['x']-$width-$move_x;
        }
        if($type==3){
            $y = $original['y']-$height-$move_y;
        }
        if($type==4){
            $x = $original['x'];
        }

        if($rotate==90){
            $x = $original['x']-$move_x;
            $y = $original['y']+$move_y;

            if($type==1)
                $x = $original['x'];
            if($type==2)
                $y = $original['y']+$width+$move_y;
            if($type==3)
                $x = $original['x']-$height-$move_x;
            if($type==4){
                $x = $original['x']-$move_x;
                $y = $original['y'];
            }

        }else if($rotate==180){
            $x = $original['x']+$move_x;
            $y = $original['y']+$move_y;
            if($type==1)
                $y = $original['y'];
            if($type==2)
                $x = $original['x']+$width+$move_x;
            if($type==3)
                $y = $original['y']+$height+$move_y;
            if($type==4){
                $y = $original['y']+$move_y;
                $x = $original['x'];
            }

        }else if($rotate==270){
            $x = $original['x']+$move_x;
            $y = $original['y']-$move_y;
            if($type==1)
                $x = $original['x'];
            if($type==2)
                $y = $original['y']-$width-$move_x;
            if($type==3)
                $x = $original['x']+$height+$move_y;
            if($type==4){
                $y = $original['y'];
            }
        }

        if($type==2 || $type==4){
            $width = round($move_x);
        }
        if($type==1 || $type==3){
            $height = round($move_y);
        }

        $content = preg_replace($patterns0, "\${1}{$x}\${3}\${4}", $content);
        $content = preg_replace($patterns1, "\${1}{$y}\${3}\${4}", $content);
        $content = preg_replace($patterns2, "\${1}{$width}\${3}\${4}", $content);
        $content = preg_replace($patterns3, "\${1}{$height}\${3}\${4}", $content);
        if($remove_poly==1)
            $content = str_replace('<defs', '<rect width="'.($width*2).'" height="'.($height*2).'" x="-10" y="-10" fill="'.$background.'" fill-opacity="1" stroke-width="0"></rect><defs', $content);
        return  array(
                'width' => $width,
                'height' => $height,
                'content' => $content,
            );
    }

    public function saveBackground()
    {
        $user_ip = User::getFolderKey();
        Session::set('user_ip', $user_ip);
        $arr_background = Session::has('user_backgrounds')?Session::get('user_backgrounds'):array();
        $arrReturn = [];
        if( Input::hasFile('background') ) {
            $uploaddir = public_path().DS.'assets'.DS.'upload'.DS.'themes'.DS.$user_ip.DS;
            if(!File::exists($uploaddir)) {
                // path does not exist
                File::makeDirectory($uploaddir, 0777, true);
            }
            $file = Input::file('background');
            $fileName = 'bg_'.md5($file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();
            $file->move($uploaddir, $fileName);
            $url = URL.'/assets/upload/themes/'.$user_ip.'/'.$fileName;
            $arr_background[] = $url;
            Session::set('user_backgrounds', $arr_background);
            $arrReturn = ['url' => $url];
        }
        return $arrReturn;
    }





    public function testScale($scale = 1.5){

        $v_content = '<svg id="SvgjsSvg1028" xmlns="http://www.w3.org/2000/svg" version="1.1" width="347" height="400" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="main-image"><image id="SvgjsImage1030" xlink:href="http://vi.local/assets/upload/themes/127.0.0.1/Music-image-music-36556275-1680-1050.jpg" width="640" height="400" x="-14.5" y="0"></image></g><path id="SvgjsPath1032" d="M10.30798053741455 376.2351989746094L25.904054641723633 383L173.5 42.72116470336914L157.9039306640625 35.95636749267578Z " fill="#dddddd" fill-opacity="0.5" stroke-width="0"></path><path id="SvgjsPath1033" d="M336.6920166015625 376.2351989746094L321.095947265625 383L173.5 42.72116470336914L189.0960693359375 35.95636749267578Z " fill="#dddddd" fill-opacity="0.5" stroke-width="0"></path><path id="SvgjsPath1034" d="M25.904054641723633 400L25.904054641723633 383L321.095947265625 383L321.095947265625 400Z " fill="#dddddd" fill-opacity="0.5" stroke-width="0"></path><path id="SvgjsPath1035" d="M0 400L-1 -1L347 -1L347 400L321.095947265625 400L321.095947265625 383L336.6920166015625 376.2351989746094L189.0960693359375 35.95636749267578L173.5 42.72116470336914L157.9039306640625 35.95636749267578L10.30798053741455 376.2351989746094L25.904054641723633 383L25.904054641723633 400Z " fill="white" fill-opacity="1" stroke-width="0"></path><defs id="SvgjsDefs1029"></defs></svg>
';
        $v_content = self::fullScaleSvg($v_content,$scale);
        self::simple_compress_image();
        self::create_pdf_svg($v_content);
        echo $v_content.'<br />';

        $v_content = '<svg id="SvgjsSvg1028" xmlns="http://www.w3.org/2000/svg" version="1.1" width="347" height="400" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="main-image"><image id="SvgjsImage1030" xlink:href="http://vi.anvyonline.com/assets/upload/themes/14.161.71.220/Music-image-music-36556275-1680-1050.jpg" width="640" height="400" x="-14.5" y="0"></image></g><path id="SvgjsPath1032" d="M10.30798053741455 376.2351989746094L25.904054641723633 383L173.5 42.72116470336914L157.9039306640625 35.95636749267578Z " fill="#dddddd" fill-opacity="0.5" stroke-width="0"></path><path id="SvgjsPath1033" d="M336.6920166015625 376.2351989746094L321.095947265625 383L173.5 42.72116470336914L189.0960693359375 35.95636749267578Z " fill="#dddddd" fill-opacity="0.5" stroke-width="0"></path><path id="SvgjsPath1034" d="M25.904054641723633 400L25.904054641723633 383L321.095947265625 383L321.095947265625 400Z " fill="#dddddd" fill-opacity="0.5" stroke-width="0"></path><path id="SvgjsPath1035" d="M0 400L-1 -1L347 -1L347 400L321.095947265625 400L321.095947265625 383L336.6920166015625 376.2351989746094L189.0960693359375 35.95636749267578L173.5 42.72116470336914L157.9039306640625 35.95636749267578L10.30798053741455 376.2351989746094L25.904054641723633 383L25.904054641723633 400Z " fill="white" fill-opacity="1" stroke-width="0"></path><defs id="SvgjsDefs1029"></defs></svg>';
        $v_content = self::fullScaleSvg($v_content,$scale);
        echo $v_content.'<br />';
        $v_content = '<svg id="SvgjsSvg1016" xmlns="http://www.w3.org/2000/svg" version="1.1" width="400" height="400" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="main-image"><image id="SvgjsImage1018" xlink:href="http://vi.anvyonline.com/assets/upload/themes/14.161.71.220/Music-image-music-36556275-1680-1050.jpg" width="640" height="400" x="-139" y="0"></image></g><path id="SvgjsPath1020" d="M0 0L200 0L0 100Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1021" d="M200 0L400 100L400 0Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1022" d="M200 400L400 400L400 300Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1023" d="M0 300L0 400L200 400Z " fill="white" stroke="white" stroke-width="1"></path><rect id="SvgjsRect1024" width="2" height="400" fill="white" stroke="white" stroke-width="1" x="199" y="0"></rect><path id="SvgjsPath1025" d="M0 99L400 299L400 301L0 101Z " fill="white" stroke="white" stroke-width="1" x="199" y="0"></path><path id="SvgjsPath1026" d="M400 99L400 101L0 301L0 299Z " fill="white" stroke="white" stroke-width="1" x="199" y="0"></path><polygon id="SvgjsPolygon1027" points="200,66.66666666666667 333.3333333333333,133.33333333333334 332.5357261548687,266.26786307743436 199.1563311897212,337.46752411152556 67.46427384513129,266.26786307743436 66.66666666666669,133.33333333333334" fill="white" stroke="white" stroke-width="1" x="199" y="0"></polygon><defs id="SvgjsDefs1017"></defs></svg>';
        $v_content = self::fullScaleSvg($v_content,$scale);
        echo $v_content.'<br />';

        $v_content = '<svg id="SvgjsSvg1036" xmlns="http://www.w3.org/2000/svg" version="1.1" width="244" height="400" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="main-image"><image id="SvgjsImage1038" xlink:href="http://vi.anvyonline.com/assets/upload/themes/14.161.71.220/Music-image-music-36556275-1680-1050.jpg" width="640" height="400" x="-66" y="0"></image></g><path id="SvgjsPath1040" d="M0 0L122 0L0 200Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1041" d="M122 0L244 200L244 0Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1042" d="M122 400L244 400L244 200Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1043" d="M0 200L0 400L122 400Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1044" d="M113.46296691894531 13.99514102935791L122 19.202733993530273L11.713666915893555 200L3.176631212234497 194.7924041748047Z " fill="white" fill-opacity="0.5" stroke-width="0"></path><path id="SvgjsPath1045" d="M122 19.202733993530273L130.5370330810547 13.99514102935791L240.8233642578125 194.7924041748047L232.2863311767578 200Z " fill="white" fill-opacity="0.5" stroke-width="0"></path><path id="SvgjsPath1046" d="M122 380.7972717285156L130.5370330810547 386.0048522949219L240.8233642578125 205.2075958251953L232.2863311767578 200Z " fill="white" fill-opacity="0.5" stroke-width="0"></path><path id="SvgjsPath1047" d="M113.46296691894531 386.0048522949219L122 380.7972717285156L11.713666915893555 200L3.176631212234497 205.2075958251953Z " fill="white" fill-opacity="0.5" stroke-width="0"></path><path id="SvgjsPath1048" d="M122 0L130.5370330810547 13.99514102935791L122 19.202733993530273L113.46296691894531 13.99514102935791Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1049" d="M232.2863311767578 200L240.8233642578125 194.7924041748047L244 200L240.8233642578125 205.2075958251953Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1050" d="M122 380.7972717285156L130.5370330810547 386.0048522949219L122 400L113.46296691894531 386.0048522949219Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1051" d="M11.713666915893555 200L3.176631212234497 205.2075958251953L0 200L3.176631212234497 194.7924041748047Z " fill="white" stroke="white" stroke-width="1"></path><defs id="SvgjsDefs1037"></defs></svg>';
        $v_content = self::fullScaleSvg($v_content,$scale);
        echo $v_content.'<br />';

        $v_content = '<svg id="SvgjsSvg1026" xmlns="http://www.w3.org/2000/svg" version="1.1" width="400" height="400" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="main-image"><image id="SvgjsImage1028" xlink:href="http://vi.anvyonline.com/assets/upload/themes/14.161.71.220/Music-image-music-36556275-1680-1050.jpg" width="400" height="426" x="0" y="-13"></image></g><path id="SvgjsPath1030" d="M0 0L400 0L0 400Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1031" d="M367.88470458984375 31.11528778076172L380.88470458984375 46.11528778076172L46 381L33 366Z " fill="white" fill-opacity="0.5" stroke-width="0"></path><rect id="SvgjsRect1032" width="19" height="334.8847117794486" fill="white" fill-opacity="0.5" stroke-width="0" x="380.8847117794486" y="46.115288220551406"></rect><rect id="SvgjsRect1033" width="335" height="19" fill="white" fill-opacity="0.5" stroke-width="0" x="46" y="381"></rect><path id="SvgjsPath1034" d="M400 0L400 46.11528778076172L380.88470458984375 46.11528778076172L367.88470458984375 31.11528778076172Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1035" d="M381 381L400 381L400 400L381 400Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1036" d="M33 366L46 381L46 400L0 400Z " fill="white" stroke="white" stroke-width="1"></path><defs id="SvgjsDefs1027"></defs></svg>';
        $v_content = self::fullScaleSvg($v_content,$scale);
        echo $v_content.'<br />';

        $v_content = '<svg id="SvgjsSvg1030" xmlns="http://www.w3.org/2000/svg" version="1.1" width="272" height="400" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="main-image"><image id="SvgjsImage1032" xlink:href="http://vi.anvyonline.com/assets/upload/themes/14.161.71.220/Music-image-music-36556275-1680-1050.jpg" width="640" height="400" x="-52" y="0"></image></g><path id="SvgjsPath1034" d="M0 0L90.66666412353516 0L0 400Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1035" d="M87.84170532226562 12.463055610656738L103.44587707519531 16L20.032541275024414 384L4.428374290466309 380.4630432128906Z " fill="white" fill-opacity="0.5" stroke-width="0"></path><rect id="SvgjsRect1036" width="152.55412571329674" height="16" fill="white" fill-opacity="0.5" stroke-width="0" x="103.44587428670326" y="0"></rect><rect id="SvgjsRect1037" width="16" height="368" fill="white" fill-opacity="0.5" stroke-width="0" x="256" y="16"></rect><rect id="SvgjsRect1038" width="235.9674590466301" height="16" fill="white" fill-opacity="0.5" stroke-width="0" x="20.03254095336991" y="384"></rect><path id="SvgjsPath1039" d="M90.66666412353516 0L103.44587707519531 0L103.44587707519531 16L87.84170532226562 12.463055610656738Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1040" d="M256 0L272 0L272 16L256 16Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1041" d="M256 384L272 384L272 400L256 400Z " fill="white" stroke="white" stroke-width="1"></path><path id="SvgjsPath1042" d="M4.428374290466309 380.4630432128906L20.032541275024414 384L20.032541275024414 400L0 400Z " fill="white" stroke="white" stroke-width="1"></path><defs id="SvgjsDefs1031"></defs></svg>';
        $v_content = self::fullScaleSvg($v_content,$scale);
        echo $v_content.'<br />';

        $v_content = '<svg id="SvgjsSvg1022" xmlns="http://www.w3.org/2000/svg" version="1.1" width="504" height="400" xmlns:xlink="http://www.w3.org/1999/xlink"><g id="main-image"><image id="SvgjsImage1024" xlink:href="http://vi.anvyonline.com/assets/upload/themes/14.161.71.220/Music-image-music-36556275-1680-1050.jpg" width="640" height="400" x="-68" y="0"></image></g><polygon id="SvgjsPolygon1026" points="17,0 487,0 487,17 504,17 504,383 487,383 487,400 17,400 17,383 0,383 0,17 17,17 17,383 487,383 487,17 17,17" fill="#dddddd" fill-opacity="0.4" stroke-width="0"></polygon><rect id="SvgjsRect1027" width="17" height="17" x="0" y="0" fill="white" fill-opacity="1" stroke-width="0"></rect><rect id="SvgjsRect1028" width="17" height="17" x="487" y="0" fill="white" fill-opacity="1" stroke-width="0"></rect><rect id="SvgjsRect1029" width="17" height="17" x="487" y="383" fill="white" fill-opacity="1" stroke-width="0"></rect><rect id="SvgjsRect1030" width="17" height="17" x="0" y="383" fill="white" fill-opacity="1" stroke-width="0"></rect><defs id="SvgjsDefs1023"></defs></svg>';
        $v_content = self::fullScaleSvg($v_content,$scale);
        echo $v_content.'<br /><br /><br /><br />';



    }

}
