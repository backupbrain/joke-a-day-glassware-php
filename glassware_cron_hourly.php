<?php
require_once('settings.php');
require_once('classes/Joke.class.php');
require_once('classes/Joke2Timeline.class.php');
require_once('classes/Google_OAuth2_Token.class.php');
require_once('classes/Google_Timeline_Item.class.php');
require_once('classes/User_Settings.class.php');

// this cron is intended to run every hour


// initialize the MySQL connection
$mysqli = new mysqli(
	$settings['mysql']['server'],
	$settings['mysql']['username'],
	$settings['mysql']['password'],
	$settings['mysql']['schema']
);

// each hour, look at the current time, time();
$now = time();
$oneHourAgo = $now - 1*60*60; // 1 hour

$Joke = new Joke($mysqli);

// fetch a random joke.
$query = "SELECT * FROM `".Joke::TABLE."` ORDER BY RAND() LIMIT 0,1";

if (!$result = $mysqli->query($query)) {
	throw new Exception("MySQL query error: ". $mysqli->error);
} else {
	// we expect only one row
	$array = $result->fetch_array(MYSQLI_ASSOC);
    $result->close();
	$Joke->fromArray($array);
}

// fetch the users
$Glasswares_Users = array();

$query = "SELECT * FROM `".User_Settings::TABLE."`";

if (!$result = $mysqli->query($query)) {
	throw new Exception("MySQL query error: ". $mysqli->error);
} else {
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
		$Glassware_User = new User_Settings($mysqli);
		$Glassware_User->fromArray($row);
		$Glassware_Users[] = $Glassware_User;
	}
    $result->close();
}

// loop through users.  Each user has a timezone and a preferred joke time
if (count($Glassware_Users)) {
	foreach ($Glassware_Users as $User) {
		// the user's desired time is mktime(d,m,y, message_time, 0, 0) + timezone;
		$userMessageTime = mktime($User->message_time, 0, 0, date('n'), date('j'), date('Y')) + $User->timezone;
		
		// if the user's desired time is less than the current time
		// but also greater than one hour ago, send them a message
		if ( ($userMessageTime <= $now) and 
			 ($userMessageTime > $oneHourAgo) ) {
				
				// fake an oauth2 token
				$Google_OAuth2_Token = new Google_OAuth2_Token();
				$Google_OAuth2_Token->access_token = $User->access_token;
				$Google_OAuth2_Token->token_type = $User->token_type;
				$Google_OAuth2_Token->authenticated = true;
				
				// insert a new timeline item for this user
				$Timeline = new Google_Timeline_Item($Google_OAuth2_Token);
				$Timeline->text = $Joke->joke;
				
				
				$Timeline->insert();
				
				
				$Joke2Timeline = new Joke2Timeline($mysqli);
				$Joke2Timeline->jokeID = $Joke->jokeID;
				$Joke2Timeline->timelineID = $Timeline->id;
				
				// make a note that we sent to that user in the joke2timeline class
				$Joke2Timeline->insert();
			}
	}
}



?>