<?php
// classes/Google_Callback.class.php
require_once('Google_Callback_UserAction.class.php');

class Google_Callback {
	
	public $collection,
		$itemId,
		$operation,
		$userToken,
		$userActions;
	
	public function fromJSONObject($jsonObject) {
		$this->collection = $jsonObject->collection;
		$this->itemId = $jsonObject->itemId;
		$this->operation = $jsonObject->operation;
		$this->userToken = $jsonObject->userToken;
		
		if ($jsonObject->userActions) {
			foreach ($jsonObject->userActions as $ua) {
				$userAction = new Google_Callback_UserAction();
				$userAction->fromJSONObject($ua);
				$this->userActions[] = $userAction;
			}
		}
	}


}

?>