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
				<td class="text-center"><span class='d-none'><?= strtotime($row->getDate()) ?></span><?= date($config ["formats"] ["date"], strtotime($row->getDate())); ?></td>
				<td class="text-center"><?= $row->getEngine()->getName() ?></td>
				<td class="text-center"><?= $row->getName() ?></td>
				<td class="text-center"><?= $row->getVehicle() ?></td>
				<td class="text-center"><?= count ($row->getInspectedHydrants()) ?></td>
				<td>
					<div class="dropdown">
						<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">Optionen</button>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="<?= $config["urls"]["hydrantapp_home"] . "/inspection/". $row->getUuid(); ?>">Anzeigen</a>
							<a class="dropdown-item" target="_blank" href="<?= $config["urls"]["hydrantapp_home"] . "/inspection/". $row->getUuid() . "/file"; ?>">PDF anzeigen</a>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?= $config["urls"]["hydrantapp_home"] . "/inspection/edit/". $row->getUuid(); ?>">Bearbeiten</a>
							<div class="dropdown-divider"></div>
							<button type="button" class="dropdown-item" data-toggle="modal" data-target="#confirmDelete<?= $row->getUuid(); ?>">Löschen</button>
						</div>
					</div>
					<?php
					createDialog('confirmDelete' . $row->getUuid(), "Prüfbericht wirklich löschen?", null, "delete", $row->getUuid());
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

