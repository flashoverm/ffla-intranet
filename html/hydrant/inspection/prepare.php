<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";


// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["hydrant"],
		'template' => "inspectionPrepare_template.php",
		'title' => "Hydrantenprüfung planen",
		'secured' => true,
		'privilege' => Privilege::ENGINEHYDRANTMANANGER
);
$variables = checkSitePermissions($variables);

//get hydrants by engine with last checkup not set or older than 6 years
$params = $_GET;
$params[ResultSet::PAGESIZE_PARAM] = -1;
$hydrants = $hydrantDAO->getUncheckedHydrantsOfEngine($userController->getCurrentUser()->getEngine()->getUuid(), $params);

$variables ['hydrants'] = $hydrants;

renderLayoutWithContentFile($variables);