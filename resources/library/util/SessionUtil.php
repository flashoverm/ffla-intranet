<?php

class SessionUtil {
	
	static function getCurrentUserUUID(){
		if(SessionUtil::userLoggedIn()){
			if(isset($_SESSION ['intranet_doasuser'])){
				return $_SESSION ['intranet_doasuser'];
			}
			return $_SESSION ['intranet_userid'];
		}
		return false;
	}
	
	static function setCurrentUserUUID($uuid){
		$_SESSION ['intranet_userid'] = $uuid;
	}
	
	static function userLoggedIn(){
		return isset ( $_SESSION ['intranet_userid'] );
	}
	
	static function imitateUser($uuid){
		$_SESSION ['intranet_doasuser'] = $uuid;
	}
	
	static function stopImitating(){
		unset($_SESSION ['intranet_doasuser']);
	}
	
	
	static function localhostRequest(){
		return strpos($_SERVER["HTTP_USER_AGENT"], "HeadlessChrome");
	}
	
	static function goToLogin(){
		global $config;
		
		$_SESSION["ref"] = "{$_SERVER['REQUEST_URI']}";
		header("Location: " . $config["urls"]["intranet_home"] . "/login"); // redirects
		exit();
	}
}
?>