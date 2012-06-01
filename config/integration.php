<?php

return array(

	// can be "proxyconnect" or "jsconnect"

	'auth_type' => 'proxyconnect',

	// get_user is used by proxyconnect and jsconnect, feel free to
	// alter the closure just make sure that it returns an array with
	// the keys formatted below. If you change the keys then things
	// will stop working.

	// In this example I'm using the id, real_name, and email fields
	// from my users table. Change these to match your table.

	'get_user'  => function()
	{

		// return an array containing the following keys:
		// uniqueid, name, email, and photourl

		// photourl is a full url
		// ( http://example.com/images/whatever.png )

		// if a user isn't signed on then the jsconnect
		// documentation suggests that you should return
		// an array that contains the name and photourl
		// keys with blank values

		if( Auth::check() )
			return array(
				'uniqueid' => Auth::user()->id,
				'name'     => Auth::user()->real_name,
				'email'    => Auth::user()->email,
				'photourl' => '',
			);
		else
			return array(
				'name'     => '',
				'photourl' => '',
			);

	},

	/* JSConnect specific configuration. Ignore for ProxyConnect */

	'jsconnect_client_id' => '',
	'jsconnect_secret'    => '',

	// set to true to use MD5, set to NULL to use no security

	'jsconnect_secure'    => true,

	// timeout set to 1 day

	'timeout'   => 60 * 24,

	'login_url' => URL::to( 'login' ),

);