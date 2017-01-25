<?php

class Contact extends \Eloquent {
	protected $fillable = [];
	protected $table = 'contacts';
	public $timestamps = false;

	public function contact_detail()
	{
		return $this->belongsTo('User', 'contact_user_id', 'id');
	}
}