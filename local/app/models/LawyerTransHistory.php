<?php

class LawyerTransHistory extends \Eloquent {
	protected $fillable = [];
	protected $table = 'lawyer_transhistory';
	public $timestamps = false;

	public function lawyer_detail()
	{
		return $this->belongsTo('LawyerProfile', 'lawyer_id', 'id');
	}
}