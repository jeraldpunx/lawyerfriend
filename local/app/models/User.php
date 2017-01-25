<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

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
	protected $fillable = ['role', 'email', 'password', 'activation_code', 'active', 'isBanned', 'first_name', 'last_name', 'birthdate', 'gender', 'ethnicity', 'street', 'city_id', 'state_id', 'zip', 'home_number', 'mobile_number', 'image'];
	protected $hidden = array('password', 'remember_token');

	public function setActivationCodeAttribute($value) 
	{
		$this->attributes['activation_code'] = md5(str_random(64) . time()*64);
	}
}
