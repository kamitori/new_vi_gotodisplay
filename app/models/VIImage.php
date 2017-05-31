<?php

class VIImage extends BaseModel {

	protected $table = 'images';

    public static function upload($file, $path, $width = 110, $makeThumb = true, $fileName = '')
    {
        if( !File::exists($path) ) {
            File::makeDirectory($path, 493, true);
        }
        if( !empty($fileName) ) {
            $fileName .= '.'.$file->getClientOriginalExtension();
        } else {
            $fileName = Str::slug(str_replace('.'.$file->getClientOriginalExtension(), '', $file->getClientOriginalName())).'.'.date('d-m-y').'.'.$file->getClientOriginalExtension();
        }
        $path = str_replace('\\', DS, $path);
        if($file->move($path, $fileName)){
            BackgroundProcess::resize($width, $path, $fileName);
            if( $makeThumb ) {
                BackgroundProcess::makeThumb($path, $fileName);
            }
            $imgPath = str_replace(public_path(), '', $path);
            $imgPath = str_replace(DS, '/', $imgPath);
            $imgPath = ltrim(rtrim($imgPath, '/'), '/');
            $imgPath .= '/'.$fileName;
            return self::insertGetId([
                    'path' => $imgPath,
                ]);
        }
        return 0;
    }

    public static function imageBrowser($type = 'all', $page = 1, $getThumb = true)
    {
        $limit = 10;
        $skip = ($page - 1) * $limit;
        $images = self::select('id', 'path');
        if( $type != 'all' ) {
            $images->where('path', 'like', '%assets/images/'.$type.'/%');
        }
        $images = $images->limit($limit)
                    ->skip($skip)
                    ->orderBy('id', 'desc')
                    ->get();
        $arrReturn = [];
        if( !$images->isEmpty() ) {
            $public_path = public_path();
            foreach($images as $image) {
                $type = explode('/', $image['path']);
                $type = $type[count($type)-2];
                $image['path'] = str_replace('/', DS, $image['path']);
                $path = $public_path.DS.$image['path'];
                $pathInfo = pathinfo($path);
                if( $getThumb ) {
                    $img = '/thumbs/'.$pathInfo['filename'].'.'.$pathInfo['extension'];
                    $path = str_replace($pathInfo['filename'].'.'.$pathInfo['extension'], $img, $path);
                } else {
                    $img = $pathInfo['filename'].'.'.$pathInfo['extension'];
                }
                try {
                    list($w, $h) = getimagesize($path);
                } catch(Exception $e) {
                    continue;
                }
                $arrReturn[$image['id']] = [
                                'type'=>$type,
                                'f' => $img,
                                'w' => $w,
                                'h' => $h,
                            ];
            }
        }
        return $arrReturn;
    }

    public function products()
    {
        return $this->morphedByMany('Product', 'imageable');
    }

    public static function getTags()
    {
        try {
            return DB::select(DB::raw('call imageTags()'));
        } catch(Exception $e) {
            return [];
        }
    }

    public static function getOthers($arrData = array('take' => 0, 'skip' => 0, 'tags' => ''))
    {
        $arrData = array_merge(array('take' => 0, 'skip' => 0, 'tags' => ''), $arrData);
        $images = self::select('images.id', 'path', 'option', 'images.store', 'images.file_id')
                        ->join('imageables', 'imageables.image_id', '=', 'images.id')
                        ->where('imageable_type', 'Other');
        if( !empty($arrData['tags']) ) {
            $images->whereRaw(
                    'MATCH(`option`) AGAINST(? IN BOOLEAN MODE)',
                    [ trim($arrData['tags']) ]
                );
        }
        $total = $images->count();
        if( $arrData['take'] ) {
            $images->take($arrData['take']);
        }
        if( $arrData['skip'] ) {
            $images->skip($arrData['skip']);
        }
        $images = $images->get();
        return ['total' => $total, 'images' => $images];
    }

    public static function getImage($id, $sizew, $sizeh, $extension, $path = '')
    {
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
                $extension = 'jpg';
                break;
            case 'png':
                $extension = 'png';
                break;
            default:
                $extension = 'jpg';
                break;
        }
        if( $sizeh > 2000 ) {
            $sizeh = 2000;
        } else if( $sizeh < 0 ) {
            $sizeh = 200;
        }

        if( $sizew > 2000 ) {
            $sizew = 2000;
        } else if( $sizew < 0 ) {
            $sizew = 200;
        }
        $key = md5($sizew.$sizeh.$extension);
        if( Cache::tags(['images', $id])->has($key) ) {
            return Cache::tags(['images', $id])->get($key);
        } else {
            $force = true;
            if( empty($path) ) {
                $force = false;
                $path = self::where('id', $id)
                            ->pluck('path');
                $path = public_path($path);
            }
            if( $path ) {
                try {
                    $image = Image::make($path);
                } catch(Exception $e) {
                    return false;
                }
                if( $force ) {
                    $image->resize($sizew, $sizeh, function($constraint){
                                                    $constraint->aspectRatio();
                                                });
                } else {
                    for( $i = 0; $i < 2; $i++ ) {
                        $w = $h = null;
                        if( $image->width() > $sizew ) {
                            $w = $sizew;
                        } else if( $image->height() > $sizeh ) {
                            $h = $sizeh;
                        }
                        if (is_null($w) && is_null($h)) {
                            break;
                        }
                        $image->resize($w, $h, function($constraint){
                                                        $constraint->aspectRatio();
                                                    });
                    }
                }
                $image = Image::canvas($sizew, $sizeh)
                                ->insert($image, 'center');
                $arr = [
                    'image' => $image->encode($extension),
                    'time'  => time(),
                    'mime'  => 'image/'.$extension,
                ];
                Cache::tags(['images', $id])->put($key, $arr, 43200);
                return $arr;
            }
        }
        return false;
    }
}