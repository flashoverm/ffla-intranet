<?php 
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

session_start ();

$isManager = current_user_has_privilege( EVENTMANAGER );

if(!$isManager){
	http_response_code(401);
} else {
	if (isset($_GET['uuid'])) {
	
		$user = get_user($_GET['uuid']);
		if($user){
			$string = "\n{\n\"uuid\":\"" . $user->uuid . "\",\n";
			$string .= "\"firstname\":\"" . $user->firstname . "\",\n";
			$string .= "\"lastname\":\"" . $user->lastname . "\",\n";
			$string .= "\"email\":\"" . $user->email . "\",\n";
			$string .= "\"engine\":\"" . $user->engine . "\"\n";
			$string .= "}";
			echo $string;
			header('Content-Type: text/plain');
			//header('Content-Type: application/json');
			
		} else {
			http_response_code(404);
		}
	} else {
		http_response_code(400);
	}
}
?>