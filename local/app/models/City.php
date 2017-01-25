<?php

class City extends \Eloquent {
	protected $fillable = [];
	protected $table = 'cities';
	public $timestamps = false;

	public static function cityName($city_id)
	{
		return ($city_id) ? ", " . City::find($city_id)->name : "Not Available";
	}
}