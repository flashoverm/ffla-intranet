<?php
if (! count ( $inspections )) {
	showInfo ( "Keine Prüfungen gefunden" );
} else {
	?>

<div class="table-responsive">
	<table class="table table-striped" data-toggle="table" data-pagination="true" data-search="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">Datum</th>
				<th data-sortable="true" class="text-center">Löschzug</th>
				<th data-sortable="true" class="text-center">Name(n)</th>
				<th data-sortable="true" class="text-center">Fahrzeug</th>
				<th data-sortable="true" class="text-center">Hydranten</th>
				<th class="text-center">Anzeigen</th>
				<th class="text-center">PDF</th>
				<th class="text-center">Bearbeiten</th>
				<th class="text-center">Löschen</th>
			</tr>
		</thead>
		<tbody>
    
    <?php
    foreach ( $inspections as $row ) {
        ?>
			<tr>
				<td class="text-center"><span><?= strtotime($row->date) ?></span><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>
				<td class="text-center"><?= get_engine($row->engine)->name; ?></td>
				<td class="text-center"><?= $row->name; ?></td>
				<td class="text-center"><?= $row->vehicle; ?></td>
				<td class="text-center"><?= $row->getCount(); ?></td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["hydrantapp_home"] . "/inspection/". $row->uuid; ?>">Anzeigen</a>
				</td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" target="_blank" href="<?= $config["urls"]["hydrantapp_home"] . "/inspection/file/". $row->uuid; ?>">PDF</a>
				</td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["hydrantapp_home"] . "/inspection/edit/". $row->uuid; ?>">Bearbeiten</a>
				</td>
				<td class="text-center">
					<form method="post" action="">
						<input type="hidden" name="delete" id="delete" value="<?= $row->uuid ?>" />
						<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#confirmDelete<?= $row->uuid; ?>">Löschen</button>
						
						<div class="modal" id="confirmDelete<?= $row->uuid; ?>">
						  <div class="modal-dialog">
						    <div class="modal-content">
						
						      <div class="modal-header">
						        <h4 class="modal-title">Prüfbericht wirklich löschen?</h4>
						        <button type="button" class="close" data-dismiss="modal">&times;</button>
						      </div>
						
						      <div class="modal-footer">
						      	<input type="submit" value="Löschen" class="btn btn-primary" onClick="showLoader()"/>
						      	<button type="button" class="btn btn-outline-primary" data-dismiss="modal">Abbrechen</button>
						      </div>
						
						    </div>
						  </div>
						</div> 
					</form>
				</td>
			</tr>
<?php
	}

?>
		</tbody>
	</table>
</div>
<?php 
}
?>

