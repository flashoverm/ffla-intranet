<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Hydranten der Stadt Landshut",
		'secured' => true
);
	
$hydrants = $hydrantDAO->getHydrants();

$variables ['hydrants'] = $hydrants;

renderLayoutWithContentFile($config["apps"]["hydrant"], "hydrantOverview_template.php", $variables);
