<?php

class ImagesController extends AdminController {

	public static $table = 'images';

	private static $take = 48;

	public function index()
	{
		$this->layout->title = 'Images';
		$tags = VIImage::getTags();
		$arrTags = [];
		foreach($tags as $k => $v) {
			$arrTags[$v->val] = $v->val;
		}
		$arrStores = [
				'local'	=> 'Local',
		];
		$googleDrive = Configure::getGoogleDrive();
		if( !empty($googleDrive) ) {
			$arrStores['google-drive'] = 'Google Drive';
		}
		$this->layout->content = View::make('admin.images-all')->with([
																	'arrTags' 	=> $arrTags,
																	'arrStores' => $arrStores
																]);
	}

	public function getImages()
	{
		if( !Request::ajax() ) {
			return App::abort(404);
		}
		$page = Input::has('page') ? (int)Input::get('page') : 1;
		$tags = Input::has('tags') ? Input::get('tags') : [];
		$tags = implode(', ', $tags);
		$skip = 0;
		$take = self::$take;
		$skip = $take * ($page - 1);
		$images = [];
		$arrImages = VIImage::getOthers(['take' => $take, 'skip' => $skip, 'tags' => $tags]);
		$total = $arrImages['total'];
		if( $total ) {
			$arrImages = $arrImages['images']->toArray();
			$service = GoogleDrive::connect();
			foreach( $arrImages as $image ) {
				if( $image['store'] == 'google-drive' && $image['file_id'] ) {
					$key = md5('600450jpg');
					if( Cache::tags(['images', $image['id']])->has($key) ) {
						$image['outter_path'] = URL.'/thumb/'.$image['id'].'/600x450.jpg';
					} else {
						$path = GoogleDrive::getFile($image['file_id'], $service)->thumbnailLink;
						$image['outter_path'] = URL.'/thumb/'.$image['id'].'/600x450.jpg?path='.urlencode($path);
					}
				}
				$images[$image['id']] = $image;
			}
		}
		$total = ceil($total / $take);
		return [
				'images' => $images,
				'page'	 => $page,
				'total'	 => $total
			];
	}

	public function updateImage()
	{
		if( !Request::ajax() ) {
			return App::abort(404);
		}
		$id = Input::has('id') ? Input::get('id') : 0;
		$store = '';
		if( Input::has('store') ) {
			$store = Input::get('store');
		}
		$tags = Input::get('tags');

		$path = public_path('assets'.DS.'images'.DS.'libs');
		$fileName = $imgPath = '';
		if( Input::hasFile('image') ) {
			if( !File::exists($path) ) {
			    File::makeDirectory($path, 493, true);
			}
			$file = Input::file('image');
			$fileName = Str::slug(str_replace('.'.$file->getClientOriginalExtension(), '', $file->getClientOriginalName())).'.'.date('d-m-y').'-'.time().'.'.$file->getClientOriginalExtension();
			$file->move($path, $fileName);
			$imgPath = 'assets/images/libs/'.$fileName;
		}
		$arrReturn = [];
		$gFile = $file_id = null;
		if( $store == 'google-drive' && !empty($imgPath) ) {
			$filePath = public_path($imgPath);
			$gFile = GoogleDrive::addFile([
										'title'		=> $file->getClientOriginalName(),
										'path' 		=> $filePath,
										'mime_type'	=> Image::make($filePath)->mime()
									]);
			$file_id = $gFile->id;
			File::delete($filePath);
			$imgPath = '';
		}
		if( $id ) {
			$arrReturn['status'] = 'ok';
			if( !empty($imgPath) || !is_null($file_id) ) {
				$image = VIImage::find($id);
				$oldImg = $image->path;
				if( $oldImg != $imgPath ) {
					File::delete( public_path($oldImg) );
				}
				$image->path = $imgPath;
				if( !empty($store) ) {
					$image->store = $store;
				}
				$image->file_id = $file_id;
				$image->save();
				$arrReturn['changeImage'] = true;
				Cache::tags(['images', $id])->flush();
			}
			DB::table('imageables')
				->where('image_id', $id)
				->where('imageable_id', 0)
				->where('imageable_type', 'Other')
				->update([
						'option' 		=> $tags
					]);
			$arrReturn['message'] = 'Image has been updated.';
			$arrReturn['data'] = [
								'id' => $id,
								'option' => $tags
							];
		} else {
			$arrReturn['status'] = 'ok';
			if( empty($imgPath) && is_null($file_id) ) {
				return ['status' => 'error', 'message' => 'You must upload image to add new image.'];
			}
			$arrInsert = [
					'path' 	=> $imgPath,
					'file_id' => $file_id
				];
			if( !empty($store) ) {
				$arrInsert['store'] = $store;
			}
			$id = VIImage::insertGetId($arrInsert);
			DB::table('imageables')
				->insert([
						'image_id' 		=> $id,
						'imageable_id' 	=> 0,
						'imageable_type'=> 'Other',
						'option' 		=> $tags
					]);
			$arrReturn['newImage'] = true;
			$arrReturn['data'] = [
								'id' =>  $id,
								'option' => $tags,
							];
			$arrReturn['message'] = 'Image has been created.';
		}
		if( isset($arrReturn['data']) ) {
			if( !is_null($gFile) ) {
				$key = md5('600450jpg');
				if( Cache::tags(['images', $id])->has($key) ) {
					$arrReturn['data']['outter_path'] = URL.'/thumb/'.$id.'/600x450.jpg';
				} else {
					$arrReturn['data']['outter_path'] = URL.'/thumb/'.$id.'/600x450.jpg?path='.urlencode($gFile->thumbnailLink);
				}
			}
			$arrReturn['data']['store'] = $store;
		}
		return $arrReturn;
	}

	public function deleteImage($id)
	{
		if( !Request::ajax() ) {
			return App::abort(404);
		}
		try {
			$image = VIImage::findorFail($id);
	    } catch(Illuminate\Database\Eloquent\ModelNotFoundException $e) {
	        return ['status' => 'error', 'message' => 'Image did not exist.'];
	    }
	    if( $image->store == 'local' ) {
	    	if( $image->path ) {
	    		File::delete( public_path($image->path) );
	    	}
	    } else if( $image->store == 'google-drive' && $image->file_id ) {
			GoogleDrive::deleteFile($image->file_id);
	    }
		$image->delete();
		DB::table('imageables')
			->where('image_id', $id)
			->where('imageable_id', 0)
			->where('imageable_type', 'Other')
			->delete();
		Cache::tags(['images', $id])->flush();
	    return ['status' => 'ok', 'message' => 'Image has been deleted.'];
	}

}
