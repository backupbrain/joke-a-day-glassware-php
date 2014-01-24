<?php
// classes/Google_Callback_UserAction.class.php

class Google_Callback_UserAction {

	public $type,
		$payload;
	
	
	public function fromJSONObject($jsonObject) {
		$this->type = $jsonObject->type;
		$this->payload = $jsonObject->payload;
	}
		
}

?>