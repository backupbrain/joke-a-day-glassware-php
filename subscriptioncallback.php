<?

require_once('settings.php');
require_once('classes/HttpPost.class.php');
require_once('classes/Google_Callback.class.php');
require_once('classes/User_Settings.class.php');
require_once('classes/Glassware_Location.class.php');

// initialize the MySQL connection
$mysqli = new mysqli(
	$settings['mysql']['server'],
	$settings['mysql']['username'],
	$settings['mysql']['password'],
	$settings['mysql']['schema']
);



// convert the incoming POST string to something we can work with

// PHP expects query post content, but Google Mirror posts JSON instead
// As a result, the PHP post parser that creates the $_POST variables doesn't
// digest Mirror content well, so we have to grab it from the $HTTP_RAW_POST_DATA
// variable instead
if ($HTTP_RAW_POST_DATA ) {
	$jsonobject = json_decode($HTTP_RAW_POST_DATA);
} else {
	exit;
}
$Google_Callback = new Google_Callback();
$Google_Callback->fromJSONObject($jsonobject);


// update the current location
$User_Settings = new User_Settings($mysqli);
$User_Settings->fetch($Google_Callback->userToken);


// step 1: determine if this is a timeline or location
if ($Google_Callback->collection == Google_Subscription::LOCATION) {
	/*
	// payload looks like this
	{
	 "collection": "locations",
	 "itemId": "latest",
	 "operation": "UPDATE",
	 "userToken": "unique_glass_user_identifier"
	}
	*/
	// build our authentication token
	$OAuth2_Token = new Google_OAuth2_Token();
	$OAuth2_Token->access_token = $User_Settings->access_token;
	$OAuth2_Token->token_type = $User_Settings->token_type;
	$OAuth2_Token->authenticated = true;
	
	// fetch the last user location
	$Google_Location = new Google_Location($OAuth2_Token);
	$Google_Location->fetch();
	
	// figure out what timezone the user is
	$Google_Timezone = new Google_Timezone();
	$Google_Timezone->location = $Google_Location->latitude.','.$Google_Location->longitude;
	$Google_Timezone->sensor = Google_Timezone::SENSOR_TRUE;
	$Google_Timezone->fetch();
	
	// Update the user
	$User_Settings->timezone = $Google_Timezone->dstOffset + $Google_Timezone->rawOffset;
	$User_Settings->update();
	
} else {
	/*
	// payload looks like this
	{
	 "collection": "timeline",
	 "itemId": "cc4bf15e-370d-4ebf-a2f3-1f1282623029",
	 "operation": "UPDATE",
	 "userToken": "unique_glass_user_identifier",
	 "userActions": [
	  {
	   "type": "CUSTOM",
	   "payload": "like"
	  }
	 ]
	}
	*/
	
	if ($jsonobject->userActions) {
		// the user liked the joke
		if ($jsonobject->userActions[0]->payload == "like") {	
			// grab the joke2timeline, then grab the joke
			// then increment the joke "like" count and save
			$Joke2Timeline = new Joke2Timeline($mysqli);
			$Joke2Timeline->fetchByTimelineIdAndUserToken($jsonobject->itemId,$jsoobject->userToken);
			$Joke2Timeline->liked();
		}
	}
	
}




$HttpPost = new HttpPost('http://tonygaitatzis.com/glassware/bitcointicker/subscriptioncallback.php');

// because you are a curious cat, you want to know what headers Google Mirror sent you
// You can grab the incoming request headers with the apache_request_headers variable
$headers = apache_request_headers();
foreach ($headers as $header => $value) {
	$httpheaders[] = $header.': '.$value;
}
$HttpPost->setHeaders($httpheaders);


// PHP expects query post content, but Google Mirror posts JSON instead
// As a result, the PHP post parser that creates the $_POST variables doesn't
// digest Mirror content well, so we have to grab it from the $HTTP_RAW_POST_DATA
// variable instead
if ($HTTP_RAW_POST_DATA ) {
	$HttpPost->setRawPostData($HTTP_RAW_POST_DATA);
}

// we will never see this page in person, so let's save it to
// the dataabase so we can check on it later
$HttpPost->insert($SQL);


// we need to determine the location of the user, then approximate their timezone


?>