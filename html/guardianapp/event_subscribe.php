<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_event.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array (
    'title' => 'Wache/Position nicht vorhanden',
    'secured' => false,
    'showFormular' => false
);

if (isset ( $_GET ['staffid'] ) and isset ( $_GET ['id'] )) {

	$staffUUID = trim ( $_GET ['staffid'] );
	$engines = get_engines ();
	$eventUUID = trim ( $_GET ['id'] );
	
	$event = get_event($eventUUID);
	$staffposition = get_events_staffposition($staffUUID);
	
	if(isset($event) and isset($staffposition)) {
	    $variables ['showFormular'] = true;
	    
    	$variables ['title'] = "In " . get_eventtype($event->type)->type . " eintragen";
    	$variables ['engines'] = $engines;
    	$variables ['event'] = $event;
    	$variables ['staffUUID'] = $staffUUID;
    	$variables ['subtitle'] = date($config ["formats"] ["date"], strtotime($event->date)) 
    	. " - " . date($config ["formats"] ["time"], strtotime($event->start_time)) . " als " . $staffposition->position;
    	if(userLoggedIn()){
    		$variables['currentUser'] = get_user($_SESSION ['intranet_userid']);
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

    		if(!email_in_use($email) ){
    			$user = inset_participant_only ( $firstname, $lastname, $email, $engineUUID );
    		} else {
    			$user = get_user_by_data($firstname, $lastname, $email, $engineUUID);
    		}
    		//if user exists with these name/engine ok - else error!
    		if($user){
    				
    			if(user_has_privilege($user->uuid, EVENTPARTICIPENT)){
    				//if uuid is already in event -> error
    				if(!is_user_already_staff($eventUUID, $user->uuid)){
    						
    					$result = subscribe_staff_user ( $staffUUID, $user->uuid );
    						
    					if($result == 1){
    							
    						mail_subscribe_staff_user ( $eventUUID, $user->uuid, $informMe);
    						$variables ['successMessage'] = "Als Wachteilnehmer eingetragen - <a href=\"" . $config["urls"]["guardianapp_home"] . "/events/" . $eventUUID . "\" class=\"alert-link\">Zurück</a>";
    						
    						insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::EventSubscribed, $staffUUID));
    						$variables ['showFormular'] = false;
    						header ( "Location: " . $config["urls"]["guardianapp_home"] . "/events/".$eventUUID); // redirects
    							
    					} else if ($result == 0) {
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

renderLayoutWithContentFile ($config["apps"]["guardian"], "eventSubscribe_template.php", $variables );
?>