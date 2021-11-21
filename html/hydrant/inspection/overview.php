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
$variables = checkPermissions($variables);

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
    $variables ['inspections'] = $inspectionDAO->getInspections();
} else {
	$variables ['inspections'] =  $inspectionDAO->getInspectionsByEngine($engine->getUuid());
}

renderLayoutWithContentFile($variables);
