<?php
use Jenssegers\Agent\Agent;
class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
    protected $layout = 'frontend.layout.default';
    protected $device = array();

	protected function setupLayout()
	{
		if ( ! is_null($this->layout)){

            //get data device and browser. Tam thoi test dong
            $arr_device = Session::has('device')?Session::get('device'):array();
            // if(!Session::has('device')){
                $agent = new Agent();
                // $arr_device = array();
                $arr_device['browser'] = $agent->browser();
                $arr_device['version'] = $agent->version($arr_device['browser']);
                $arr_device['platform'] = $agent->platform();
                $arr_device['mobile'] = $agent->isMobile();
                $arr_device['tablet'] = $agent->isTablet();
                $arr_device['desktop'] = $agent->isDesktop();
                $arr_device['device'] = $agent->device();
                Session::set('device',$arr_device);
            // }
            $this->device = Session::get('device');
			$this->layout = View::make($this->layout);
            $this->layout->cartQuantity = CartController::getCartQuantity();
            $this->layout->metaInfo = Home::getMetaInfo();
            $this->layout->banner = Home::getBanner();
            $this->layout->small_banner = Home::getSmallBanner();
            $this->layout->headerMenu = Menu::getCache(['header' => true]);
            $this->layout->footerMenu = Menu::getCache(['footer' => true]);
            $this->layout->is_mobile = $this->device['mobile']?true:false;
            $this->layout->route = $uri = Request::path();
		}
	}

	public static function errors($code = 404, $title = '', $message = '')
    {
        $ajax = Request::ajax();
        if( !$code ) {
            $code = 500;
            $title = 'Internal Server Error';
            $message = 'We got problems over here. Please try again later!';
        } else if( $code == 404 ) {
            $title = 'Oops! You\'re lost.';
            $message = 'We can not find the page you\'re looking for.';
        }
        if( Request::ajax() ) {
            return Response::json([
                'error' => [
                    'message' => $message
                    ]
            ],$code);
        }

        $arrData = [];
        $arrData['cartQuantity'] = CartController::getCartQuantity();
        $arrData['metaInfo'] = Home::getMetaInfo();
        $arrData['banner'] = Home::getBanner();
        $arrData['small_banner'] = Home::getSmallBanner();
        $arrData['headerMenu'] = Menu::getCache(['header' => true]);
        $arrData['footerMenu'] = Menu::getCache(['footer' => true]);
        $agent = new Agent();
        $arrData['is_mobile'] = $agent->isMobile();
        $arrData['route'] = $uri = Request::path();
        $arrData['cartQuantity'] = CartController::getCartQuantity();
        $arrData['content'] = View::make('frontend.errors.error')->with(['title' => $title, 'code' => $code, 'message' => $message]);
        $arrData['metaInfo'] = Home::getMetaInfo();
        $arrData['metaInfo']['meta_title'] = $title;
        $arrData['headerMenu'] = Menu::getCache(['header' => true]);
        return View::make('frontend.layout.default')->with($arrData);
    }
    
}
