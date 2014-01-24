<?php
// classes/Google_Subscription.class.php
require_once('Google_OAuth2_Token.class.php');
require_once('HttpPost.class.php');

/**
 * Get the Google+ user account from
 * https://www.googleapis.com/oauth2/v1/userinfo
 * 
 * Requires that user has been OAuth authenticated
 */
class Google_Subscription {
	
	const URL = 'https://www.googleapis.com/mirror/v1/subscriptions';
	public $fetched = false;
	
	const OPERATION_UPDATE = "UPDATE";
	const OPERATION_INSERT = "INSERT";
	const OPERATION_DELETE = "DELETE";
	
	const COLLECTION_TIMELINE = "timeline";
	const COLLECTION_LOCATIONS = "locations";
	
	// this is the scope required to access the userinfo
	public static $scopes = array(
		'glass.timeline' => 'https://www.googleapis.com/auth/glass.timeline',
		'glass.location' => 'https://www.googleapis.com/auth/glass.location'
	);
	
	
	// we can only grab userinfo from an authenticated user
	public $Google_OAuth2_Token;
		
	
	// these are the variables that will come back from the server
	public $id,
		$timestamp,
		$latitude,
		$longitude,
		$accuracy,
		$kind = "mirror#subscription",
		$collection,
		$operation = array(), // OPERATION_UPDATE, OPERATION_INSERT, OPERATION_DELETE
		$callbackUrl,
		$userToken,
		$updated;
		
		
	/**
	 * Use the authenticated Google_OAuth2_Token
	 */
	public function __construct($Google_OAuth2_Token) {
		$this->Google_OAuth2_Token = $Google_OAuth2_Token;
	}
		
	/**
	 * Subscribe to a user's timeline changes
	 */
	public function insert() {
		$postData = array(
			'collection' => $this->collection,
			'userToken' => $this->userToken,
			'operation' => $this->operation,
			'callbackUrl' => $this->callbackUrl
		);
		$json = json_encode($postData);


		// we will be stending the OAuth2 access_token through the HTTP headers
		$headers = array(
			'Authorization: '.$this->Google_OAuth2_Token->token_type.' '.$this->Google_OAuth2_Token->access_token,
			'Content-Type: application/json',
			'Content-length: '. strlen($json)
		);

		$this->HttpPost = new HttpPost(self::URL);
		$this->HttpPost->setHeaders( $headers );
		$this->HttpPost->setRawPostData( $json );

		if ($this->Google_OAuth2_Token->authenticated) {
			$this->HttpPost->post();
		    $response = json_decode($this->HttpPost->httpResponse);

		} else {
			throw new Exception ("Google_OAuth2_Token needs to be authenticated before you can subscribe.");
		}



		// is there an error here?
		if ($response->error) {
			print_r($response);
			throw new Exception("The server reported an error: '".$response->error->errors[0]->message."'");
		} else {
			$this->fetched = true;
		}
	}

	
	public function delete() {
		
		
		// we will be stending the OAuth2 access_token through the HTTP headers
		$headers = array(
			'Authorization: '.$this->Google_OAuth2_Token->token_type.' '.$this->Google_OAuth2_Token->access_token
		);
		
		$this->HttpPost = new HttpPost(self::URL.'/'.$this->id);
		$this->HttpPost->setHeaders( $headers );
		
		if ($this->Google_OAuth2_Token->authenticated) {
			$this->HttpPost->delete();
		    $response = json_decode($this->HttpPost->httpResponse);
		
		} else {
			throw new Exception ("Google_OAuth2_Token needs to be authenticated before you can subscribe.");
		}

		
		
		// is there an error here?
		if ($response->error) {
			print_r($response);
			throw new Exception("The server reported an error: '".$response->error->errors[0]->message."'");
		} else {
			$this->fetched = true;
		}
	}

	public function fromJSONObject($jsonObject) {
		$this->kind = $jsonObject->kind;
		$this->id = $jsonObject->id;
		$this->updated = $jsonObject->updated;
		$this->collection = $jsonObject->collection;
		$this->operation = $jsonObject->operation;
		$this->callbackUrl = $jsonObject->callbackUrl;
		$this->userToken = $jsonObject->userToken;
		
		
		$this->timestamp = $jsonObject->timestamp;
		$this->latitude = $jsonObject->latitude;
		$this->longitude = $jsonObject->longitude;
		$this->accuracy = $jsonObject->accuracy;
	}
	
	
	public function fromJSON($json) {
		$this->fromJSONObject(json_decode($json));
	}
	
}

?>