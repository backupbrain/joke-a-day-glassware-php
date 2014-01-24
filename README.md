Joke-A-Day Glassware tutorial
=============================
This code shows how to create a Glassware that sends out a joke
to a user's Glass every hour, enabling them to "like" that joke

It is intended as a complement to my book,
Programming Google Glass with PHP

Configuration
--------------
Set up an OAuth2 Client App in the Google Code Console:
https://code.google.com/apis/console/

Once you register an app, create  you will get a client id and client secret. 
You will also need to create a Browser API Key for the Google Maps API.  

Edit your settings.php to reflect your oauth2 client app's settings.

// google oauth2 settings
$settings['oauth2']['oauth2_client_id'] = 'YOURCLIENTID.apps.googleusercontent.com';
$settings['oauth2']['oauth2_secret'] = 'YOURCLIENTSECRET';
$settings['oauth2']['oauth2_redirect'] = 'https://example.com/oauth2callback';

// mysql settings
$settings['mysql']['server'] = 'localhost';
$settings['mysql']['username'] = 'mysqluser';
$settings['mysql']['password'] = 'mysqlpassword';
$settings['mysql']['schema'] = 'schema';


Now you should be good to go.


