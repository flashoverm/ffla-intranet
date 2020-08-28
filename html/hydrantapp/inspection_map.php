<?php
require_once realpath(dirname(__FILE__) . "/../../resources/config.php");
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_inspection.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Hydrantenprüfung Karte",
		'secured' => true,
		'privilege' => ENGINEHYDRANTMANANGER
);

$hydrants = array();

if(isset($_POST['hydrants'])){
	for( $i=0; $i<sizeof($_POST['hydrants']); $i++ ){
		$hydrants[] = get_hydrant($_POST['hydrants'][$i]);
	}
}

$variables ['hydrants'] = $hydrants;

renderContentFile($config["apps"]["hydrant"], "inspectionMap_template.php", $variables);

?>
