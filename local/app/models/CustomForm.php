<?php

class CustomForm extends \Eloquent {
	protected $fillable = [];
	protected $table = 'forms';
	public $timestamps = false;

	public function questions()
	{
		return $this->hasMany('FormQuestion', 'form_id', 'id');
	}
}