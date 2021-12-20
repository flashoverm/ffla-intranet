<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["hydrant"],
		'template' => "hydrantOverview_template.php",
		'title' => "Hydranten der Stadt Landshut",
		'secured' => true
);
checkSitePermissions($variables);

$hydrants = $hydrantDAO->getHydrants($_GET);

$variables ['hydrants'] = $hydrants;

renderLayoutWithContentFile($variables);
