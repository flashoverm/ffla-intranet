<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["guardian"],
		'template' => "reportOverview_template.php",
	    'title' => "Übersicht Wachberichte",
	    'secured' => true,
		'privilege' => Privilege::EVENTADMIN
);
$variables = checkPermissions($variables);
  
if (isset ( $_POST ['delete'] )) {
	$delete_report_uuid = trim ( $_POST ['delete'] );
	$log = LogbookEntry::fromAction(LogbookActions::ReportDeleted, $delete_report_uuid);
	if($reportDAO->deleteReport( $delete_report_uuid )){
		$variables ['successMessage'] = "Bericht gelöscht";
		$logbookDAO->save($log);
	} else {
		$variables ['alertMessage'] = "Bericht konnte nicht gelöscht werden";
	}
}

$variables ['reports'] = $reportDAO->getReports($_GET);

renderLayoutWithContentFile ( $variables );

?>