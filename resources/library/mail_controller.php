<?php 
require_once LIBRARY_PATH . "/mail_body.php";
require_once LIBRARY_PATH . "/mail.php";

function sendMailWithTemplate($recipient, $subject, $template, array $parameter, $attachments = NULL, $footer = true){
    
    //Do not send if user is locked
    if(
        ( ! $recipient->getLocked() && $recipient->getPassword() != null )
        || 
        $recipient->getPassword() == null 
    ) {
               
        $body = renderMail($recipient, $template, $parameter);
        return send_mail($recipient->getEmail(), $subject, $body, $attachments, $footer);
    }
    return true;
}

function sendMailsWithTemplate($recipients, $subject, $template, array $parameter, $attachment = NULL) {
    $success = true;
    foreach ($recipients as $to) {
        if(!sendMailWithTemplate($to, $subject, $template, $parameter, $attachment)){
            $success = false;
        }
    }
    return $success;
}

function sendMailsWithBody($recipients, $subject, $body, $attachment = NULL) {
    $success = true;
    foreach ($recipients as $to) {
        if(
            ( ! $to->getLocked() && $to->getPassword() != null )
            ||
            $to->getPassword() == null
        ) {
            if(!send_mail($to->getEmail(), $subject, $body, $attachment)){
                $success = false;
            }
        }
    }
    return $success;
}




/*
 * hydrant inspection
 */

function mail_send_inspection_report($report_uuid){
    global $config;
    global $bodies;
    
    $file = $config["paths"]["inspections"] . $report_uuid . ".pdf";
    
    $subject = "Hydranten-Prüfbericht";
    $body = $bodies["report_insert"] . get_inspection_link($report_uuid);

    return send_mail_to_mailinglist(INSPECTIONREPORT, $subject, $body, $file);
}

function mail_send_inspection_report_update($report_uuid){
    global $config;
    global $bodies;
    
    $file = $config["paths"]["inspections"] . $report_uuid . ".pdf";
    
    $subject = "Hydranten-Prüfbericht aktualisiert";
    $body = $bodies["report_update"] . get_inspection_link($report_uuid);
        
    return send_mail_to_mailinglist(INSPECTIONREPORT, $subject, $body, $file);
}

function get_inspection_link($inspection_uuid){
	global $config;
	return $config ["urls"] ["base_url"] . $config ["urls"] ["hydrantapp_home"] . "/inspection/view/" . $inspection_uuid;
}


/*
 * user
 */

function mail_add_user($email, $password) {
    global $userDAO;
	$subject = "Benutzer angelegt";
	
	$user = $userDAO->getUserByEmail($email);
	
	$parameter = array(
	    'email' => $email, 
	    'password' => $password
	);
		
	return sendMailWithTemplate ( $user, $subject, TEMPLATES_PATH . "/usersapp/mails/addUser_mail.php", $parameter);
}

function mail_reset_password($user_uuid, $password) {
	global $userDAO;
	$subject = "Passwort zurückgesetzt";
	
	$user = $userDAO->getUserByUUID($user_uuid);
	
	$parameter = array(
	    'password' => $password
	);
	
	return sendMailWithTemplate ( $user, $subject, TEMPLATES_PATH . "/usersapp/mails/resetPassword_mail.php", $parameter);
	
}

function mail_forgot_password(String $email, String $token) {
    global $config, $userDAO;
	$subject = "Passwort zurücksetzen";
	
	$user = $userDAO->getUserByEmail($email);
	
	$parameter = array(
	    'reset_link' => $config ["urls"] ["base_url"] . "/users/password/reset/" . $token
	);
			
	return sendMailWithTemplate ( $user, $subject, TEMPLATES_PATH . "/usersapp/mails/forgotPassword_mail.php", $parameter);
	
}


/*
 * event
 */

/**
 * Event info to creator if set
 *
 * If assigned to other engine: Mail to all manager of this engine
 * Else: Mail to all manager of own engine
 *
 * Mail to all other manager if published
 *
 */
function mail_insert_event($event_uuid, $inform_creator, $publish) {
	global $bodies, $eventDAO;
	
	$event = $eventDAO->getEvent( $event_uuid );
	
	$subject = "Neue Wache eingestellt" . event_subject($event_uuid);
	$body =  $bodies["event_insert"] . get_event_link($event_uuid);
	
	$sendOK = true;
	
	if($inform_creator){
		$sendOK = mail_to_creator ( $event, $subject, $body );
	}
	
	if ($event->getEngine()->getUuid() != $event->getCreator()->getEngine()->getUuid()){
		$assignedOk = mail_assigned_event($event);
		$sendOK = $sendOK && $assignedOk;
	}
	
	if ($publish) {
		$publishOK = mail_publish_event ( $event);
		$sendOK = $sendOK && $publishOK;
	}
	return $sendOK;
}

/**
 * Info about assignemt to all manager of the engine
 */
function mail_assigned_event($event) {
	global $bodies;
	
	$subject = "Neue Wache zugewiesen" . event_subject($event->getUuid());
	
	$body = $bodies["event_assign"] . get_event_link($event->getUuid());
	
	return mail_to_manager($event, $subject, $body);
}

/**
 * Info an every manager except assigned engine and creator
 */
function mail_publish_event($event_obj) {
	global $bodies, $guardianUserController;
	
	$subject = "Neue Wache veröffentlicht" . event_subject($event_obj->getUuid());
	
	$body = $bodies["event_publish"] . get_event_link($event_obj->getUuid());
	
	$recipients = $guardianUserController->getEventManangerExeptEngineAndCreator($event_obj->getEngine()->getUuid(), $event_obj->getCreator()->getUuid());
	
	return send_mails($recipients, $subject, $body);
}

function mail_not_full($event_uuid) {
	global $bodies, $eventDAO;
	
	$subject = "Erinnerung: Wache nicht voll belegt" . event_subject($event_uuid);
	
	$body = $bodies["event_not_full"] . get_event_link($event_uuid);
	
	$event = $eventDAO->getEvent( $event_uuid );
	
	return mail_to_manager($event, $subject, $body);
}


/*
 * staff
 */

function mail_event_updates($event_uuid){
	global $bodies, $eventDAO;
	
	$subject = "Wache aktualisiert" . event_subject($event_uuid);
	$body =  $bodies["event_update"] . get_event_link($event_uuid);
	
	$event = $eventDAO->getEvent( $event_uuid );
	
	return mail_to_staff($event, $subject, $body);
}

function mail_delete_event($event_uuid) {
	global $bodies, $eventDAO;
	
	$subject = "Wache abgesagt" . event_subject($event_uuid);
	$body = $bodies["event_delete"] . get_event_link($event_uuid);
	
	$event = $eventDAO->getEvent( $event_uuid );
	
	return mail_to_staff($event, $subject, $body);
	
}


//by user
function mail_subscribe_staff_user($event_uuid, $user_uuid, $informMe) {
	global $config, $bodies, $userDAO, $eventDAO;
	
	$sendOK = true;
	
	$event = $eventDAO->getEvent( $event_uuid );
	$user = $userDAO->getUserByUUID($user_uuid);
	
	if($event->getStaffConfirmation()){
		//send mail to manager of the event
		$subject = "In Wache eingeschrieben (Bestätigung ausstehend)" . event_subject($event_uuid);
		$body = $bodies["event_subscribe_engine_confirm"] . get_event_link($event_uuid);
		
		$sendOK = $sendOK && mail_to_manager($event, $subject, $body);
		
	} else {
		
		//send mail to manager of the user
		$sendOK = $sendOK && inform_users_manager($event_uuid, $user);
		
		//Send mail to user
		
		if($informMe){
			$subject = "In Wache eingeschrieben" . event_subject($event_uuid);
			$body = $bodies["event_subscribe"] . get_event_link($event_uuid) . $bodies["event_report_link"] . get_report_create_link($event_uuid);
			
			$sendOK = $sendOK && send_mail($user->getEmail(), $subject, $body);
		}
		
		//send mail to manager of the event
		if ($event->isEventFull()) {
			$subject = "Wache voll belegt" . event_subject($event_uuid);
			$body = $bodies["event_full"] . get_event_link($event_uuid);
			
		} else if ($config ["settings"] ["creatormailonsubscription"]) {
			$subject = "In Wache eingeschrieben" . event_subject($event_uuid);
			$body = $bodies["event_subscribe_engine"] . get_event_link($event_uuid);
		} else {
			return $sendOK;
		}
		$sendOK = $sendOK && mail_to_manager($event, $subject, $body);
		
	}
	return $sendOK;
}

function mail_unscribe_staff_user($staff_uuid, $event_uuid) {
	global $config, $bodies, $eventDAO, $staffDAO, $guardianUserController;
	
	$event = $eventDAO->getEvent( $event_uuid );
	$staffuser = $staffDAO->getEventStaffEntry( $staff_uuid )->getUser();
	$sendOK = true;
	
	$subject = "Aus Wache ausgetragen" . event_subject($event_uuid);
	
	//send mail to manager of the user
	if ($config ["settings"] ["enginemgrmailonsubscription"]) {
		
		$body = $bodies["event_unscribe_by_user_engine"] . get_event_link($event_uuid);
		$recipients = $guardianUserController->getEventmanagerOfEngine($staffuser->getEngine()->getUuid());
		$sendOK = send_mails($recipients, $subject, $body);
	}
	
	//send mail to manager of the event
	$body = $bodies["event_unscribe_by_user"] . get_event_link($event_uuid);
	$sendOK = $sendOK && mail_to_manager($event, $subject, $body);
	
	return $sendOK;
}

//by manager
function mail_confirm_staff_user($staff_uuid, $event_uuid) {
	global $bodies, $staffDAO;
	
	$sendOK = true;
	
	//send mail to user
	$subject = "Wachteilnahme bestätigt" . event_subject($event_uuid);
	$body = $bodies["event_staff_confirmed"] . get_event_link($event_uuid) . $bodies["event_report_link"] . get_report_create_link($event_uuid);;
	
	$user = $staffDAO->getEventStaffEntry( $staff_uuid )->getUser();
	$sendOK = $sendOK && send_mail ( $user->getEmail(), $subject, $body );
	
	//send mail to manager of the user
	$sendOK = $sendOK && inform_users_manager($event_uuid, $user);
	
	return $sendOK;
}

//by manager
function mail_add_staff_user($event_uuid, $user_uuid, $staffUUID) {
	global $bodies, $userDAO;
	
	$sendOK = true;
	
	//send mail to added user
	$subject = "In Wache eingeteilt" . event_subject($event_uuid);
	$body = $bodies["event_staff_add"] . get_event_link($event_uuid) 
	. $bodies["event_staff_ack"] . get_staff_acknowledge_link($event_uuid, $staffUUID)
	. $bodies["event_report_link"] . get_report_create_link($event_uuid);
	
	$user = $userDAO->getUserByUUID($user_uuid);
	$sendOK = $sendOK && send_mail ( $user->getEmail(), $subject, $body );
	
	//send mail to manager of the user
	$sendOK = $sendOK && inform_users_manager($event_uuid, $user);
}

//by manager
function mail_remove_staff_user($staff_uuid, $event_uuid) {
	global $config, $bodies, $guardianUserController, $staffDAO;
		
	//inform staff
	$subject = "Aus Wache entfernt" . event_subject($event_uuid);
	$body = $bodies["event_unscribe"] . get_event_link($event_uuid);
	
	$user = $staffDAO->getEventStaffEntry($staff_uuid)->getUser();
	send_mail ( $user->getEmail(), $subject, $body );
	
	//send mail to manager of the user
	if ($config ["settings"] ["enginemgrmailonsubscription"]) {
		
		$body = $bodies["event_unscribe_engine"] . get_event_link($event_uuid);
		
		$recipients = $guardianUserController->getEventmanagerOfEngine($user->getEngine()->getUuid());
		send_mails($recipients, $subject, $body);
	}
}


/*
 * aux
 */

function get_event_link($event_uuid){
	global $config;
	return $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/view/" . $event_uuid;
}

function get_staff_acknowledge_link($event_uuid, $staff_uuid){
	global $config;
	return $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/" . $event_uuid . "/acknowledge/" . $staff_uuid;
}

function get_report_create_link($event_uuid){
	global $config;
	return $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/" . $event_uuid;
}

function get_report_link($report_uuid){
	global $config;
	return $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/view/" . $report_uuid;
}

function event_subject($event_uuid){
	global $config, $eventDAO;
	$event = $eventDAO->getEvent($event_uuid);
	
	$subject = " - "
			. date($config ["formats"] ["date"], strtotime($event->getDate())) . " "
					. date($config ["formats"] ["time"], strtotime($event->getStartTime())) . " Uhr "
							. $event->getType()->getType();
							
							return $subject;
}

function mail_to_manager($event_obj, $subject, $body){
	global $guardianUserController;
	
	$recipients = $guardianUserController->getEventmanagerOfEngine($event_obj->getEngine()->getUuid());
	
	return send_mails($recipients, $subject, $body);
}

function mail_to_creator(Event $event, $subject, $body){
	
	return send_mail ( $event->getCreator()->getEmail(), $subject, $body );
}

function mail_to_staff($event_obj, $subject, $body){
	$recipients = array();
	$staff = $event_obj->getStaff();
	foreach($staff as $entry) {
		if($entry->getUser() != NULL) {
			$recipients[] = $entry->getUser();
		}
	}

	return send_mails($recipients, $subject, $body);
}


function inform_users_manager($event_uuid, $user){
	global $config, $bodies, $guardianUserController;
		
	if ($config ["settings"] ["enginemgrmailonsubscription"]) {
		$subject = "Information über Wachteilnahme" . event_subject($event_uuid);
		
		$body = $bodies["event_subscribe_manager"] . get_event_link($event_uuid);
		
		$recipients = $guardianUserController->getEventmanagerOfEngine($user->engine);
		return send_mails($recipients, $subject, $body);
	}
	return true;
}

/*
 * Reports
 */

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


/*
 * Confirmations
 */

function mail_send_confirmation_request($confirmation){
	global $config, $bodies, $userDAO;
	
	$subject = "Neue Anfrage einer Arbeitgeberbestätigung";
	$body = $bodies["confirmation_requested"] . $config ["urls"] ["base_url"] . $config["urls"]["employerapp_home"] . "/confirmations/process";
		
	$administration = $userDAO->getUsersWithPrivilegeByName(Privilege::FFADMINISTRATION);
	return send_mails($administration, $subject, $body);
}

function mail_send_confirmation_declined($confirmation){
	global $config, $bodies;
		
	$subject = "Angefragte Arbeitgeberbestätigung abgelehnt";
	$body = $bodies["confirmation_declined"] . $config ["urls"] ["base_url"] . $config["urls"]["employerapp_home"] . "/confirmations/overview";
	
	return send_mail ( $confirmation->getUser()->getEmail(), $subject, $body );
}

function mail_send_confirmation($confirmation){
	global $config, $bodies;
		
	$employer_informed = false;
	if( $confirmation->getUser()->getEmployerMail() ){
		$employer_informed = mail_send_to_employer($confirmation, $confirmation->getUser());
	}

	$files = array();
	$files[] = $config["paths"]["confirmations"] . $confirmation->getUuid() . ".pdf";
	$files[] = $config["paths"]["files"] . "Lohnerstattung-Verdienstausfall.pdf";
	$subject = "Arbeitgebernachweis für Einsatztätigkeit";
	$body = $bodies["confirmation_accepted"] . $config ["urls"] ["base_url"] . $config["urls"]["employerapp_home"] . "/confirmations/accepted";
	
	if($confirmation->getUser()->getEmployerMail()){
		if( $employer_informed ){
			$body = $body . "\n\n" . "Die Bestätigung wurde bereits an die in den Benutzerdaten hinterlegte E-Mail-Adresse des Arbeitgebers gesendet.";
		} else {
			$body = $body . "\n\n" . "Die Bestätigung konnte aufgrund eines Fehler nicht an den Arbeitgeber gesendet werden. \n"
						. "Bitte leiten Sie die Bestätigung selbst weiter.";
		}
	} else {
		$body = $body . "\n\n" . "Bitte leiten Sie die Bestätigung an Ihren Arbeitgeber weiter. \n"
					. "(In den Benutzerdaten kann die E-Mail-Adresse des Arbeitgebers hinterlegt werden. Die Bestätigung wird dann direkt an diese Adresse gesendet)";
	}
		
	return send_mail ( $confirmation->getUser()->getEmail(), $subject, $body, $files);
}

function mail_send_to_employer(Confirmation $confirmation, User $user){
	global $config;

	$files = array();
	$files[] = $config["paths"]["confirmations"] . $confirmation->getUuid() . ".pdf";
	$files[] = $config["paths"]["files"] . "Lohnerstattung-Verdienstausfall.pdf";
	$subject = "Arbeitgebernachweis für Einsatztätigkeit";
	$body = "Sehr geehrte Damen und Herren,\n\n"
		. "der/die Feuerwehrmann/frau " . $user->getFullName() . "\n\n"
				. "war am " . date($config ["formats"] ["date"], strtotime($confirmation->getDate())) . " zwischen " . date($config ["formats"] ["time"], strtotime($confirmation->getStartTime())) . " Uhr und " . date($config ["formats"] ["time"], strtotime($confirmation->getEndTime())) . " Uhr\n\n"
		. "im Feuerwehreinsatz tätig. \n\n"
		. "Im Anhang finden Sie die formelle Bestätigung als PDF. \n\n"
		. "Mit freundlichen Grüßen \n"
		. "Stadt Landshut\nReferat 5 Feuerwehr";
	
	return send_mail ( $user->getEmployerMail(), $subject, $body, $files, false);
}


/*
 * DataChangeRequests
 */

function mail_send_datachange_request(){
	global $config, $bodies, $userDAO;
	
	$subject = "Neue Anfrage einer Stammdatenänderung";
	$body = $bodies["datachange_requested"] . $config ["urls"] ["base_url"] . $config["urls"]["masterdataapp_home"] . "/datachangerequests/process";
	
	$dataadmins = $userDAO->getUsersWithPrivilegeByName(Privilege::MASTERDATAADMIN);
	return send_mails($dataadmins, $subject, $body);
}

function mail_send_datachange_request_update(){
	global $config, $bodies, $userDAO;
	
	$subject = "Stammdatenänderung mit Rückfrage aktualisiert";
	$body = $bodies["datachange_request_update"] . $config ["urls"] ["base_url"] . $config["urls"]["masterdataapp_home"] . "/datachangerequests/process";
	
	$dataadmins = $userDAO->getUsersWithPrivilegeByName(Privilege::MASTERDATAADMIN);
	return send_mails($dataadmins, $subject, $body);
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

?>