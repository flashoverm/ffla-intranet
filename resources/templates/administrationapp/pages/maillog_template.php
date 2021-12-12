<?php
renderTable(
	TEMPLATES_PATH . "/administrationapp/elements/maillog_row.php", 
	array(
			array( "label" => "Datum", "sort" => MailLogDAO::ORDER_TIMESTAMP),
			array( "label" => "Empfänger", "sort" => MailLogDAO::ORDER_RECIPIENT),
			array( "label" => "Betreff", "sort" => MailLogDAO::ORDER_SUBJECT),
			array( "label" => "Status", "sort" => MailLogDAO::ORDER_STATE),
	),
	$mails
);
?>
<br>

<form method="post" >
	<input type="submit" name="testmail" value="Testmail"  class="btn btn-primary">
	<input type="submit" name="purge" value="Log leeren" class="btn btn-primary">
</form>
