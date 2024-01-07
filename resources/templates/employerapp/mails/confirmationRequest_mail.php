eine neue Anfrage einer Arbeitgeberbestätigung wurde erstellt:

Antragsteller: <?= $confirmation->getUser()->getFullName() ?>&nbsp;

Einsatz: <?= $confirmation->getDescription() ?>&nbsp;

Datum: <?= date($config ["formats"] ["date"], strtotime($confirmation->getDate())) ?>&nbsp;
Von: <?= date($config ["formats"] ["time"], strtotime($confirmation->getStartTime())) ?> Uhr
Bis: <?= date($config ["formats"] ["time"], strtotime($confirmation->getEndTime())) ?> Uhr

Einsatzzeiten geprüft und korrekt - Antrag direkt annehmen:

<?= $config ["urls"] ["base_url"] . $config["urls"]["employerapp_home"] . "/confirmations/process?confirmation=" . $confirmation->getUuid() . "&accept" ?>&nbsp;
	
Bearbeitung der Anfragen unter:

<?= $config ["urls"] ["base_url"] . $config["urls"]["employerapp_home"] . "/confirmations/process" ?>