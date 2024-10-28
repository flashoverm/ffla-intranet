<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/mail_controller.php";
require_once LIBRARY_PATH . "/file_create.php";

// Pass variables (as an array) to template
$variables = array(
	'app' => $config["apps"]["employer"],
	'template' => "confirmationProcess_template.php",
	'title' => "Arbeitgebernachweise bearbeiten",
	'secured' => true,
    'privilege' => array(Privilege::FFADMINISTRATION, Privilege::ENGINECONFIRMATIONMANAGER)
);
checkSitePermissions($variables);

if( isset($_GET['accept']) && isset($_GET['confirmation']) ){
    $confirmationUuid = trim ( $_GET['confirmation'] );
    acceptConfirmationRequest($confirmationUuid, $variables);
}

if( isset($_POST['confirmation']) ){
	$confirmationUuid = trim ( $_POST['confirmation'] );
		
	if( isset($_POST['accept']) ){

	    acceptConfirmationRequest($confirmationUuid, $variables);
		
	} elseif ( isset($_POST['decline']) ){
		
		$reason = null;
		if(isset( $_POST ['reason'] ) && !empty( $_POST ['reason'] ) ){
			$reason = trim( $_POST ['reason'] );
		}
		
		$confirmation = $confirmationController->declineConfirmation(
		    $confirmationUuid, $reason, $userController->getCurrentUser()
		);
		
		if($confirmation){
			if( ! mail_send_confirmation_declined($confirmation)){
				$variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
			}
			$variables ['successMessage'] = "Anfrage abgelehnt";
			$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ConfirmationDeclined, $confirmation->getUuid()));
			
		} else {
			$variables ['alertMessage'] = "Anfrage konnte nicht bearbeitet werden";
		}
	}
	
}

if( isset( $_GET["accepted"] ) ){
	$variables ['tab'] = 'accepted';
	$state = Confirmation::ACCEPTED;
} elseif ( isset( $_GET["declined"] ) ){
	$variables ['tab'] = 'declined';
	$state = Confirmation::DECLINED;
} else {
	$variables ['tab'] = 'open';
	$state = Confirmation::OPEN;
}

if($currentUser->hasPrivilegeByName(Privilege::FFADMINISTRATION)){
    $variables['confirmations'] = $confirmationDAO->getConfirmationsByState(
        $state, $_GET);
} else {
    $variables['confirmations'] = $confirmationDAO->getConfirmationsByStateAndEngineAndAssignee(
        $state, $currentUser->getEngine()->getUuid(), $currentUser->getUuid(), $_GET);
}

renderLayoutWithContentFile($variables);

function acceptConfirmationRequest($confirmationUuid, &$variables){
    global $currentUser, $confirmationController, $logbookDAO, $confirmationDAO;
    
    $confirmation = $confirmationDAO->getConfirmation($confirmationUuid);
    if($confirmation->getState() == Confirmation::ACCEPTED){
        $variables ['alertMessage'] = "Anfrage wurde bereits angenommen";
        return;
    }
    $confirmation = $confirmationController->acceptConfirmation($confirmationUuid, $currentUser);
    if($confirmation){
        createConfirmationFile($confirmation->getUuid());
        if( ! mail_send_confirmation($confirmation)){
            $variables ['alertMessage'] = "Mindestens eine E-Mail konnte nicht versendet werden";
        }
        $variables ['successMessage'] = "Anfrage akzeptiert";
        $logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ConfirmationAccepted, $confirmation->getUuid()));
        
    } else {
        $variables ['alertMessage'] = "Anfrage konnte nicht bearbeitet werden";
    }
}
