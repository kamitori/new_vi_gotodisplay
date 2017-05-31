<?php

    defined('DS') || define('DS', DIRECTORY_SEPARATOR);
    defined('APP_PATH') || define('APP_PATH', __DIR__);
    defined('MONGO_VERSION')    || define('MONGO_VERSION', 0);
    defined('ARTISAN')  || define('ARTISAN', APP_PATH.DS.'..'.DS.'artisan');
    defined('PHAMTOM_CONVERT') || define('PHAMTOM_CONVERT', APP_PATH.DS.'phantomjs'.DS.'phantomjs '.APP_PATH.DS.'phantomjs'.DS.'rasterize.js');
    defined('CACHED_DIR') || define('CACHED_DIR', APP_PATH . DS.'storage'.DS.'cache');
    defined('CACHED_VIEW') || define('CACHED_VIEW', APP_PATH . DS.'storage'.DS.'views');
    //=================================================================================
    $info = getInfo();
    defined('DEBUG') || define('DEBUG', $info['debug']);
    defined('URL') || define('URL', $info['url']);
    defined('DB_HOST') || define('DB_HOST', $info['db_host']);
    defined('JT_DB') || define('JT_DB', $info['jt_db']);
    defined('JT_IP') || define('JT_IP',$info['jt_ip']);
    defined('JT_PASS') || define('JT_PASS','2016Anvy!');
    defined('PHP_PATH')   || define('PHP_PATH', $info['php_path']);

    defined('PUSHER_APP_ID') || define('PUSHER_APP_ID', $info['pusher_api_id']);
    defined('PUSHER_KEY') || define('PUSHER_KEY', $info['pusher_key']);
    defined('PUSHER_SECRET') || define('PUSHER_SECRET', $info['pusher_secret']);

    defined('DEFAULT_CURRENCY') || define('DEFAULT_CURRENCY', '$');

    //=================================================================================
    function getDeliveryDetai($tracking_number){        
        try {
            $client = createPWSSOAPClient();    

            @$request->PIN->Value = $tracking_number;
            $arr_data = $client->GetDeliveryDetails($request);
            return $arr_data;
        } catch (SoapFault $exception) {
            return array();
        }
    }
    function getShippingDocument($tracking_number){
        try {
            $client = createPWSSOAPClientShippingDocumentService();
            @$request->ShipmentManifestDocumentCriterium->ShipmentManifestDocumentCriteria->ManifestDate = "2010-03-15";    
            return $client->GetShipmentManifestDocument($request);
        } catch (SoapFault $exception) {
            return array();
        }
    }
    function getPuralatorCourrierTrackPackage($track_number){
        try {
            $client = createPWSSOAPClient();
            @$request->PINs->PIN->Value = $track_number;
            return $client->TrackPackagesByPin($request);
        } catch (SoapFault $exception) {
            return array();
        }    
    }
    function pr($value) {
        echo    '<pre>';
        print_r($value);
        echo    '</pre>';
    }

    function getInfo() {
        $server_name = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
        $arrInfo = [];
        $arrConfigs = [
            'vi1.com'                   => [
                                            'url'           => 'http://vi1.com',
                                            'db_host'       => 'vi1.com',
                                            'jt_db'         => 'jobtraqlocal',
                                            'jt_ip'         => '127.0.0.1',
                                            'php_path'      => 'php',
                                            'socket_url'    => 'localhost',
                                            'pusher_api_id' => '102747',
                                            'pusher_key'    => '08025e712a12829abe94',
                                            'pusher_secret' => '56620386dd3816159b30',
                                        ],

            'vi1.anvyonline.com'         => [
                                            'url'           => 'http://vi1.anvyonline.com',
                                            'db_host'       => 'vi1.anvyonline.com',
                                            'jt_db'         => 'jobtraqserver',
                                            'jt_ip'         => '167.114.209.179',
                                            'php_path'      =>  '/usr/local/bin/php',
                                            'socket_url'    => 'localhost',
                                            'pusher_api_id' => '102747',
                                            'pusher_key'    => '08025e712a12829abe94',
                                            'pusher_secret' => '56620386dd3816159b30',
                                        ],
        ];
        if( php_sapi_name() === 'cli' ) {
            if( DS == '\\' ) {
                $arrInfo = $arrConfigs['vi1.com'];
            } else {
                $arrInfo = $arrConfigs['vi1.anvyonline.com'];
            }
        } else {
            $arrInfo = $arrConfigs[$server_name];
        }

        if( in_array($server_name, ['vi1.com', '']) ) {
            $arrInfo['debug'] = true;
        } else {
            $arrInfo['debug'] = false;
        }

        return $arrInfo;
    }

    function clearCached() {
        // clearProcess(CACHED_DIR);
        clearProcess(CACHED_VIEW);
    }
    function return_to_array($obj){
        if($obj==null || $obj=='') return $obj;
        
        if(MONGO_VERSION) return (array) $obj;
        return $obj->toArray();

    }
    function clearProcess($dir){
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if(in_array($object, array('.','..','.gitignore'))) continue;
                if (filetype($dir.DS.$object) == 'dir')
                    clearProcess($dir.DS.$object);
                else
                    unlink($dir.DS.$object);
            }
            unset($objects);
            @rmdir($dir);
        }
    }

    function getMimeType($ext) {
        $mimeType = [
            'ai' => 'application/postscript', 'bcpio' => 'application/x-bcpio', 'bin' => 'application/octet-stream',
            'ccad' => 'application/clariscad', 'cdf' => 'application/x-netcdf', 'class' => 'application/octet-stream',
            'cpio' => 'application/x-cpio', 'cpt' => 'application/mac-compactpro', 'csh' => 'application/x-csh',
            'csv' => 'application/csv', 'dcr' => 'application/x-director', 'dir' => 'application/x-director',
            'dms' => 'application/octet-stream', 'doc' => 'application/msword', 'drw' => 'application/drafting',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'one' => 'application/onenote',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'dvi' => 'application/x-dvi', 'dwg' => 'application/acad', 'dxf' => 'application/dxf',
            'dxr' => 'application/x-director', 'eot' => 'application/vnd.ms-fontobject', 'eps' => 'application/postscript',
            'exe' => 'application/octet-stream', 'ez' => 'application/andrew-inset',
            'flv' => 'video/x-flv', 'gtar' => 'application/x-gtar', 'gz' => 'application/x-gzip',
            'bz2' => 'application/x-bzip', '7z' => 'application/x-7z-compressed', 'hdf' => 'application/x-hdf',
            'hqx' => 'application/mac-binhex40', 'ico' => 'image/vnd.microsoft.icon', 'ips' => 'application/x-ipscript',
            'ipx' => 'application/x-ipix', 'js' => 'application/x-javascript', 'latex' => 'application/x-latex',
            'lha' => 'application/octet-stream', 'lsp' => 'application/x-lisp', 'lzh' => 'application/octet-stream',
            'man' => 'application/x-troff-man', 'me' => 'application/x-troff-me', 'mif' => 'application/vnd.mif',
            'ms' => 'application/x-troff-ms', 'nc' => 'application/x-netcdf', 'oda' => 'application/oda',
            'otf' => 'font/otf', 'pdf' => 'application/pdf',
            'pgn' => 'application/x-chess-pgn', 'pot' => 'application/mspowerpoint', 'pps' => 'application/mspowerpoint',
            'ppt' => 'application/mspowerpoint', 'ppz' => 'application/mspowerpoint', 'pre' => 'application/x-freelance',
            'prt' => 'application/pro_eng', 'ps' => 'application/postscript', 'roff' => 'application/x-troff',
            'scm' => 'application/x-lotusscreencam', 'set' => 'application/set', 'sh' => 'application/x-sh',
            'shar' => 'application/x-shar', 'sit' => 'application/x-stuffit', 'skd' => 'application/x-koan',
            'skm' => 'application/x-koan', 'skp' => 'application/x-koan', 'skt' => 'application/x-koan',
            'smi' => 'application/smil', 'smil' => 'application/smil', 'sol' => 'application/solids',
            'spl' => 'application/x-futuresplash', 'src' => 'application/x-wais-source', 'step' => 'application/STEP',
            'stl' => 'application/SLA', 'stp' => 'application/STEP', 'sv4cpio' => 'application/x-sv4cpio',
            'sv4crc' => 'application/x-sv4crc', 'svg' => 'image/svg+xml', 'svgz' => 'image/svg+xml',
            'swf' => 'application/x-shockwave-flash', 't' => 'application/x-troff',
            'tar' => 'application/x-tar', 'tcl' => 'application/x-tcl', 'tex' => 'application/x-tex',
            'texi' => 'application/x-texinfo', 'texinfo' => 'application/x-texinfo', 'tr' => 'application/x-troff',
            'tsp' => 'application/dsptype', 'ttf' => 'font/ttf',
            'unv' => 'application/i-deas', 'ustar' => 'application/x-ustar',
            'vcd' => 'application/x-cdlink', 'vda' => 'application/vda', 'xlc' => 'application/vnd.ms-excel',
            'xll' => 'application/vnd.ms-excel', 'xlm' => 'application/vnd.ms-excel', 'xls' => 'application/vnd.ms-excel',
            'xlw' => 'application/vnd.ms-excel', 'zip' => 'application/zip', 'aif' => 'audio/x-aiff', 'aifc' => 'audio/x-aiff',
            'aiff' => 'audio/x-aiff', 'au' => 'audio/basic', 'kar' => 'audio/midi', 'mid' => 'audio/midi',
            'midi' => 'audio/midi', 'mp2' => 'audio/mpeg', 'mp3' => 'audio/mpeg', 'mpga' => 'audio/mpeg',
            'ra' => 'audio/x-realaudio', 'ram' => 'audio/x-pn-realaudio', 'rm' => 'audio/x-pn-realaudio',
            'rpm' => 'audio/x-pn-realaudio-plugin', 'snd' => 'audio/basic', 'tsi' => 'audio/TSP-audio', 'wav' => 'audio/x-wav',
            'asc' => 'text/plain', 'c' => 'text/plain', 'cc' => 'text/plain', 'css' => 'text/css', 'etx' => 'text/x-setext',
            'f' => 'text/plain', 'f90' => 'text/plain', 'h' => 'text/plain', 'hh' => 'text/plain', 'htm' => 'text/html',
            'html' => 'text/html', 'm' => 'text/plain', 'rtf' => 'text/rtf', 'rtx' => 'text/richtext', 'sgm' => 'text/sgml',
            'sgml' => 'text/sgml', 'tsv' => 'text/tab-separated-values', 'tpl' => 'text/template', 'txt' => 'text/plain',
            'xml' => 'text/xml', 'avi' => 'video/x-msvideo', 'fli' => 'video/x-fli', 'mov' => 'video/quicktime',
            'movie' => 'video/x-sgi-movie', 'mpe' => 'video/mpeg', 'mpeg' => 'video/mpeg', 'mpg' => 'video/mpeg',
            'qt' => 'video/quicktime', 'viv' => 'video/vnd.vivo', 'vivo' => 'video/vnd.vivo', 'gif' => 'image/gif',
            'ief' => 'image/ief', 'jpe' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg',
            'pbm' => 'image/x-portable-bitmap', 'pgm' => 'image/x-portable-graymap', 'png' => 'image/png',
            'pnm' => 'image/x-portable-anymap', 'ppm' => 'image/x-portable-pixmap', 'ras' => 'image/cmu-raster',
            'rgb' => 'image/x-rgb', 'tif' => 'image/tiff', 'tiff' => 'image/tiff', 'xbm' => 'image/x-xbitmap',
            'xpm' => 'image/x-xpixmap', 'xwd' => 'image/x-xwindowdump', 'ice' => 'x-conference/x-cooltalk',
            'iges' => 'model/iges', 'igs' => 'model/iges', 'mesh' => 'model/mesh', 'msh' => 'model/mesh',
            'silo' => 'model/mesh', 'vrml' => 'model/vrml', 'wrl' => 'model/vrml',
            'mime' => 'www/mime', 'pdb' => 'chemical/x-pdb', 'xyz' => 'chemical/x-pdb'];
        return isset($mimeType[$ext]) ? $mimeType[$ext] : 'text/plain';
    }

    function showQuery($last = false)
    {
        $queries = DB::getQueryLog();
        if( $last )
            pr( end($queries) );
        else
            pr( $queries );
    }

    // Sort mảng theo giá trị key, hàm đơn giản
    function aasort(&$array=array(), $key='',$order=1,$isResetKey = false) {
        $sorter=array();
        $ret=array();
        if(is_array($array) && count($array)>0){
            reset($array);
            foreach ($array as $ii => $va) {
                if(!isset($va[$key])) continue;
                $sorter[$ii]=$va[$key];
            }
        }
        if($order==1)
            asort($sorter);
        else
            arsort($sorter);
        if(!$isResetKey)
            foreach ($sorter as $ii => $va) {
                $ret[$ii]=$array[$ii];
            }
        else
            foreach ($sorter as $ii => $va) {
                $ret[]=$array[$ii];
            }
        $array=$ret;
        return $array;
    }

    // Sort mảng theo giá trị key, cho phép theo nhiều cách sort_flags
    function msort($array, $key,$order=1,$sort_flags = SORT_REGULAR) {
        if (is_array($array) && count($array) > 0) {
            if (!empty($key)) {
                $mapping = array();
                foreach ($array as $k => $v) {
                    $sort_key = '';
                    if (!is_array($key)) {
                        $sort_key = $v[$key];
                    } else {
                        // @TODO This should be fixed, now it will be sorted as string
                        foreach ($key as $key_key) {
                            $sort_key .= $v[$key_key];
                        }
                        $sort_flags = SORT_STRING;
                    }
                    $mapping[$k] = $sort_key;
                }
                if($order==1)
                    asort($mapping, $sort_flags);
                else
                    arsort($mapping, $sort_flags);
                $sorted = array();
                foreach ($mapping as $k => $v) {
                    $sorted[] = $array[$k];
                }
                return $sorted;
            }
        }
        return $array;
    }
    // start
    define('DEVELOPER',0);
    if(DEVELOPER){
          define("PRODUCTION_KEY", "6286dfb90dd841af9ee2de940246e186");
          define("PRODUCTION_PASS", "soyK.VKF");
          define("BILLING_ACCOUNT", "9999999999");
          define("REGISTERED_ACCOUNT", "9999999999");
    }
    else
    {      
          define("PRODUCTION_KEY", "95683a9374c347cda3987cf5a0afe604");
          define("PRODUCTION_PASS", "Y#kqRnkp");
          define("BILLING_ACCOUNT", "0222347");
          define("REGISTERED_ACCOUNT", "0222347");  
    }
    class SOAPStruct
    {
        function __construct($user, $pass) 
        {
            $this->username = $user;
            $this->password = $pass;
        }
    }
    function createPWSSOAPClientEstimate()
    {
        if(!defined('DEVELOPER') || DEVELOPER)
        {
          $client = new SoapClient( 
            "./wsdl/Development/EstimatingService.wsdl", 
            array (
              'trace'     =>  true,
              'location'  =>  "https://devwebservices.purolator.com/PWS/V1/Estimating/EstimatingService.asmx",                                      
              'uri'       =>  "http://purolator.com/pws/datatypes/v1",
              'login'     =>  PRODUCTION_KEY,
              'password'  =>  PRODUCTION_PASS
            )
          );
        }
        else
        {
          $client = new SoapClient( 
            "./wsdl/Development/EstimatingService.wsdl", 
              array (
                'trace'     =>  true,            
                'location' =>  "https://webservices.purolator.com/PWS/V1/Estimating/EstimatingService.asmx",
                'uri'       =>  "http://purolator.com/pws/datatypes/v1",
                'login'     =>  PRODUCTION_KEY,
                'password'  =>  PRODUCTION_PASS
              )
            );
        }
        $headers[] = new SoapHeader ( 'http://purolator.com/pws/datatypes/v1', 
          'RequestContext', 
          array (
            'Version'           =>  '1.4',
            'Language'          =>  'en',
            'GroupID'           =>  'xxx',
            'RequestReference'  =>  'Rating Example',
            'UserToken'         =>  USER_TOKEN
          )
        ); 
        $client->__setSoapHeaders($headers);
        return $client;
    }
    function createPWSSOAPClient()
    {
        if(!defined('DEVELOPER') || DEVELOPER)
        {
            $client = new SoapClient(app_path()."/wsdl/Development/TrackingService.wsdl", 
            // $client = new SoapClient("http://54.235.252.131/wsdl/Development/TrackingService.wsdl",            
                array   (
                        'trace'         =>  true,
                        // 'location'   =>  "https://webservices.purolator.com/PWS/V1/Tracking/TrackingService.asmx",
                        'location'  =>  "https://devwebservices.purolator.com/PWS/V1/Tracking/TrackingService.asmx",
                        'uri'               =>  "http://purolator.com/pws/datatypes/v1",
                        'login'         =>  PRODUCTION_KEY,
                        'password'  =>  PRODUCTION_PASS
                      )
              );
        }
        else
        {
            $client = new SoapClient( app_path()."/wsdl/Production/TrackingService.wsdl", 
            // $client = new SoapClient("http://54.235.252.131/wsdl/Production/TrackingService.wsdl",            
                array   (
                        'trace'         =>  true,                    
                        'location'  =>  "https://webservices.purolator.com/PWS/V1/Tracking/TrackingService.asmx",
                        'uri'               =>  "http://purolator.com/pws/datatypes/v1",
                        'login'         =>  PRODUCTION_KEY,
                        'password'  =>  PRODUCTION_PASS
                      )
              );
        }    
        $headers[] = new SoapHeader ( 'http://purolator.com/pws/datatypes/v1', 
        'RequestContext', 
        array (
                'Version'           =>  '1.2',
                'Language'          =>  'en',
                 'GroupID'           =>  'xxx',
                 'RequestReference'  =>  'Rating Example'
              )
      ); 
      $client->__setSoapHeaders($headers);
      return $client;
    }
    function get_list_image($_shipping_create){
        
        $v_return = '';
        switch($_shipping_create){
            case 1:
                $v_return = '/assets/images/tracking/ui_track_status_bar_large_created.png';
                break;
            case 2:
                $v_return = '/assets/images/tracking/ui_track_status_bar_large_pickup.png';
                break;
            case 3:
                $v_return = '/assets/images/tracking/ui_track_status_bar_large_intransit.png';
                break;
            case 4:
                $v_return = '/assets/images/tracking/ui_track_status_bar_large_attention.png';
                break;
            case 5:
                $v_return = '/assets/images/tracking/ui_track_status_bar_large_delivered.png';
                break;
            default:
                $v_return = '/assets/images/tracking/ui_track_status_bar_large_created.png';
                break;
        }
        return $v_return;
    }
    function createPWSSOAPClientShippingDocumentService()
    {   
        if(!defined('DEVELOPER') || DEVELOPER){
            $client = new SoapClient( app_path()."/wsdl/Development/ShippingDocumentsService.wsdl", 
            // $client = new SoapClient( "http://54.235.252.131/wsdl/Development/ShippingDocumentsService.wsdl",             
                array   (
                        'trace'         =>  true,                                       
                        'location'  =>  "https://devwebservices.purolator.com/EWS/V1/ShippingDocuments/ShippingDocumentsService.asmx",                  
                        'uri'               =>  "http://purolator.com/pws/datatypes/v1",
                        'login'         =>  PRODUCTION_KEY,
                        'password'  =>  PRODUCTION_PASS
                      )
              );
        }else
        {
            $client = new SoapClient( app_path()."/wsdl/Production/ShippingDocumentsService.wsdl", 
            // $client = new SoapClient( "http://54.235.252.131/wsdl/Production/ShippingDocumentsService.wsdl",             
                array   (
                        'trace'         =>  true,                                       
                        'location'  =>  "https://webservices.purolator.com/EWS/V1/ShippingDocuments/ShippingDocumentsService.asmx",                  
                        'uri'               =>  "http://purolator.com/pws/datatypes/v1",
                        'login'         =>  PRODUCTION_KEY,
                        'password'  =>  PRODUCTION_PASS
                      )
              );
        }
      $headers[] = new SoapHeader ( 'http://purolator.com/pws/datatypes/v1', 
                                    'RequestContext', 
                                    array (
                                            'Version'           =>  '1.3',
                                            'Language'          =>  'en',
                                            'GroupID'           =>  'xxx',
                                            'RequestReference'  =>  'Example Code'
                                          )
                                  );                        
      $client->__setSoapHeaders($headers);

      return $client;
    }

