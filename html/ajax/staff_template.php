<?php 
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );

$isManager = $currentUser->hasPrivilegeByName(Privilege::EVENTMANAGER) || $currentUser->hasPrivilegeByName(Privilege::EVENTADMIN );

if(!$isManager){
	http_response_code(401);
} else {
	if (isset($_GET['uuid'])) {
		
		$staffTemplate = $staffTemplateDAO->getStaffTemplate($_GET['uuid']);
		if($staffTemplate){
		    header('Content-Type: application/json');
		    //header('Content-Type: text/plain');
			echo json_encode($staffTemplate);
			
		} else {
			http_response_code(404);
		}
	} else {
		http_response_code(400);
	}
	
}

?>