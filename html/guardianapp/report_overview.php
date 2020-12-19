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
        if(set_ems_entry($_POST ['emsEntry'])){
        	$logbookDAO->save(LogbookEntry::fromAction(LogbookActions::ReportEMSSet, $_POST ['emsEntry']));
            $variables['successMessage'] = "Bericht aktualisiert";
        } else {
            $variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
        }
    }
        
    if($userController->getCurrentUser()->hasPrivilegeByName(Privilege::FFADMINISTRATION)){
        $variables ['reports'] = get_reports();
    } else {
    	$variables ['reports'] = get_filtered_reports(get_current_user_obj()->engine);
        $variables ['infoMessage'] = "Es werden nur Wachberichte angezeigt, die Ihrem Zug zugewiesen wurden";
    }
}

renderLayoutWithContentFile ($config["apps"]["guardian"], "reportOverview_template.php", $variables );

?>