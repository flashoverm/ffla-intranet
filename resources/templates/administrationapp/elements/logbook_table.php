<?php
global $logbookDAO;

if ( ! isset($data) || ! count ( $data->getData() )) {
	showInfo ( "Es ist kein Eintrag vorhanden" );
} else {
	//renderSearch($data);
?>
<div class="table-responsive">
	<?php 
	renderTableDescription($data);
	?>
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