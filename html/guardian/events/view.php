<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

if (! isset($_GET['id'])) {

    // Pass variables (as an array) to template
    $variables = array(
    		'app' => $config["apps"]["guardian"],
    		'template' => "eventDetails/eventDetails_template.php",
	        'title' => 'Wache kann nicht angezeigt werden',
	        'secured' => false,
	        'showFormular' => false,
	        'alertMessage' => "Wache kann nicht angezeigt werden"
    );
    checkSitePermissions($variables);
    
} else {
    $uuid = trim($_GET['id']);
    $event = $eventDAO->getEvent($uuid);
    
    if($event){
    	
        $isCreator = false;
        $otherEngine = null;
        if (SessionUtil::userLoggedIn()) {
    		$isCreator = (strcmp($event->getCreator()->getUuid(), SessionUtil::getCurrentUserUUID()) == 0);
    		
    		if(strcmp($userController->getCurrentUser()->getEngine()->getUuid(), $event->getEngine()->getUuid()) != 0){
    			$otherEngine = $event->getEngine();
    		 
    		}
    	}
    	
    	// Pass variables (as an array) to template
    	$variables = array(
    			'app' => $config["apps"]["guardian"],
    			'template' => "eventDetails/eventDetails_template.php",
    			'title' => $event->getType()->getType(),
    			'secured' => true,
    			'showFormular' => true,
    	        'isCreator' => $isCreator,
    	        'otherEngine' => $otherEngine
    	);
    	checkSitePermissions($variables);
    	
    	if( ! $event->getPublished()){
    		checkPermissions(array(
    				array("privilege" => Privilege::EVENTADMIN),
    				array("privilege" => Privilege::EVENTMANAGER, "engine" => $event->getEngine()),
    				array("privilege" => Privilege::EVENTPARTICIPENT, "engine" => $event->getEngine()),
    				array("user" => $event->getCreator())
    		), $variables);
    	}
    	
    	if($event->getTypeOther() != null){
    		$variables['subtitle'] = $event->getTypeOther();
    	}
    	
    	if(isset($_GET['acknowledge'])){
    		$staffUuid = $_GET['acknowledge'];
    	}
    	if(isset($_POST['acknowledgeID'])){
    		$staffUuid = trim($_POST['acknowledgeID']);
    	}
    	if(isset($staffUuid)){
    		$staffEntry = $staffDAO->getEventStaffEntry($staffUuid);
    		if($currentUser->getUuid() == $staffEntry->getUser()->getUuid()){
    			if($eventController->acknowledgeStaffUser($staffUuid)){
    				$variables['successMessage'] = "Die Wachteilnahme wurde zur Kenntnis genommen";
    			} else {
    				$variables['alertMessage'] = "Kenntnisnahme nicht möglich";
    			}
    		} else {
    			$variables['alertMessage'] = "Kenntnisnahme nur bei eigenem Eintrag möglich";
    		}
    	}
    	
    	if (isset($_POST['removestaffid'])) {
    		// Remove by manager
    		checkPermissions(array(
    				array("privilege" => Privilege::EVENTADMIN),
    				array("privilege" => Privilege::EVENTMANAGER, "engine" => $event->getEngine()),
    				array("user" => $event->getCreator())
    		), $variables);
    		
    		$staff_uuid = trim($_POST['removestaffid']);
    		mail_remove_staff_user($staff_uuid, $uuid);
    		$log = LogbookEntry::fromAction(LogbookActions::EventUnscribed, $staff_uuid);
    		if($eventController->removeUser($staff_uuid)){
    			$logbookDAO->save($log);
    			$variables['successMessage'] = "Personal-Eintrag entfernt";
    		} else {
    			$variables['alertMessage'] = "Eintrag konnte nicht entfernt werden";
    		}
    	}
    	
    	if (isset($_POST['removestaffbyuserid'])) {
    		// Remove by user itself
    		$staff_uuid = trim($_POST['removestaffbyuserid']);
    		$staffEntry = $staffDAO->getEventStaffEntry($staff_uuid);
    		
    		if($staffEntry->getUser() != null && SessionUtil::getCurrentUserUUID() == $staffEntry->getUser()->getUuid()){
    			$log = LogbookEntry::fromAction(LogbookActions::EventUnscribedByUser, $staff_uuid);
    			if($eventController->removeUser($staff_uuid)){
    				mail_unscribe_staff_user($staff_uuid, $uuid);
    				$logbookDAO->save($log);
    				$variables['successMessage'] = "Personal-Eintrag entfernt";
    			} else {
    				$variables['alertMessage'] = "Eintrag konnte nicht entfernt werden";
    			}
    		} else {
    			$variables['alertMessage'] = "Eintrag konnte nicht entfernt werden - Andere Benutzer können nicht entfernt werden";
    		}
    	}
    	
    	if (isset($_POST['confirmstaffid'])) {
    		checkPermissions(array(
    				array("privilege" => Privilege::EVENTADMIN),
    				array("privilege" => Privilege::EVENTMANAGER, "engine" => $event->getEngine()),
    				array("user" => $event->getCreator())
    		), $variables);
    		
    		$staff_uuid = trim($_POST['confirmstaffid']);
    		mail_confirm_staff_user($staff_uuid, $uuid);
    		if($eventController->confirmStaffUser($staff_uuid)){
    			$variables['successMessage'] = "Personal bestätigt";
    			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::EventStaffConfirmed, $staff_uuid));
    		} else {
    			$variables['alertMessage'] = "Personal konnte nicht bestätigt werden";
    		}
    	}
    	
    	if (isset($_POST['publish']) && $event->getEngine() != NULL) {
    		checkPermissions(array(
    				array("privilege" => Privilege::EVENTADMIN),
    				array("privilege" => Privilege::EVENTMANAGER, "engine" => $event->getEngine()),
    				array("user" => $event->getCreator())
    		), $variables);
    		
    		$event = $eventController->publishEvent($uuid);
    		if($event){
    			mail_publish_event($event);
    			$variables['successMessage'] = "Wache veröffentlich - Mögliche Wachteilnehmer und Wachbeauftrage werden informiert";
    			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::EventPublished, $uuid));
    		} else {
    			$variables['alertMessage'] = "Wache konnte nicht veröffentlicht werden";
    		}
    	}
    	
    	//if cancel
    	//cancelEvent
    	//log LogbookActions::EventCanceled
    	if (isset($_POST['reason'])) {
    	    checkPermissions(array(
    	        array("privilege" => Privilege::EVENTADMIN),
    	        array("privilege" => Privilege::EVENTMANAGER, "engine" => $event->getEngine()),
    	        array("user" => $event->getCreator())
    	    ), $variables);
    	    
    	    $reason = trim($_POST['reason']);
    	    if($eventController->cancelEvent($uuid, $reason)){
    	        mail_cancel_event($event->getUuid());
    	        $variables['successMessage'] = "Wache abgesagt - Teilnehmer informiert";
    	        $logbookDAO->save(LogbookEntry::fromAction(LogbookActions::EventCanceled, $uuid));
    	    } else {
    	        $variables['alertMessage'] = "Wache konnte nicht abgesagt werden";
    	    }
    	}
    	
    	$variables['event'] = $eventDAO->getEvent($event->getUuid());
    } else {
    	// Pass variables (as an array) to template
    	$variables = array(
    			'app' => $config["apps"]["guardian"],
    			'template' =>"eventDetails/eventDetails_template.php",
    			'title' => 'Wache nicht gefunden',
    			'secured' => false,
    			'showFormular' => false,
    			'alertMessage' => "Wache nicht gefunden"
    	);
    	checkSitePermissions($variables);
    	
    }
}

if(isset($_GET['print'])){
	
	$variables['app'] = $config["apps"]["guardian"];
	$variables['template'] = "eventDetails/eventPrint_template.php";
	$variables['showFormular'] = true;
	$variables['orientation'] = 'portrait';
	$variables['print'] = true;
	renderPrintContentFile($variables);

} else {
	renderLayoutWithContentFile($variables);
}
?>