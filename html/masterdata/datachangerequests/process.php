<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/file_create.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["masterdata"],
		'template' => "dataChangeProcess_template.php",
		'title' => "Stammdatenänderungen bearbeiten",
		'secured' => true,
		'privilege' => Privilege::MASTERDATAADMIN
);
$variables = checkPermissions($variables);

if( isset($_POST['datachangerequest']) ){
	$dataChangeRequestUuid = trim ( $_POST['datachangerequest'] );
	
	if( isset($_POST['done']) ){
		$dataChangeRequest = $dataChangeRequestController->dataChangeRequestDone($dataChangeRequestUuid, $userController->getCurrentUser());
		
		if($dataChangeRequest){
			if( ! mail_send_datachange_status($dataChangeRequest)){
				$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
			}
			$variables ['successMessage'] = "Anfrage umgesetzt";
			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::DataChangeDone, $dataChangeRequest->getUuid()));
						
		} else {
			$variables ['alertMessage'] = "Anfrage konnte nicht bearbeitet werden";
		}
		
	} else if ( isset($_POST['declined']) ){
		
		$dataChangeRequest = $dataChangeRequestController->declineDataChangeRequest($dataChangeRequestUuid, $userController->getCurrentUser());
		
		if($dataChangeRequest){
			if( ! mail_send_datachange_status($dataChangeRequest)){
				$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
			}
			$variables ['successMessage'] = "Anfrage abgelehnt";
			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::DataChangeDeclined, $dataChangeRequest->getUuid()));
			
		} else {
			$variables ['alertMessage'] = "Anfrage konnte nicht bearbeitet werden";
		}
	} else if ( isset($_POST['request']) ){
		
		$requestText = trim($_POST['requesttext']);
		
		$dataChangeRequest = $dataChangeRequestController->requestToDataChangeRequest($dataChangeRequestUuid, $requestText);
		
		if($dataChangeRequest){
			if( ! mail_send_datachange_status($dataChangeRequest)){
				$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
			}
			$variables ['successMessage'] = "Rückfrage gestellt";
			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::DataChangeDeclined, $dataChangeRequest->getUuid()));
			
		} else {
			$variables ['alertMessage'] = "Anfrage konnte nicht bearbeitet werden";
		}
	}
	
}

if( isset( $_GET["done"] ) ){
	$variables ['tab'] = 'done';
	$variables['dataChangeRequests'] = $dataChangeRequestDAO->getDataChangeRequestsByState(DataChangeRequest::DONE);
	
} else if (isset( $_GET["declined"] ) ){
	$variables ['tab'] = 'declined';
	$variables['dataChangeRequests'] = $dataChangeRequestDAO->getDataChangeRequestsByState(DataChangeRequest::DECLINED);
	
} else if (isset( $_GET["request"] ) ){
	$variables ['tab'] = 'request';
	$variables['dataChangeRequests'] = $dataChangeRequestDAO->getDataChangeRequestsByState(DataChangeRequest::REQUEST);
	
} else {
	$variables ['tab'] = 'open';
	$variables['dataChangeRequests'] = $dataChangeRequestDAO->getDataChangeRequestsByState(DataChangeRequest::OPEN);
}

renderLayoutWithContentFile($variables);
