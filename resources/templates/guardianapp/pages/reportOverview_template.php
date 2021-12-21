<?php
global $currentUser;

if($currentUser->hasPrivilegeByName(Privilege::EVENTMANAGER)){
	$options = array(
			'showEMS' => true,
	);
} else {
	$options = array();
}

$columns = array(
		array( "label" => "Datum", "sort" => ReportDAO::ORDER_DATE),
		array( "label" => "Beginn", "sort" => ReportDAO::ORDER_START),
		array( "label" => "Ende", "sort" => ReportDAO::ORDER_END),
		array( "label" => "Typ", "sort" => ReportDAO::ORDER_TYPE),
		array( "label" => "Titel", "sort" => ReportDAO::ORDER_TITLE),
		array( "label" => "Zuständig", "sort" => ReportDAO::ORDER_ENGINE),
		array( "label" => "Vorkomnisse", "sort" => ReportDAO::ORDER_INCIDENTS),
		array( "label" => "Freigabe", "sort" => ReportDAO::ORDER_APPROVED),
);
if(!empty($options['showEMS'])){
	$columns[] = array( "label" => "EMS", "sort" => ReportDAO::ORDER_EMSENTRY);
}
$columns[] = array();

renderTable(
		TEMPLATES_PATH . "/guardianapp/elements/report_row.php",
		$columns,
		$reports,
		$options
		);