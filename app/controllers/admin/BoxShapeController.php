<?php
class BoxShapeController extends AdminController{

    public function __contruct(){
        parrent::__contruct();
    }
    public function saveBox(){
        $svg_content = Input::has('txt_svg_content') ? Input::get('txt_svg_content') : "";
        $txt_id = Input::has('txt_id') ? Input::get('txt_id') : 0;
        $txt_layout_id = (int) Input::has('txt_layout_id') ? Input::get('txt_layout_id') : 0;
        if($svg_content){
            $v_name = md5(date('d-m-y-h-m-s'));
            $v_path = HomeController::create_file_svg($svg_content,$txt_id,$v_name,1);
            $v_image_fie = HomeController::ExportPDFWithInkScape('png',$v_path,$v_name.'.svg',1);
            if($txt_layout_id){
                $arr_where [0] = array('field'=>'id', 'operator'=>'=', 'value'=>$txt_layout_id);
                $layout = ShapeLayout::condition($arr_where)->first();

                $layout->layout_image = str_replace(public_path(), '', $v_path).$v_image_fie;
                $layout->save();
            }
        }
        $arr_object_content = Input::has('txt_obj_data') ? Input::get('txt_obj_data') : "";
        $is_save = false;
        // xoa het toan bo box
        $arr_where [0] = array('field'=>'shape_layout_id', 'operator'=>'=', 'value'=>$txt_layout_id);
        $layout = BoxShape::condition($arr_where)->delete();
        $is_save = true;
        if(is_array($arr_object_content)){
            // fix lai, code cu khi co 2 box, xoa 1 box roi save thi sai
            $is_save = true;
             foreach ($arr_object_content as $key => $value) {
                $box = new BoxShape;
                $box->id = $value['id'];
                $box->width = $value['width'];
                $box->height = $value['height'];
                $box->height = $value['height'];
                $box->coor_x =  $value['coor_x'];
                $box->rotate = $value['rotate'];
                $box->coor_y = $value['coor_y'];
                $box->layout_id = $value['layout_id'];
                $box->shape_type = $value['shape_type'];
                $box->shape_layout_id = $value['layout_id'];
                $box->svg = $value['d'];
                $is_save = $is_save && $box->save();
            }
        }
        if ($is_save) echo "Saved";
        else echo "Fail";
    }
    public function deleteBox(){
    	if (count($_POST['arr_deleted'])) {
    		Box::destroy($_POST['arr_deleted']);
	    	echo count($_POST['arr_deleted']);
	    }
    }
    private static function detectChangeBox(Box $model){
        $v_return = 0;
        foreach($model->getDirty() as $attr=>$value){
            if($model->getOriginal($attr)!=$value) $v_return++;
        }
        return $v_return;
    }
    public function test(){
        $box1 = Box::where('id', '=', 19)->get(); // mang
        pr($box1[0]);
        echo "<hr>";
        $box2 = Box::find(21);
        pr($box2);
        echo "<hr>";
        echo Box::where('id', '=', 21)->count();

        /*$box = Box::find(100);
        $box->delete();

        Box::destroy(2);*/
    }
    public function test1(){
        $jt = JTProduct::getOptions('543cfbd9005fc30c1f00025d');
        pr($jt);die;
    }
    public function test2(){
        $check = JTContact::checkExist('speralta@twistmarketing.com');
        pr($check);die;
        $jt = JTContact::getOptions('speralta@twistmarketing.com');
        pr($jt->email);die;
    }
}
