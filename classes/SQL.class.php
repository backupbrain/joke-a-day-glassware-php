<?
// classes/SQL.class.php

/**
 * This class inserts data into a MySQL server
 */
class SQL {
	
	public $mysqli;
	
	public function __construct($server, $username, $password, $schema) {
		$this->mysqli = new mysqli($server, $username, $password);
		print_r($this->mysqli);
		$this->mysqli->select_db($schema);
		print_r($this->mysqli);
	}
	
	public function query($string) {
		$this->mysqli->query($string);
		echo($string);
	}
	
}

?>