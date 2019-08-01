<?php

	require_once "../tractis_identity.php";
	
	echo "<h1>Welcome to the test php site </h1>";
	
	// Calculate your url (for the notification callback)
	$notification_callback = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	$notification_callback_method = "GET";
	
	// Get an api_key: http://www.tractis.com/identity
	// tractis_identity contructor class (api_key, notification_callback, public_verification (true/false), image_button_url, $notification_callback_method (GET/POST))
	$tractis_identity = new tractis_identity("Your API KEY HERE", $notification_callback, "false", "/your/url/to/images/trac_but_bg_lrg_b_en.png", $notification_callback_method);
	
	echo $tractis_identity->show_form();
	
	echo "<h2>Button Information</h2>";
	echo "Notification callback is $notification_callback and will be sent with the $notification_callback_method HTTP method.<br/><br/>";
	
	echo "<h2>Status Information</h2>";
	// Check if a callback from Tractis if performed and the Authentication Response
	if ($user = $tractis_identity->check_auth())
	{
		// Own code to integrate in the auth mechanism or sessions, ...
		echo "The Tractis Auth has been performed, the data received needs to be integrated on your site. <br/><br/>";
		echo "Now you have in the \$user array the follow information:<br/><br/>";
		print_r($user);
	}
	else
	{
		echo "Not Tractis Auth, please click on the button and follow the intructions";
	}

?>
