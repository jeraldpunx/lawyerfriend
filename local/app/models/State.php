<?php

class State extends \Eloquent {
	protected $fillable = [];
	protected $table = 'states';
	public $timestamps = false;

	public static function stateName($state_id)
	{
		return ($state_id) ? State::find($state_id)->text : "Not Available";
	}
}