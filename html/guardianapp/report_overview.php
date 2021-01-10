<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
    'title' => "Übersicht Wachberichte",
    'secured' => true,
		'privilege' => Privilege::EVENTMANAGER,
);

if(userLoggedIn()){
    
    if (isset ( $_POST ['emsEntry'] )) {
        if($reportController->setEmsEntry($_POST ['emsEntry'])){
        	$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportEMSSet, $_POST ['emsEntry']));
            $variables['successMessage'] = "Bericht aktualisiert";
        } else {
            $variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
        }
    }
        
    if($userController->hasCurrentUserPrivilege(Privilege::FFADMINISTRATION)){
        $variables ['reports'] = $reportDAO->getReports();
    } else {
    	$variables ['reports'] = $reportDAO->getReportsByEngine($userController->getCurrentUser()->getEngine()->getUuid());
        $variables ['infoMessage'] = "Es werden nur Wachberichte angezeigt, die Ihrem Zug zugewiesen wurden";
    }
}

renderLayoutWithContentFile ($config["apps"]["guardian"], "reportOverview_template.php", $variables );

?>