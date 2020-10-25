<?php 
require_once (realpath ( dirname ( __FILE__ ) . "/../config.php" ));
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_event.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/db_user_guardian.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_connect.php";
require_once LIBRARY_PATH . "/mail_body.php";
require_once LIBRARY_PATH . "/mail.php";

require_once LIBRARY_PATH . '/class/EventReport.php';
require_once LIBRARY_PATH . '/class/ReportUnit.php';
require_once LIBRARY_PATH . '/class/ReportUnitStaff.php';

function mail_send_inspection_report($report_uuid){
    global $config;
    global $bodies;
    
    $file = $config["paths"]["inspections"] . $report_uuid . ".pdf";
    
    $subject = "Hydranten-Prüfbericht";
    $body = $bodies["report_insert"] . get_inspection_link($report_uuid);

    return send_mail_to_mailing(INSPECTIONREPORT, $subject, $body, $file);
}

function mail_send_inspection_report_update($report_uuid){
    global $config;
    global $bodies;
    
    $file = $config["paths"]["inspections"] . $report_uuid . ".pdf";
    
    $subject = "Hydranten-Prüfbericht aktualisiert";
    $body = $bodies["report_update"] . get_inspection_link($report_uuid);
        
    return send_mail_to_mailing(INSPECTIONREPORT, $subject, $body, $file);
}

function get_inspection_link($inspection_uuid){
	global $config;
	return $config ["urls"] ["base_url"] . $config ["urls"] ["hydrantapp_home"] . "/inspection/" . $inspection_uuid;
}


/*
 * user
 */

function mail_add_user($email, $password) {
	global $bodies;
	$subject = "Benutzer angelegt";
	
	$body = $bodies["user_add"] . $bodies["login"] . $email . $bodies["password"] . $password . $bodies["user_add2"];
	
	return send_mail ( $email, $subject, $body );
}

function mail_reset_password($user_uuid, $password) {
	global $bodies;
	$subject = "Passwort zurückgesetzt";
	
	$body = $bodies["reset_password"] . $bodies["password"] . $password . $bodies["reset_password2"];
	
	$user = get_user($user_uuid);
	return send_mail ($user->email, $subject, $body );
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
	global $bodies;
	
	$event = get_event( $event_uuid );
	
	$subject = "Neue Wache eingestellt" . event_subject($event_uuid);
	$body =  $bodies["event_insert"] . get_event_link($event_uuid);
	
	$sendOK = true;
	
	if($inform_creator){
		$sendOK = mail_to_creator ( $event, $subject, $body );
	}
	
	if ($event->engine != get_user($event->creator)->engine){
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
	
	$subject = "Neue Wache zugewiesen" . event_subject($event->uuid);
	
	$body = $bodies["event_assign"] . get_event_link($event->uuid);
	
	return mail_to_manager($event, $subject, $body);
}

/**
 * Info an every manager except assigned engine and creator
 */
function mail_publish_event($event_obj) {
	global $bodies;
	
	$subject = "Neue Wache veröffentlicht" . event_subject($event_obj->uuid);
	
	$body = $bodies["event_publish"] . get_event_link($event_obj->uuid);
	
	$recipients = get_eventmanager_except_engine_and_creator($event_obj->engine, $event_obj->creator);
	
	return send_mails($recipients, $subject, $body);
}

function mail_not_full($event_uuid) {
	global $bodies;
	
	$subject = "Erinnerung: Wache nicht voll belegt" . event_subject($event_uuid);
	
	$body = $bodies["event_not_full"] . get_event_link($event_uuid);
	
	$event = get_event( $event_uuid );
	
	return mail_to_manager($event, $subject, $body);
}


/*
 * staff
 */

function mail_event_updates($event_uuid){
	global $bodies;
	
	$subject = "Wache aktualisiert" . event_subject($event_uuid);
	$body =  $bodies["event_update"] . get_event_link($event_uuid);
	
	$event = get_event( $event_uuid );
	
	return mail_to_staff($event, $subject, $body);
}

function mail_delete_event($event_uuid) {
	global $bodies;
	
	$subject = "Wache abgesagt" . event_subject($event_uuid);
	$body = $bodies["event_delete"] . get_event_link($event_uuid);
	
	$event = get_event( $event_uuid );
	
	return mail_to_staff($event, $subject, $body);
	
}


//by user
function mail_subscribe_staff_user($event_uuid, $user_uuid, $informMe) {
	global $config;
	global $bodies;
	
	$sendOK = true;
	
	$event = get_event( $event_uuid );
	$user = get_user($user_uuid);
	
	if($event->staff_confirmation){
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
			
			$sendOK = $sendOK && send_mail($user->email, $subject, $body);
		}
		
		//send mail to manager of the event
		if (is_event_full($event_uuid)) {
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
	global $config;
	global $bodies;
	
	$event = get_event( $event_uuid );
	$staffuser = get_staff_user( $staff_uuid );
	$sendOK = true;
	
	$subject = "Aus Wache ausgetragen" . event_subject($event_uuid);
	
	//send mail to manager of the user
	if ($config ["settings"] ["enginemgrmailonsubscription"]) {
		
		$body = $bodies["event_unscribe_by_user_engine"] . get_event_link($event_uuid);
		$recipients = get_eventmanager_of_engine($staffuser->engine);
		$sendOK = send_mails($recipients, $subject, $body);
	}
	
	//send mail to manager of the event
	$body = $bodies["event_unscribe_by_user"] . get_event_link($event_uuid);
	$sendOK = $sendOK && mail_to_manager($event, $subject, $body);
	
	return $sendOK;
}

//by manager
function mail_confirm_staff_user($staff_uuid, $event_uuid) {
	global $bodies;
	
	$sendOK = true;
	
	//send mail to user
	$subject = "Wachteilnahme bestätigt" . event_subject($event_uuid);
	$body = $bodies["event_staff_confirmed"] . get_event_link($event_uuid) . $bodies["event_report_link"] . get_report_create_link($event_uuid);;
	
	$user = get_staff_user($staff_uuid);
	$sendOK = $sendOK && send_mail ( $user->email, $subject, $body );
	
	//send mail to manager of the user
	$sendOK = $sendOK && inform_users_manager($event_uuid, $user);
	
	return $sendOK;
}

//by manager
function mail_add_staff_user($event_uuid, $user_uuid) {
	global $bodies;
	
	$sendOK = true;
	
	//send mail to added user
	$subject = "In Wache eingeteilt" . event_subject($event_uuid);
	$body = $bodies["event_staff_add"] . get_event_link($event_uuid) . $bodies["event_report_link"] . get_report_create_link($event_uuid);;
	
	$user = get_user($user_uuid);
	$sendOK = $sendOK && send_mail ( $user->email, $subject, $body );
	
	//send mail to manager of the user
	$sendOK = $sendOK && inform_users_manager($event_uuid, $user);
}

//by manager
function mail_remove_staff_user($staff_uuid, $event_uuid) {
	global $config;
	global $bodies;
	
	//inform staff
	$subject = "Aus Wache entfernt" . event_subject($event_uuid);
	$body = $bodies["event_unscribe"] . get_event_link($event_uuid);
	
	$user = get_staff_user($staff_uuid);
	send_mail ( $user->email, $subject, $body );
	
	//send mail to manager of the user
	if ($config ["settings"] ["enginemgrmailonsubscription"]) {
		
		$body = $bodies["event_unscribe_engine"] . get_event_link($event_uuid);
		
		$recipients = get_eventmanager_of_engine($user->engine);
		send_mails($recipients, $subject, $body);
	}
}


/*
 * aux
 */

function get_event_link($event_uuid){
	global $config;
	return $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/" . $event_uuid;
}

function get_report_create_link($event_uuid){
	global $config;
	return $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/" . $event_uuid;
}

function get_report_link($report_uuid){
	global $config;
	return $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/" . $report_uuid;
}

function event_subject($event_uuid){
	global $config;
	$event = get_event($event_uuid);
	
	$subject = " - "
			. date($config ["formats"] ["date"], strtotime($event->date)) . " "
					. date($config ["formats"] ["time"], strtotime($event->start_time)) . " Uhr "
							. get_eventtype($event->type)->type;
							
							return $subject;
}

function mail_to_manager($event_obj, $subject, $body){
	$recipients = get_eventmanager_of_engine($event_obj->engine);
	
	return send_mails($recipients, $subject, $body);
}

function mail_to_creator($event_obj, $subject, $body){
	$creator = get_user( $event_obj->creator );
	
	return send_mail ( $creator->email, $subject, $body );
}

function mail_to_staff($event_obj, $subject, $body){
	$recipients = get_personal($event_obj->uuid);
	
	return send_mails($recipients, $subject, $body);
}


function inform_users_manager($event_uuid, $user){
	global $config;
	global $bodies;
	
	if ($config ["settings"] ["enginemgrmailonsubscription"]) {
		$subject = "Information über Wachteilnahme" . event_subject($event_uuid);
		
		$body = $bodies["event_subscribe_manager"] . get_event_link($event_uuid);
		
		$recipients = get_eventmanager_of_engine($user->engine);
		return send_mails($recipients, $subject, $body);
	}
	return true;
}

/*
 * Reports
 */

function mail_insert_event_report($report){
	global $config;
	global $bodies;
	
	$subject = "Wachbericht";
	$body = $bodies["event_report"] . get_report_link($report->uuid);
	
	$file = $config["paths"]["reports"] . $report->uuid . ".pdf";
	
	//send report to administration if event is no series
	//if(!get_eventtype_from_name($report->type)->isseries){
	$administration = get_users_of_engine(get_administration()->uuid);
	send_mails($administration, $subject, $body, $file);
	//}
	
	//send report to manager of the assigned engine
	$managerList = get_eventmanager_of_engine($report->engine);
	if(sizeof($managerList) > 0){
		send_mails($managerList, $subject, $body, $file);
		return true;
	}
	return false;
}

function mail_update_report($report){
	global $config;
	global $bodies;
	
	$subject = "Wachbericht aktualisiert";
	$body = $bodies["event_report_update"] . get_report_link($report->uuid);
	
	$file = $config["paths"]["reports"] . $report->uuid . ".pdf";
	
	//send report to administration if event is no series
	//if(!get_eventtype_from_name($report->type)->isseries){
	$administration = get_users_with_privilege_by_name(FFADMINISTRATION);
	send_mails($administration, $subject, $body, $file);
	//}
	
	//send report to manager of the assigned engine
	$managerList = get_eventmanager_of_engine($report->engine);
	if(sizeof($managerList) > 0){
		send_mails($managerList, $subject, $body, $file);
		return true;
	}
	return false;
}

function mail_report_approved($report_uuid){
	global $config;
	global $bodies;
	
	$subject = "Wachbericht freigegeben";
	$body = $bodies["event_report_approved"] . get_report_link($report_uuid);
	
	$file = $config["paths"]["reports"] . $report_uuid . ".pdf";
	
	$administration = get_users_with_privilege_by_name(FFADMINISTRATION);
	return send_mails($administration, $subject, $body, $file);
}


/*
 * Confirmations
 */

function mail_send_confirmation_request($confirmation_uuid){
	global $config;
	global $bodies;
	
	$subject = "Neue Anfrage einer Arbeitgeberbestätigung";
	$body = $bodies["confirmation_requested"] . $config ["urls"] ["base_url"] . $config["urls"]["employerapp_home"] . "/confirmations/process";
		
	$administration = get_users_with_privilege_by_name(FFADMINISTRATION);
	return send_mails($administration, $subject, $body);
}

function mail_send_confirmation_declined($confirmation_uuid){
	global $config;
	global $bodies;
	
	$confirmation = get_confirmation($confirmation_uuid);
	$user = get_user($confirmation->user);
	
	$subject = "Angefragte Arbeitgeberbestätigung abgelehnt";
	$body = $bodies["confirmation_declined"] . $config ["urls"] ["base_url"] . $config["urls"]["employerapp_home"] . "/confirmations";
	
	return send_mail ( $user->email, $subject, $body );
}

function mail_send_confirmation($confirmation_uuid){
	global $config;
	global $bodies;
	
	$confirmation = get_confirmation($confirmation_uuid);
	$user = get_user($confirmation->user);
	
	$employer_informed = false;
	if( $user->employer_mail ){
		$employer_informed = mail_send_to_employer($confirmation, $user);
	}

	$file = $config["paths"]["confirmations"] . $confirmation->uuid . ".pdf";
	$subject = "Arbeitgebernachweis für Einsatztätigkeit";
	$body = $bodies["confirmation_accepted"] . $config ["urls"] ["base_url"] . $config["urls"]["employerapp_home"] . "/confirmations";
	
	if($user->employer_mail){
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
		
	return send_mail ( $user->email, $subject, $body, $file);
}

function mail_send_to_employer($confirmation, $user){
	global $config;

	$file = $config["paths"]["confirmations"] . $confirmation->uuid . ".pdf";
	$subject = "Arbeitgebernachweis für Einsatztätigkeit";
	$body = "Sehr geehrte Damen und Herren,\n\n"
		. "der/die Feuerwehrmann/frau " . $user->firstname . " " . $user->lastname . "\n\n"
				. "war am " . date($config ["formats"] ["date"], strtotime($confirmation->date)) . " zwischen " . date($config ["formats"] ["time"], strtotime($confirmation->start_time)) . " Uhr und " . date($config ["formats"] ["time"], strtotime($confirmation->end_time)) . " Uhr\n\n"
		. "im Feuerwehreinsatz tätig. \n\n"
		. "Im Anhang finden Sie die formelle Bestätigung als PDF. \n\n"
		. "Mit freundlichen Grüßen \n"
		. "Stadt Landshut\nReferat 5 Feuerwehr";
	
	return send_mail ( $user->employer_mail, $subject, $body, $file, false);
}

?>