<?php

class DashboardsController extends AdminController {

    public function index()
    {
        $min_date = '01/01/2015';
    	$max_date = date('m/d/Y');

        $data = ['admin_id' => Auth::admin()->get()->id];

        $arrData = [];
        if( URL == 'http://vi-demo.anvyonline.com' ) {
            $arrData['sync'] = (object)DB::connection('jobtraq-demo.anvyonline.com')
                                            ->collection('tb_stuffs')
                                            ->select('sync_time', 'date_modified')
                                            ->where('name', 'last_sync_date')
                                            ->first();
        } else {
            $arrData['sync'] = JTStuff::select('sync_time', 'date_modified')
                                        ->where('name', 'last_sync_date')
                                        ->first();
        }
        $arrData['notifications'] = [
                                    'users'         => Notification::getNew( 'User', $data ),
                                    'products'      => Notification::getNew( 'Product', $data ),
                                    'orders'        => Notification::getNew( 'Order', $data ),
                                ];
        $arrData['filters'] = [
                                'categories'    => ProductCategory::getSource(),
                                'order_status'      => ['New', 'Submitted', 'In production', 'Partly shipped', 'Completed', 'Cancelled'],
                            ];
        $arrData['date'] = [
                            'min_date'      => $min_date,
                            'max_date'      => $max_date,
                            'current_date'  => new DateTime(),
                            'start_date'    => new DateTime('7 days ago')
                        ];

        $this->layout->title = 'Dashboard';
        $this->layout->content = View::make('admin.dashboard')->with( $arrData );
    }

    public function getOrderStatistic()
    {
    	if( !Request::ajax() ) {
    		return App::abort(404);
    	}
    	$fromDate = Input::has('date_start') ? Input::get('date_start') : date('m/d/Y');
    	$toDate = Input::has('date_end') ? Input::get('date_end') : date('m/d/Y');
    	$status = Input::has('order_status') ? Input::get('order_status') : '';
    	$category = Input::has('product_category') ? Input::get('product_category') : '';
    	$groupBy = Input::has('range_filter') ? Input::get('range_filter') : 'day';

    	$arrData = Dashboard::getOrders([
    							'fromDate' 	=> $fromDate,
    							'toDate' 	=> $toDate,
    							'status' 	=> $status,
    							'category' 	=> $category,
    							'groupBy' 	=> $groupBy,
    						]);

		$response = Response::json($arrData);
		$response->header('Content-Type', 'application/json');
		return $response;
    }

    public function getVisitStatistic(){
         if( !Request::ajax() ) {
             return App::abort(404);
         }
        $arrReturn = ['status' => 'ok', 'data' => []];
        $fromDate = Input::has('date_start') ? Input::get('date_start') : date('m/d/Y');
        $toDate = Input::has('date_end') ? Input::get('date_end') : date('m/d/Y');
        $groupBy = Input::has('range_filter') ? Input::get('range_filter') : 'day';
        $toDate .='23:59:59';
        $fromDate_analytic = new DateTime($fromDate);
        $fromDate_analytic = $fromDate_analytic->format('Y-m-d');
        $toDate_analytic = new DateTime($toDate);
        $toDate_analytic = $toDate_analytic->format('Y-m-d');
        $site_id = Analytics::getSiteIdByUrl(URL);

        $data = [];
        if( $groupBy == 'day' ) {
            $stats = Analytics::query($site_id, $fromDate_analytic, $toDate_analytic, 'ga:visits',['dimensions'=>'ga:day']);
            $dateRange  = new DatePeriod(
                 new DateTime($fromDate),
                 new DateInterval('P1D'),
                 new DateTime($toDate)
            );
            foreach($dateRange as $key => $date) {
                $data[] = [$date->format('M d'), $stats->rows[$key][1]];
            }

        } else if ( $groupBy == 'month' ) {
            $stats = Analytics::query($site_id, $fromDate_analytic, $toDate_analytic, 'ga:visits',['dimensions'=>'ga:month']);
            $dateRange  = new DatePeriod(
                 new DateTime($fromDate),
                 new DateInterval('P1M'),
                 new DateTime($toDate)
            );
            foreach($dateRange as $key => $date) {
                $data[] = [$date->format('M Y'), $stats->rows[$key][1]];
            }
        } else {
            $stats = Analytics::query($site_id, $fromDate_analytic, $toDate_analytic, 'ga:visits',['dimensions'=>'ga:year']);
            $dateRange  = new DatePeriod(
                 new DateTime($fromDate),
                 new DateInterval('P1Y'),
                 new DateTime($toDate)
            );
            foreach($dateRange as $key => $date) {
                $data[] = [$date->format('Y'), $stats->rows[$key][1]];
            }
        }

        $arrReturn['data']=$data;
        $response = Response::json($arrReturn);
        $response->header('Content-Type', 'application/json');
        return $response;
    }
}
