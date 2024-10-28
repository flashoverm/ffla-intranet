<?php 

require_once LIBRARY_PATH . "/mail/MailBase.php";

function mail_send_datachange_request(){
	global $config, $bodies, $userDAO;
	
	$subject = "Neue Anfrage einer Stammdatenänderung";
	$body = $bodies["datachange_requested"] . $config ["urls"] ["base_url"] . $config["urls"]["masterdataapp_home"] . "/datachangerequests/process";
	
	$dataadmins = $userDAO->getUsersWithPrivilegeByName(Privilege::MASTERDATAADMIN);
	return sendMailsWithBody($dataadmins, $subject, $body);
}

function mail_send_datachange_request_update(){
	global $config, $bodies, $userDAO;
	
	$subject = "Stammdatenänderung mit Rückfrage aktualisiert";
	$body = $bodies["datachange_request_update"] . $config ["urls"] ["base_url"] . $config["urls"]["masterdataapp_home"] . "/datachangerequests/process";
	
	$dataadmins = $userDAO->getUsersWithPrivilegeByName(Privilege::MASTERDATAADMIN);
	return sendMailsWithBody($dataadmins, $subject, $body);
}

function mail_send_datachange_status(DataChangeRequest $datachangerequest){
	global $config, $bodies;
	
	$state = $datachangerequest->getState();
	if($state == DataChangeRequest::DONE){
		$subject = "Angefragte Stammdatenänderung umgesetzt";
		$body = $bodies["datachange_done"];
	} else if($state == DataChangeRequest::DECLINED){
		$subject = "Angefragte Stammdatenänderung abgelehnt";
		$body = $bodies["datachange_declined"];
	} else if($state == DataChangeRequest::REQUEST){
		$subject = "Rückfrage zu angefragter Stammdatenänderung";
		$body = $bodies["datachange_request"];
	} else {
		return false;
	}
	$body = $body . $config ["urls"] ["base_url"] . $config["urls"]["masterdataapp_home"] . "/datachangerequests/overview";
	
	return send_mail ( $datachangerequest->getUser()->getEmail(), $subject, $body );
}
