<?php

class BaseModel {
	
	public function toJson(){
		$vars = get_object_vars($this);
		return json_encode($vars, JSON_UNESCAPED_UNICODE);
	}
	
}