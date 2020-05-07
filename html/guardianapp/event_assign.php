<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
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
	$eventUUID = trim ( $_GET ['id'] );
	
	$event = get_event($eventUUID);
	$staffposition = get_events_staffposition($staffUUID);
	
	if(isset($event) and isset($staffposition)) {
	    $variables ['showFormular'] = true;
	    
    	$variables ['title'] = "In " . get_eventtype($event->type)->type . " einteilen";
    	$variables ['engines'] = get_engines ();
    	$variables ['user'] = get_user_of_engine(get_engine_of_user($_SESSION ['guardian_userid']));
    	$variables ['eventUUID'] = $eventUUID;
    	$variables ['staffUUID'] = $staffUUID;
    	$variables ['subtitle'] = date($config ["formats"] ["date"], strtotime($event->date)) 
    	. " - " . date($config ["formats"] ["time"], strtotime($event->start_time)) . " " . $staffposition->position;
        		
    	if (isset ( $_POST ['firstname'] ) and isset ( $_POST ['lastname'] ) && isset ( $_POST ['email'] ) && isset ( $_POST ['engine_hid'] )) {
    		
    		$user_uuid = null;
    		if(isset($_POST ['user_uuid'])){
    			$user_uuid = $_POST ['user_uuid'];
    		}
    		
    		$firstname = trim ( $_POST ['firstname'] );
    		$lastname = trim ( $_POST ['lastname'] );
    		$email = strtolower(trim ( $_POST ['email'] ));
    		$engineUUID = trim ( $_POST ['engine_hid'] );
    		
    		
    		if(!email_in_use($email) ){
    			$user = insert_user ( $firstname, $lastname, $email, $engineUUID );
    		} else {
    			$user = get_user_by_data($firstname, $lastname, $email, $engineUUID);
    		}
    		//if user exists with these name/engine ok - else message: Please select user!
    		if($user){
    			
    			if($user->available){
    				//if uuid is already in event -> error
    				if(!is_user_already_staff($eventUUID, $user->uuid)){
    					
    					if(add_staff_user ( $staffUUID, $user->uuid )){
    						mail_add_staff_user ($eventUUID, $user->uuid);
    						
    						$variables ['successMessage'] = "Wachteilnehmer zugewiesen - <a href=\"" . $config["urls"]["guardianapp_home"] . "/events/" . $eventUUID . "\" class=\"alert-link\">Zurück</a>";
    						$variables ['showFormular'] = false;
    						header ( "Location: " . $config["urls"]["guardianapp_home"] . "/events/".$eventUUID); // redirects
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
    			$variables ['alertMessage'] = "E-Mail-Adresse bereits mit anderem Namen/Zug in Verwendung! Bitte Eingaben kontrollieren oder Auswahl verwenden";
    		}
    	}
	}
} else {
    $variables ['alertMessage'] = "Fehlende Parameter";
}

renderLayoutWithContentFile ($config["apps"]["guardian"], "eventAssign_template.php", $variables );
?>