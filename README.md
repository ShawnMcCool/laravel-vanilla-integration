## A Vanilla Forums Integration Bundle for Laravel Applications

**Version: 1.0**

[Vanilla Forums](http://vanillaforums.org/) is popular open-source forum software that is basically the best-in-class when it comes to integrating into your site.

### Feature Overview

- Supports both ProxyConnect and JSConnect integration methods
- Instantly integrate any site which uses Laravel Auth
- Customizable to work within any system

### Installation

Install with artisan

	php artisan bundle:install vanilla-integration

or, clone the project into **bundles/vanilla-integration**.

Then, update your bundles.php to auto-start the bundle.

	return array(
		'vanilla-integration' => array( 'auto' => true ),
	);

### Configure The Bundle

Choose your integration type: ProxyConnect or JSConnect in **config/integration.php**.

Verify that the get_user closure works for your site and make any necessary changes. Default is:

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

If using JSConnect configure based on your Vanilla Forums settings.

	/* JSConnect specific configuration. Ignore for ProxyConnect */

	'jsconnect_client_id' => 'client id goes here',
	'jsconnect_secret'    => 'secret key goes here',

	// set to true to use MD5, set to NULL to use no additional security

	'jsconnect_secure'    => true,

	// timeout set to 1 day

	'timeout'   => 60 * 24,

	'login_url' => URL::to( 'login' ),

### Configure Vanilla Forums

1. Install the integration plugin of your choice (ProxyConnect or JSConnect)

2. Configure the authentication plugin. Your authentication URL is yoursite.com/forum_authenticate (this can be changed in the bundle's start.php).

3. Give it a go!

### Notes

- I'm sure that some of this could be refactored a bit. I threw this together quick-like and will update it when I can.
- Community participation would be appreciated.
- To force Vanilla to clear its authentication cache use the following code to drop its cookies:

    setcookie("Vanilla", "deleted", time() - 1,'/', "", false);
    setcookie("Vanilla-Volatile", "deleted", time() - 1, '/', "", false);