<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/file_create.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Stammdatenänderungen bearbeiten",
		'secured' => true,
		'privilege' => Privilege::MASTERDATAADMIN
);

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

$open = $dataChangeRequestDAO->getDataChangeRequestsByState(DataChangeRequest::OPEN);
$variables['open'] = $open;
$done = $dataChangeRequestDAO->getDataChangeRequestsByState(DataChangeRequest::DONE);
$variables['done'] = $done;
$declined = $dataChangeRequestDAO->getDataChangeRequestsByState(DataChangeRequest::DECLINED);
$variables['declined'] = $declined;

renderLayoutWithContentFile($config["apps"]["masterdata"], "dataChangeProcess_template.php", $variables);
