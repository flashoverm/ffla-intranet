<?php
class ReportUnitStaff {
	
	public $position;
	public $name;
	public $engine;
	
	function __construct($position, $name, $engine) {
		$this->position = $position;
		$this->name = $name;
		$this->engine = $engine;
	}
	
	function toMail(){
		return "Position: \t" . $this->position
		. "\n Name: \t" . $this->name
		. "\n Zug: \t" . $this->engine
		. "\n\n";
	}	

}
?>