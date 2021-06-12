<?php
if (! count ( $files )) {
    showInfo ( "Keine Dateien gefunden" );
} else {
    ?>
<div class="table-responsive">
	<table class="table table-striped"  data-toggle="table" data-pagination="true" data-search="true">
		<thead>
			<tr>
				<th data-sortable="true">Beschreibung</th>
				<th data-sortable="true" class="text-center">Upload-Datum</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
    
    <?php
    foreach ( $files as $row ) {
        ?>
			<tr>
				<td><?= $row->getDescription(); ?></td>
				<td class="text-center"><span class='d-none'><?= strtotime($row->getDate()) ?></span><?= date($config ["formats"] ["date"], strtotime($row->getDate())); ?></td>

				<td class="text-center">
					<a class="btn btn-primary btn-sm" target="_blank" href="<?= $config ["urls"] ["files"] . "/" . $row->getFilename(); ?>">Anzeigen</a>
				</td>
				<td class="text-center">
					<form method="post" action="">
						<input type="hidden" name="delete" id="delete" value="<?= $row->getUuid() ?>" />
						<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#confirmDelete<?= $row->getUuid(); ?>">Löschen</button>
						
						<div class="modal" id="confirmDelete<?= $row->getUuid(); ?>">
						  <div class="modal-dialog">
						    <div class="modal-content">
						
						      <div class="modal-header">
						        <h4 class="modal-title">Datei wirklich löschen?</h4>
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
<a href='<?= $config["urls"]["filesapp_home"]?>/forms/new' class="btn btn-primary">Formular hochladen</a>
