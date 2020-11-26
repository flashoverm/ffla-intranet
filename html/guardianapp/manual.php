<?php
require_once realpath(__DIR__ . "/../../resources/bootstrap.php");

require_once TEMPLATES_PATH . "/template.php";

// Pass variables (as an array) to template
$variables = array (
    'title' => 'Anleitung',
    'secured' => false,
);

renderLayoutWithContentFile ( "manual_template.php", $variables );
