<?php
global $currentUser;

$columns = array(
	array( "label" => "HY", "sort" => HydrantDAO::ORDER_HY),
    array( "label" => "FID", "sort" => HydrantDAO::ORDER_FID),
    array( "label" => "Straße", "sort" => HydrantDAO::ORDER_STREET),
    array( "label" => "Stadtteil", "sort" => HydrantDAO::ORDER_DISTRICT),
);

if(!empty($options['showEngine'])){
    $columns[] = array("label" => "Löschzug", "sort" => HydrantDAO::ORDER_ENGINE);
}

if(!empty($options['showOperating'])){
    $columns[] = array("label" => "Betrieb", "sort" => HydrantDAO::ORDER_OPERATING);
}

$columns[] = array("label" => "Typ", "sort" => HydrantDAO::ORDER_TYPE);

if(!empty($options['showLastCheck'])){
    $columns[] = array("label" => "Geprüft am", "sort" => HydrantDAO::ORDER_LASTCHECK);
}

$columns[] = array();

if($currentUser->hasPrivilegeByName(Privilege::HYDRANTADMINISTRATOR)){
    $columns[] = array();
}

renderTable(
		TEMPLATES_PATH . "/hydrantapp/elements/hydrant_row.php",
		$columns,
		$data,
		$options
		);
?>