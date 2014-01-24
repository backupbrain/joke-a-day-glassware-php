<?php
// index.php


require_once('settings.php');
require_once('classes/Google_OAuth2.class.php');
require_once('classes/Google_Userinfo.class.php');
require_once('classes/Google_Location.class.php');
require_once('classes/Google_Timeline_Item.class.php');

$Google_OAuth2 = new Google_OAuth2();
$Google_OAuth2->response_type = "code";
$Google_OAuth2->client_id = $settings['oauth2']['oauth2_client_id'];
$Google_OAuth2->redirect_uri = $settings['oauth2']['oauth2_redirect'];

// want to know who the user is, where they are, and be able to post to their timeline
$Google_OAuth2->addScopes(Google_Userinfo::$scopes);
$Google_OAuth2->addScopes(Google_Location::$scopes);
$Google_OAuth2->addScopes(Google_Timeline_Item::$scopes);


$Google_OAuth2->authenticate();


?>
