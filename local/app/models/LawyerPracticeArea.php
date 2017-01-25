<?php

class LawyerPracticeArea extends \Eloquent {
	protected $fillable = [];
	protected $table = 'lawyer_practice_areas';
	public $timestamps = false;

	public function practice_area()
	{
		return $this->belongsTo('PracticeArea', 'practice_area_id');
	}

	public function lawyer()
	{
		return $this->belongsTo('LawyerProfile', 'lawyer_id');
	}

	public function state()
	{
		return $this->belongsTo('State', 'state_id');
	}

	public function city()
	{
		return $this->belongsTo('City', 'city_id');
	}

	public function cities()
	{
		return $this->hasMany('City', 'state_id', 'state_id');
	}
}