<?php

function getCurrentUserUUID(){
	if(isset($_SESSION ['intranet_doasuser'])){
		return $_SESSION ['intranet_doasuser'];
	}
	return $_SESSION ['intranet_userid'];
}

function setCurrentUserUUID($uuid){
	$_SESSION ['intranet_userid'] = $uuid;
}

function userLoggedIn(){
	return isset ( $_SESSION ['intranet_userid'] );
}

function imitateUser($uuid){
	$_SESSION ['intranet_doasuser'] = $uuid;
}

function stopImitating(){
	unset($_SESSION ['intranet_doasuser']);
}


function localhostRequest(){
    return 
    //$_SERVER['REMOTE_ADDR'] == '127.0.0.1' 
    	//|| $_SERVER['REMOTE_ADDR'] == '::1' 
    	//|| startsWith($_SERVER['REMOTE_ADDR'], '192.168') 
    	strpos($_SERVER["HTTP_USER_AGENT"], "HeadlessChrome")
    ;
}

function goToLogin(){
    global $config;
    
    $_SESSION["ref"] = "{$_SERVER['REQUEST_URI']}";
    header("Location: " . $config["urls"]["intranet_home"] . "/login"); // redirects
    exit();
}

function startsWith( $haystack, $needle ) {
	$length = strlen( $needle );
	return substr( $haystack, 0, $length ) === $needle;
}

function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    
    return (substr($haystack, -$length) === $needle);
}

function timeToHm ($time){
	if(true || strlen($time) > 5){
		return substr($time, 0, strlen($time)-3);
	}
	return $time;
}

function convertToWindowsCharset($string) {
	$charset =  mb_detect_encoding(
			$string,
			"UTF-8, ISO-8859-1, ISO-8859-15",
			true
			);
	
	$string =  mb_convert_encoding($string, "Windows-1252", $charset);
	return $string;
}
