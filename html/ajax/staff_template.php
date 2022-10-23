<?php 
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

$isManager = $currentUser->hasPrivilegeByName(Privilege::EVENTMANAGER) || $currentUser->hasPrivilegeByName(Privilege::EVENTADMIN );

if(!$isManager){
	http_response_code(401);
} else {
	if (isset($_GET['uuid'])) {
		
		$staffTemplate = $staffTemplateDAO->getStaffTemplate($_GET['uuid']);
		if($staffTemplate){
			echo json_encode($staffTemplate);
			//header('Content-Type: text/plain');
			//header('Content-Type: application/json');
			
		} else {
			http_response_code(404);
		}
	} else {
		http_response_code(400);
	}
	
}

?>