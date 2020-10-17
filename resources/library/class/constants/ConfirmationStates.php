<?php
require_once realpath(dirname(__FILE__) . "/../../../../resources/config.php");

$confirmationStates = array(
		
	1 => "Offen",
	2 => "Akzeptiert",
	3 => "Abgelehnt",
);

abstract class ConfirmationState {
	
	const Open = 1;
	const Accepted = 2;
	const Declined = 3;
}