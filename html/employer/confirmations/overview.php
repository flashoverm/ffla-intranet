<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["employer"],
		'template' => "confirmationOverview_template.php",
		'title' => "Eigene Arbeitgebernachweise",
		'secured' => true,
);
$variables = checkSitePermissions($variables);

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
	$variables['confirmations'] = $confirmationDAO->getConfirmationsByStateAndUser(Confirmation::ACCEPTED, SessionUtil::getCurrentUserUUID(), $_GET);
	
} else {
	$variables ['tab'] = 'open';
	$variables['confirmations'] = $confirmationDAO->getConfirmationsByStateAndUser(Confirmation::OPEN, SessionUtil::getCurrentUserUUID(), $_GET);
	$variables['declined'] = $confirmationDAO->getConfirmationsByStateAndUser(Confirmation::DECLINED, SessionUtil::getCurrentUserUUID(), $_GET);
}

renderLayoutWithContentFile($variables);
