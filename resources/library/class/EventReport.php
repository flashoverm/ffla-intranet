<?php

class EventReport {
    
    public $event;
	
	public $date;
	public $start_time;
	public $end_time;
	
	public $type;
	
	public $title;
	public $engine;
	
	public $noIncidents;
	public $report;
	public $creator;
	public $ilsEntry;
	
	public $uuid;
	public $type_other;
	public $emsEntry;
	public $managerApproved;
	
	public $units = array();
	
	function __construct($date, $start_time, $end_time, $type, $type_other, $title, $engine, 
	    $noIncidents, $report, $creator, $ilsEntry) {
		
		$this->date = $date;
		$this->start_time = $start_time;
		$this->end_time = $end_time;
		
		$this->type = $type;
		$this->type_other = $type_other;
		
		$this->title = $title;
		$this->engine = $engine;
		
		$this->noIncidents = $noIncidents;
		$this->report = $report;
		$this->creator = $creator;
		$this->ilsEntry = $ilsEntry;
		
		$this->emsEntry = false;
		$this->managerApproved = false;
	}
	
	function addUnit($unit){
		if(get_class ($unit) == "ReportUnit"){
			array_push($this->units, $unit);
			return true;
		}
		return false;
	}
	
	function updateReport($date, $start_time, $end_time, $type, $type_other,
			$title, $engine, $noIncidents, $report, $creator, $ilsEntry){
				
			$this->event = null;
			$this->date = $date;
			$this->start_time = $start_time;
			$this->end_time = $end_time;
			
			$this->type = $type;
			$this->type_other = $type_other;
			
			$this->title = $title;
			$this->engine = $engine;
			
			$this->noIncidents = $noIncidents;
			$this->report = $report;
			$this->creator = $creator;
			$this->ilsEntry = $ilsEntry;
			
			$this->units = array();
	}
	
	static function fromEvent($event, $staff, $creator){
		global $userDAO;
		
		 $report = new EventReport($event->date, $event->start_time, $event->end_time, 
			$event->type, $event->type_other, $event->title, $event->engine, 
			false, "", $creator, false);
		 
		 $report->event = $event->uuid;
		 		 
		 $unit = new ReportUnit("Stationäre Wache", $event->date, $event->start_time, $event->end_time);
		 foreach($staff as $position){
		 	$user = $userDAO->getUserByUUID($position->user);
		 	if($user){
		 		$unit->addStaff(new ReportUnitStaff($position->position, $user->getFullName(), $user->getEngine()->getUuid()));
		 	}
		 }
		 $report->addUnit($unit);
		 		 
		 return $report;
	}
	
	function toHTML(){
		$string = $this->toMail();
		return nl2br($string);
	}
	
	function toMail(){
		$string = "----------------------- Wachbericht -----------------------"
				. "\n\n" .$this->type;
		
		if($this->type_other != null){
		    $string = $string . " (" . $this->type_other . ")";
		}
		
		if($this->title != null){
			$string = $string . "\nTitel: \t\t" . $this->title;
		}
		$string = $string
		. "\n\nDatum: \t" . date("d.m.Y", strtotime($this->date))
		. "\nWachbeginn: \t" . $this->start_time
		. "\nEnde: \t\t" . $this->end_time . "\n\n";
		
		if($this->ilsEntry){
		    $string = $string . "Wache durch ILS angelegt!\n\n";
		}				
		if($this->noIncidents){
			$string = $string . "Keine Vorkomnisse";
		} else {
			$string = $string . "Vorkomnisse gemeldet - siehe Bericht";
		}
				
		$string = $string . "\n\nBericht: \n". $this->report . "\n\n";
		
		foreach ($this->units as $value) {
			$string = $string . $value->toMail();
		}
		
		$string = $string
				. "-----------------------------------------------------------"
				. "\n\nZuständiger Zug: \t" . $this->engine
				. "\n\nErsteller: \t\t" . $this->creator;
		
		if(isset($this->event)){
            $string = $string
		    . "\n\n Erstellt aus Wache: " . $this->event;
		}
	   		
		return $string;
	}
}
?>