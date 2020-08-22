<?php
if (! count ( $files )) {
    showInfo ( "Keine Dateien gefunden" );
} else {
    ?>
<div class="table-responsive">
	<table class="table table-striped" data-toggle="table" data-pagination="true" data-search="true"">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">Beschreibung</th>
				<th data-sortable="true" class="text-center">Upload-Datum</th>
				<th class="text-center">Anzeigen</th>
			</tr>
		</thead>
		<tbody>
    
    <?php
    foreach ( $files as $row ) {
        ?>
				<tr>
				<td class="text-center"><?= $row->description; ?></td>
				<td class="text-center"><span class='d-none'><?= strtotime($row->date) ?></span><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>

				<td class="text-center">
					<a class="btn btn-primary btn-sm" target="_blank" href="<?= $config ["urls"] ["files"] . "/" . $row->filename; ?>">Anzeigen</a>
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