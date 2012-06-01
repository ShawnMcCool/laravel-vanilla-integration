<?php

Route::get( 'forum_authenticate', function()
{

	if( Config::get( 'vanilla-integration::integration.auth_type' ) == 'proxyconnect' )
	{

		$user_data = call_user_func( Config::get( 'vanilla-integration::integration.get_user' ) );

		$return = '';

		if( isset( $user_data['uniqueid'] ) ) $return = 'UniqueID=' . $user_data['uniqueid'] . "\n\n";
		$return.= 'Name=' . $user_data['name'] . "\n\n";
		if( isset( $user_data['email'] ) ) $return.= 'Email=' . $user_data['email'] . "\n\n";
		$return.= 'PhotoUrl=' . $user_data['photourl'];

		return $return;

	}
	elseif( Config::get( 'vanilla-integration::integration.auth_type' ) == 'jsconnect' )
	{

		Autoloader::map(array(
			'JSConnect' => __DIR__.'/libraries/jsconnect.php'
		));

		if( Auth::guest() )
			return Redirect::to( Config::get( 'vanilla-integration::integration.login_url' ) );

		$connect_response = JSConnect::connect();

		return $connect_response;

	}

});