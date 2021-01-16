<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Eigene Stammdatenänderungen",
		'secured' => true,
);

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

$furtherRequest = $dataChangeRequestDAO->getDataChangeRequestsByStateAndUser(DataChangeRequest::REQUEST, $_SESSION ['intranet_userid']);
$variables['furtherRequest'] = $furtherRequest;

$open = $dataChangeRequestDAO->getDataChangeRequestsByStateAndUser(DataChangeRequest::OPEN, $_SESSION ['intranet_userid']);
$variables['open'] = $open;

$done = $dataChangeRequestDAO->getDataChangeRequestsByStateAndUser(DataChangeRequest::DONE, $_SESSION ['intranet_userid']);
$variables['done'] = $done;

$declined = $dataChangeRequestDAO->getDataChangeRequestsByStateAndUser(DataChangeRequest::DECLINED, $_SESSION ['intranet_userid']);
$variables['declined'] = $declined;

renderLayoutWithContentFile($config["apps"]["masterdata"], "dataChangeOverview_template.php", $variables);
