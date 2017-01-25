<?php

class LawyerProfile extends \Eloquent {
	protected $fillable = [];
	protected $table = 'lawyer_profiles';
	public $timestamps = false;

	public function user()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function lawyer_practice_area()
	{
		return $this->hasMany('LawyerPracticeArea', 'lawyer_id', 'id');
	}

	public static function practice_areas($lawyer_id)
	{
		return LawyerPracticeArea::with(['practice_area', 'state', 'city'])->where('lawyer_id', '=', $lawyer_id)->orderBy('practice_area_id', 'ASC')->get();
	}
}