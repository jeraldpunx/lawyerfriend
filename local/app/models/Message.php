<?php

class Message extends \Eloquent {
	protected $fillable = [];
	protected $table = 'messages';
	public $timestamps = false;

	public function from()
	{
		return $this->belongsTo('User', 'user_id');
	}

	public function to()
	{
		return $this->belongsTo('User', 'user_id');
	}
}