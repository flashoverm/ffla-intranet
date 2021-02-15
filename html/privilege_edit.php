<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
    'title' => "Rechte bearbeiten",
    'secured' => true,
	'privilege' => Privilege::PORTALADMIN,	
	'privileges' => $privilegeDAO->getPrivileges()
);

if( isset( $_GET["user"] ) ) {
	
	$user = $userDAO->getUserByUUID( $_GET["user"] );
	
	$variables['user'] = $user;
	
	if( isset( $_GET["engine"] ) ) {
		
		$engine = $engineDAO->getEngine( $_GET["engine"] );
		
		
		$variables['engine'] = $engine;
	}
}

//user


//engine
//isset -> bearbeiten
//else -> hinzufügen


renderLayoutWithContentFile ($config["apps"]["landing"], "privilegeEdit_template.php", $variables );
