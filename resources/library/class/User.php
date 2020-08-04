<?php
class User {
	
	public $uuid;
	public $firstname;
	public $lastname;
	public $email;
	public $engine;
	public $password;
	public $locked;
	
	function __construct($firstname, $lastname, $email, $engine, $password) {
		$this->firstname = $firstname;
		$this->lastname = $lastname;
		$this->email = $email;
		$this->engine = $engine;
		$this->password = $password;
	}
	
}

?>