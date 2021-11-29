<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["guardian"],
		'template' => "eventAssign_template.php",
	    'title' => 'Wache/Position nicht vorhanden',
	    'secured' => true,
	    'showFormular' => false,
		'privilege' => Privilege::EVENTMANAGER
);
$variables = checkPermissions($variables);

if (isset ( $_GET ['staffid'] ) and isset ( $_GET ['id'] )) {

	$staffUUID = trim ( $_GET ['staffid'] );
	$eventUUID = trim ( $_GET ['id'] );
	
	$event = $eventDAO->getEvent($eventUUID);
	$staffposition = $staffDAO->getEventStaffEntry($staffUUID);
	
	if(isset($event) and isset($staffposition)) {
	    $variables ['showFormular'] = true;
	    
	    $variables ['title'] = "In " . $event->getType()->getType() . " einteilen";
    	$variables ['engines'] = $engineDAO->getEngines();
    	$variables ['user'] = $guardianUserController->getEventParticipantOfEngine($userController->getCurrentUser()->getEngine()->getUuid());
    	$variables ['event'] = $event;
    	$variables ['subtitle'] = date($config ["formats"] ["date"], strtotime($event->getDate())) 
    	. " - " . date($config ["formats"] ["time"], strtotime($event->getStartTime())) . " " . $staffposition->getPosition()->getPosition();
        		
    	if (isset ( $_POST ['user_uuid'] ) ) {
    		
    		$user_uuid = $_POST ['user_uuid'];
    		
    		$user = $userDAO->getUserByUUID($user_uuid);

    		if($user){
    			
   				if($user->hasPrivilegeByName(Privilege::EVENTPARTICIPENT)){
    				//if uuid is already in event -> error
    				if( ! $event->isUserAlreadyStaff($user->getUuid())){
    					
    					if($eventController->assignUser( $staffUUID, $user )){
    						mail_add_staff_user ($eventUUID, $user->getUuid(), $staffUUID);
    						$variables ['successMessage'] = "Wachteilnehmer zugewiesen - <a href=\"" . $config["urls"]["guardianapp_home"] . "/events/view/" . $eventUUID . "\" class=\"alert-link\">Zurück</a>";
    						$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::EventAssigned, $staffUUID));
    						$variables ['showFormular'] = false;
    						header ( "Location: " . $config["urls"]["guardianapp_home"] . "/events/view/". $event->getUuid()); // redirects
    					} else {
    						$variables ['alertMessage'] = "Eintragen fehlgeschlagen";
    					}
    				} else {
    					$variables ['alertMessage'] = "Eintragen nicht möglich - Person besetzt bereits eine Position";
    				}
    		    } else {
    		    	$variables ['alertMessage'] = "Eintragen nicht möglich - Person ist nicht für Wachen freigegeben";
    		    }
    		} else {
    			$variables ['alertMessage'] = "Eintragen nicht möglich - Der ausgewählte Benutzer existiert nicht";
    		}
    	}
	} else {
		$variables ['alertMessage'] = "Position existiert nicht";
	}
} else {
    $variables ['alertMessage'] = "Fehlende Parameter";
}

renderLayoutWithContentFile ( $variables );
?>