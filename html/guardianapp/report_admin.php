<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
    'title' => "Übersicht Wachberichte",
    'secured' => true,
		'privilege' => Privilege::EVENTADMIN
);

  
if (isset ( $_POST ['delete'] )) {
	$delete_report_uuid = trim ( $_POST ['delete'] );
	$log = LogbookEntry::fromAction(LogbookActions::ReportDeleted, $delete_report_uuid);
	if(delete_report ( $delete_report_uuid )){
		$variables ['successMessage'] = "Bericht gelöscht";
		$logbookDAO->save($log);
	} else {
		$variables ['alertMessage'] = "Bericht konnte nicht gelöscht werden";
	}
}

$variables ['reports'] = get_reports();

renderLayoutWithContentFile ($config["apps"]["guardian"], "reportOverview_template.php", $variables );

?>