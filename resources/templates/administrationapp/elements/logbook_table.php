<?php
global $logbookDAO;

if ( ! isset($data) || ! count ( $data->getData() )) {
	showInfo ( "Es ist kein Eintrag vorhanden" );
} else {
?>
<div class="table-responsive" id="table">
	<?php 
	renderTableDescription($data);
	?>
		<thead>
			<tr>
				<?= renderTableHead("Datum/Uhrzeit", $data, LogbookDAO::ORDER_TIMESTAMP)?>
				<?= renderTableHead("Action-Code", $data, LogbookDAO::ORDER_ACTION)?>
				<?= renderTableHead("Nachricht", $data)?>
				<?= renderTableHead("Angemeldeter Benutzer", $data, LogbookDAO::ORDER_USER)?>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $data->getData() as $row ) {
			render(TEMPLATES_PATH . "/administrationapp/elements/logbook_row.php", $row, $options);
		}
		?>
		</tbody>
	</table>
</div>
<?php 
renderPagination($data);
}
?>

<br>

<form method="post" >
	<input type="submit" name="purge" value="Log leeren" class="btn btn-primary">
</form>