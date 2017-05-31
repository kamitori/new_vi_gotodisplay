<?php

class ConfiguresController extends AdminController {

	public static $table = 'configures';

	public function index()
	{
		$this->layout->title = 'Configures';
		$configures = Configure::select('ckey', 'cvalue')
								->whereIn('ckey', ['title_site', 'meta_description', 'main_logo', 'favicon'])
								->get();
		$configure = [];
		foreach($configures as $value) {
			$configure[$value->ckey] = $value->cvalue;
		}
		$this->layout->content = View::make('admin.configures-all')->with([
																			'configure' => $configure
																		]);
	}

	public function listConfigure()
	{
   		if( !Request::ajax() ) {
            return App::abort(404);
        }
		$start = Input::has('start') ? (int)Input::get('start') : 0;
		$length = Input::has('length') ? Input::get('length') : 10;
		$search = Input::has('search') ? Input::get('search') : [];
		$configures = Configure::select('id', 'cname', 'ckey', 'cvalue', 'cdescription', 'active')
								->whereNotIn('ckey', ['title_site', 'meta_description', 'main_logo', 'favicon'])
								->where('ckey', 'not like', 'email_%');
		if(!empty($search)){
			foreach($search as $key => $value){
				if(empty($value)) continue;
				if( $key == 'active' ) {
					if( $value == 'yes' ) {
						$value = 1;
					} else {
						$value = 0;
					}
	        		$configures->where($key, $value);
	        	} else {
	                $value = ltrim(rtrim($value));
	        		$configures->where($key,'like', '%'.$value.'%');
				}
			}
		}
		$order = Input::has('order') ? Input::get('order') : [];
		if(!empty($order)){
			$columns = Input::has('columns') ? Input::get('columns') : [];
			foreach($order as $value){
				$column = $value['column'];
				if( !isset($columns[$column]['name']) || empty($columns[$column]['name']) )continue;
				$configures->orderBy($columns[$column]['name'], ($value['dir'] == 'asc' ? 'asc' : 'desc'));
			}
		}
        $count = $configures->count();
        if($length > 0) {
			$configures = $configures->skip($start)->take($length);
		}
		$arrConfigures = $configures->get()->toArray();
		$arrReturn = ['draw' => Input::has('draw') ? Input::get('draw') : 1, 'recordsTotal' => Configure::count(),'recordsFiltered' => $count, 'data' => []];
		if(!empty($arrConfigures)){
			foreach($arrConfigures as $configure){
				if( strpos($configure['cvalue'], URL.'/assets/') !== false ) {
					$value = ['file' => 'image', 'value' => $configure['cvalue']];
				} else if( File::exists($configure['cvalue']) ) {
					$value = ['file' => 'other', 'value' => str_replace(app_path().DS.'files'.DS, '', $configure['cvalue'])];
				} else {
					$value = ['file' => false, 'value' => $configure['cvalue']];
				}
				$arrReturn['data'][] = array(
	                              ++$start,
	                              $configure['id'],
	                              $configure['cname'],
	                              $configure['ckey'],
	                              $value,
	                              $configure['cdescription'],
	                              $configure['active'],
	                              );
			}
		}
		$response = Response::json($arrReturn);
		$response->header('Content-Type', 'application/json');
		return $response;
	}

	public function updateConfigure()
	{
		if( Input::has('pk') ) {
   			if( !Request::ajax() ) {
	   			return App::abort(404);
	   		}
	   		return self::updateQuickEdit();
		} else if( Request::ajax() ) {
			$arrReturn = ['status' => 'error'];

			if( Input::has('id') ) {
				$configure = Configure::find( Input::get('id') );
			} else {
				$configure = new Configure;
			}
			if( Input::has('cname') ) {
				$configure->cname 	= Input::get('cname');
			}
			if( Input::has('ckey') ) {
				$configure->ckey 	= Input::get('ckey');
			}
			if( Input::hasFile('cvalue') ) {
				$file = Input::file('cvalue');
				if(substr($file->getMimeType(), 0, 5) == 'image') {
					$path = public_path('assets/images');
		        	$path = str_replace(['\\', '/'], DS, $path);
		        	$name = $configure->ckey.'-'.md5($file->getClientOriginalName()).'.'.$file->getClientOriginalExtension();
					$file->move( $path, $name );
					$configure->cvalue 	= URL.'/assets/images/'.$name;
				} else {
					$path = app_path('files');
					if( !File::exists($path) ) {
			            File::makeDirectory($path, 493, true);
			        }
		        	$path = str_replace(['\\', '/'], DS, $path);
					$file->move( $path, $configure->ckey.'.'.$file->getClientOriginalExtension() );
					$configure->cvalue 	= $path.DS.$name;
				}
			} else if( Input::has('cvalue') ) {
				$configure->cvalue 	= Input::get('cvalue');
			}
			if( Input::has('cdescription') ) {
				$configure->cdescription = Input::get('cdescription');
			}
			if( Input::has('active') ) {
				$configure->active 	= Input::has('active') ? 1 : 0;
			}

			$pass = $configure->valid();

			if( $pass ) {
				$configure->save();
				$arrReturn = ['status' => 'ok'];
		     	$arrReturn['message'] = $configure->cname.' has been saved';
		     	$arrReturn['data'] = $configure;
			} else {
	        	$arrReturn['message'] = '';
	        	$arrErr = $pass->messages()->all();
	        	foreach($arrErr as $value)
	        	    $arrReturn['message'] .= "$value\n";
	        }

			$response = Response::json($arrReturn);
			$response->header('Content-Type', 'application/json');
			return $response;
		}
		$arrPost = Input::all();
		$choose = $arrPost['choose'];
		unset($arrPost['choose'], $arrPost['_token']);
		foreach($arrPost as $key => $value) {
			if( $key == 'main_logo' ) {
				$imageId = 0;
				if( $choose ) {
					$imageId = $choose;
				} else if( Input::hasFile('main_logo') ) {
   					$imageId = VIImage::upload(Input::file('main_logo'), public_path().DS.'assets'.DS.'images'.DS.'logos', 400, false);
				}
				if( $imageId ) {
					$path = VIImage::where('id', $imageId)->pluck('path');
					$configure = Configure::firstOrNew(['ckey'=> $key]);
					$configure->ckey = $key;
					$configure->cname = Str::title(str_replace('_', ' ', $key));
					$configure->cvalue = $path;
					$configure->save();
		   		}
			} else if( $key == 'favicon' ) {
				if( !Input::hasFile('favicon') ) continue;
   				$imageId = VIImage::upload(Input::file('favicon'), public_path().DS.'assets'.DS.'favicons', 16, false, 'favicon');
   				$path = VIImage::where('id', $imageId)->pluck('path');
   				$configure = Configure::firstOrNew(['ckey'=> $key]);
				$configure->ckey = $key;
				$configure->cname = Str::title(str_replace('_', ' ', $key));
				$configure->cvalue = $path;
				$configure->save();
			} else {
				$configure = Configure::firstOrNew(['ckey'=> $key]);
				$configure->ckey = $key;
				$configure->cname = Str::title(str_replace('_', ' ', $key));
				$configure->cvalue = $value;
				$configure->save();
			}
		}

		return Redirect::to(URL.'/admin/configures')->with('flash_success', 'Main Configure has been saved.');
	}

	public function updateQuickEdit()
	{
   		$arrReturn = ['status' => 'error'];
   		$id = (int)Input::get('pk');
   		$name = (string)Input::get('name');
   		try {
   			$configure = Configure::findorFail($id);
			$value = Input::get('value');
			if( $name == 'active' ) {
				$configure->active = (int)$value;
			} else {
				$configure->$name = $value;
			}
	    } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
	        return App::abort(404);
	    }
	    $pass = $configure->valid();
        if($pass->passes()) {
        	$configure->save();
   			$arrReturn = ['status' => 'ok'];
        	$arrReturn['message'] = $configure->cname.' has been saved';
        } else {
        	$arrReturn['message'] = '';
        	$arrErr = $pass->messages()->all();
        	foreach($arrErr as $value)
        	    $arrReturn['message'] .= "$value\n";
        }
        $response = Response::json($arrReturn);
		$response->header('Content-Type', 'application/json');
		return $response;
	}

	public function deleteConfigure($id)
	{
		if( Request::ajax() ) {
   			$arrReturn = ['status' => 'error', 'message' => 'Please refresh and try again.'];
   			try {
	   			$configure = Configure::findorFail($id);
		    } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
		        return App::abort(404);
		    }
		    $name = $configure->cname;
		    if( File::exists($configure->cvalue) ) {
		    	File::delete($configure->cvalue);
		    }
   		    if( $configure->delete() )
   		        $arrReturn = ['status' => 'ok', 'message' => "<b>{$name}</b> has been deleted."];
   		    $response = Response::json($arrReturn);
   		    $response->header('Content-Type', 'application/json');
   		    return $response;
   		}
   		return App::abort(404);
	}

	public function imageBrowser($page = 1)
    {
    	if( Request::ajax() ) {
	   		if( Input::has('page') ) {
	   			$page = Input::get('page');
	   		}
	   		$response = Response::json(VIImage::imageBrowser('logos', $page, false));
			$response->header('Content-Type', 'application/json');
			return $response;
		}
   		return App::abort(404);
    }
}