<?php


class JTCompany extends JT {
    
    public $code = '';
    public $name = '';

    protected $collection = 'tb_company';

    public function getSource(){
        return 'tb_company';
    }

   public static function getCompanyDefault(){
	    $arr_return = array('tax'=>5,'_id'=>'','taxkey'=>'','tax_no'=>'');
	    //id company Retail Customer
	    $company = self::where('deleted',false)->where('pos_default',1)->orderBy('code',-1)->first();	    
	   	if(!empty($company)){
	   		$arr_return['_id'] = (string)$company->_id;
	   		if(isset($company->account)){
	   			$arr_return['tax_no'] = (string)$company->account->tax_no;
	   		}
	   		$sales_account = JTSalesaccount::where('deleted',false)->where('company_id',$company->_id)->orderBy('code',-1)->first();
	   		
	   		if(!empty($sales_account) && $sales_account->tax_code_id!=''){
	   			$tax = JTTax::where('deleted',false)->where('province_key',$sales_account->tax_code_id)->orderBy('code',-1)->first();	   			
	   			if($tax->fed_tax!=''){
	   				$arr_return['tax'] = $tax->fed_tax;
	   				$arr_return['taxkey'] = $tax->province_key;
	   			}
	   		}
	   	}

	   	return $arr_return;

	}
}

