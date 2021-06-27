<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["masterdata"],
		'template' => "dataChangeEdit_template.php",
		'title' => "Stammdatenänderung beantragen",
		'secured' => true,
);
$variables = checkPermissions($variables);

$dataChangeRequest = new DataChangeRequest();
if(isset($_GET ['id'])) {
	$variables ['title'] = 'Stammdatenänderung bearbeiten';
	$dataChangeRequest = $dataChangeRequestDAO->getDataChangeRequest($_GET ['id']);
	$variables ['dataChangeRequest'] = $dataChangeRequest;
}

if( isset($_POST['datatype']) && isset($_POST['newvalue']) ){
	
	if(! isset($_GET ['id'])
			|| (isset($_GET ['id']) && ($variables ['dataChangeRequest']->getState() == DataChangeRequest::OPEN 
					|| $variables ['dataChangeRequest']->getState() == DataChangeRequest::REQUEST  ) ) ){
		
		$dataTypeID = trim ( $_POST ['datatype'] );
		$newValue = trim ( $_POST ['newvalue'] );
		
		$forperson = null;
		if(isset( $_POST ['forperson'] ) && !empty( $_POST ['forperson'] ) ){
			$forperson = trim( $_POST ['forperson'] );
		}
		
		$comment = null;
		if(isset( $_POST ['comment'] ) && !empty( $_POST ['comment'] ) ){
			$comment = trim( $_POST ['comment'] );
		}
		
		$priviousState = $dataChangeRequest->getState();
				
		$dataChangeRequest->setState(DataChangeRequest::OPEN);
		$dataChangeRequest->setFurtherRequest(NULL);
		$dataChangeRequest->setDataChangeRequestData($dataTypeID, $newValue, $comment, $forperson, $userController->getCurrentUser());
				
		if( ! isset($_GET ['id'])) {
			$dataChangeRequest->setCreateDate(date('Y-m-d H:i:s'));
		}
		
		$dataChangeRequest = $dataChangeRequestDAO->save($dataChangeRequest);
		
		if(isset($_GET ['id'])) {
			
			if($dataChangeRequest){

				if($priviousState == DataChangeRequest::REQUEST){
					if( ! mail_send_datachange_request_update($dataChangeRequest)){
						$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
					}
				}
				
				$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::DataChangeUpdated, $dataChangeRequest->getUuid()));
				$variables ['successMessage'] = "Anfrage aktualisiert";
				//header ( "Location: " . $config["urls"]["masterdataapp_home"] . "/datachangerequests"); // redirects
				
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
	

renderLayoutWithContentFile($variables);
