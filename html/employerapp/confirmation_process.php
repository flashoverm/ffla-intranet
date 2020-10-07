<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_confirmation.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Arbeitgebernachweise bearbeiten",
		'secured' => true,
		'privilege' => FFADMINISTRATION
);

if( isset($_POST['confirmation']) ){
	$confirmation_uuid = trim ( $_POST['confirmation'] );
	
	if( isset($_POST['accept']) ){
		accept_confirmation($confirmation_uuid, $_SESSION ['intranet_userid']);
		//TODO log, info and mail (to special address)
		
	} else if ( isset($_POST['decline']) ){
		$reason = null;
		if(isset( $_POST ['reason'] ) && !empty( $_POST ['reason'] ) ){
			$reason = trim( $_POST ['reason'] );
		}
		decline_confirmation($confirmation_uuid, $reason, $_SESSION ['intranet_userid']);
		//TODO log, info and mail (to user)
	}
	
}

$open = get_confirmations_with_state(ConfirmationState::Open);
$variables['open'] = $open;

$accepted = get_confirmations_with_state(ConfirmationState::Accepted);
$variables['accepted'] = $accepted;

renderLayoutWithContentFile($config["apps"]["employer"], "confirmationProcess_template.php", $variables);
