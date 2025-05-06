<?php

require_once LIBRARY_PATH . "/mail/MailBase.php";


function mail_send_confirmation_request($confirmation){
    global $config, $bodies, $userDAO;
    
    $subject = "Neue Anfrage einer Arbeitgeberbestätigung";
    $parameter = array(
        'confirmation' => $confirmation,
    );
    if($confirmation->getAssignedTo() == null){
        $recipients = $userDAO->getUsersWithPrivilegeByName(Privilege::FFADMINISTRATION);
    } else {
        $recipients = $confirmation->getAssignedTo();
    }
    return sendMailsWithTemplate (
        $recipients,
        $subject,
        TEMPLATES_PATH . "/employerapp/mails/confirmationRequest_mail.php",
        $parameter
        );
    
}

function mail_send_confirmation_declined($confirmation){
    global $config, $bodies;
    
    $subject = "Angefragte Arbeitgeberbestätigung abgelehnt";
    $body = $bodies["confirmation_declined"]
    . $config ["urls"] ["base_url"]
    . $config["urls"]["employerapp_home"] . "/confirmations/overview";
    
    return send_mail ( $confirmation->getUser()->getEmail(), $subject, $body );
}

function mail_send_confirmation($confirmation){
    global $config, $bodies;
    
    if( ! $confirmation->getLastAdvisor()->hasPrivilegeByName(Privilege::FFADMINISTRATION) ){
        mail_inform_administration($confirmation);
    }    
    
    $employer_informed = false;
    if( $confirmation->getUser()->getEmployerMail() ){
        $employer_informed = mail_send_to_employer($confirmation, $confirmation->getUser());
    }
    
    $files = array();
    $files[] = $config["paths"]["confirmations"] . $confirmation->getUuid() . ".pdf";
    $files[] = $config["paths"]["files"] . "Lohnerstattung-Verdienstausfall.pdf";
    $subject = "Arbeitgebernachweis für Einsatztätigkeit";
    $body = $bodies["confirmation_accepted"]
    . $config ["urls"] ["base_url"]
    . $config["urls"]["employerapp_home"] . "/confirmations/accepted";
    
    if($confirmation->getUser()->getEmployerMail()){
        if( $employer_informed ){
            $body = $body . "\n\n" . "Die Bestätigung wurde bereits an die in den Benutzerdaten hinterlegte E-Mail-Adresse des Arbeitgebers gesendet.";
        } else {
            $body = $body . "\n\n" . "Die Bestätigung konnte aufgrund eines Fehler nicht an den Arbeitgeber gesendet werden. \n"
                . "Bitte leite die Bestätigung selbst weiter.";
        }
    } else {
        $body = $body . "\n\n" . "Bitte leite die Bestätigung an deinen Arbeitgeber weiter. \n"
            . "(In den Benutzerdaten kann die E-Mail-Adresse des Arbeitgebers hinterlegt werden. Die Bestätigung wird dann direkt an diese Adresse gesendet)";
    }
    
    return send_mail ( $confirmation->getUser()->getEmail(), $subject, $body, $files);
}

function mail_inform_administration($confirmation){
    global $config, $bodies, $userDAO, $guardianUserController;
    
    $files = array();
    $files[] = $config["paths"]["confirmations"] . $confirmation->getUuid() . ".pdf";
    $subject = "Arbeitgebernachweis wurde durch Einheitsführer akzeptiert";
    $body = $bodies["confirmation_accepted_info"] . $config ["urls"] ["base_url"] . $config["urls"]["employerapp_home"] . "/confirmations/process/accepted";
    
    $recipients = $userDAO->getUsersWithPrivilegeByName(Privilege::FFADMINISTRATION);
    $recipients = $guardianUserController->filterUserWithSetting($recipients, SettingDAO::NO_ADMIN_INFOMAIL_ON_CONFIRMATION);
    
    return sendMailsWithBody ( $recipients, $subject, $body, $files);
    
}

function mail_send_to_employer(Confirmation $confirmation, User $user){
    global $config;
    
    $files = array();
    $files[] = $config["paths"]["confirmations"] . $confirmation->getUuid() . ".pdf";
    $files[] = $config["paths"]["files"] . "Lohnerstattung-Verdienstausfall.pdf";
    $subject = "Arbeitgebernachweis für Einsatztätigkeit";
    $body = "Sehr geehrte Damen und Herren,\n\n"
        . "der/die Feuerwehrmann/frau " . $user->getFullName() . "\n\n"
            . "war am " . date($config ["formats"] ["date"], strtotime($confirmation->getDate())) 
            . " zwischen " . date($config ["formats"] ["time"], strtotime($confirmation->getStartTime())) 
            . " Uhr und " . date($config ["formats"] ["time"], strtotime($confirmation->getEndTime())) . " Uhr\n\n"
                . "im Feuerwehreinsatz tätig. \n\n"
                    . "Im Anhang finden Sie die formelle Bestätigung als PDF. \n\n"
                        . "Mit freundlichen Grüßen \n"
                            . "Stadt Landshut\nReferat 5 Feuerwehr";
                            
                            return send_mail ( $user->getEmployerMail(), $subject, $body, $files, false);
}

