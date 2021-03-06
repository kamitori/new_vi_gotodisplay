<?php

use Jenssegers\Mongodb\Model as Eloquent;

class JT extends Eloquent{

	protected $connection = JT_DB;
	public function __default(&$arrData)
    {
        $defaultField = $this->getDefault();
        foreach($defaultField as $field => $type){
            if( isset($arrData[$field]) ) continue;
            if( $type == 'string' ){
                $arrData[$field] = '';
            } else if( $type == 'number' ){
                $arrData[$field] = 0;
            } else if( $type == 'date' ){
                $arrData[$field] = new \MongoDate();
            } else if( $type == 'bool' ){
                $arrData[$field] = false;
            } else if( $type == 'array' ){
                $arrData[$field] = [];
            } else if( is_array($type) && isset($type['default']) ) {
                $arrData[$field] = $type['default'];
            }
        }
        $arrData['created_by'] = new \MongoId('100000000000000000000000');
        $arrData['modified_by'] = new \MongoId('100000000000000000000000');
        $arrData['date_modified'] = new \MongoDate();
    }
}