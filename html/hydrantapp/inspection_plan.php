<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Hydrantenprüfung Karte",
		'secured' => true,
);

$hydrants = array();

if(isset($_POST['hydrants'])){
	for( $i=0; $i<sizeof($_POST['hydrants']); $i++ ){
		$hydrants[] = $hydrantDAO->getHydrantByHy($_POST['hydrants'][$i]);
	}
}

$variables = array(
		'title' => "Prüfbericht",
		'secured' => true,
		'privilege' => Privilege::ENGINEHYDRANTMANANGER,
		'criteria' => InspectedHydrant::HYDRANTCRITERIA,
		'hydrants' => $hydrants,
		'orientation' => 'landscape'
);

renderPrintContentFile($config["apps"]["hydrant"], "inspectionPlan_template.php", $variables);

?>
