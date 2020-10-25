<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_report.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";
require_once LIBRARY_PATH . "/db_user.php";

// Pass variables (as an array) to template
$variables = array (
    'title' => "Übersicht Wachberichte",
    'secured' => true,
	'privilege' => EVENTMANAGER,
);

if(userLoggedIn()){
    
    if (isset ( $_POST ['emsEntry'] )) {
        if(set_ems_entry($_POST ['emsEntry'])){
        	insert_logbook_entry(LogbookEntry::fromAction(LogbookActions::ReportEMSSet, $_POST ['emsEntry']));
            $variables['successMessage'] = "Bericht aktualisiert";
        } else {
            $variables['alertMessage'] = "Bericht konnte nicht aktualisiert werden";
        }
    }
        
    if(current_user_has_privilege(FFADMINISTRATION)){
        $variables ['reports'] = get_reports();
    } else {
    	$variables ['reports'] = get_filtered_reports(get_current_user_obj()->engine);
        $variables ['infoMessage'] = "Es werden nur Wachberichte angezeigt, die Ihrem Zug zugewiesen wurden";
    }
}

renderLayoutWithContentFile ($config["apps"]["guardian"], "reportOverview_template.php", $variables );

?>