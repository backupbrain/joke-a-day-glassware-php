<?php
// classes/Google_Userinfo.class.php
require_once('Google_OAuth2_Token.class.php');
require_once('HttpPost.class.php');

/**
 * Get the Google+ user account from
 * https://www.googleapis.com/oauth2/v1/userinfo
 * 
 * Requires that user has been OAuth authenticated
 */
class Google_Userinfo {
	
	const URL = 'https://www.googleapis.com/oauth2/v1/userinfo';
	public $fetched = false;
	
	// this is the scope required to access the userinfo
	public static $scopes = array(
		'https://www.googleapis.com/auth/userinfo.profile'
	);
	
	// we can only grab userinfo from an authenticated user
	public $Google_OAuth2_Token;
	
	// these are the variables that will come back from the user
	public $id,
		$name,
		$given_name,
		$family_name,
		$link,
		$picture,
		$gender;
		
	/**
	 * Use the authenticated Google_OAuth2_Token
	 */
	public function __construct($Google_OAuth2_Token) {
		$this->Google_OAuth2_Token = $Google_OAuth2_Token;
	}
	
	/**
	 * Fetch the Google+ profile information
	 */
	public function fetch() {
		// we will be stending the OAuth2 access_token through the HTTP headers
		$headers = array(
			'Authorization: '.$this->Google_OAuth2_Token->token_type.' '.$this->Google_OAuth2_Token->access_token
		);
		
		$this->HttpPost = new HttpPost(self::URL);
		$this->HttpPost->setHeaders( $headers );
		
		if ($this->Google_OAuth2_Token->authenticated) {
			$this->HttpPost->post();
		    $response = json_decode($this->HttpPost->httpResponse);
		
		} else {
			throw new Exception ("Google_OAuth2_Token needs to be authenticated before you can fetch userinfo.");
		}

		
		// is there an error here?
		if ($response->error) {
			throw new Exception("The server reported an error: '".$response->error->errors[0]->message."'");
		} else {
			$this->fromJSONObject($response);
			
			$this->authenticated = true;
		}
	}
	
	
	public function fromJSONObject($jsonObject) {
		$this->id = $jsonObject->id;
		$this->given_name = $jsonObject->given_name;
		$this->family_name = $jsonObject->family_name;
		$this->link = $jsonObject->link;
		$this->picture = $jsonObject->picture;
		$this->gender = $jsonObject->gender;
	}

	
}

?>