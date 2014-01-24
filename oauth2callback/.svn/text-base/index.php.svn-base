<?php
// oauth2callback/index.php

require('../settings.php');

require_once('../classes/Google_OAuth2_Token.class.php');
require_once('../classes/Google_Userinfo.class.php');
require_once('../classes/Google_SubscriptionList.class.php');
require_once('../classes/User_Settings.class.php');
	
/**
 * the OAuth server should have brought us to this page with a $_GET['code']
 */
if(isset($_GET['code'])) {
    // try to get an access token
    $code = $_GET['code'];
 
	// authenticate the user
	$Google_OAuth2_Token = new Google_OAuth2_Token();
	$Google_OAuth2_Token->code = $code;
	$Google_OAuth2_Token->client_id = $settings['oauth2']['oauth2_client_id'];
	$Google_OAuth2_Token->client_secret = $settings['oauth2']['oauth2_secret'];
	$Google_OAuth2_Token->redirect_uri = $settings['oauth2']['oauth2_redirect'];
	$Google_OAuth2_Token->grant_type = "authorization_code";

	try {
		$Google_OAuth2_Token->authenticate();
	} catch (Exception $e) {
		// handle this exception
		print_r($e);
	}
	
	print_r($Google_OAuth2_Token);

	// A user just logged in.  
	if ($Google_OAuth2_Token->authenticated) {
		
		// first let's grab some user info
		$Google_Userinfo = new Google_Userinfo($Google_OAuth2_Token);
		try {
			$Google_Userinfo->fetch();
		} catch (Exception $e) {
			print_r($e);
		}
		
		// initialize the MySQL connection
		$mysqli = new mysqli(
			$settings['mysql']['server'],
			$settings['mysql']['username'],
			$settings['mysql']['password'],
			$settings['mysql']['schema']
		);
	
		// we need to associate the access token and the user ID
		// in the User_Settings
		$User_Settings = new User_Settings($mysqli);
		$User_Settings->fetch($Google_Userinfo->id);
		$User_Settings->googleplus_id = $Google_Userinfo->id;
		$User_Settings->access_token = $Google_OAuth2_Token->access_token;
		$User_Settings->token_type = $Google_OAuth2_Token->token_type;
		$User_Settings->save();
		
		// let's subscribe to timeline updates
		$Google_Subscription = new Google_Subscription($Google_OAuth2_Token);
		$Google_Subscription->collection = Google_Subscription::COLLECTION_TIMELINE;
		$Google_Subscription->userToken = $Google_Userinfo->id;
		$Google_Subscription->callbackUrl = 'https://tonygaitatzis.com/glassware/bitcointicker/subscriptioncallback.php';
		
	
		try {
			$Google_Subscription->insert();
		} catch (Exception $e) {
			// handle this exception
			print_r($e);
		}
		
		// let's subscribe to location updates
		$Google_Subscription = new Google_Subscription($Google_OAuth2_Token);
		$Google_Subscription->collection = Google_Subscription::COLLECTION_LOCATIONS;
		$Google_Subscription->userToken = $Google_Userinfo->id;
		$Google_Subscription->callbackUrl = 'https://tonygaitatzis.com/glassware/bitcointicker/subscriptioncallback.php';
		
	
		try {
			$Google_Subscription->insert();
		} catch (Exception $e) {
			// handle this exception
			print_r($e);
		}
		
		
	}
}

// test comment
?>

<!DOCTYPE html>
<html>
<head>
	<title>Example Glassware Settings</title>
	<link rel="stylesheet" href="../assets/css/screen.css" />
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
	<script>
	$( document ).ready(function() {
		$("#submit").bind("click", function(event) {
			$.post( "savesettings.php", $('#settingsform').serialize())
			.done(function(data) {
				$("#server_response").text(data).show();
			});
			event.preventDefault();
		});
	});
	</script>
</head>
<body>
	
	<h1>
		Example Glassware
	</h1>

	<div id="userinfo" class="left">
		<figure>
			
		<img src="<?= $Google_Userinfo->picture; ?>" width="64" height="64" alt="Profile photo" />
			<figcaption>Logged in as <a href="<?= $Google_Userinfo->link; ?>"><?= $Google_Userinfo->given_name; ?> <?= $Google_Userinfo->family_name ;?></a></figcaption>
		</figure>
	</div>
	
	<div id="settingspanel" class="right">
		<h2>Settings</h2>
		
		<div id="server_response" class="note" style="display:none">
			Server response here.
		</div>
		
		<form id="settingsform" action="" method="post">
			<input type="hidden" name="googleplus_id" value="<?= $Google_Userinfo->id; ?>" />
			<input type="hidden" name="access_token" value="<?= $Google_OAuth2_Token->access_token; ?>" />
		<div class="description">
			Example Glassware sends one message per day at around a time that you specify.
		</div>
		<div class="setting">
		Send messages around
		
		<select id="time" name="message_time">
			<option value="10">10 am</option>
			<option value="12">12 am</option>
			<option value="14">2 pm</option>
			<option value="17">5 pm</option>
			<option value="19">7 pm</option>
			<option value="21">9 pm</option>
		</select>
		</div>
		
		<input id="submit" type="submit" value="Save" />
		
		</form>
	</div>
	
</body>
</html>
