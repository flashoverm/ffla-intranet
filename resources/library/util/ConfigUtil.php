<?php

class ConfigUtil {
    
    static function nearestYearBeforeOrEqual(array $yearList, int $targetYear): ?int {
        $validYears = array_filter(array_keys($yearList), function($year) use ($targetYear) {
            return $year <= $targetYear;
        });
        if (empty($validYears)) {
            return null;
        }
        return max($validYears);
    }
    
    static function getEventHourlyRate($year){
        global $config;
        
        $selectedYear = ConfigUtil::nearestYearBeforeOrEqual($config["settings"]["eventHourlyRate"], $year);
        if($selectedYear != null){
            return $config["settings"]["eventHourlyRate"][$selectedYear];
        } else {
            return end($config["settings"]["eventHourlyRate"]);
        }
    }
    
    static function getYearlyEventLimit($year){
        global $config;
        
        $selectedYear = ConfigUtil::nearestYearBeforeOrEqual($config["settings"]["yearlyEventLimit"], $year);
        if($selectedYear != null){
            return $config["settings"]["yearlyEventLimit"][$selectedYear];
        } else {
            return end($config["settings"]["yearlyEventLimit"]);
        }
    }
}