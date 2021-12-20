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
checkSitePermissions($variables);

$engine = $userController->getCurrentUser()->getEngine();

if(isset($_POST['delete'])){
	
	$inspection = $inspectionDAO->getInspection($_POST['delete']);
	checkPermissions(array(
			array('engine' => $inspection->getEngine(), 'privilege' => Privilege::ENGINEHYDRANTMANANGER),
			array('privilege' => Privilege::HYDRANTADMINISTRATOR),
			array('privilege' => Privilege::FFADMINISTRATION),
	), $variables);
	
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
