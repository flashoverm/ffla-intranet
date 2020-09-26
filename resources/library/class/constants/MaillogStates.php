<?php
require_once realpath(dirname(__FILE__) . "/../../../../resources/config.php");

$maillogStates = array(
		
	1 => "Gesendet",
	2 => "Fehlgeschlagen",
	3 => "Anhang-Fehler",
	4 => "Server-Verbindungs-Fehler"
);

abstract class MaillogStates {
	
	const Sent = 1;
	const Failed = 2;
	const AttachmentError = 3;
	const MailConnectError = 4;
}