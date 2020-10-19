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
				<th class="text-center"></th>
			</tr>
		</thead>
		<tbody>
    
    <?php
    foreach ( $inspections as $row ) {
        ?>
			<tr>
				<td class="text-center"><span class='d-none'><?= strtotime($row->date) ?></span><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>
				<td class="text-center"><?= get_engine($row->engine)->name; ?></td>
				<td class="text-center"><?= $row->name; ?></td>
				<td class="text-center"><?= $row->vehicle; ?></td>
				<td class="text-center"><?= $row->getCount(); ?></td>
				<td>
					<div class="dropdown">
						<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">Optionen</button>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="<?= $config["urls"]["hydrantapp_home"] . "/inspection/". $row->uuid; ?>">Anzeigen</a>
							<a class="dropdown-item" target="_blank" href="<?= $config["urls"]["hydrantapp_home"] . "/inspection/". $row->uuid . "/file"; ?>">PDF anzeigen</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?= $config["urls"]["hydrantapp_home"] . "/inspection/edit/". $row->uuid; ?>">Bearbeiten</a>
							<div class="dropdown-divider"></div>
							<button type="button" class="dropdown-item" data-toggle="modal" data-target="#confirmDelete<?= $row->uuid; ?>">Löschen</button>
						</div>
					</div>
					<?php
						createDialog('confirmDelete' . $row->uuid, "Prüfbericht wirklich löschen?", null, "delete", $row->uuid);
					?>
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

