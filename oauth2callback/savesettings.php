<?php
require_once('../settings.php');
require_once('../classes/Google_Timeline_Item.class.php');
require_once('../classes/User_Settings.class.php');

// process incoming POST
if (!$_POST) {
	exit;
}


$error = false;

// initialize the MySQL connection
$mysqli = new mysqli(
	$settings['mysql']['server'],
	$settings['mysql']['username'],
	$settings['mysql']['password'],
	$settings['mysql']['schema']
);

// we need to associate the access token and the user ID
// in the User_Settings
try {
	$User_Settings = new User_Settings($mysqli);
} catch (Exception $e) {
	$error = true;
}

// if the user already exists, we should fetch them
$User_Settings->fetch($_POST['googleplus_id']);

// let's store a unique identifier for this user
$User_Settings->googleplus_id = $_POST['googleplus_id'];

// the access token is how we will send data to this user later
$User_Settings->access_token = $_POST['access_token'];

// this is the user's preference change
$User_Settings->message_time = intval($_POST['message_time']);

// commit the changes to the database
try {
	$User_Settings->save();
} catch (Exception $e) {
	$error = true;
}

if ($error) {
	echo("There was a problem updating your settings");
} else {
	echo("Your settings have been updated.");
	
	$OAuth2_Token = new Google_OAuth2_Token();
	$OAuth2_Token->access_token = $User_Settings->access_token;
	$OAuth2_Token->token_type = $User_Settings->token_type;
	$OAuth2_Token->authenticated = true;
	$Google_Timeline_Item = new Google_Timeline_Item($OAuth2_Token);
	$Google_Timeline_Item->text = "Your settings have been updated";
	$Google_Timeline_Item->insert();
}

?>