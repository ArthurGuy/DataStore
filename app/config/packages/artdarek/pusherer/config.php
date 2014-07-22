<?php 

return array( 
	
	/*
	|--------------------------------------------------------------------------
	| Pusher Config
	|--------------------------------------------------------------------------
	|
	| Pusher is a simple hosted API for quickly, easily and securely adding
	| realtime bi-directional functionality via WebSockets to web and mobile 
	| apps, or any other Internet connected device.
	|
	*/

	/**
	 * App id
	 */
	'app_id' => $_ENV['PUSHER_APP_ID'],

	/**
	 * App key
	 */
	'key' => $_ENV['PUSHER_APP_KEY'],

	/**
	 * App Secret
	 */
	'secret' => $_ENV['PUSHER_APP_SECRET']

);