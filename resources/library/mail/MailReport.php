<?php 

require_once LIBRARY_PATH . "/mail/MailBase.php";


function mail_insert_event_report(Report $report){
	global $config, $userDAO, $guardianUserController;
	
	$subject = "Wachbericht";
	$parameter = array(
	    'report_link' => get_report_link($report->getUuid()),
	    'report' => $report,
	);
	$file = $config["paths"]["reports"] . $report->getUuid() . ".pdf";
	
	//send report to administration
	$administration = $userDAO->getUsersWithPrivilegeByName(Privilege::FFADMINISTRATION);
	//send report to manager of the assigned engine
	$managerList = $guardianUserController->getEventmanagerOfEngine($report->getEngine()->getUuid());
	
	$recipients = array_merge($managerList, $administration);
	
	return sendMailsWithTemplate ( 
	    $recipients, 
	    $subject, 
	    TEMPLATES_PATH . "/guardianapp/mails/insertReport_mail.php", 
	    $parameter, 
	    $file
	);
	
}

function mail_update_report(Report $report){
	global $config, $userDAO, $guardianUserController;
	
	$subject = "Wachbericht aktualisiert";
	$parameter = array(
	    'report_link' => get_report_link($report->getUuid()),
	    'report' => $report,
	);
	$file = $config["paths"]["reports"] . $report->getUuid() . ".pdf";

	//send report to administration
	$administration = $userDAO->getUsersWithPrivilegeByName(Privilege::FFADMINISTRATION);
	//send report to manager of the assigned engine
	$managerList = $guardianUserController->getEventmanagerOfEngine($report->getEngine()->getUuid());
	
	$recipients = array_merge($managerList, $administration);
	return sendMailsWithTemplate (
	    $recipients,
	    $subject,
	    TEMPLATES_PATH . "/guardianapp/mails/updateReport_mail.php",
	    $parameter,
	    $file
	    );
}

function mail_report_approved($report_uuid){
	global $config, $userDAO, $reportDAO;
	
	$report = $reportDAO->getReport($report_uuid);
	
	$subject = "Wachbericht freigegeben";
	$parameter = array(
	    'report_link' => get_report_link($report_uuid),
	    'report' => $report,
	);	
	$file = $config["paths"]["reports"] . $report_uuid . ".pdf";
	
	$administration = $userDAO->getUsersWithPrivilegeByName(Privilege::FFADMINISTRATION);
	
	return sendMailsWithTemplate (
	    $administration,
	    $subject,
	    TEMPLATES_PATH . "/guardianapp/mails/updateReport_mail.php",
	    $parameter,
	    $file
	    );
}

function mail_not_approved($report_uuid) {
    global $bodies, $reportDAO, $guardianUserController;
    
    $subject = "Erinnerung: Wachbericht nicht freigegeben";
    
    $body = $bodies["report_not_approved"] . get_report_link($report_uuid);
    
    $report = $reportDAO->getReport( $report_uuid );
    
    $recipients = $guardianUserController->getEventmanagerOfEngine($report->getEngine()->getUuid());
    
    return sendMailsWithBody($recipients, $subject, $body);
}

function get_report_link($report_uuid){
    global $config;
    return $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/view/" . $report_uuid;
}

