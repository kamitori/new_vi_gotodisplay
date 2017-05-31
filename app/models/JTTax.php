<?php

class JTTax extends JT {

	protected $collection = 'tb_tax';

	public static function getSource($toJSON = false, $provinceKey = null)
	{
		$taxs = self::select('province_key', 'fed_tax')
						->where('deleted', false);
		if( $provinceKey ) {
			if( is_array($provinceKey) ) {
				$taxs->whereIn('province_key', $provinceKey);
			} else {
				$taxs->where('province_key', $provinceKey);
			}
		}
		$arrReturn = [];
		$taxs = $taxs->orderBy('fed_tax', 'asc')
						->orderBy('province', 'asc')
						->get();
		if( !$taxs->isEmpty() ) {
			foreach($taxs as $tax) {
				$arrReturn[$tax['province_key']] = floatval($tax['fed_tax']);
			}
			if( $toJSON ) {
				$arrReturn = json_encode($arrReturn);
			}
		}
		return $arrReturn;
	}

	public static function taxSelectList(){
		$arr_tmp = self::select('hst_tax', 'province_key', 'province', 'fed_tax')
						->where('deleted', '=', false)
						->orderBy('_id', 'desc')
						->get()
						->toArray();
		$arr_ret = [];
		$arr_ret[''] = '0% (No tax)';
		foreach($arr_tmp as $kk=>$vv){
			if(isset($vv['hst_tax']) && $vv['hst_tax']=='H')
				$typetax = 'HST';
			else
				$typetax = 'GST';
			if(isset($vv['province_key']) && isset($vv['fed_tax']) && isset($vv['province']))
			$arr_ret[$vv['province_key']] = $vv['fed_tax'].'% ('.$vv['province'].') '.$typetax;
		}
		return $arr_ret;
	}

}