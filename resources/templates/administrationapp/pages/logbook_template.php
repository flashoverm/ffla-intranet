<?php

if(isset($forUser)){
	showInfo("Es werden nur Logeinträge für den Benutzer \"" . $forUser->getFullNameWithEmail() . "\" angezeigt");
}

renderTable(
	TEMPLATES_PATH . "/administrationapp/elements/logbook_row.php", 
	array(
			array( "label" => "Datum/Uhrzeit", "sort" => LogbookDAO::ORDER_TIMESTAMP),
			array( "label" => "Action-Code", "sort" => LogbookDAO::ORDER_ACTION),
			array( "label" => "Nachricht"),
			array( "label" => "Angemeldeter Benutzer", "sort" => LogbookDAO::ORDER_USER),
	),
	$logbook
);

?>
<br>

<form method="post" >
<input type="submit" name="purge" value="Log leeren" class="btn btn-primary">
</form>