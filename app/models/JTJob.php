<?php

class JTJob extends JT {
    protected $collection = 'tb_job';
    public function getSource()
    {
        return 'tb_job';
    }

    protected function getCode()
    {
        $y = date('y');
        $m = str_pad(date('m'), 2, '', STR_PAD_LEFT);
        $prefix = "$y-$m-";
        $lastJob = self::where('no',new \MongoRegex('/'+ $prefix +'/'))->orderBy('no','desc')->first();
        $lastJob = return_to_array($lastJob);
        $code = isset($lastJob['no']) ? $lastJob['no'] : 0;
        if( strpos($code, $prefix) !== false ) {
            $code = (int)str_replace($prefix, '', $code);
        } else {
            $code = 0;
        }
        return $prefix.str_pad(++$code, 3, 0, STR_PAD_LEFT);
    }

    public function createJob($arrData = [])
    {
        $workStart = new \MongoDate(strtotime(date('Y-m-d 00:00:00')));
        $workEnd = new \MongoDate($workStart->sec + 3600);
        $arrSave = [
            'no'        => $this->getCode(),
            'work_start'=> $workStart,
            'work_end'  => $workEnd,
            'company_name'  => '',
            'company_id'    => '',
            'company_phone' => '',
        ];
        $company = JTCompany::select('contact_default_id', 'contact_id', 'name', 'email', 'phone', 'addresses', 'addresses_default_key')
            ->where('name','Retail Customer')->first();
        
        if (isset($company->_id)) {
            $arrSave['company_id'] = $company->_id;
        }
        if (isset($company->name)) {
            $arrSave['company_name'] = $company->name;
        }
        if (isset($company->phone)) {
            $arrSave['phone'] = $arrSave['company_phone'] = $company->phone;
        }
        if (isset($company->email)) {
            $arrSave['email'] = $company->email;
        }
        if (isset($company->fax)) {
            $arrSave['fax'] = $company->fax;
        }
        if (isset($company->addresses_default_key) && isset($company->addresses) && isset($company->addresses[$company->addresses_default_key])) {
            foreach ($company->addresses[$company->addresses_default_key] as $addressField => $value) {
                $arrSave['invoice_'.$addressField] = $value;
            }
        }
        $arrSave = array_merge($arrSave, $arrData);
        $this->__default($arrSave);
        $this->getConnection()->collection($this->getSource())->insert($arrSave);        
        return self::where('_id'    , $arrSave['_id'])->first();
    }

    public function todayJob($arrData = [])
    {
        $todayJob = self::select('no', 'name', '_id')
        ->where('deleted',false)->where('work_start',array(
            '$gte'  => new \MongoDate(strtotime(date('Y-m-d 00:00:00'))),
            '$lte'  => new \MongoDate(strtotime(date('Y-m-d 23:59:59'))),
        ))->where('company_name' , 'Retail Customer')->first();
        
        if (!is_object($todayJob)) {
            $todayJob = $this->createJob($arrData);
        }
        return $todayJob;
    }

    protected function getDefault()
    {
        return [
            'deleted'   => 'bool',
            'status'    => ['default' => 'New'],
            'status_id' => ['default' => 'New'],
            'type'      => 'string',
            'fax'       => 'string',
            'email'     => 'string',
            'mobile'    => 'string',
            'name'      => 'string',
            'direct_phone'=> 'string',
        ];
    }
}
