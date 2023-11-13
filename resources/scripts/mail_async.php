<?php

require_once realpath ( dirname ( __FILE__ ) . "/../bootstrap.php" );
require_once LIBRARY_PATH . "/mail_controller.php";

$to = $argv[1];
$subject = $argv[2];
$body = $argv[3];

send_mail($to, $subject, $body, NULL, false, false);
