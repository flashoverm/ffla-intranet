<?php
require_once realpath ( dirname ( __FILE__ ) . "/../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
		'title' => 'Impressum',
		'secured' => false,
);

renderLayoutWithContentFile ($config["apps"]["landing"], "impressum_template.php", $variables );
