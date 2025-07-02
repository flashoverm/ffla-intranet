<?php

class DateFormatUtil {
	
	static function timeToHm ($time){
		if(true || strlen($time) > 5){
			return substr($time, 0, strlen($time)-3);
		}
		return $time;
	}

	static function formatSecondsToHHMM($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        return sprintf('%d:%02d', $hours, $minutes);
	}
}

?>