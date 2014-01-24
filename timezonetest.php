<?
require_once('settings.php');
require_once('classes/Google_Location.class.php');
require_once('classes/Google_Timezone.class.php');


$lat = 40.69847032728747;
$lng = -73.9514422416687;

$OAuth2_Token = new Google_OAuth2_Token();
$OAuth2_Token->access_token = 'ya29.1.AADtN_WwN2bPmGaO1s4Qy6kOXTSrASKNW5HYxkcREQL-t4uBRJp5_FDt1cNixyA';
$OAuth2_Token->token_type = 'Bearer';
$OAuth2_Token->authenticated = true;

$Google_Location = new Google_Location($OAuth2_Token);
$Google_Location->fetch();

$Google_Timezone = new Google_Timezone();
$Google_Timezone->location = $lat.','.$lng;
$Google_Timezone->sensor = Google_Timezone::SENSOR_TRUE;
$Google_Timezone->fetch();

print_r($Google_Timezone);


?>