<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Stammdatenänderung beantragen",
		'secured' => true,
);

$dataChangeRequest = new DataChangeRequest();
if(isset($_GET ['id'])) {
	$variables ['title'] = 'Stammdatenänderung bearbeiten';
	$dataChangeRequest = $dataChangeRequestDAO->getDataChangeRequest($_GET ['id']);
	$variables ['dataChangeRequest'] = $dataChangeRequest;
}

if( isset($_POST['datatype']) && isset($_POST['newvalue']) ){
	
	if(! isset($_GET ['id'])
			|| (isset($_GET ['id']) && $variables ['dataChangeRequest']->getState() == DataChangeRequest::OPEN) ){
		
		$dataTypeID = trim ( $_POST ['datatype'] );
		$newValue = trim ( $_POST ['newvalue'] );
		
		$comment = null;
		if(isset( $_POST ['comment'] ) && !empty( $_POST ['comment'] ) ){
			$comment = trim( $_POST ['comment'] );
		}
		
		$dataChangeRequest->setState(DataChangeRequest::OPEN);
		$dataChangeRequest->setDataChangeRequestData($dataTypeID, $newValue, $comment, $userController->getCurrentUser());
		
		if( ! isset($_GET ['id'])) {
			$dataChangeRequest->setCreateDate(date('Y-m-d H:i:s'));
		}
		
		$dataChangeRequest = $dataChangeRequestDAO->save($dataChangeRequest);
		
		if(isset($_GET ['id'])) {
			
			if($dataChangeRequest){
				
				$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::DataChangeUpdated, $dataChangeRequest->getUuid()));
				$variables ['successMessage'] = "Anfrage aktualisiert";
				header ( "Location: " . $config["urls"]["masterdataapp_home"] . "/datachangerequests"); // redirects
				
			} else {
				$variables ['alertMessage'] = "Anfrage konnte nicht aktualisiert werden";
			}
			
		} else {
			
			if($dataChangeRequest){
				if( ! mail_send_datachange_request($dataChangeRequest)){
					$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
				}
				$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::DataChangeRequested, $dataChangeRequest->getUuid()));
				$variables ['successMessage'] = "Anfrage gespeichert";
				header ( "Location: " . $config["urls"]["masterdataapp_home"] . "/datachangerequests"); // redirects
				
			} else {
				$variables ['alertMessage'] = "Anfrage konnte nicht gespeichert werden";
			}
		}
				
	} else {
		$variables ['showFormular'] = false;
		$variables ['errorMessage'] = "Abgeschlossene oder abgelehnte Anträge können nicht mehr bearbeitet werden.";
	}
}
	

renderLayoutWithContentFile($config["apps"]["masterdata"], "dataChangeEdit_template.php", $variables);
