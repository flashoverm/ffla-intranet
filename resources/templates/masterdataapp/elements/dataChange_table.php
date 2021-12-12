<?php
$columns = array(
	array( "label" => "Erstellt", "sort" => DataChangeRequestDAO::ORDER_DATE),
	array( "label" => "Typ", "sort" => DataChangeRequestDAO::ORDER_TYPE),
	array( "label" => "Neuer Wert", "sort" => DataChangeRequestDAO::ORDER_NEWVALUE),
	array( "label" => "Änderung bei", "sort" => DataChangeRequestDAO::ORDER_PERSON),
);
if(!empty($options['showUserData'])){
	$columns[] = array("label" => "Antragsteller", "sort" => DataChangeRequestDAO::ORDER_USER);
	$columns[] = array("label" => "Löschzug", "sort" => DataChangeRequestDAO::ORDER_ENGINE);
}
if(!empty($options['showLastUpdate'])){
	$columns[] = array("label" => "Geändert", "sort" => DataChangeRequestDAO::ORDER_LASTUPDATE);
}
if(!empty($options['showLastUpdate'])){
	$columns[] = array();
}
if(!empty($options['showUserOptions'])){
	$columns[] = array();
	$columns[] = array();
}
if(!empty($options['showAdminOptions'])){
	$columns[] = array();
}

renderTable(
		TEMPLATES_PATH . "/masterdataapp/elements/dataChange_row.php",
		$columns,
		$data,
		$options
		);
?>