<?php
global $guardianUserController, $currentUser;

$columns = array(
		array( "label" => "Datum", "sort" => EventDAO::ORDER_DATE),
		array( "label" => "Beginn", "sort" => EventDAO::ORDER_START),
		array( "label" => "Ende", "sort" => EventDAO::ORDER_END),
		array( "label" => "Typ", "sort" => EventDAO::ORDER_TYPE),
		array( "label" => "Titel", "sort" => EventDAO::ORDER_TITLE),
		array( "label" => "Zuständig", "sort" => EventDAO::ORDER_ENGINE),
);

if(!empty($options['showOccupation'])){
	$columns[] = array("label" => "Belegung");
}
if(!empty($options['showPublic'])){
	$columns[] = array("label" => "Öffentlich", "sort" => EventDAO::ORDER_PUBLIC);
}
$columns[] = array();
if( !empty($options['showDelete']) &&
		$guardianUserController->isUserAllowedToEditSomeEvent($currentUser)){
	$columns[] = array();
}
if( !empty($options['showDeleteDB']) ){
	$columns[] = array();
}

renderTable(
		TEMPLATES_PATH . "/guardianapp/elements/event_row.php",
		$columns,
		$data,
		$options
		);
?>