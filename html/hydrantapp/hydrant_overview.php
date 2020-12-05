<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_hydrant.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Hydranten der Stadt Landshut",
		'secured' => true
);
	
$hydrants = get_hydrants();

$variables ['hydrants'] = $hydrants;

renderLayoutWithContentFile($config["apps"]["hydrant"], "hydrantOverview_template.php", $variables);
