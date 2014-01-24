<?php
// classes/Joke2Timeline.class.php

class Joke2Timeline {
	
	const TABLE = 'Joke2Timeline';
	
	// Joke2Timeline saves to the database
	public $mysqli;
	
	public $jokeID,
		$timelineID,
		$liked;
		
	public $exists = false;
	
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}
	
	public function fromArray($array) {
		$this->jokeID = $array['jokeID'];
		$this->timelineID = $array['timelineID'];
		$this->liked = $array['liked'];
		if ($this->jokeID) {
			$this->exists = true;
		}
	}
	
	public function fetch($jokeID) {
		if (!$jokeID) return;
		
		$query = "SELECT * FROM `".self::TABLE."` where `jokeID` =  '".$jokeID."' LIMIT 0,1";
		$this->mysqli->query($query);
		// retrieve the data
		
	    if (!$result = $this->mysqli->query($query)) {
			throw new Exception("MySQL query error in Joke2Timeline::fetch: ". $this->mysqli->error);
	    } else {
			// we expect only one row
			$array = $result->fetch_array(MYSQLI_ASSOC);
		    $result->close();
			$this->fromArray($array);
		}
	}
	
	
	public function liked() {
		if (!$this->jokeID) return;
		
		$query = "UPDATE `".self::TABLE."` SET ";
		$query .= "`liked`='1'";
		$query .= "WHERE `jokeID`='".$this->mysqli->real_escape_string($this->jokeID)."' and `timelineID`='".$this->mysqli->real_escape_string($this->timelineID)."'";
		
	    if (!$result = $this->mysqli->query($query)) {
			throw new Exception("MySQL query error in Joke2Timeline:liked: ". $this->mysqli->error);
	    } else {
			$this->exists = true;
		}
	}
	
	public function insert() {
		$query = "INSERT INTO `".self::TABLE."` ";
		$query .= "(`jokeID`,`timelineID`,`liked`) ";
		$query .= " VALUES ";
		$query .= "("; 
		$query .= "'".$this->mysqli->real_escape_string($this->jokeID)."',";
		$query .= "'".$this->mysqli->real_escape_string($this->timelineID)."',";
		$query .= "'".$this->mysqli->real_escape_string($this->liked)."'";
		$query .= ")";
		
	    if (!$result = $this->mysqli->query($query)) {
			throw new Exception("MySQL query error in Joke2Timeline:save: ". $this->mysqli->error);
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