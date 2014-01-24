<?php
// classes/Google_Timezone.class.php
require_once('HttpPost.class.php');

/**
 * Convert a geolocation to a timezone using
 * https://maps.googleapis.com/maps/api/timezone
 */
class Google_Timezone {
	
	const URL = 'https://maps.googleapis.com/maps/api/timezone/json';
	
	const SENSOR_TRUE = 'true';
	const SENSOR_FALSE = 'false';
	
	const STATUS_OK = "OK";
	const STATUS_INVALID_REQUEST = "INVALID_REQUEST";
	const STATUS_OVER_QUERY_LIMIT = "OVER_QUERY_LIMIT";
	const STATUS_REQUEST_DENIED = "REQUEST_DENIED";
	const STATUS_UNKNOWN_ERROR= "UNKNOWN_ERROR";
	const STATUS_ZERO_RESULTS = "ZERO_RESULTS";
	
	public $location,
		$timestamp,
		$sensor = self::SENSOR_FALSE;
	
	public $dstOffset,
		$rawOffset,
		$status,
		$timeZoneId,
		$timeZoneName,
		$error_message;
		
	public function __construct() {
		$this->timestamp = time();
	}
	
	/**
	 * fetch the timezone
	 */
	public function fetch() {
		// documented at https://developers.google.com/maps/documentation/timezone/
		$getParms = array(
			'location' => $this->location,
			'timestamp' => $this->timestamp,
			'sensor' => $this->sensor
		);

		$this->HttpPost = new HttpPost(self::URL);

		foreach ($getParms as $key=>$val) {
			$this->HttpPost->addGetParameter($key, $val);
		}

		$this->HttpPost->execute();
		
	    $response = json_decode($this->HttpPost->httpResponse);
		
		

		// is there an error here?
		if ($response->status != self::STATUS_OK) {
			throw new Exception("The server reported an error: '".$response->error_message."'");
		} else {
			$this->fromJSONObject($response);
		}
	}
	
	/**
	 * convert a JSON object to a Google_Timezone
	 */
	public function fromJSONObject($jsonobject) {
		$this->dstOffset = $jsonobject->dstOffset;
		$this->rawOffset = $jsonobject->rawOffset;
		$this->status = $jsonobject->status;
		$this->timeZoneId = $jsonobject->timeZoneId;
		$this->timeZoneName = $jsonobject->timeZoneName;
		$this->error_message = $jsonobject->error_message;
	}
	
}


?>