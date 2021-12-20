<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["hydrant"],
		'template' => "hydrantInspectedView_template.php",
		'title' => "Geprüfte Hydranten",
		'secured' => true,
		'privilege' => Privilege::ENGINEHYDRANTMANANGER
);
checkSitePermissions($variables);

$engine = $userController->getCurrentUser()->getEngine();

if($userController->hasCurrentUserPrivilege(Privilege::HYDRANTADMINISTRATOR)){
    $variables ['hydrants'] = $hydrantDAO->getHydrants($_GET);
} else {
    $variables ['hydrants'] =  $hydrantDAO->getHydrantsOfEngine($engine->getUuid(), $_GET);
}
  
renderLayoutWithContentFile($variables);
