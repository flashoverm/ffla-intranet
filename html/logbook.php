<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/config.php" );
require_once TEMPLATES_PATH . "/template.php";
require_once LIBRARY_PATH . "/db_user.php";
require_once LIBRARY_PATH . "/db_logbook.php";
require_once LIBRARY_PATH . "/logbook_controller.php";

// Pass variables (as an array) to template
$variables = array (
		'title' => "Logbuch",
		'secured' => true,
		'privilege' => PORTALADMIN
);

$logbook = get_logbook();
$variables ['logbook'] = $logbook;

renderLayoutWithContentFile ($config["apps"]["landing"], "logbook_template.php", $variables );

?>