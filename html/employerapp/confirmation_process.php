<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_confirmation.php";
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/file_create.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Arbeitgebernachweise bearbeiten",
		'secured' => true,
		'privilege' => FFADMINISTRATION
);

if( isset($_POST['confirmation']) ){
	$confirmation_uuid = trim ( $_POST['confirmation'] );
	
	if( isset($_POST['accept']) ){
		$confirmation_uuid = accept_confirmation($confirmation_uuid, $_SESSION ['intranet_userid']);
		createConfirmationFile($confirmation_uuid);
		if($confirmation_uuid){
			if( ! mail_send_confirmation($confirmation_uuid)){
				$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
			}
			$variables ['successMessage'] = "Anfrage akzeptiert";
			insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::ConfirmationAccepted, $confirmation_uuid));
						
		} else {
			$variables ['alertMessage'] = "Anfrage konnte nicht bearbeitet werden";
		}
		
	} else if ( isset($_POST['decline']) ){
		$reason = null;
		if(isset( $_POST ['reason'] ) && !empty( $_POST ['reason'] ) ){
			$reason = trim( $_POST ['reason'] );
		}
		
		$confirmation_uuid = decline_confirmation($confirmation_uuid, $reason, $_SESSION ['intranet_userid']);
		if($confirmation_uuid){
			if( ! mail_send_confirmation_declined($confirmation_uuid)){
				$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
			}
			$variables ['successMessage'] = "Anfrage abgelehnt";
			insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::ConfirmationDeclined, $confirmation_uuid));
			
		} else {
			$variables ['alertMessage'] = "Anfrage konnte nicht bearbeitet werden";
		}
	}
	
}

$open = get_confirmations_with_state(ConfirmationState::Open);
$variables['open'] = $open;

$accepted = get_confirmations_with_state(ConfirmationState::Accepted);
$variables['accepted'] = $accepted;

renderLayoutWithContentFile($config["apps"]["employer"], "confirmationProcess_template.php", $variables);
