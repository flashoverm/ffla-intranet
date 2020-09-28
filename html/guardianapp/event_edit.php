<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_event.php";
require_once LIBRARY_PATH . "/db_staffpositions.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_staff_template.php";


$eventtypes = get_eventtypes ();
$staffpositions = get_staffpositions();
$engines = get_engines();

// Pass variables (as an array) to template
$variables = array (
		'secured' => true,
		'eventtypes' => $eventtypes,
        'staffpositions' => $staffpositions,
        'engines' => $engines,
);

if(userLoggedIn()){
	$user = $_SESSION ['intranet_userid'];
	$usersEngine = get_engine_of_user($user);
	
	$variables ['usersEngine'] = $usersEngine;
}

//Display event if uuid is parameter
if (isset($_GET['id'])) {
    
	$variables['title'] = 'Wache bearbeiten';
	
    $uuid = trim($_GET['id']);
    $event = get_event($uuid);
    
    if($event){
    	
    	$dateNow = getdate();
    	$now = strtotime( $dateNow['year']."-".$dateNow['mon']."-".($dateNow['mday']) );
    	if(strtotime($event->date) >= $now){

    		if (userLoggedIn() && is_user_manager_or_creator($event->uuid, $_SESSION['intranet_userid'])){
	            $staff = get_staff($uuid);
	            $variables['event'] = $event;
	            $variables['staff'] = $staff;
	            
	        } else {
	            $variables ['alertMessage'] = "Sie sind nicht berechtigt, diese Wache zu bearbeiten";
	            $variables ['showFormular'] = false;
	        }
	        
    	} else {
    		$variables ['alertMessage'] = "Diese Wache hat bereits stattgefunden - Bearbeitung nicht mehr möglich";
    		$variables ['showFormular'] = false;
    	}
    	
    } else {
        $variables ['alertMessage'] = "Wache nicht gefunden";
        $variables ['showFormular'] = false;
    }
    
} else {
	//Display empty form	
	$variables['title'] = 'Neue Wache anlegen';
}
    
//Update or Insert is set
if (isset ( $_POST ['type'] ) ) {
	
	$date = trim ( $_POST ['date'] );
	$start = trim ( $_POST ['start'] );
	$end = trim ( $_POST ['end'] );
	$type = trim ( $_POST ['type'] );
	$engine = $_POST ['engine'];
	
	if (preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1]).(0[1-9]|1[0-2]).[0-9]{4}$/", $date)) {
	    //European date format -> change to yyyy-mm-dd
	    $date = date_create_from_format('d.m.Y', $date)->format('Y-m-d');
	}
	
	if($end == ""){
		$end = null;
	}
	
	$typeOther = null;
	if(isset( $_POST ['typeOther'] ) && !empty( $_POST ['typeOther'] ) ){
		$typeOther = trim( $_POST ['typeOther'] );
	}
	
	$title = trim ( $_POST ['title'] );
	if(empty ($title)){
	    $title = null;
	}
	
	$comment = "";
	$inform = false;
	$publish = false;
	$staff_confirmation = false;
	
	$creator = $_SESSION ['intranet_userid'];
	
	if (isset ( $_POST ['comment'] )) {
		$comment = trim ( $_POST ['comment'] );
	}
	if(isset($_POST ['inform'])){
	    $inform = true;
	}
	if(isset($_POST ['publish'])){
		$publish = true;
	}
	if(isset($_POST ['confirmation'])){
		$staff_confirmation = true;
	}
	
	if(isset($_POST ['eventid'])){
		$event_uuid = $_POST ['eventid'];
				
		$updateSuccess = update_event ($event_uuid, $date, $start, $end, $type, $typeOther, $title, $comment, $engine, $staff_confirmation);
		
		foreach($staff as $entry):
			if(!isset($_POST [$entry->uuid])){
				//Remove entry from db!
				if($entry->user != null){
					mail_remove_staff_user($entry->uuid, $event_uuid);
				}
				delete_staff_entry($entry->uuid);
		} else {
		    update_staff($entry->uuid, $_POST [$entry->uuid]);
		}
		endforeach;
	} else {
		$event_uuid = insert_event ( $date, $start, $end, $type, $typeOther, $title, $comment, $engine, $creator, $publish, $staff_confirmation);
	}
	
	if($event_uuid){
		$count = $_POST ['positionCount'];
		for ($i = 0; $i <= $count; $i++) {
			if(isset($_POST ["staff" . $i])){
				insert_staff ( $event_uuid, $_POST ["staff" . $i], $staff_confirmation);
			}
		}
	}
	
	if(isset($_POST ['eventid'])){
		if($updateSuccess){
			insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::EventUpdated, $event_uuid));
			$variables ['successMessage'] = "Wache aktualisiert";
			
			if($inform){
				if(!mail_event_updates($event_uuid)){
					$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
				} else{
				    header ( "Location: " . $config["urls"]["guardianapp_home"] . "/events/" . $event_uuid ); // redirects
				}
			} else {
			    header ( "Location: " . $config["urls"]["guardianapp_home"] . "/events/" . $event_uuid ); // redirects
			}
		}
	} else{
		
		if($event_uuid){
			$informMe = false;
			if(isset($_POST ['informMe'])){
				$informMe = true;
			}
			
			if(mail_insert_event ( $event_uuid, $informMe, $publish)){
				insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::EventCreated, $event_uuid));
				$variables ['successMessage'] = "Wache angelegt";
				
				if(isset($_POST ['forwardToEvent']) && $_POST ['forwardToEvent'] == 1){
				    header ( "Location: " . $config["urls"]["guardianapp_home"] . "/events/" . $event_uuid ); // redirects
				} else {
				    $event_uuid = null;
				}
				
				
			} else {
				$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
			}
		} else {
			$variables ['alertMessage'] = "Wache konnte nicht angelegt werden";
		}
	}
	if($event_uuid != null){
	    $event = get_event($event_uuid);
	    $staff = get_staff($event_uuid);
	    $variables['event'] = $event;
	    $variables['staff'] = $staff;
	}
}

renderLayoutWithContentFile ($config["apps"]["guardian"], "eventEdit_template.php", $variables );
?>