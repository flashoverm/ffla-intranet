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
	$confirmation_uuid = $_POST['withdraw'];
	$log = LogbookEntry::fromAction(LogbookActions::ConfirmationWithdraw, $confirmation_uuid);
	if( delete_confirmation( $confirmation_uuid ) ){
		insert_logbook_entry($log);
		$variables ['successMessage'] = "Anfrage zurückgezogen";
	} else {
		$variables ['alertMessage'] = "Anfrage konnte nicht zurückgezogen werden";
	}
}

$declined = get_confirmations_of_user_with_state($_SESSION ['intranet_userid'], ConfirmationState::Declined);
$variables['declined'] = $declined;

$open = get_confirmations_of_user_with_state($_SESSION ['intranet_userid'], ConfirmationState::Open);
$variables['open'] = $open;

$accepted = get_confirmations_of_user_with_state($_SESSION ['intranet_userid'], ConfirmationState::Accepted);
$variables['accepted'] = $accepted;

renderLayoutWithContentFile($config["apps"]["employer"], "confirmationOverview_template.php", $variables);
