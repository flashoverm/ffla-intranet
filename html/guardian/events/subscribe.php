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
$variables = checkPermissions($variables);

if (isset ( $_GET ['staffid'] ) and isset ( $_GET ['id'] )) {

	$staffUUID = trim ( $_GET ['staffid'] );
	$eventUUID = trim ( $_GET ['id'] );
	
	$event = $eventDAO->getEvent($eventUUID);
	$staffposition = $staffDAO->getEventStaffEntry($staffUUID);
	
	if(isset($event) and isset($staffposition)) {
	    $variables ['showFormular'] = true;
	    
	    $variables ['title'] = "In " . $event->getType()->getType() . " eintragen";
	    $variables ['engines'] = $engineDAO->getEngines();;
    	$variables ['event'] = $event;
    	$variables ['subtitle'] = date($config ["formats"] ["date"], strtotime($event->getDate())) 
    	. " - " . date($config ["formats"] ["time"], strtotime($event->getStartTime())) . " als " . $staffposition->getPosition()->getPosition();
    	
    	if(userLoggedIn()){
    		$variables['currentUser'] = $userController->getCurrentUser();
    	}else{
    		$actual_link = "{$_SERVER['REQUEST_URI']}";
    		$_SESSION["ref"] = $actual_link;
    		$variables['infoMessage'] = "Haben Sie bereits einen Benutzer? <a href='" . $config ["urls"] ["intranet_home"] . "/login'>Anmelden</a>";
    	}
    
    	if (isset ( $_POST ['firstname'] ) and isset ( $_POST ['lastname'] ) && isset ( $_POST ['email'] ) && isset ( $_POST ['engine'] )) {
    
    		$firstname = trim ( $_POST ['firstname'] );
    		$lastname = trim ( $_POST ['lastname'] );
    		$email = strtolower(trim ( $_POST ['email'] ));
    		$engineUUID = trim ( $_POST ['engine'] );
    		
    		$informMe = false;
    		if(isset($_POST ['informMe'])){
    			$informMe = true;
    		}
    		
    		if(userLoggedIn()){
    			$user = $variables['currentUser'];
    		} else {
    			if(! $guardianUserController->isEmailInUse($email) ){
    				$engine = $engineDAO->getEngine($engineUUID);
    				$user = $guardianUserController->insertEventParticipant($firstname, $lastname, $email, $engine);
    			} else {
    				$user = $userDAO->getUserByData($firstname, $lastname, $email, $engineUUID);
    			}
    		}
    		
    		//if user exists with these name/engine ok - else error!
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
    					$variables ['alertMessage'] = "Eintragen nicht möglich - Sie besetzen bereits eine Position";
    				}
    			} else {
    				$variables ['alertMessage'] = "Eintragen nicht möglich - Sie sind nicht für Wachen freigegeben";
    			}
    		} else {
    			$variables ['alertMessage'] = "E-Mail-Adresse bereits mit anderem Namen/Zug in Verwendung! Bitte Eingaben kontrollieren";
    		}
    	}
	}
} else {
    $variables ['alertMessage'] = "Fehlende Parameter";
}

renderLayoutWithContentFile ( $variables );
?>