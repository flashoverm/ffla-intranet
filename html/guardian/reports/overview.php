<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["guardian"],
		'template' => "reportOverview_template.php",
	    'title' => "Übersicht Wachberichte",
	    'secured' => true,
);
checkSitePermissions($variables);

    if (isset ( $_POST ['emsEntry'] )) {
    	$report = $reportDAO->getReport($_POST ['emsEntry']);
    	checkPermissions(array(
    			array("privilege" => Privilege::EVENTADMIN),
    			array("privilege" => Privilege::EVENTMANAGER, "engine" => $report->getEngine()),
    			array("user" => $report->getCreator())
    	), $variables);
    	
        if($reportController->setEmsEntry($_POST ['emsEntry'])){
        	$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportEMSSet, $_POST ['emsEntry']));
            $variables['successMessage'] = "Bericht aktualisiert";
        } else {
            $variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
        }
    }
        
    if($userController->hasCurrentUserPrivilege(Privilege::FFADMINISTRATION)){
    	$variables ['reports'] = $reportDAO->getReports($_GET);
    } else if($currentUser->hasPrivilegeByName(Privilege::EVENTMANAGER)){
    	$variables ['reports'] = $reportDAO->getReportsByEngine($userController->getCurrentUser()->getEngine()->getUuid(), $_GET);
        $variables ['infoMessage'] = "Es werden nur Wachberichte angezeigt, die Ihrem Zug zugewiesen wurden";
    } else {
    	$variables ['reports'] = $reportDAO->getReportsByCreator($userController->getCurrentUser()->getUuid(), $_GET);
    	$variables ['infoMessage'] = "Es werden nur Wachberichte angezeigt, die von Ihnen erstellt wurden";
    }

renderLayoutWithContentFile ( $variables );

?>