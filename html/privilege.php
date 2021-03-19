<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
		'secured' => true,
		'privilege' => Privilege::PORTALADMIN,
		'title' => "Berechtigungen",
		'privileges' => $privilegeDAO->getPrivileges(),
);
	
renderLayoutWithContentFile ($config["apps"]["landing"], "privilege_template.php", $variables );
