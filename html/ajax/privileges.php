<?php 
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

session_start ();

$isAdmin = $userController->hasCurrentUserPrivilege(Privilege::PORTALADMIN ) ;

if(!$isAdmin){
	http_response_code(401);
} else {
	if (isset($_GET['uuid'])) {
		
		$user = $userDAO->getUserByUUID($_GET['uuid']);
		
		$privileges = $user->getPrivileges();
		
		$string = "[";
		foreach($privileges as $privilege){		
			$string .= "\n\t" . $privilege->toJson() . ",";
		}
		if(strlen($string) > 1){
			$string = substr($string, 0, -1);
		}
		$string .= "\n]";
		echo $string;
		header('Content-Type: text/plain');
	} else {
		http_response_code(400);
	}
	
}

?>