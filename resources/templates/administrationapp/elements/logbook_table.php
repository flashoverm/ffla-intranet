<?php
global $logbookDAO;

if ( ! isset($data) || ! count ( $data )) {
	showInfo ( "Es ist kein Eintrag vorhanden" );
} else {
	renderSearch();
?>
<div class="table-responsive">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th class="text-center">Datum/Uhrzeit</th>
				<th class="text-center">Action-Code</th>
				<th class="text-center">Nachricht</th>
				<th class="text-center">Angemeldeter Benutzer</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $data as $row ) {
			render(TEMPLATES_PATH . "/administrationapp/elements/logbook_row.php", $row, $options);
		}
		?>
		</tbody>
	</table>
</div>
<?php 
renderPagination($logbookDAO->getLogbookEntryCount(), $options['currentPage']);
}
?>

<br>

<form method="post" >
	<input type="submit" name="purge" value="Log leeren" class="btn btn-primary">
</form>