<?php

class ConfigUtil {
    
    static function getEventHourlyRate($year){
        global $config;
        
        foreach ($config["settings"]["eventHourlyRate"] as $rateYear => $rate) {
            if ($year >= $rateYear) {
                return $rate;
            }
        }
        return  end($config["settings"]["eventHourlyRate"]);
    }
    
    static function getYearlyEventLimit($year){
        global $config;
        foreach ($config["settings"]["yearlyEventLimit"] as $rateYear => $rate) {
            if ($year >= $rateYear) {
                return $rate;
            }
        }
        return  end($config["settings"]["yearlyEventLimit"]);
    }
}