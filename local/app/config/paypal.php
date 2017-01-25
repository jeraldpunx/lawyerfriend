<?php

return array(
	// set your paypal credential
	'client_id' => "AexP7tvBQ-m2eocUOSclS3l0ueU_h3lzWaZ__XsTExUd0uqk32u4eKaqiIDf8FBwJn8uCpZsHCyv6Cat",
	'secret' => "EIjH05GCR62U1GoFMjok1YZhCkZAUrqyjpae-eoE_WOMD4qOfzbhWHHRQ0-7IIYEdc4OaB7YwsVvuyHU",
	/**
	* SDK configuration 
	*/
	'settings' => array(
		/**
		* Available option 'sandbox' or 'live'
		*/
		'mode' => 'sandbox',

		/**
		* Specify the max request time in seconds
		*/
		'http.ConnectionTimeOut' => 30,

		/**
		* Whether want to log to a file
		*/
		'log.LogEnabled' => true,

		/**
		* Specify the file that want to write on
		*/
		'log.FileName' => storage_path() . '/logs/paypal.log',

		/**
		* Available option 'FINE', 'INFO', 'WARN' or 'ERROR'
		*
		* Logging is most verbose in the 'FINE' level and decreases as you
		* proceed towards ERROR
		*/
		'log.LogLevel' => 'FINE'
	),
);
