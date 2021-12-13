<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["hydrant"],
		'template' => "inspectionOverview_template.php",
	    'title' => "Hydrantenprüfungen",
	    'secured' => true,
		'privilege' => Privilege::ENGINEHYDRANTMANANGER
);
$variables = checkSitePermissions($variables);

$engine = $userController->getCurrentUser()->getEngine();

if(isset($_POST['delete'])){
	$log = LogbookEntry::fromAction(LogbookActions::InspectionDeleted, $_POST['delete']);
    if($inspectionDAO->deleteInspection($_POST['delete'])){
        $variables ['successMessage'] = "Prüfbericht gelöscht";
        $logbookDAO->save($log);
    } else {
        $variables ['alertMessage'] = "Prüfbericht konnte nicht gelöscht werden";
    }
}

if($userController->hasCurrentUserPrivilege(Privilege::HYDRANTADMINISTRATOR)){
	$variables ['inspections'] = $inspectionDAO->getInspections($_GET);
} else {
	$variables ['inspections'] =  $inspectionDAO->getInspectionsByEngine($engine->getUuid(), $_GET);
}

renderLayoutWithContentFile($variables);
