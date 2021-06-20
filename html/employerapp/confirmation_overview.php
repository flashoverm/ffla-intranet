<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Eigene Arbeitgebernachweise",
		'secured' => true,
);

if( isset( $_POST['withdraw'] ) ){
	//create logentry
	$confirmationUuid = $_POST['withdraw'];
	$log = LogbookEntry::fromAction(LogbookActions::ConfirmationWithdraw, $confirmationUuid);
	
	if( $confirmationDAO->deleteConfirmation($confirmationUuid) ){
		$logbookDAO->save($log);
		$variables ['successMessage'] = "Anfrage zurückgezogen";
	} else {
		$variables ['alertMessage'] = "Anfrage konnte nicht zurückgezogen werden";
	}
}

if( isset( $_GET["accepted"] ) ){
	$variables ['tab'] = 'accepted';
	$variables['confirmations'] = $confirmationDAO->getConfirmationsByStateAndUser(Confirmation::ACCEPTED, getCurrentUserUUID());
	
} else {
	$variables ['tab'] = 'open';
	$variables['confirmations'] = $confirmationDAO->getConfirmationsByStateAndUser(Confirmation::OPEN, getCurrentUserUUID());
	$variables['declined'] = $confirmationDAO->getConfirmationsByStateAndUser(Confirmation::DECLINED, getCurrentUserUUID());
}

renderLayoutWithContentFile($config["apps"]["employer"], "confirmationOverview_template.php", $variables);
