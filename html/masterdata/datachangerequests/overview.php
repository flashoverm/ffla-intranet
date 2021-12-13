<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["masterdata"],
		'template' => "dataChangeOverview_template.php",
		'title' => "Eigene Stammdatenänderungen",
		'secured' => true,
);
$variables = checkSitePermissions($variables);

if( isset( $_POST['withdraw'] ) ){
	//create logentry
	$dataChangeRequestUuid = $_POST['withdraw'];
	$log = LogbookEntry::fromAction(LogbookActions::DataChangeWithdraw, $dataChangeRequestUuid);
	
	if( $dataChangeRequestDAO->deleteDataChangeRequest($dataChangeRequestUuid) ){
		$logbookDAO->save($log);
		$variables ['successMessage'] = "Anfrage zurückgezogen";
	} else {
		$variables ['alertMessage'] = "Anfrage konnte nicht zurückgezogen werden";
	}
}

if( isset( $_GET["done"] ) ){
	$variables ['tab'] = 'done';
	$variables['dataChangeRequests'] = $dataChangeRequestDAO->getDataChangeRequestsByStateAndUser(DataChangeRequest::DONE, getCurrentUserUUID(), $_GET);
	
} else if (isset( $_GET["declined"] ) ){
	$variables ['tab'] = 'declined';
	$variables['dataChangeRequests'] = $dataChangeRequestDAO->getDataChangeRequestsByStateAndUser(DataChangeRequest::DECLINED, getCurrentUserUUID(), $_GET);
	
} else {
	$variables ['tab'] = 'open';
	$variables['dataChangeRequests'] = $dataChangeRequestDAO->getDataChangeRequestsByStateAndUser(DataChangeRequest::OPEN, getCurrentUserUUID(), $_GET);
	$variables['furtherRequest'] = $dataChangeRequestDAO->getDataChangeRequestsByStateAndUser(DataChangeRequest::REQUEST, getCurrentUserUUID(), $_GET);
}

renderLayoutWithContentFile($variables);
