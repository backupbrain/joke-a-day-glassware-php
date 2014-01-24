<?php
// classes/Google_SubscriptionList.class.php
require_once('HttpPost.class.php');
require_once('Google_OAuth2_Token.class.php');
require_once('Google_Subscription.class.php');

/**
 * Get the Google+ user account from
 * https://www.googleapis.com/oauth2/v1/userinfo
 * 
 * Requires that user has been OAuth authenticated
 */
class Google_SubscriptionList {
	
	const URL = 'https://www.googleapis.com/mirror/v1/subscriptions';
	public $fetched = false;
		
	// we can only grab userinfo from an authenticated user
	public $Google_OAuth2_Token;
	
	// these are the variables we are sending
	public $kind = "mirror#subscriptionsList";
	
	
	// these are the variables that will come back from the server
	public $items = array();
	
		
	/**
	 * Use the authenticated Google_OAuth2_Token
	 */
	public function __construct($Google_OAuth2_Token) {
		$this->Google_OAuth2_Token = $Google_OAuth2_Token;
	}
	
	/**
	 * Get the list of subscriptions registered to 
	 * the authenticated user
	 */
	public function list_subscriptions() {
		// we will be stending the OAuth2 access_token through the HTTP headers
		$headers = array(
			'Authorization: '.$this->Google_OAuth2_Token->token_type.' '.$this->Google_OAuth2_Token->access_token
		);
		
		$this->HttpPost = new HttpPost(self::URL);
		$this->HttpPost->setHeaders( $headers );
		
		if ($this->Google_OAuth2_Token->authenticated) {
			$this->HttpPost->get();
		    $response = json_decode($this->HttpPost->httpResponse);
		
		} else {
			throw new Exception ("Google_OAuth2_Token needs to be authenticated before you can fetch locations.");
		}
	
		
		
		// is there an error here?
		if ($response->error) {
			throw new Exception("The server reported an error: '".$response->error->errors[0]->message."'");
		} else {
			if ($response->kind == 'mirror#subscriptionsList') {
				foreach ($response->items as $subscriptionItem) {
					$Subscription = new Google_Subscription($this->Google_OAuth2_Token);
					$Subscription->fromJSONObject($subscriptionItem);
				}
				$this->items[] = $Subscription;
			}
			
			$this->fetched = true;
		}
		
	}
	
	
	
	
}

?>