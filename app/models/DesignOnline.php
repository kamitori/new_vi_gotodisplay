<?php
class DesignOnline {
	public static function getDPIOption($true_w,$true_h,$view_w,$view_h){
		if($true_w/$view_w > $true_h/$view_h)
			return $view_w/$true_w; //thu theo chieu width
		else
			return $view_h/$true_h; //thu theo chieu height
	}

	public static function DrawPolygon($data=array(),$border = 1){
		$x = isset($data['coor_x'])?$data['coor_x']:0;
		$y = isset($data['coor_y'])?$data['coor_y']:0;
		$width = isset($data['width'])?$data['width']:100;
		$height = isset($data['height'])?$data['height']:50;
		$bleed = isset($data['bleed'])?$data['bleed']:10;
		$bh = ($border/2);
		$x_0 = $x ;
		$x_1 = ($x_0+$bleed);
		$x_2 = ($x_0+$width-$bleed);
		$x_3 = ($x_0+$width);

		$y_0 = $y;
		$y_1 = ($y_0+$bleed);
		$y_2 = ($y_0+$height-$bleed);
		$y_3 = ($y_0+$height);

		$arr_return = array();
		$points  = $x_0.','.$y_0.' ';
		$points .= $x_3.','.$y_0.' ';
		$points .= $x_3.','.$y_3.' ';
		$points .= $x_0.','.$y_3.' ';
		$points .= $x_0.','.$y_2.' ';
		$points .= $x_2.','.$y_2.' ';
		$points .= $x_2.','.$y_1.' ';
		$points .= $x_1.','.$y_1.' ';
		$points .= $x_1.','.$y_2.' ';
		$points .= $x_0.','.$y_2.' ';
		$points .= $x_0.','.$y_0.' ';

		$polygon_points = $x_1.','.$y_0.' ';
		$polygon_points .= $x_2.','.$y_0.' ';
		$polygon_points .= $x_2.','.$y_1.' ';
		$polygon_points .= $x_3.','.$y_1.' ';
		$polygon_points .= $x_3.','.$y_2.' ';
		$polygon_points .= $x_2.','.$y_2.' ';
		$polygon_points .= $x_2.','.$y_3.' ';
		$polygon_points .= $x_1.','.$y_3.' ';
		$polygon_points .= $x_1.','.$y_2.' ';
		$polygon_points .= $x_0.','.$y_2.' ';
		$polygon_points .= $x_0.','.$y_1.' ';
		$polygon_points .= $x_1.','.$y_1.' ';
		$polygon_points .= $x_1.','.$y_2.' ';
		$polygon_points .= $x_2.','.$y_2.' ';
		$polygon_points .= $x_2.','.$y_1.' ';
		$polygon_points .= $x_1.','.$y_1.' ';

		$arr_return['polygon'] = $polygon_points;

		$arr_return['rect'][0]['x'] = $x_0-1;
		$arr_return['rect'][0]['y'] = $y_0-1;
		$arr_return['rect'][0]['width'] = ($x_1-$x_0+1);
		$arr_return['rect'][0]['height'] = ($y_1-$y_0+1);
		$arr_return['rect'][1]['x'] = $x_2;
		$arr_return['rect'][1]['y'] = $y_0;
		$arr_return['rect'][1]['width'] = ($x_3-$x_2+1);
		$arr_return['rect'][1]['height'] = ($y_1-$y_0);
		$arr_return['rect'][2]['x'] = $x_2;
		$arr_return['rect'][2]['y'] = $y_2;
		$arr_return['rect'][2]['width'] = ($x_3-$x_2+1);
		$arr_return['rect'][2]['height'] = ($y_3-$y_2+1);
		$arr_return['rect'][3]['x'] = $x_0-1;
		$arr_return['rect'][3]['y'] = $y_2;
		$arr_return['rect'][3]['width'] = ($x_1-$x_0+1);
		$arr_return['rect'][3]['height'] = ($y_3-$y_2);

		return $arr_return;
	}

	public static function DrawClusterDesign($arr_layout,$zoom=1){
		$dpi = 72;
		$max_w = 1000;//pt
		$max_h= 400;//pt
		$svg = array();
		$svg_w = isset($arr_layout['wall_size_w'])?$arr_layout['wall_size_w']:62;//inch
		$svg_h = isset($arr_layout['wall_size_h'])?$arr_layout['wall_size_h']:16;//inch
		$svg_bleed = (float)$arr_layout['bleed'];
		$svg_w = (float)$svg_w+2*$svg_bleed; //inch
		$svg_h = (float)$svg_h+2*$svg_bleed; //inch
		$svg_w = $svg_w*$dpi; //pt
		$svg_h = $svg_h*$dpi; //pt
		$svg_bleed_pt = $svg_bleed*$dpi; //pt

		$view_dpi = self::getDPIOption($svg_w,$svg_h,$max_w,$max_h);
		$svg['width'] = ($svg_w*$view_dpi + $svg_bleed_pt)*$zoom ;
		$svg['height'] = ($svg_h*$view_dpi + $svg_bleed_pt)*$zoom ;
		$svg['view_dpi'] = $view_dpi;

		if(isset($arr_layout['data'])){
			foreach ($arr_layout['data'] as $key => $value) {
				$value['bleed'] = ($svg_bleed*$dpi*$view_dpi);
				$value['width'] = ($value['width']*$dpi*$view_dpi + 2*$value['bleed']) *$zoom;
				$value['height'] = ($value['height']*$dpi*$view_dpi + 2*$value['bleed']) *$zoom;
				$value['coor_x'] = ($value['coor_x']*$dpi*$view_dpi)*$zoom;
				$value['coor_y'] = ($value['coor_y']*$dpi*$view_dpi)*$zoom;
				$arr_layout['data'][$key] = $value;
			}
			$svg['data'] = $arr_layout['data'];
		}
		return $svg;
	}

	public static function drawWrapFramePiece($input_data=array(),$pieces = 1,$current=1,$type=0){

		//Get Data
		$width = 6;
		$height = 4;
		$bleed = 1;
		$border = 0;
		$border_in = 0;//inch
		$view_dpi = 1;
		$dpi = 72;
		$fixed_width = 600;//pt
		$fixed_height = 400;//pt
		if(isset($input_data['big_resolution']) && $input_data['big_resolution'] ){
			$fixed_width = 800;//pt
			$fixed_height = 400;//pt
			$width = 8;
			$height = 4;
		}
		if(isset($input_data['width']))
			$width = $input_data['width'];
		if(isset($input_data['height']))
			$height = $input_data['height'];
		if(isset($input_data['bleed']))
			$bleed = $input_data['bleed'];
		if(isset($input_data['border_in']))
			$border_in = $input_data['border_in'];
		if(isset($input_data['dpi']))
			$dpi = $input_data['dpi'];
		if(isset($input_data['fixed_box']) && isset($input_data['fixed_box']['width']))
			$fixed_width = $input_data['fixed_box']['width'];
		if(isset($input_data['fixed_box']) && isset($input_data['fixed_box']['height']))
			$fixed_height = $input_data['fixed_box']['height'];


		//chuyen sang don vi ponter

		$width_pt = $width*$dpi;
		$height_pt = $height*$dpi;

		$bleed_pt = $bleed*$dpi;
		$border_pt = $border_in*$dpi;


		//Tinh toa do truc
		$x_0 = 0; $y_0 = 0;
		if(isset($input_data['x_0']))
			$x_0 = $input_data['x_0'];
		if(isset($input_data['y_0']))
			$y_0 = $input_data['y_0'];

		$x_3 = $width_pt + $x_0;

		$y_3 = $height_pt + $y_0;
		$x_1 = $x_0+$bleed_pt; $x_10 = $x_1+$border_pt;
		$y_1 = $y_0+$bleed_pt; $y_10 = $y_1+$border_pt;
		$x_2 = $x_3-$bleed_pt; $x_20 = $x_2-$border_pt;
		$y_2 = $y_3-$bleed_pt; $y_20 = $y_2-$border_pt;
		/* ------- Tam giac ---------- */
		$x_B = 0; $y_A = 0;
		if(isset($input_data['y_A']))
			$y_A = $input_data['y_A'];
		if(isset($input_data['x_B']))
			$x_B = $input_data['x_B'];

		$AB = sqrt($width_pt*$width_pt/4 + $height_pt*$height_pt);
		$x_C = $width_pt + $x_B;
		$x_A  = ($x_C+$x_B)/2;
		$y_BC = $height_pt + $y_A;
		$y_DE = $y_BC-$bleed_pt;
		$y_Ai = $y_A + 2*$bleed_pt*$AB/$width_pt;

		$hi = $y_DE - $y_Ai;
		$DE = $hi * $width_pt / $height_pt; // tam giac dong dang
		$x_D = $x_B + ($width_pt - $DE)/2;
		$x_E = $x_C - ($width_pt - $DE)/2;

		$fh = $bleed_pt*($height_pt/$AB); // fh la keo vuong goc xuong AH
		$Ah = $fh*2*$height_pt/$width_pt;

		$x_f = $x_A - $fh;
		$y_f = $y_A + $Ah;

		$x_g = $x_A + $fh;
		$y_g = $y_f;

		$x_m = $x_D;
		$y_m = $y_BC;

		$x_n = $x_E;
		$y_n = $y_BC;

		$x_r = $x_D - $fh;
		$y_r = $y_DE - ($y_Ai - $Ah);

		$x_t = $x_E + $fh;
		$y_t = $y_r;

		$d0 = "M $x_r $y_r L $x_D $y_DE L $x_A $y_Ai L $x_f $y_f Z";
		$d1 = "M $x_t $y_t L $x_E $y_DE L $x_A $y_Ai L $x_g $y_g Z";
		$d2 = "M $x_m $y_m L $x_D $y_DE L $x_E $y_DE L $x_n $y_n Z";
		$d3 = "M 0 $height_pt L -1 -1 L $width_pt -1 L ".($width_pt+1).' ' .($height_pt+1)." L $x_n $y_n L $x_E $y_DE L $x_t $y_t L $x_g $y_g L $x_A $y_Ai L $x_f $y_f L $x_r $y_r L $x_D $y_DE L $x_m $y_m Z";


		$polygon_points = $x_1.','.$y_0.' ';
		$polygon_points .= $x_2.','.$y_0.' ';
		$polygon_points .= $x_2.','.$y_1.' ';
		$polygon_points .= $x_3.','.$y_1.' ';
		$polygon_points .= $x_3.','.$y_2.' ';
		$polygon_points .= $x_2.','.$y_2.' ';
		$polygon_points .= $x_2.','.$y_3.' ';
		$polygon_points .= $x_1.','.$y_3.' ';
		$polygon_points .= $x_1.','.$y_2.' ';
		$polygon_points .= $x_0.','.$y_2.' ';
		$polygon_points .= $x_0.','.$y_1.' ';
		$polygon_points .= $x_1.','.$y_1.' ';
		$polygon_points .= $x_1.','.$y_2.' ';
		$polygon_points .= $x_2.','.$y_2.' ';
		$polygon_points .= $x_2.','.$y_1.' ';
		$polygon_points .= $x_1.','.$y_1.' ';

		$border_points = '';
		if($border_in!=0){
			$border_points .= $x_1.','.$y_1.' ';
			$border_points .= $x_2.','.$y_1.' ';
			$border_points .= $x_2.','.$y_2.' ';
			$border_points .= $x_1.','.$y_2.' ';
			$border_points .= $x_1.','.$y_20.' ';
			$border_points .= $x_20.','.$y_20.' ';
			$border_points .= $x_20.','.$y_10.' ';
			$border_points .= $x_10.','.$y_10.' ';
			$border_points .= $x_10.','.$y_20.' ';
			$border_points .= $x_1.','.$y_20.' ';
			$border_points .= $x_1.','.$y_1.' ';
		}

		$outline_border = round($border/2);
		$bi_x = $x_0+$bleed_pt-$outline_border;
		$bi_y = $y_0+$bleed_pt-$outline_border;
		$bi_width = $width_pt - 2*$bleed_pt;
		$bi_height = $height_pt - 2*$bleed_pt;

		$arr_frame_svg = array();
		$arr_frame_svg['svg']['width'] = $x_3;
		$arr_frame_svg['svg']['height'] = $y_3;
		$arr_frame_svg['svg']['bleed'] = $bleed_pt;
		$arr_frame_svg['svg']['border'] = $border_pt;
		$arr_frame_svg['svg']['view_dpi'] = $view_dpi;
		$arr_frame_svg['image']['x'] = $bi_x;
		$arr_frame_svg['image']['y'] = $bi_y;
		$arr_frame_svg['image']['width'] = $width_pt;
		$arr_frame_svg['image']['height'] = $height_pt;
		$arr_frame_svg['image']['xlink:href'] = $input_data['image_link'];
		$arr_frame_svg['polygon']['points'] = $polygon_points;
		$arr_frame_svg['border']['points'] = $border_points;

		$arr_frame_svg['rect'][0]['x'] = $x_0;
		$arr_frame_svg['rect'][0]['y'] = $y_0;
		$arr_frame_svg['rect'][0]['width'] = $x_1-$x_0;
		$arr_frame_svg['rect'][0]['height'] = $y_1-$y_0;
		$arr_frame_svg['rect'][1]['x'] = $x_2;
		$arr_frame_svg['rect'][1]['y'] = $y_0;
		$arr_frame_svg['rect'][1]['width'] = $x_3-$x_2+5;
		$arr_frame_svg['rect'][1]['height'] = $y_1-$y_0;
		$arr_frame_svg['rect'][2]['x'] = $x_2;
		$arr_frame_svg['rect'][2]['y'] = $y_2;
		$arr_frame_svg['rect'][2]['width'] = $x_3-$x_2+5;
		$arr_frame_svg['rect'][2]['height'] = $y_3-$y_2+5;
		$arr_frame_svg['rect'][3]['x'] = $x_0;
		$arr_frame_svg['rect'][3]['y'] = $y_2;
		$arr_frame_svg['rect'][3]['width'] = $x_1-$x_0;
		$arr_frame_svg['rect'][3]['height'] = $y_3-$y_2+5;
		$arr_frame_svg['path'][0] = $d0;
		$arr_frame_svg['path'][1] = $d1;
		$arr_frame_svg['path'][2] = $d2;
		$arr_frame_svg['path'][3] = $d3;



		return $arr_frame_svg;

	}

	public static function drawWrapFrame($input_data=array(),$pieces = 1,$current=1,$type=0){
		/*$input_data = array();
		$input_data['width'] = 100; //inch
		$input_data['height'] = 80; //inch
		$input_data['bleed'] = 15; //inch
		$input_data['dpi'] = 72;
		$input_data['border'] = 50; //pt
		$input_data['fixed_box'] = array('width'=>600,'height'=>400);
		$input_data['image_link'] = "http://vi.local/assets/upload/16x20-main.png";
		$input_data['x_0'] = 0;
		$input_data['y_0'] = 0;*/

		//Get Data
		$width = 6;
		$height = 4;
		$bleed = 1;
		$border = 0;
		$border_in = 0;//inch
		$view_dpi = 1;
		$dpi = 72;
		$fixed_width = 600;//pt
		$fixed_height = 400;//pt
		if(isset($input_data['big_resolution']) && $input_data['big_resolution'] ){
			$fixed_width = 800;//pt
			$fixed_height = 400;//pt
			$width = 8;
			$height = 4;
		}
		if(isset($input_data['width']))
			$width = $input_data['width'];
		if(isset($input_data['height']))
			$height = $input_data['height'];
		if(isset($input_data['bleed']))
			$bleed = $input_data['bleed'];
		if(isset($input_data['border_in']))
			$border_in = $input_data['border_in'];
		if(isset($input_data['view_dpi']))
			$view_dpi = $input_data['view_dpi'];
		if(isset($input_data['dpi']))
			$dpi = $input_data['dpi'];
		if(isset($input_data['fixed_box']) && isset($input_data['fixed_box']['width']))
			$fixed_width = $input_data['fixed_box']['width'];
		if(isset($input_data['fixed_box']) && isset($input_data['fixed_box']['height']))
			$fixed_height = $input_data['fixed_box']['height'];


		//chuyen sang don vi ponter

		$width_pt = $width*$dpi;
		$height_pt = $height*$dpi;
		if($pieces>1){
			if(!$type) $width_pt -= (10 *($pieces+1));
			else $height_pt -= (10 *($pieces+1));
		}
		$bleed_pt = $bleed*$dpi;
		$border_pt = $border_in*$dpi;

		if(Request::ajax()){
			if($pieces>1){
				if(!$type) $view_dpi = $width_pt/$fixed_width;
				else $view_dpi = $height_pt/$fixed_height;
				if($width_pt/$height_pt > $fixed_width/$fixed_height)
					$view_dpi2 = $width_pt/$fixed_width; //thu theo chieu width
				else
					$view_dpi2 = $height_pt/$fixed_height; //thu theo chieu height
				$height_pt = round($height_pt/$view_dpi2);
				$bleed_pt = round($bleed_pt/$view_dpi2);
			}else{
				if($width_pt/$height_pt > $fixed_width/$fixed_height)
					$view_dpi = $width_pt/$fixed_width; //thu theo chieu width
				else
					$view_dpi = $height_pt/$fixed_height; //thu theo chieu height
				$height_pt = round($height_pt/$view_dpi);
				$bleed_pt = round($bleed_pt/$view_dpi);
			}
			$width_pt = round($width_pt/$view_dpi);
		}else{
				//tinh ty le view : ty le hinh goc thu nho lai n lan
			if($width_pt/$height_pt > $fixed_width/$fixed_height)
				$view_dpi = $width_pt/$fixed_width; //thu theo chieu width
			else
				$view_dpi = $height_pt/$fixed_height; //thu theo chieu height
			$width_pt = round($width_pt/$view_dpi);
			$height_pt = round($height_pt/$view_dpi);
			$bleed_pt = round($bleed_pt/$view_dpi);
		}
		if(!$type) $width_pt = ($width_pt / $pieces );
		else $height_pt = ($height_pt / $pieces );

		$border_pt = round($border_pt/$view_dpi);

		if(isset($input_data['border']))
			$border = round($input_data['border']/$view_dpi);


		//Tinh toa do truc
		$x_0 = 0; $y_0 = 0;
		if(isset($input_data['x_0']))
			$x_0 = $input_data['x_0'];
		if(isset($input_data['y_0']))
			$y_0 = $input_data['y_0'];

		if($current>1){
			if(!$type){
				// hang ngang
				$x_0 = ($width_pt * ($current-1) + 10* ($current-1));
			}else{
				$y_0 = ($height_pt * ($current-1) + 10* ($current-1));
			}
		}

		$x_3 = $width_pt + $x_0;

		$y_3 = $height_pt + $y_0;
		$x_1 = $x_0+$bleed_pt; $x_10 = $x_1+$border_pt;
		$y_1 = $y_0+$bleed_pt; $y_10 = $y_1+$border_pt;
		$x_2 = $x_3-$bleed_pt; $x_20 = $x_2-$border_pt;
		$y_2 = $y_3-$bleed_pt; $y_20 = $y_2-$border_pt;
		/* ------- Tam giac ---------- */
		$x_B = 0; $y_A = 0;
		if(isset($input_data['y_A']))
			$y_A = $input_data['y_A'];
		if(isset($input_data['x_B']))
			$x_B = $input_data['x_B'];

		$AB = sqrt($width_pt*$width_pt/4 + $height_pt*$height_pt);
		$x_C = $width_pt + $x_B;
		$x_A  = ($x_C+$x_B)/2;
		$y_BC = $height_pt + $y_A;
		$y_DE = $y_BC-$bleed_pt;
		$y_Ai = $y_A + 2*$bleed_pt*$AB/$width_pt;

		$hi = $y_DE - $y_Ai;
		$DE = $hi * $width_pt / $height_pt; // tam giac dong dang
		$x_D = $x_B + ($width_pt - $DE)/2;
		$x_E = $x_C - ($width_pt - $DE)/2;

		$fh = $bleed_pt*($height_pt/$AB); // fh la keo vuong goc xuong AH
		$Ah = $fh*2*$height_pt/$width_pt;

		$x_f = $x_A - $fh;
		$y_f = $y_A + $Ah;

		$x_g = $x_A + $fh;
		$y_g = $y_f;

		$x_m = $x_D;
		$y_m = $y_BC;

		$x_n = $x_E;
		$y_n = $y_BC;

		$x_r = $x_D - $fh;
		$y_r = $y_DE - ($y_Ai - $Ah);

		$x_t = $x_E + $fh;
		$y_t = $y_r;

		$d0 = "M $x_r $y_r L $x_D $y_DE L $x_A $y_Ai L $x_f $y_f Z";
		$d1 = "M $x_t $y_t L $x_E $y_DE L $x_A $y_Ai L $x_g $y_g Z";
		$d2 = "M $x_m $y_m L $x_D $y_DE L $x_E $y_DE L $x_n $y_n Z";
		$d3 = "M 0 $height_pt L -1 -1 L $width_pt -1 L ".($width_pt+1).' ' .($height_pt+1)." L $x_n $y_n L $x_E $y_DE L $x_t $y_t L $x_g $y_g L $x_A $y_Ai L $x_f $y_f L $x_r $y_r L $x_D $y_DE L $x_m $y_m Z";

		// Tao hinh chu nhat

		// if($current>1){
		// 	$x_1 *=$current;
		// 	$x_2 *=$current;
		// 	$x_3 *=$current;
		// }

		$polygon_points = $x_1.','.$y_0.' ';
		$polygon_points .= $x_2.','.$y_0.' ';
		$polygon_points .= $x_2.','.$y_1.' ';
		$polygon_points .= $x_3.','.$y_1.' ';
		$polygon_points .= $x_3.','.$y_2.' ';
		$polygon_points .= $x_2.','.$y_2.' ';
		$polygon_points .= $x_2.','.$y_3.' ';
		$polygon_points .= $x_1.','.$y_3.' ';
		$polygon_points .= $x_1.','.$y_2.' ';
		$polygon_points .= $x_0.','.$y_2.' ';
		$polygon_points .= $x_0.','.$y_1.' ';
		$polygon_points .= $x_1.','.$y_1.' ';
		$polygon_points .= $x_1.','.$y_2.' ';
		$polygon_points .= $x_2.','.$y_2.' ';
		$polygon_points .= $x_2.','.$y_1.' ';
		$polygon_points .= $x_1.','.$y_1.' ';

		$border_points = '';
		if($border_in!=0){
			$border_points .= $x_1.','.$y_1.' ';
			$border_points .= $x_2.','.$y_1.' ';
			$border_points .= $x_2.','.$y_2.' ';
			$border_points .= $x_1.','.$y_2.' ';
			$border_points .= $x_1.','.$y_20.' ';
			$border_points .= $x_20.','.$y_20.' ';
			$border_points .= $x_20.','.$y_10.' ';
			$border_points .= $x_10.','.$y_10.' ';
			$border_points .= $x_10.','.$y_20.' ';
			$border_points .= $x_1.','.$y_20.' ';
			$border_points .= $x_1.','.$y_1.' ';
		}

		//  --------- Tao hinh tam giac---------
		/*$polygon_points = $x_A.','.$y_A.' ';
		$polygon_points .= $x_C.','.$y_BC.' ';
		$polygon_points .= $x_B.','.$y_BC.' ';
		$polygon_points .= $x_D.','.$y_DE.' ';
		$polygon_points .= $x_E.','.$y_DE.' ';
		$polygon_points .= $x_A.','.$y_Ai.' ';
		$polygon_points .= $x_D.','.$y_DE.' ';
		$polygon_points .= $x_B.','.$y_BC.' ';*/

		$outline_border = round($border/2);
		$bi_x = $x_0+$bleed_pt-$outline_border;
		$bi_y = $y_0+$bleed_pt-$outline_border;
		$bi_width = $width_pt - 2*$bleed_pt;
		$bi_height = $height_pt - 2*$bleed_pt;

		$arr_frame_svg = array();
		$arr_frame_svg['svg']['width'] = $x_3;
		$arr_frame_svg['svg']['height'] = $y_3;
		$arr_frame_svg['svg']['bleed'] = $bleed_pt;
		$arr_frame_svg['svg']['border'] = $border_pt;
		$arr_frame_svg['svg']['view_dpi'] = $view_dpi;
		$arr_frame_svg['image']['x'] = $bi_x;
		$arr_frame_svg['image']['y'] = $bi_y;
		$arr_frame_svg['image']['width'] = $width_pt;
		$arr_frame_svg['image']['height'] = $height_pt;
		$arr_frame_svg['image']['xlink:href'] = $input_data['image_link'];
		$arr_frame_svg['polygon']['points'] = $polygon_points;
		$arr_frame_svg['border']['points'] = $border_points;

		$arr_frame_svg['rect'][0]['x'] = $x_0;
		$arr_frame_svg['rect'][0]['y'] = $y_0;
		$arr_frame_svg['rect'][0]['width'] = $x_1-$x_0;
		$arr_frame_svg['rect'][0]['height'] = $y_1-$y_0;
		$arr_frame_svg['rect'][1]['x'] = $x_2;
		$arr_frame_svg['rect'][1]['y'] = $y_0;
		$arr_frame_svg['rect'][1]['width'] = $x_3-$x_2+5;
		$arr_frame_svg['rect'][1]['height'] = $y_1-$y_0;
		$arr_frame_svg['rect'][2]['x'] = $x_2;
		$arr_frame_svg['rect'][2]['y'] = $y_2;
		$arr_frame_svg['rect'][2]['width'] = $x_3-$x_2+5;
		$arr_frame_svg['rect'][2]['height'] = $y_3-$y_2+5;
		$arr_frame_svg['rect'][3]['x'] = $x_0;
		$arr_frame_svg['rect'][3]['y'] = $y_2;
		$arr_frame_svg['rect'][3]['width'] = $x_1-$x_0;
		$arr_frame_svg['rect'][3]['height'] = $y_3-$y_2+5;
		$arr_frame_svg['path'][0] = $d0;
		$arr_frame_svg['path'][1] = $d1;
		$arr_frame_svg['path'][2] = $d2;
		$arr_frame_svg['path'][3] = $d3;

		// $frame_svg = '';
		// $frame_svg .= '<image x="-'.$bi_x.'" y="-'.$bi_y.'" width="'.($bi_width+400).'" height="'.($bi_height+400).'"  xlink:href="'.$input_data['image_link'].'" style="stroke:red;stroke-width:1;opacity:1" />';
		// $frame_svg .= '<polygon points="'.$polygon_points.'" style="fill:#aaaaaa;stroke:#aaaaaa;stroke-width:1;opacity:0.6" />';
		// $frame_svg .=  '<rect x="'.$x_0.'" y="'.$y_0.'" width="'.($x_1-$x_0).'" height="'.($y_1-$y_0).'"style="fill:'.$bg_color.';stroke:'.$bg_color.';stroke-width:1;opacity:1" />';
		// $frame_svg .=  '<rect x="'.$x_2.'" y="'.$y_0.'" width="'.($x_3-$x_2).'" height="'.($y_1-$y_0).'"style="fill:'.$bg_color.';stroke:'.$bg_color.';stroke-width:1;opacity:1" />';
		// $frame_svg .=  '<rect x="'.$x_2.'" y="'.$y_2.'" width="'.($x_3-$x_2).'" height="'.($y_3-$y_2).'"style="fill:'.$bg_color.';stroke:'.$bg_color.';stroke-width:1;opacity:1" />';
		// $frame_svg .=  '<rect x="'.$x_0.'" y="'.$y_2.'" width="'.($x_1-$x_0).'" height="'.($y_3-$y_2).'"style="fill:'.$bg_color.';stroke:'.$bg_color.';stroke-width:1;opacity:1" />';

		return $arr_frame_svg;

	}

	public static function getTemplateList($arr_where=array()){
		return array(
				array(
					'id'=>23,
					'name'=>'New theme',
					'bleed'=>2,
					'image_file'=>'http://designonline.com/assets/images/templates/23/design.png',
					'width' => '6',
					'height' => '6',
					)
				);
	}

	public static function exportSvgTo($svg_link,$ext='png',$width = '800',$height='800'){
		$outlink = str_replace("svg",$ext,$svg_link);
		$phantomjs_exe = app_path().DS.'phantomjs'.DS.'phantomjs '.app_path().DS.'phantomjs'.DS.'rasterize.js';
		$cmd = $phantomjs_exe.' "'.getcwd().$svg_link.'" "'.getcwd().$outlink.'" '.$width.'*'.$height;
        if(exec($cmd)=='ok'){
        	return $outlink;
        }else
        	return 'error';
	}


	// MATH LAP
	public static function linear_equations($pointA,$pointB,$mode='normal'){
		$vectorAB = $normalvector = array();
		$vectorAB['x'] = $pointB['x'] - $pointA['x'];
		$vectorAB['y'] = $pointB['y'] - $pointA['y'];
		// $gcl = self::greatest_common_divisor($vectorAB['x'],$vectorAB['y']);
		// $vectorAB['x'] = $vectorAB['x']/$gcl;
		// $vectorAB['y'] = $vectorAB['y']/$gcl;
		$normalvector['x'] = -1*$vectorAB['y'];
		$normalvector['y'] = $vectorAB['x'];

		$line = array();
		if($mode=='normal'){
			$line['a'] = $normalvector['x'];
			$line['b'] = $normalvector['y'];
			$line['c'] = $normalvector['x']*$pointA['x'] + $normalvector['y']*$pointA['y'];
		}else{
			$line['a'] = $vectorAB['x'];
			$line['b'] = $vectorAB['y'];
			$line['c'] = $vectorAB['x']*$pointA['x'] + $vectorAB['y']*$pointA['y'];
		}
		return $line;
	}

	public static function linear_parallel($line,$distance){
		$line_parallel = $arr_line = array();
		$line_parallel['a'] = $line['a'];
		$line_parallel['b'] = $line['b'];
		$line_parallel['c'] = ($distance*sqrt($line['a']*$line['a'] + $line['b']*$line['b'])) - $line['c'];
		$arr_line[] = $line_parallel;
		$line_parallel['c'] = -1*($distance*sqrt($line['a']*$line['a'] + $line['b']*$line['b'])) - $line['c'];
		$arr_line[] = $line_parallel;
		return $arr_line;
	}

	public static function two_point_same_located($line,$pointA,$pointB){
		$check_pA = $line['a']*$pointA['x'] + $line['b']*$pointA['y'] - $line['c'];
		$check_pB = $line['a']*$pointB['x'] + $line['b']*$pointB['y'] - $line['c'];
		if( ($check_pA>0 && $check_pB>0) || ($check_pA<0 && $check_pB<0)){
			return true;
		}else{
			return false;
		}
	}

	public static function coor_node($lineA,$lineB){
		$tmp = $lineA['a']*$lineB['b'] - $lineA['b']*$lineB['a'];
		if($tmp!=0)
			$m['y'] = ($lineA['a']*$lineB['c'] - $lineA['c']*$lineB['a'])/$tmp;
		else
			$m['y'] = 0;
		if($lineA['a']!=0)
			$m['x'] = ($lineA['c'] - $lineA['b']*$m['y'])/$lineA['a'];
		return $m;
	}

	public static function greatest_common_divisor($a,$b,$mode='gcl'){
		if(!isset($a) || !isset($b))
			return false;
		$minnum = ($a<$b)?$a:$b;
		$i = 1;
		do{
			if( $a%$i==0 && $b%$i==0)
				$gcl = $i;
			$i++;
		}
		while ($i <= $minnum);
		@$lcm = ($a*$b)/$gcl;

		if($mode=='gcl')
			return $gcl;
		else
			return $lcm;
	}



}
?>