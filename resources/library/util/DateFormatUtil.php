<?php

class DateFormatUtil {
	
	static function timeToHm ($time){
		if(true || strlen($time) > 5){
			return substr($time, 0, strlen($time)-3);
		}
		return $time;
	}
	
}

?>