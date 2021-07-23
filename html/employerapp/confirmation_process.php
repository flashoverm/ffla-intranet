<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/file_create.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["employer"],
		'template' => "confirmationProcess_template.php",
		'title' => "Arbeitgebernachweise bearbeiten",
		'secured' => true,
		'privilege' => Privilege::FFADMINISTRATION
);
$variables = checkPermissions($variables);

if( isset($_POST['confirmation']) ){
	$confirmationUuid = trim ( $_POST['confirmation'] );
	
	if( isset($_POST['accept']) ){
		$confirmation = $confirmationController->acceptConfirmation($confirmationUuid, $userController->getCurrentUser());
		if($confirmation){
			createConfirmationFile($confirmation->getUuid());
			if( ! mail_send_confirmation($confirmation)){
				$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
			}
			$variables ['successMessage'] = "Anfrage akzeptiert";
			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ConfirmationAccepted, $confirmation->getUuid()));
						
		} else {
			$variables ['alertMessage'] = "Anfrage konnte nicht bearbeitet werden";
		}
		
	} else if ( isset($_POST['decline']) ){
		$reason = null;
		if(isset( $_POST ['reason'] ) && !empty( $_POST ['reason'] ) ){
			$reason = trim( $_POST ['reason'] );
		}
		
		$confirmation = $confirmationController->declineConfirmation($confirmationUuid, $reason, $userController->getCurrentUser());
		
		if($confirmation){
			if( ! mail_send_confirmation_declined($confirmation)){
				$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
			}
			$variables ['successMessage'] = "Anfrage abgelehnt";
			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ConfirmationDeclined, $confirmation->getUuid()));
			
		} else {
			$variables ['alertMessage'] = "Anfrage konnte nicht bearbeitet werden";
		}
	}
	
}

if( isset( $_GET["accepted"] ) ){
	$variables ['tab'] = 'accepted';
	$variables['confirmations'] = $confirmationDAO->getConfirmationsByState(Confirmation::ACCEPTED);
} else if ( isset( $_GET["declined"] ) ){
	$variables ['tab'] = 'declined';
	$variables['confirmations'] = $confirmationDAO->getConfirmationsByState(Confirmation::DECLINED);
} else if ( isset( $_GET["all"] ) ){
	$variables ['tab'] = 'all';
	$variables['confirmations'] = $confirmationDAO->getConfirmations();
} else {
	$variables ['tab'] = 'open';
	$variables['confirmations'] = $confirmationDAO->getConfirmationsByState(Confirmation::OPEN);
}

renderLayoutWithContentFile($variables);
