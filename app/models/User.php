<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends BaseModel implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array(/*'password', */'remember_token');

	protected $rules = array(
            		'password' 	=> 'required|min:6',
			'email' 	=> 'required|email|unique:users',
		);

	public function valid()
    {
        $arr = $this->toArray();
        if(isset($arr['id'])) {
            $this->rules['email'] .= ',email,'.$arr['id'];
            if(!isset($arr['password'])) {
                unset($this->rules['password']);
            } else {
                $this->rules['password'] .= '|confirmed';
                $this->rules['password_confirmation'] = 'required|min:6';
            }
        } else {
            $this->rules['password'] .= '|confirmed';
            $this->rules['password_confirmation'] = 'required|min:6';
        }

        return Validator::make(
            $arr,
            $this->rules
        );
    }

	public function images()
	{
		return $this->morphToMany('VIImage', 'imageable', 'imageables', 'imageable_id', 'image_id');
	}

	public function address()
	{
        return $this->hasMany('Address', 'user_id')
        				->orderBy('addresses.id', 'asc');
    }

	public function beforeDelete($user)
	{
		$user->images()->detach();
	}

	public function afterCreate($user)
	{
		Notification::add($user->id, 'User');
	}

	public static function getFolderKey()
	{
		$user_data = Auth::user()->get();
        if($user_data)
            $user_ip = $user_data->id;
        else
            $user_ip = Request::getClientIp(true);
        return $user_ip;
	}

}
