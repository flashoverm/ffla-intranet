<?php 
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

$isManager = $userController->hasCurrentUserPrivilege(Privilege::EVENTMANAGER);

if(!$isManager){
	http_response_code(401);
} else {
	if (isset($_GET['uuid'])) {
	
		$user = $userDAO->getUserByUUID($_GET['uuid']);
		if($user){
			echo json_encode($user);
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