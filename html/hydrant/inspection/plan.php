<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["hydrant"],
		'template' => "inspectionPlan_template.php",
		'title' => "Prüfbericht",
		'secured' => true,
		'privilege' => Privilege::ENGINEHYDRANTMANANGER,
		'criteria' => InspectedHydrant::HYDRANTCRITERIA,
		'orientation' => 'landscape'
);
checkSitePermissions($variables);

$hydrants = array();

if(isset($_POST['hydrants'])){
	for( $i=0; $i<sizeof($_POST['hydrants']); $i++ ){
		$hydrants[] = $hydrantDAO->getHydrantByHy($_POST['hydrants'][$i]);
	}
}
$variables['hydrants'] = $hydrants;

renderPrintContentFile($variables);

?>
