<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'title' => "Geprüfte Hydranten",
		'secured' => true,
		'privilege' => Privilege::ENGINEHYDRANTMANANGER
);

$engine = $userController->getCurrentUser()->getEngine();

if($userController->hasCurrentUserPrivilege(Privilege::HYDRANTADMINISTRATOR)){
	$variables ['hydrants'] = $hydrantDAO->getHydrants();
} else {
	$variables ['hydrants'] =  $hydrantDAO->getHydrantsOfEngine($engine->getUuid());
}
  
renderLayoutWithContentFile($config["apps"]["hydrant"], "hydrantInspectedView_template.php", $variables);
