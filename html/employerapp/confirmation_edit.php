<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_confirmation.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Arbeitgebernachweis beantragen",
		'secured' => true,
);

if(isset($_GET ['id'])) {
	$variables ['title'] = 'Arbeitgebernachweis bearbeiten';
	$variables ['confirmation'] = get_confirmation($_GET ['id']);
}

if( isset($_POST['date']) && isset($_POST['start']) && isset($_POST['end']) ){
	
	if(! isset($_GET ['id']) 
			|| (isset($_GET ['id']) && $variables ['confirmation']->state != ConfirmationState::Accepted) ){
		
		$date = trim ( $_POST ['date'] );
		$beginn = trim ( $_POST ['start'] );
		$end = trim ( $_POST ['end'] );
		
		$description = null;
		if(isset( $_POST ['description'] ) && !empty( $_POST ['description'] ) ){
			$description = trim( $_POST ['description'] );
		}
		
		if(isset($_GET ['id'])) {
			$confirmation_uuid = update_confirmations($_GET ['id'], $date, $beginn, $end, $description);
			if($confirmation_uuid){
				$variables ['successMessage'] = "Anfrage aktualisiert";
				insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::ConfirmationUpdated, $confirmation_uuid));
				header ( "Location: " . $config["urls"]["employerapp_home"] . "/confirmations"); // redirects
			} else {
				$variables ['alertMessage'] = "Anfrage konnte nicht aktualisiert werden";
			}
		} else {
			$confirmation_uuid = create_confirmation($date, $beginn, $end, $description, $_SESSION ['intranet_userid']);
			
			if($confirmation_uuid){
				if(mail_send_confirmation_request($confirmation_uuid)){
					$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
				}
				$variables ['successMessage'] = "Anfrage gespeichert";
				insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::ConfirmationRequested, $confirmation_uuid));
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
