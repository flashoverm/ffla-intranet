<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["guardian"],
		'template' => "reportOverview_template.php",
	    'title' => "Übersicht Wachberichte",
	    'secured' => true,
		'privilege' => Privilege::EVENTMANAGER,
);
$variables = checkSitePermissions($variables);

    if (isset ( $_POST ['emsEntry'] )) {
        if($reportController->setEmsEntry($_POST ['emsEntry'])){
        	$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportEMSSet, $_POST ['emsEntry']));
            $variables['successMessage'] = "Bericht aktualisiert";
        } else {
            $variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
        }
    }
        
    if($userController->hasCurrentUserPrivilege(Privilege::FFADMINISTRATION)){
    	$variables ['reports'] = $reportDAO->getReports($_GET);
    } else {
    	$variables ['reports'] = $reportDAO->getReportsByEngine($userController->getCurrentUser()->getEngine()->getUuid(), $_GET);
        $variables ['infoMessage'] = "Es werden nur Wachberichte angezeigt, die Ihrem Zug zugewiesen wurden";
    }

renderLayoutWithContentFile ( $variables );

?>