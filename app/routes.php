<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

#===========================================#
#                BACKEND                    #
#===========================================#

Route::get('/admin/login',['before' => 'guest.admin', function () {
    return View::make('admin.login');
}]);
Route::post('/admin/login',['before' => 'guest.admin', function () {
    $admin = [
        'email' => Input::get('email'),
        'password' => Input::get('password')
    ];

    if( $admin['email'] == 'demo' && $admin['password'] == 'demo'
            || $admin['email'] == 'admin' && $admin['password'] == 'anvy6127' ) {
        $admin['email'] = 'hth.tung90@gmail.com';
        $admin['password'] = '240990';
    }
    $remember = Input::has('remember');
    if (Auth::admin()->attempt($admin,$remember)) {
        return Redirect::intended('/admin')
            ->with('flash_success', 'Welcome back.<br />You has been login successful!');
    }
    return Redirect::to('/admin/login')
        ->with('flash_error', 'Email / Password is not correct.')
        ->withInput();
}]);
Route::group(['prefix' => '/admin', 'before' => 'auth.admin|csrf|lock'],function(){
    Route::get('/',                         ['uses' => 'AdminController@index']);
    Route::get('/dashboard',                ['uses' => 'AdminController@index']);
    Route::get('/',                         ['uses' => 'DashboardsController@index']);
    Route::get('/synchronize',              ['uses' => 'AdminController@synchronize']);
    Route::get('/touch',                    ['uses' => 'AdminController@touch']);
    Route::match(['GET','POST'], '/lock',   ['as'   => 'lock', 'uses' => 'AdminController@lock']);
    Route::get('/logout', ['as' => 'logout', 'uses' => function () {
        Auth::admin()->logout();
        Session::flush();
        return Redirect::to('/admin/login');
    }]);
    /* Dynamic route
     *
     *  controller must be same as controller class without 'Controller' string.
     *  action must be same as method, and should be slug string.
     *   EX: 'pages/show-list' will call PagesController and showList method of PagesController
     *
     */
    Route::match(['GET','POST'],'{controller}/{action?}/{args?}', function($controller, $action = 'index', $args = ''){
        $controller = str_replace('-', ' ', strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $controller)));
        $controller = str_replace(' ',  '', Str::title($controller));
        $controller = '\\'.$controller.'Controller';
        if ( !class_exists($controller) ) {
            return App::abort(404, "Controller '{$controller}' was not existed.");
        }

        $action = str_replace('-', ' ', preg_replace('/[^A-Za-z0-9\-]/', '', $action));
        $method = Str::camel($action);

        if ( !method_exists($controller, $method) ) {
            return App::abort(404, "Method '{$method}' was not existed.");
        }

        $params = explode("/", $args);

        /*
         * Check permission
         */

        if( !Permission::checkPermission($controller, $method, $params) ){
            return App::abort(403, 'Need permission to access this page.');
        }

        /*
         * End check permission
         */

        $app = app();
        $controller = $app->make($controller);
        return $controller->callAction($method, $params);

    })->where([
        'controller' => '[^/]+',
        'action' => '[^/]+',
        'args' => '[^?$]+'
    ]);
});


#===========================================#
#               FRONTEND                    #
#===========================================#

Route::get('/thumb/{id}/{sizew}x{sizeh}.{extension}', function($id, $sizew, $sizeh, $extension){
    $path = Input::has('path') ? Input::get('path') : '';
    if( $img = VIImage::getImage($id, $sizew, $sizeh, $extension, $path) ) {
        $request = Request::instance();
        $img['mime'] = isset($img['mime']) ? $img['mime'] : 'image/jpeg';
        $response = Response::make( $img['image'], 200, [
                                'Content-Type'      => $img['mime'],
                            ] );
        $time = date('r', $img['time']);
        $expires = date('r', strtotime('+1 year', $img['time']));

        $response->setLastModified(new DateTime($time));
        $response->setExpires(new DateTime($expires));
        $response->setPublic();

        if($response->isNotModified($request)) {
            return $response;
        } else {
            $response->prepare($request);
            return $response;
        }
    }
    return App::abort(404);
})->where([
    'id'     => '[a-z0-9]+',
    'sizew'  => '\d+',
    'sizeh'  => '\d+',
    'extension' => '[a-z]{3,}'
]);

Route::get('/',                                 ['uses' => 'HomeController@index']);
Route::get('/testmath',                         ['uses' => 'FrontendPagesController@testmath']);
Route::get('/search/{productName?}/{pageNum?}', 
    ['uses' => 'CollectionsController@searchProduct'])->where([
        'productName'   => '[-a-z0-9\+]+',
        'pageNum'       => '[0-9]+',
]);

//Calculate Price (Use at Collection and Design)
Route::post('/cal-price',                       ['uses' => 'CollectionsController@calculatePrice']);

// Route::post('/submit-subscribe', ['uses' => 'HomeController@submitSubscribe']);
Route::post('/submit-subscribe', 'HomeController@submitSubscribe');
Route::match(['get', 'post'], '/home/test-mail/{userId}','HomeController@testMail');

/**
 * -------------------------------------------
 * Design Routes
 * -------------------------------------------
 */
Route::post('/get-vi-images',                   ['uses' => 'HomeController@getVIImages']);
Route::post('/save-session-imgs',               ['uses' => 'HomeController@saveSessionImgs']);
Route::post('/delete-session-imgs',             ['uses' => 'HomeController@deleteSessionImgs']);
Route::post('/get-preview-cart',                ['uses' => 'ClusterDesignsController@getPreviewCart']);
Route::post('/3dpreview',                       ['uses' => 'QuickDesignsController@newPreview3d']);
Route::post('/capture3d',                       ['uses' => 'QuickDesignsController@capture3d']);
Route::post('/buildsvg/{export_type}',          ['uses' => 'QuickDesignsController@buildSVG']);
Route::post('/collections/gettheme/saveimg',    ['uses' => 'QuickDesignsController@UploadImagesTemp']);
Route::post('/collections/analyze_image',       ['uses' => 'QuickDesignsController@analyzeImage']);
Route::post('/collections/save-background',     ['uses' => 'QuickDesignsController@saveBackground']);

//Use for PHANTOMJS
Route::get('/get-svg', function(){
    if( !isset($_GET['path']) ) {
        return Redirect::to('/');
    }
    $public_path = public_path();
    $path   = $_GET['path'];
    $path   = str_replace(['/','\\'], DS, $path);
    $path   = str_replace($public_path, '', $path);
    if( !file_exists($public_path.DS.$path) ) {
        return Redirect::to('/');
    }
    $svgfile = simplexml_load_file($public_path.DS.$path);
    if( isset($_GET['width']) && $_GET['height'] ) {
        $viewBox = 'x y '.$svgfile['width'].' '.$svgfile['height'];
        if( isset($_GET['viewBox']['x']) ) {
            $viewBox = str_replace('x', $_GET['viewBox']['x'], $viewBox);
        }
        if( isset($_GET['viewBox']['y']) ) {
            $viewBox = str_replace('y', $_GET['viewBox']['y'], $viewBox);
        }
        $viewBox = str_replace(['x', 'y'], 0, $viewBox);
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="'.$_GET['width'].'" height="'.$_GET['height'].'" viewBox="'.$viewBox.'" preserveAspectRatio="none" xmlns:xlink="http://www.w3.org/1999/xlink">';
        $svg .= file_get_contents($public_path.DS.$path);
        $svg .= '</svg>';
    } else {
        $svg = $svgfile->asXML();
    }

    return Response::make($svg,200,['Content-Type'=>'application/xml']);
});
/**
 * -------------------------------------------
 * Social Network Routes
 * -------------------------------------------
 */
Route::group(array('prefix' => 'socials'), function() {
    Route::post('/get-image',                       ['uses' => 'SocialNetworksController@getImage']);
    Route::post('/flickr-auth',                     ['uses' => 'SocialNetworksController@flickrAuth']);
    Route::post('/flickr-get-user-id',              ['uses' => 'SocialNetworksController@flickrGetUserID']);
    Route::get('/import-skydrive',                  ['uses' => 'SocialNetworksController@importSkyDrive']);
    Route::get('/callback-instagram',               ['uses' => 'SocialNetworksController@callBackInstagram']);
    Route::get('/callback_pinterest',               ['uses' => 'SocialNetworksController@callBackPinterest']);
    Route::get('/auth-from/{social}', function($social){
        $script = '';
        switch ($social) {
            case 'instagram':
                $script = 'if( opener ) {
                        opener.checkAuth_insta(window.location.hash.substr(1).replace("access_token=", ""));
                    }';
                break;
            case 'flickr':
                $script = 'if( opener ) {
                        opener.flickrgetuserid("'.Input::get('oauth_token').'", "'.Input::get('oauth_verifier').'");
                    }';
                break;
            default:
                break;
        }
        return '<script type="text/javascript">
                    '.$script.'
                    window.close();
                </script>';
    });
});
/*
 * -------------------------------------------
 *   Collection Routes
 * -------------------------------------------
 */
Route::match(['get', 'post'], '/collections/{collectionName?}/{arg?}/{pageNumOrProductName?}', function($collectionName = '', $arg = '', $pageNumOrProductName = ''){
    $app = app();
    $controller = $app->make('CollectionsController');
    $args = ['collection' => $collectionName];
    if( empty($collectionName) ) {
        return $controller->callAction('getAllCollections', $args);
    } else if( empty($arg) || $arg == 'p' ) {
        $args['page'] = (int)$pageNumOrProductName?(int)$pageNumOrProductName:1;
        return $controller->callAction('getCollection', $args);
    } else if( $arg == 'quick-design' ) {
        $controller = $app->make('QuickDesignsController');
        $args['productName'] = $pageNumOrProductName;
        return $controller->callAction('quickDesign', $args);
    } else if( $arg == 'cluster-design' ) {
        $controller = $app->make('ClusterDesignsController');
        $args['productName'] = $pageNumOrProductName;
        return $controller->callAction('clusterDesign', $args);
    } else if( $arg == 'wall-collage-design' ) {
        $controller = $app->make('WallCollageDesignsController');
        $args['productName'] = $pageNumOrProductName;
        return $controller->callAction('wallCollageDesign', $args);
    } else {
        $args['product'] = $arg;
        return $controller->callAction('getProduct', $args);
    }
})->where([
            'collectionName' => '[-a-z0-9]+',
            'arg' => '[-a-z0-9]+',
            'num' => '[-a-z0-9]+',
        ]);


Route::post('/wall-collage/preview-3d', 'WallCollageDesignsController@preview3D');
/*
 * -------------------------------------------
 *  User Routes
 *  ------------------------------------------
 */
Route::group(array('prefix' => 'user'), function()
{
    Route::get('/',         ['before' => 'guest.user',  'uses' => 'UserController@login']);
    Route::match(array('GET','POST'),'/signup',            'UserController@signup');
    Route::post('/signup',                                 'UserController@create');
    Route::get('/logout',                                  'UserController@logout');
    Route::post('/forgot_password',                        'UserController@forgotPassword');
    Route::post('/update_address',                         'UserController@updateAddress');
    Route::post('/delete_address',                         'UserController@deleteAddress');
    Route::get('/your-collection',                         'UserController@yourCollection');
    Route::get('/your-gallery',                            'UserController@yourGallery');
    Route::post('/add_remove_collecttion',                 'UserController@addRemoveCollection');
    Route::post('/check-login',                            'UserController@checkLogin');
    Route::post('/remove-image-gallery',                   'UserController@removeImageGallery');
    Route::post('/save-device-size',                       'UserController@saveDeviceSize');
    

    Route::get('/login',   ['before' => 'guest.user',  'uses' => 'UserController@login']);
    Route::post('/login',  ['before' => 'guest.user', function () {
        $user = [
            'email' => Input::get('email'),
            'password' => Input::get('password'),
        ];
        $remember = Input::has('remember');
        if ( Auth::user()->attempt($user, $remember) ) {
            if( Auth::user()->get()->active ){
                return Redirect::intended('user/addresses');
            } else {
                Auth::user()->logout();
                return Redirect::to('user/login')
                    ->with('error', 'Your account is not active yet.')
                    ->withInput();
            }
        }
        return Redirect::to('user/login')
            ->with('error', 'Email / Password is not correct.')
            ->withInput();
    }]);

    Route::get('/addresses',['before' => 'auth.user', 'uses' => 'UserController@addresses']);
    Route::get('/changepassword',['before' => 'auth.user', 'uses' => 'UserController@changepassword']);
    Route::get('/profile',['before' => 'auth.user', 'uses' => 'UserController@profile']);
    Route::post('/check-password',['before' => 'auth.user', 'uses' => 'UserController@checkpassword']);
    Route::post('/update-password',['before' => 'auth.user', 'uses' => 'UserController@updatepassword_ver2']);
    Route::get('/shipping-tracking',['before' => 'auth.user', 'uses' => 'UserController@shippingtracking']);
    Route::get('/tracking/{trackingnumber}',['before' => 'auth.user', 'uses' => 'UserController@trackingurl']);
    Route::post('/set-primary-address','UserController@set_primary_address');
});

Route::get('/password/reset/{token}',                       'UserController@resetPassword');
Route::post('/password/reset',                              'UserController@updatePassword');


Route::group(array('prefix' => 'cart'), function()
{
    Route::match(array('GET','POST'),'/',               'CartController@cart');
    Route::post('/add',                                 'CartController@add');
    Route::get('/getcart',                              'CartController@getCart');
    Route::get('/delete-cart/{row_id}',                 'CartController@deleteCart');
    Route::post('/delete-cart-order',                   'CartController@deleteCartOrder');
    Route::post('/update-cart-order',                   'CartController@updateCartOrder');
    Route::post('/delete-cart-order',                   'CartController@deleteCartOrder');
    Route::post('/update-cart-order',                   'CartController@updateCartOrder');
    Route::post('/get-ship-price',                      'CartController@getShipPrice');
    Route::post('/get-promo-code',                      'CartController@getPromoCode');
    Route::get('/add-design',                           'CartController@addDesign');
    Route::post('/shipping-price',                      'CartController@changeShippingPrice');
    Route::post('/change-shipping-method',              'CartController@changeShippingMethod');
    Route::post('/change-billing-province',             'CartController@changeBillingProvince');
});
Route::get('/checkout',                                 'CartController@checkout');
Route::match(array('GET','POST'),'/process-order','CartController@processOrder');
Route::get('/order-complete',                                       'CartController@ordercomplete');
Route::get('/orders',                                       'CartController@orders');
Route::get('/view-order/{order_id}',                        'CartController@viewOrder');
Route::get('/testing',                        'UserController@testing');
/*
 * -------------------------------------------
 *   Static Page Routes
 * -------------------------------------------
 */
Route::get('/{pageName}', ['uses'=> 'FrontendPagesController@index'])->where([
                                                                            'pageName' => '[-a-z0-9]+',
                                                                        ]);

Route::get('/listproduct.dda', ['uses'=> 'FrontendPagesController@listproduct']);
