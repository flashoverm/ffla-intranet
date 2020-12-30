<?php

function getCurrentUserUUID(){
	return $_SESSION ['intranet_userid'];
}

function userLoggedIn(){
	return isset ( $_SESSION ['intranet_userid'] );
}

function localhostRequest(){
    return $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1' || startsWith($_SERVER['REMOTE_ADDR'], '192.168') ;
}

function goToLogin(){
    global $config;
    
    $actual_link = "{$_SERVER['REQUEST_URI']}";
    $_SESSION["ref"] = $actual_link;
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
