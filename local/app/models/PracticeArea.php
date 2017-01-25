<?php

class PracticeArea extends \Eloquent {
	protected $fillable = [];
	protected $table = 'practice_areas';
	public $timestamps = false;

	public function form()
	{
		return $this->belongsTo('CustomForm', 'form_id');
	}
}