<?php
require_once realpath ( dirname ( __FILE__ ) . "/../../resources/bootstrap.php" );
require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array(
		'app' => $config["apps"]["scba"],
		'template' => "scbaPersonalOverview_template.php",
		'title' => "Atemschutzgeräteträger:innen",
		'secured' => true
);
checkSitePermissions($variables);

$scbaPersonal = $scbaPersonalDao->getPersonal($_GET);

$variables ['scbaPersonal'] = $scbaPersonal;

renderLayoutWithContentFile($variables);
