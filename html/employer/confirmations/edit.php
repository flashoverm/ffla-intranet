<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["employer"],
		'template' => "confirmationEdit_template.php",
		'title' => "Arbeitgebernachweis beantragen",
		'secured' => true,
);
checkSitePermissions($variables);

$confirmation = new Confirmation();
if(isset($_GET ['id'])) {
	$variables ['title'] = 'Arbeitgebernachweis bearbeiten';
	$confirmation = $confirmationDAO->getConfirmation($_GET ['id']);
	$variables ['confirmation'] = $confirmation;
	
	checkPermissions(array("user" => $confirmation->getUser()), $variables);
}

if( isset($_POST['date']) && isset($_POST['start']) && isset($_POST['end']) ){
	
	if(! isset($_GET ['id'])
			|| (isset($_GET ['id']) && $variables ['confirmation']->getState() != Confirmation::ACCEPTED) ){
		
		$date = trim ( $_POST ['date'] );
		
		if (preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1]).(0[1-9]|1[0-2]).[0-9]{4}$/", $date)) {
			//European date format -> change to yyyy-mm-dd
			$date = date_create_from_format('d.m.Y', $date)->format('Y-m-d');
		}
		
		$beginn = trim ( $_POST ['start'] );
		$end = trim ( $_POST ['end'] );
		
		$description = null;
		if(isset( $_POST ['description'] ) && !empty( $_POST ['description'] ) ){
			$description = trim( $_POST ['description'] );
		}
		
		$previousAssignedTo = $confirmation->getAssignedTo();
		
		if(isset($_POST ['assign']) && isset($_POST ['assignedTo'])){
		    $confirmation->setAssignedTo($userDAO->getUserByUUID($_POST ['assignedTo']));
		} else {
		    $confirmation->setAssignedTo(null);
		}
		
		$previousState = $confirmation->getState();
		
		$confirmation->setState(Confirmation::OPEN);
		$confirmation->setReason(null);

		$confirmation->setConfirmationData($date, $beginn, $end, $description, $userController->getCurrentUser());
		$confirmation = $confirmationDAO->save($confirmation);
		
		if(isset($_GET ['id'])) {
		    //Update
			if($confirmation){
			    //If request is resubmitted or the assignee changed, send a info mail
			    if($previousState == Confirmation::DECLINED || $previousAssignedTo != $confirmation->getAssignedTo()){
					if( ! mail_send_confirmation_request($confirmation)){
						$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
					}
				}
				$variables ['successMessage'] = "Anfrage aktualisiert";
				$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ConfirmationUpdated, $confirmation->getUuid()));
				header ( "Location: " . $config["urls"]["employerapp_home"] . "/confirmations/overview"); // redirects
			} else {
				$variables ['alertMessage'] = "Anfrage konnte nicht aktualisiert werden";
			}
		} else {
		    //Insert
			if($confirmation){
				if( ! mail_send_confirmation_request($confirmation)){
					$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
				}
				$variables ['successMessage'] = "Anfrage gespeichert";
				$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ConfirmationRequested, $confirmation->getUuid()));
				header ( "Location: " . $config["urls"]["employerapp_home"] . "/confirmations/overview"); // redirects
				
			} else {
				$variables ['alertMessage'] = "Anfrage konnte nicht gespeichert werden";
			}
		}
		
	} else {
		$variables ['showFormular'] = false;
		$variables ['errorMessage'] = "Angenommene Anträge können nicht mehr bearbeitet werden.";
	}
}

renderLayoutWithContentFile($variables);
