<?php
if (! count ( $inspections )) {
	showInfo ( "Keine Prüfungen gefunden" );
} else {
	$columns = array(
			array( "label" => "Datum", "sort" => InspectionDAO::ORDER_DATE),
			array( "label" => "Löschzug", "sort" => InspectionDAO::ORDER_ENGINE),
			array( "label" => "Name(n)", "sort" => InspectionDAO::ORDER_NAMES),
			array( "label" => "Fahrzeug", "sort" => InspectionDAO::ORDER_VEHICLE),
			array( "label" => "Hydranten", "sort" => InspectionDAO::ORDER_HYDRANTS),
			array(),
			array(),
	);
	
	renderTable(
			TEMPLATES_PATH . "/hydrantapp/elements/inspection_row.php",
			$columns,
			$inspections,
			);
}
