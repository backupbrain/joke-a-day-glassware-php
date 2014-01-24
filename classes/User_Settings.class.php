<?php
// classes/User_Settings.class.php

class User_Settings {
	
	const TABLE = 'User_Settings';
	
	// User_Settings saves to the database
	public $mysqli;
	
	public $googleplus_id,
		$access_token,
		$token_type,
		$timezone,
		$message_time;
		
	public $exists = false;
	
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}
	
	public function fromArray($array) {
		$this->googleplus_id = $array['googleplus_id'];
		$this->access_token = $array['access_token'];
		$this->token_type = $array['token_type'];
		$this->timezone = $array['timezone'];
		$this->message_time = $array['message_time'];
		if ($this->googleplus_id) {
			$this->exists = true;
		}
	}
	
	public function fetch($googleplus_id) {
		if (!$googleplus_id) return;
		
		$query = "SELECT * FROM `".self::TABLE."` where `googleplus_id` =  '".$googleplus_id."' LIMIT 0,1";
		$this->mysqli->query($query);
		// retrieve the data
		
	    if (!$result = $this->mysqli->query($query)) {
			throw new Exception("MySQL query error in User_Settings::fetch: ". $this->mysqli->error);
	    } else {
			// we expect only one row
			$array = $result->fetch_array(MYSQLI_ASSOC);
		    $result->close();
			$this->fromArray($array);
		}
	}
	
	public function update() {
		if (!$this->googleplus_id) return;
		
		$query = "UPDATE `".self::TABLE."` SET ";
		$query .= "`access_token`='".$this->mysqli->real_escape_string($this->access_token)."', ";
		$query .= "`token_type`='".$this->mysqli->real_escape_string($this->token_type)."', ";
		$query .= "`timezone`='".$this->mysqli->real_escape_string($this->timezone)."', ";
		$query .= "`message_time`='".$this->mysqli->real_escape_string($this->message_time)."' ";
		$query .= "WHERE `googleplus_id`='".$this->mysqli->real_escape_string($this->googleplus_id)."'";
		
	    if (!$result = $this->mysqli->query($query)) {
			throw new Exception("MySQL query error in User_Settings:update: ". $this->mysqli->error);
	    } else {
			$this->exists = true;
		}
	}
	
	public function insert() {
		$query = "INSERT INTO `".self::TABLE."` ";
		$query .= "(`googleplus_id`,`access_token`,`token_type`,`timezone`,`message_time`) ";
		$query .= " VALUES ";
		$query .= "("; 
		$query .= "'".$this->mysqli->real_escape_string($this->googleplus_id)."',";
		$query .= "'".$this->mysqli->real_escape_string($this->access_token)."',";
		$query .= "'".$this->mysqli->real_escape_string($this->token_type)."',";
		$query .= "'".$this->mysqli->real_escape_string($this->timezone)."',";
		$query .= "'".$this->mysqli->real_escape_string($this->message_time)."'";
		$query .= ")";
		
	    if (!$result = $this->mysqli->query($query)) {
			throw new Exception("MySQL query error in User_Settings:save: ". $this->mysqli->error);
	    }
		
	}
	
	public function save() {
		if ($this->exists) {
			$this->update();
		} else {
			$this->insert();
		}
	}
}

?>