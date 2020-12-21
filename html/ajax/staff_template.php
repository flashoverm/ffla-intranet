<?php 
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

session_start ();

$currentUser = $userController->getCurrentUser();
$isManager = $currentUser->hasPrivilegeByName(Privilege::EVENTMANAGER) || $currentUser->hasPrivilegeByName(Privilege::EVENTADMIN );

if(!$isManager){
	http_response_code(401);
} else {
	if (isset($_GET['uuid'])) {
		
		$template = $staffTemplateDAO->getStaffTemplate($_GET['uuid']);
		if($template){
			echo $template->toJson();
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