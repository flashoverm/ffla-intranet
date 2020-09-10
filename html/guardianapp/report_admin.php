<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_report.php";
require_once LIBRARY_PATH . "/db_engines.php";
require_once LIBRARY_PATH . "/db_eventtypes.php";

// Pass variables (as an array) to template
$variables = array (
    'title' => "Übersicht Wachberichte",
    'secured' => true,
	'privilege' => EVENTADMIN
);

  
if (isset ( $_POST ['delete'] )) {
	$delete_report_uuid = trim ( $_POST ['delete'] );
	if(delete_report ( $delete_report_uuid )){
		$variables ['successMessage'] = "Bericht gelöscht";
		insert_log(LogbookActions::ReportDeleted, $delete_report_uuid);
	} else {
		$variables ['alertMessage'] = "Bericht konnte nicht gelöscht werden";
	}
}

$variables ['reports'] = get_reports();

renderLayoutWithContentFile ($config["apps"]["guardian"], "reportOverview_template.php", $variables );

?>