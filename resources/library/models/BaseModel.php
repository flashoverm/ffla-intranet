<?php

abstract class BaseModel implements JsonSerializable {
	
	public function __construct() {
		
	}
		
	public function toJson(){
		$vars = get_object_vars($this);
		return json_encode($vars, JSON_UNESCAPED_UNICODE);
	}
	
	
}