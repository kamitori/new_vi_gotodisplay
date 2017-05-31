<?php

class JTContact extends JT {
    public function getSource()
    {
        return 'tb_contact';
    }
    protected $collection = 'tb_contact';
    public function create_contact($arr){
    	$arrSave = array();
    	$email = isset($arr['email']) ? $arr['email'] : '';
    	if($email!=''){
    		$check_email = JTContact::where('email',$email)->first();
    		if(!$check_email){
    			$arrSave['email'] = $email;
    			$arrSave['first_name'] = $email;
    			$arrSave['last_name'] = '';
    			$arrSave['full_name'] = $email;
    			$arrSave['phone'] = '';
    			$arrSave['updated_at'] = new \MongoDate();
    			$arrSave['created_at'] = new \MongoDate();
    			return $this->getConnection()->collection($this->getSource())->insertGetId($arrSave);
    		}    		
    	}
    	return false;
    }
}
