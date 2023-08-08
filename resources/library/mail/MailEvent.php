<?php 

require_once LIBRARY_PATH . "/mail/MailBase.php";

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
        $sendOK = send_mail ( $event->getCreator()->getEmail(), $subject, $body );
    }
    
    if ($event->getEngine()->getUuid() != $event->getCreator()->getEngine()->getUuid()){
        $assignedOk = mail_assigned_event($event);
        $sendOK = $sendOK && $assignedOk;
    }
    
    if ($publish) {
        $publishOK = mail_publish_event ( $event);
        $sendOK = $sendOK && $publishOK;
    }
    
    $sendOK = $sendOK && mail_new_event_participants($event);
    
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
    
    $sendOK = true;
    
    $sendOK = sendMailsWithBody($recipients, $subject, $body);
    
    $sendOK = $sendOK && mail_new_event_participants_publish($event_obj);
    
    return $sendOK;
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

function mail_cancel_event($event_uuid) {
    global $bodies, $eventDAO, $userDAO;
    
    $subject = "Wache abgesagt" . event_subject($event_uuid);
    $body = $bodies["event_cancel"] . get_event_link($event_uuid);
    
    $event = $eventDAO->getEvent( $event_uuid );
    
    $sendOK = mail_to_staff($event, $subject, $body);
    
    $administration = $userDAO->getUsersWithPrivilegeByName(Privilege::FFADMINISTRATION);
    
    $body = $bodies["event_cancel_administration"] . get_event_link($event_uuid);
    
    return $sendOK && sendMailsWithBody($administration, $subject, $body);
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
        $sendOK = sendMailsWithBody($recipients, $subject, $body);
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
        sendMailsWithBody($recipients, $subject, $body);
    }
}


function inform_users_manager($event_uuid, $user){
    global $config, $bodies, $guardianUserController;
    
    if ($config ["settings"] ["enginemgrmailonsubscription"]) {
        $subject = "Information über Wachteilnahme" . event_subject($event_uuid);
        
        $body = $bodies["event_subscribe_manager"] . get_event_link($event_uuid);
        
        $recipients = $guardianUserController->getEventmanagerOfEngine($user->engine);
        return sendMailsWithBody($recipients, $subject, $body);
    }
    return true;
}

function mail_to_staff($event_obj, $subject, $body){
    $recipients = array();
    $staff = $event_obj->getStaff();
    foreach($staff as $entry) {
        if($entry->getUser() != NULL) {
            $recipients[] = $entry->getUser();
        }
    }
    
    return sendMailsWithBody($recipients, $subject, $body);
}

function mail_to_manager($event_obj, $subject, $body){
    global $guardianUserController;
    
    $recipients = $guardianUserController->getEventmanagerOfEngine($event_obj->getEngine()->getUuid());
    
    return sendMailsWithBody($recipients, $subject, $body);
}

function mail_new_event_participants(Event $event){
    global $bodies, $guardianUserController, $userDAO;
    
    $subject = "Neue Wache eingestellt" . event_subject($event->getUuid());
    $body =  $bodies["event_insert_all"] . get_event_link($event->getUuid()) . $bodies["event_insert_all_disc"];
    
    $recipients = array();
    if (! $event->getPublished()) {
        $recipients = $guardianUserController->getEventParticipantOfEngine($event->getEngine()->getUuid());
        $recipients = $guardianUserController->filterUserWithSetting($recipients, SettingDAO::RECEIVE_NO_MAIL_ON_NEW_EVENT);
    } else {
        $recipients = $userDAO->getUsersWithPrivilegeByName(Privilege::EVENTPARTICIPENT);
        $recipients = $guardianUserController->filterUserWithSetting($recipients, SettingDAO::RECEIVE_NO_MAIL_ON_NEW_EVENT);
    }
    
    return sendMailsWithBody($recipients, $subject, $body);
}

function mail_new_event_participants_publish($event){
    global $bodies, $guardianUserController, $userDAO;
    
    $subject = "Neue Wache eingestellt" . event_subject($event->getUuid());
    $body =  $bodies["event_insert_all"] . get_event_link($event->getUuid()) . $bodies["event_insert_all_disc"];
    
    $recipients = $userDAO->getUsersWithPrivilegeByName(Privilege::EVENTPARTICIPENT);
    $recipients = $guardianUserController->filterUserWithSetting($recipients, SettingDAO::RECEIVE_NO_MAIL_ON_NEW_EVENT);
    $recipients = $guardianUserController->filterUserOfEngine($recipients, $event->getEngine());
    
    return sendMailsWithBody($recipients, $subject, $body);
}




function get_event_link($event_uuid){
    global $config;
    return $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/view/" . $event_uuid;
}

function get_staff_acknowledge_link($event_uuid, $staff_uuid){
    global $config;
    return $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/" . $event_uuid . "/acknowledge/" . $staff_uuid;
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
