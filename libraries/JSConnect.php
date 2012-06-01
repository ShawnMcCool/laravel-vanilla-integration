<?php

/**
 * This file contains the client code for Vanilla jsConnect single sign on.
 * @author Shawn McCool <shawn@heybigname.com>
 * @version 1.0
 * @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
 *
 * This code is a rewritten work based on work by Todd Burry <todd@vanillaforums.com>
 */

class JSConnect
{

	public static $user_data = null;

	public static function initialize()
	{

		static::$user_data = call_user_func( Config::get( 'vanilla-integration::integration.get_user' ) );

		static::$user_data = array_change_key_case( static::$user_data );

   		ksort(static::$user_data);

	}

	public static function connect()
	{

		static::initialize();

		if( Config::get( 'vanilla-integration::integration.secure' ) && !static::secure() )
			return false;

		if( !Config::get( 'vanilla-integration::integration.secure' ) )
			$return_value = json_encode( static::$user_data );
		else
			$return_value = json_encode( static::sign() );


		if( Input::has( 'callback' ) )
			return Input::get( 'callback' ) . '(' . $return_value . ')';
		else
			return $return_value;

	}

	public static function sign()
	{

		$user_string = http_build_query( static::$user_data, null, '&' );

		$signature = static::hash( $user_string . Config::get( 'vanilla-integration::integration.secret' ) );

		return static::$user_data + array(
			'client_id' => Config::get( 'vanilla-integration::integration.client_id' ),
			'signature' => $signature,
		);

	}

	public static function secure()
	{

		// check client_id

		if( Config::get( 'vanilla-integration::integration.client_id' ) != Input::get( 'client_id' ) )
			return false;

		// check if no signature was sent

		if( !Input::has( 'timestamp' ) && !Input::has( 'signature' ) )
		{
			return array(
				'name'     => static::$user_data['name'],
				'photourl' => static::$user_data['photourl'],
			);

		}
		else
	    	if( abs( Input::get( 'timestamp' ) ) - static::generate_timestamp() > Config::get( 'vanilla-integration::integration.timeout' ) )
		    	return false;

	    // check signature content

	    if( Input::get( 'signature' ) != static::hash( Input::get( 'timestamp' ) . Config::get( 'vanilla-integration::integration.secret' ) ) )
	    	return false;

	    return true;

	}

	public static function generate_timestamp()
	{

		return time();

	}

	public static function hash( $string )
	{

		return md5( $string );

	}

}