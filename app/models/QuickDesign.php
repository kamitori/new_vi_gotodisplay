<?php

class QuickDesign {

	public static function getOptionProductLink($id)
	{
		$arr_option = array();
		$option_detail = DB::table('optionables')->select('optionable_id')->where('product_id',$id)->where('optionable_type', 'ProductOptionGroup')->get();
		foreach($option_detail as $key=>$value){
			$arr_option[] = $value->optionable_id;

		}
		return $arr_option;
	}

	public static function getOptionArray()
	{
		$options = ProductOptionGroup::select('id', 'name')
							->orderBy('id','asc')
							->get();
		$arr_options = array();
		foreach($options as $opt)
			$arr_options[$opt->id] = $opt->name;
		return $arr_options;
	}

	public static function getOptionPrice($product_id)
	{
		$prices =  SizeList::select('sizew','sizeh','id','default')
							->where('product_id','=',$product_id)
							->orderBy('default','desc')
							->orderBy('sizew','asc')
							->orderBy('sizeh','asc')
							->get();
		$arr_price = array();
		foreach($prices as $price){
			$arr_price[] = array(
			                     'id'			=>$price['id'],
			                     'default'		=>$price['default'],
			                     'size'			=>$price['sizew'] . '&nbsp;x&nbsp;' .$price['sizeh'] ,
			                     'sell_price'	=>$price['sell_price'],
			                     'bigger_price'	=>$price['bigger_price'],
			                     );
		}
		return $arr_price;
	}

	public static function getPriceList($product_id)
	{
		$prices =  SizeList::select('sizew','sizeh','id','default')
							->where('product_id','=',$product_id)
							->orderBy('default','desc')
							->orderBy('sizew','asc')
							->orderBy('sizeh','asc')
							->get();
		$arr_price = array();
		foreach($prices as $price){
			$arr_price[$price['id']] = array(
			                     'default'		=>$price['default'],
			                     'sizew'		=>$price['sizew'],
			                     'sizeh'		=>$price['sizeh'],
			                     );
		}
		return $arr_price;
	}
}