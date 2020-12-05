<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_inspection.php";

require_once LIBRARY_PATH . "/class/constants/HydrantCriteria.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Hydrantenprüfung Karte",
		'secured' => true,
);

$hydrants = array();

if(isset($_POST['hydrants'])){
	for( $i=0; $i<sizeof($_POST['hydrants']); $i++ ){
		$hydrants[] = get_hydrant($_POST['hydrants'][$i]);
	}
}

$variables = array(
		'title' => "Prüfbericht",
		'secured' => true,
		'privilege' => ENGINEHYDRANTMANANGER,
		'criteria' => $hydrant_criteria,
		'hydrants' => $hydrants,
		'orientation' => 'landscape'
);

renderPrintContentFile($config["apps"]["hydrant"], "inspectionPlan_template.php", $variables);

?>
