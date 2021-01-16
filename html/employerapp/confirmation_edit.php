<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Arbeitgebernachweis beantragen",
		'secured' => true,
);

$confirmation = new Confirmation();
if(isset($_GET ['id'])) {
	$variables ['title'] = 'Arbeitgebernachweis bearbeiten';
	$confirmation = $confirmationDAO->getConfirmation($_GET ['id']);
	$variables ['confirmation'] = $confirmation;
}

if( isset($_POST['date']) && isset($_POST['start']) && isset($_POST['end']) ){
	
	if(! isset($_GET ['id']) 
			|| (isset($_GET ['id']) && $variables ['confirmation']->getState() != Confirmation::ACCEPTED) ){
		
		$date = trim ( $_POST ['date'] );
		$beginn = trim ( $_POST ['start'] );
		$end = trim ( $_POST ['end'] );
		
		$description = null;
		if(isset( $_POST ['description'] ) && !empty( $_POST ['description'] ) ){
			$description = trim( $_POST ['description'] );
		}
		
		$previousState = $confirmation->getState();
		
		$confirmation->setState(Confirmation::OPEN);
		$confirmation->setReason(NULL);
		
		if(isset($_GET ['id'])) {
			$confirmation->setConfirmationData($date, $beginn, $end, $description, $userController->getCurrentUser());
			$confirmation = $confirmationDAO->save($confirmation);
			
			if($confirmation){
				if($previousState == Confirmation::DECLINED){
					if( ! mail_send_confirmation_request($confirmation)){
						$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
					}
				}
				$variables ['successMessage'] = "Anfrage aktualisiert";
				$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ConfirmationUpdated, $confirmation->getUuid()));
				header ( "Location: " . $config["urls"]["employerapp_home"] . "/confirmations"); // redirects
			} else {
				$variables ['alertMessage'] = "Anfrage konnte nicht aktualisiert werden";
			}
		} else {
			$confirmation->setConfirmationData($date, $beginn, $end, $description, $userController->getCurrentUser());
			$confirmation = $confirmationDAO->save($confirmation);
						
			if($confirmation){
				if( ! mail_send_confirmation_request($confirmation)){
					$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
				}
				$variables ['successMessage'] = "Anfrage gespeichert";
				$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ConfirmationRequested, $confirmation->getUuid()));
				header ( "Location: " . $config["urls"]["employerapp_home"] . "/confirmations"); // redirects
				
			} else {
				$variables ['alertMessage'] = "Anfrage konnte nicht gespeichert werden";
			}
		}
		
	} else {
		$variables ['showFormular'] = false;
		$variables ['errorMessage'] = "Angenommene Anträge können nicht mehr bearbeitet werden.";
	}
}

renderLayoutWithContentFile($config["apps"]["employer"], "confirmationEdit_template.php", $variables);
