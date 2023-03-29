<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["guardian"],
		'template' => "eventSubscribe_template.php",
	    'title' => 'Wache/Position nicht vorhanden',
	    'secured' => true,
	    'showFormular' => false
);
checkSitePermissions($variables);

if (isset ( $_GET ['staffid'] ) and isset ( $_GET ['id'] )) {

	$staffUUID = trim ( $_GET ['staffid'] );
	$eventUUID = trim ( $_GET ['id'] );
	
	$event = $eventDAO->getEvent($eventUUID);
	$staffposition = $staffDAO->getEventStaffEntry($staffUUID);
	
	if( ! $event->getPublished()){
		checkPermissions(array(
				array("privilege" => Privilege::EVENTADMIN),
				array("privilege" => Privilege::EVENTMANAGER, "engine" => $event->getEngine()),
				array("privilege" => Privilege::EVENTPARTICIPENT, "engine" => $event->getEngine()),
				array("user" => $event->getCreator())
		), $variables);
	}
	
	if(isset($event) and isset($staffposition) && ! $event->isCanceled()) {
	    $variables ['showFormular'] = true;
	    
	    $variables ['title'] = "In " . $event->getType()->getType() . " eintragen";
	    $variables ['engines'] = $engineDAO->getEngines();;
    	$variables ['event'] = $event;
    	$variables ['subtitle'] = date($config ["formats"] ["date"], strtotime($event->getDate())) 
    	. " - " . date($config ["formats"] ["time"], strtotime($event->getStartTime())) . " als " . $staffposition->getPosition()->getPosition();
    	
    	if( isset ( $_POST ['user_uuid'] ) ){

    		$user = $userController->getCurrentUser();
    		
    		$informMe = false;
    		if(isset($_POST ['informMe'])){
    			$informMe = true;
    		}
    		
    		if($user){
    			if($user->hasPrivilegeByName(Privilege::EVENTPARTICIPENT)){
    				//if uuid is already in event -> error
    				if( ! $event->isUserAlreadyStaff($user->getUuid())){
    					
    					$result = $eventController->subscribeUser($staffUUID, $user);
    						    						
    					if($result){
    						mail_subscribe_staff_user ( $eventUUID, $user->getUuid(), $informMe);
    						$variables ['successMessage'] = "Als Wachteilnehmer eingetragen - <a href=\"" . $config["urls"]["guardianapp_home"] . "/events/view/" . $event->getUuid() . "\" class=\"alert-link\">Zurück</a>";
    						$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::EventSubscribed, $staffUUID));
    						$variables ['showFormular'] = false;
    						header ( "Location: " . $config["urls"]["guardianapp_home"] . "/events/view/" . $event->getUuid()); // redirects
    							
    					} else if ($result == -1) {
    						$variables ['alertMessage'] = "Eintragen nicht möglich - Position bereits belegt";
    					} else {
    						$variables ['alertMessage'] = "Eintragen fehlgeschlagen";
    					}
    				} else {
    					$variables ['alertMessage'] = "Eintragen nicht möglich - Du besetzt bereits eine Position";
    				}
    			} else {
    				$variables ['alertMessage'] = "Eintragen nicht möglich - Du bist nicht für Wachen freigegeben";
    			}
    		} else {
    			$variables ['alertMessage'] = "Eintragen nicht möglich - Der aktuelle Benutzer wurde nicht gefunden";
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