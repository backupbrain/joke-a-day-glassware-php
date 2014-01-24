<?php
// classes/Joke.class.php

class Joke {
	
	const TABLE = 'Joke';
	
	// Joke saves to the database
	public $mysqli;
	
	public $jokeID,
		$joke;
		
	public $exists = false;
	
	public function __construct($mysqli) {
		$this->mysqli = $mysqli;
	}
	
	public function fromArray($array) {
		$this->jokeID = $array['jokeID'];
		$this->joke = $array['joke'];
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
			throw new Exception("MySQL query error in Joke::fetch: ". $this->mysqli->error);
	    } else {
			// we expect only one row
			$array = $result->fetch_array(MYSQLI_ASSOC);
		    $result->close();
			$this->fromArray($array);
		}
	}
	
	public function update() {
		if (!$this->jokeID) return;
		
		$query = "UPDATE `".self::TABLE."` SET ";
		$query .= "`joke`='".$this->mysqli->real_escape_string($this->joke)."', ";
		$query .= "WHERE `jokeID`='".$this->mysqli->real_escape_string($this->jokeID)."'";
		
	    if (!$result = $this->mysqli->query($query)) {
			throw new Exception("MySQL query error in Joke:update: ". $this->mysqli->error);
	    } else {
			$this->exists = true;
		}
	}
	
	public function insert() {
		$query = "INSERT INTO `".self::TABLE."` ";
		$query .= "(`jokeID`,`joke`,`likes`) ";
		$query .= " VALUES ";
		$query .= "("; 
		$query .= "'".$this->mysqli->real_escape_string($this->jokeID)."',";
		$query .= "'".$this->mysqli->real_escape_string($this->joke)."'";
		$query .= ")";
		
	    if (!$result = $this->mysqli->query($query)) {
			throw new Exception("MySQL query error in Joke:save: ". $this->mysqli->error);
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