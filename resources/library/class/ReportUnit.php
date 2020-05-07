<?php
class ReportUnit {
	
	public $unit;
	public $km;
	
	public $date;
	public $beginn;
	public $end;
	
	public $staffList = array();
	
	function __construct($unit, $date, $beginn, $end) {
		$this->unit = $unit;
		$this->date = $date;
		$this->beginn = $beginn;
		$this->end = $end;
	}
	
	function addStaff($staff){
		if(get_class ($staff) == "ReportUnitStaff"){
			array_push($this->staffList, $staff);
			return true;
		}
		return false;
	}
	
	function setKM($km){
		$this->km = $km;
	}
	
	function toMail(){
		$string = "------------------------- Einheit --------------------------"
				. "\n\n" . $this->unit;
		
		if($this->km != null && $this->km != ""){
			$string = $string
			. " (km: ". $this->km . ")";
		}
		
		$parts = explode('-', $this->date);
		
		$string = $string
		. "\nDatum: \t" . $parts[2] . "." . $parts[1] . "." . $parts[0]
				. "\nWachbeginn: \t" . $this->beginn 
				. "\nEnde: \t\t" . $this->end
				. "\n\n";
						
		foreach ($this->staffList as $value) {
			$string = $string . $value->toMail();
		}
		
		return $string . "\n";
	}
	
}