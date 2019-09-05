<?php

class HydrantInspection {
    
    public $uuid;
    public $engine;
    
    public $date;
    public $name;
    public $vehicle;
    
    public $notes;
    
    public $hydrants = array();
        
    function __construct($date, $name, $vehicle, $notes){
        $this->date = $date;
        $this->name = $name;
        $this->vehicle = $vehicle;
        $this->notes = $notes;
    }
    
    function addHydrant($hydrant){
        if(get_class ($hydrant) == "Hydrant"){
            array_push($this->hydrants, $hydrant);
            return true;
        }
        return false;
    }
    
    function getMaxIdx(){
        return end($this->hydrants)->idx;
    }
    
    function getCount(){
        return sizeof($this->hydrants);
    }
    
    function toString(){
        $string = $this->date . " - " . $this->name . " - " . $this->vehicle . "<br>" . $this->notes . "<br>";
        
        foreach($this->hydrants as $hydrant) {
            $string = $string . $hydrant->toString() . "<br><br>";
        }
        
        return $string;
    }
}

class Hydrant {
    
    public $uuid;
    public $hy;
    public $idx;
    
    public $type;
    
    public $criteria = array();
    
    function __construct($hy, $idx, $type){
        $this->hy = $hy;
        $this->idx = $idx;
        $this->type = $type;
    }
    
    function addCriterion($criterion){
        if(get_class ($criterion) == "Criterion"){
            $criterion->hy_idx = $this->idx;
            array_push($this->criteria, $criterion);
            return true;
        }
        return false;
    }
    
    function toString(){
        $string = $this->uuid . " - " . $this->hy . " - " . $this->idx . "<br>" . $this->type . "<br>";
        
        foreach($this->criteria as $criterion) {
            $string = $string . $criterion->toString();
        }
        
        return $string;
    }
    
    function setCriteria($json){
        $temp = json_decode($json, true);
        
        foreach($temp as $crit){
            $criterion = new Criterion($crit['idx'], $crit['value']);
            $criterion->hy_idx = $crit['hy_idx'];
            $this->addCriterion($criterion);
        }
    }
    
}

class Criterion {
    
    public $hy_idx;
    public $idx;
    public $value;
    
    function __construct($idx, $value){
        $this->idx = $idx;
        $this->value = $value;
    }

    function toString(){
        return $this->hy_idx . " - " . $this->idx . " - " . $this->value . "<br>";
    }
}