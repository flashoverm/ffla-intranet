<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";


$eventtypes = $eventTypeDAO->getEventTypes();
$staffpositions = $staffPositionDAO->getStaffPositions();
$engines = $engineDAO->getEngines();

// Pass variables (as an array) to template
$variables = array (
		'secured' => true,
		'eventtypes' => $eventtypes,
        'staffpositions' => $staffpositions,
        'engines' => $engines,
);

//Display event if uuid is parameter
if (isset($_GET['id'])) {
    
	$variables['title'] = 'Wache bearbeiten';
	
    $uuid = trim($_GET['id']);
    $event = $eventDAO->getEvent($uuid);
    
    if($event){
    	
    	$dateNow = getdate();
    	$now = strtotime( $dateNow['year']."-".$dateNow['mon']."-".($dateNow['mday']) );

		if (userLoggedIn() && $eventController->isUserManagerOrCreator(getCurrentUserUUID(), $event->getUuid())){
			$variables['event'] = $event;
	            
		} else {
			$variables ['alertMessage'] = "Sie sind nicht berechtigt, diese Wache zu bearbeiten";
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
	$typeUuid = trim ( $_POST ['type'] );
	$type = $eventTypeDAO->getEventType($typeUuid);
	$engineUuid = $_POST ['engine'];
	$engine = $engineDAO->getEngine($engineUuid);
	
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
	
	$creator = $userController->getCurrentUser();

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
	$informMe = false;
	if(isset($_POST ['informMe'])){
		$informMe = true;
	}
	
	$staffDeleted = array();
	if(isset($_POST ['staffDeleted'])){
		$staffDeleted = $_POST ['staffDeleted'];
	}
	
	if (isset($_GET['id'])) {
		//update 
		$event = $variables['event'];
	} else {
		//insert
		$event = new Event();
		$event->setCreator($creator);
	}
			
	$event->setEventData($date, $start, $end, $type, $typeOther, $title, $comment, $engine, $staff_confirmation);
	
	//get staff list
	$staff = array();
	for ($i = 0; $i <= $_POST['positionCount']; $i++) {
		if(isset($_POST['staff'][$i])){
			$object = new Staff();
			if(isset($_POST['staff'][$i]['uuid'])){
				$object->setUnconfirmed($_POST['staff'][$i]['unconfirmed']);
			}
			if(isset($_POST['staff'][$i]['uuid'])){
				$object->setUuid($_POST['staff'][$i]['uuid']);
			}
			if(isset($_POST['staff'][$i]['user'])){
				$object->setUser($userDAO->getUserByUUID($_POST['staff'][$i]['user']));
			}
			$object->setPosition($staffPositionDAO->getStaffPosition($_POST['staff'][$i]['position']));
			$staff[] = $object;
		}
	}
	$event->setStaff($staff);
	
	//insert or update event and staff
	$event = $eventController->saveEvent($event, $staffDeleted);
	
	//result handling
	if (isset($_GET['id'])) {
		//update 
		if($event){
			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::EventUpdated, $event->getUuid()));
			$variables ['successMessage'] = "Wache aktualisiert";
			
			if($inform){
				if(!mail_event_updates($event->getUuid())){
					$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
				} else {
					header ( "Location: " . $config["urls"]["guardianapp_home"] . "/events/" . $event->getUuid() ); // redirects
				}
			} else {
				header ( "Location: " . $config["urls"]["guardianapp_home"] . "/events/" . $event->getUuid() ); // redirects
			}
			
		} else {
			$variables ['alertMessage'] = "Wache konnte nicht aktualisiert werden";
		}
		$variables['event'] = $eventDAO->getEvent($_GET['id']);
	} else {
		//insert
		if($event){
			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::EventCreated, $event->getUuid()));
			$variables ['successMessage'] = "Wache angelegt";
			$variables['event'] = $eventDAO->getEvent($event->getUuid());
			
			if(!mail_insert_event ( $event->getUuid(), $informMe, $publish)){
				$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
			} else {
				if( ! isset($_POST ['forwardToEvent']) || $_POST ['forwardToEvent'] != 1){
					unset($variables['event']);
				} else {
					header ( "Location: " . $config["urls"]["guardianapp_home"] . "/events/" . $event->getUuid() ); // redirects
				}
			}

		} else {
			$variables ['alertMessage'] = "Wache konnte nicht angelegt werden";
		}
	}
}

renderLayoutWithContentFile ($config["apps"]["guardian"], "eventEdit_template.php", $variables );
?>