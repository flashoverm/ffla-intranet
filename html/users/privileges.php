<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
		'app' => $config["apps"]["users"],
		'template' => "privileges_template.php",
		'secured' => true,
		'privilege' => Privilege::PORTALADMIN,
		'title' => "Berechtigungen",
		'privileges' => $privilegeDAO->getPrivileges(),
);
$variables = checkSitePermissions($variables);

renderLayoutWithContentFile ( $variables );
