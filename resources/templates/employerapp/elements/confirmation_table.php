<?php
$columns = array(
	array( "label" => "Datum/Uhrzeit", "sort" => ConfirmationDAO::ORDER_DATE),
	array( "label" => "Beginn", "sort" => ConfirmationDAO::ORDER_START),
	array( "label" => "Ende", "sort" => ConfirmationDAO::ORDER_END),
	array( "label" => "Einsatz", "sort" => ConfirmationDAO::ORDER_ALARM),
);
if(!empty($options['showUserData'])){
	$columns[] = array("label" => "Antragsteller", "sort" => ConfirmationDAO::ORDER_USER);
	$columns[] = array("label" => "Löschzug", "sort" => ConfirmationDAO::ORDER_ENGINE);
}
if(!empty($options['showReason'])){
	$columns[] = array("label" => "Grund für Ablehnung", "sort" => ConfirmationDAO::ORDER_REASON);
}
if(!empty($options['showLastUpdate'])){
	$columns[] = array("label" => "Geändert", "sort" => ConfirmationDAO::ORDER_LASTUPDATE);
}
if(!empty($options['showUserOptions'])){
	$columns[] = array();
	$columns[] = array();
}
if(!empty($options['showAdminOptions']) || !empty($options['showViewConfirmation'])){
	$columns[] = array();
}

renderTable(
		TEMPLATES_PATH . "/employerapp/elements/confirmation_row.php",
		$columns,
		$data,
		$options
		);
?>