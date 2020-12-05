<?php

$maillogStates = array(
		
	1 => "Gesendet",
	2 => "Fehlgeschlagen",
	3 => "Anhang-Fehler",
	4 => "Server-Verbindungs-Fehler",
	5 => "Mailausgang deaktiviert",
	6 => "Ungültige E-Mail-Adresse",
);

abstract class MaillogStates {
	
	const Sent = 1;
	const Failed = 2;
	const AttachmentError = 3;
	const MailConnectError = 4;
	const Deactivated = 5;
	const InvalidMailAddress = 6;
}