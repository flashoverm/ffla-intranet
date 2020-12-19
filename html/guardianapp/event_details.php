<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

if (! isset($_GET['id'])) {

    // Pass variables (as an array) to template
    $variables = array(
        'title' => 'Wache kann nicht angezeigt werden',
        'secured' => false,
        'showFormular' => false,
        'alertMessage' => "Wache kann nicht angezeigt werden"
    );
} else {
    $uuid = trim($_GET['id']);
    $event = get_event($uuid);
    
    if($event){
    	
        $isCreator = false;
        $otherEngine = null;
    	if (userLoggedIn()) {
    		$isCreator = (strcmp($event->creator, $_SESSION['intranet_userid']) == 0);
    		
    		if(strcmp($userController->getCurrentUser()->getEngine()->getUuid(), $event->engine) != 0){
    		    $otherEngine = $engineDAO->getEngine($event->engine);
    		 
    		}
    	}
    	
    	// Pass variables (as an array) to template
    	$variables = array(
    			'title' => get_eventtype($event->type)->type,
    			'secured' => false,
    			'showFormular' => true,
    	        'isCreator' => $isCreator,
    	        'otherEngine' => $otherEngine
    	);
    	
    	if($event->type_other != null){
    		$variables['subtitle'] = $event->type_other;
    	}
    	
    	if (isset($_POST['removestaffid'])) {
    		$staff_uuid = trim($_POST['removestaffid']);
    		mail_remove_staff_user($staff_uuid, $uuid);
    		$log = LogbookEntry::fromAction(LogbookActions::EventUnscribed, $staff_uuid);
    		if(remove_staff_user($staff_uuid)){
    			$staffpos = get_events_staffposition($staff_uuid);
    			$logbookDAO->save($log);
    			$variables['successMessage'] = "Personal-Eintrag entfernt";
    		} else {
    			$variables['alertMessage'] = "Eintrag konnte nicht entfernt werden";
    		}
    	}
    	
    	if (isset($_POST['removestaffbyuserid'])) {
    		$staff_uuid = trim($_POST['removestaffbyuserid']);
    		mail_unscribe_staff_user($staff_uuid, $uuid);
    		$staffpos = get_events_staffposition($staff_uuid);
    		$log = LogbookEntry::fromAction(LogbookActions::EventUnscribedByUser, $staff_uuid);
    		if(remove_staff_user($staff_uuid)){
    			$logbookDAO->save($log);
    			$variables['successMessage'] = "Personal-Eintrag entfernt";
    		} else {
    			$variables['alertMessage'] = "Eintrag konnte nicht entfernt werden";
    		}
    	}
    	
    	if (isset($_POST['confirmstaffid'])) {
    		$staff_uuid = trim($_POST['confirmstaffid']);
    		mail_confirm_staff_user($staff_uuid, $uuid);
    		if(confirm_staff_user($staff_uuid)){
    			$variables['successMessage'] = "Personal bestätigt";
    			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::EventStaffConfirmed, $staff_uuid));
    		} else {
    			$variables['alertMessage'] = "Personal konnte nicht bestätigt werden";
    		}
    	}
    	
    	if (isset($_POST['publish']) && $event->engine != NULL) {
    		if(publish_event($uuid) ){
    			$event = get_event($uuid);
    			mail_publish_event($event);
    			$variables['successMessage'] = "Wache veröffentlich - Wachbeauftragte informiert";
    			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::EventPublished, $uuid));
    			$event = get_event($uuid);
    		} else {
    			$variables['alertMessage'] = "Wache konnte nicht veröffentlicht werden";
    		}
    	}
    	
    	$staff = get_staff($uuid);
    	$variables['event'] = $event;
    	$variables['staff'] = $staff;
    } else {
    	// Pass variables (as an array) to template
    	$variables = array(
    			'title' => 'Wache nicht gefunden',
    			'secured' => false,
    			'showFormular' => false,
    			'alertMessage' => "Wache nicht gefunden"
    	);
    }
}

if(isset($_GET['print'])){
	
	$variables['showFormular'] = true;
	$variables['orientation'] = 'portrait';
	renderPrintContentFile($config["apps"]["guardian"], "eventDetails/eventPrint_template.php", $variables);

} else {
	renderLayoutWithContentFile($config["apps"]["guardian"], "eventDetails/eventDetails_template.php", $variables);
}
?>