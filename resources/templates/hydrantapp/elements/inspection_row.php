<?php
global $config;
?>
<tr>
	<td class="text-center"><span class='d-none'><?= strtotime($data->getDate()) ?></span><?= date($config ["formats"] ["date"], strtotime($data->getDate())); ?></td>
	<td class="text-center"><?= $data->getEngine()->getName() ?></td>
	<td class="text-center"><?= $data->getName() ?></td>
	<td class="text-center"><?= $data->getVehicle() ?></td>
	<td class="text-center"><?= count ($data->getInspectedHydrants()) ?></td>
	<td><a class="btn btn-primary btn-sm" href="<?= $config["urls"]["hydrantapp_home"] . "/inspection/view/". $data->getUuid(); ?>">Anzeigen</a></td>
	<td>
		<div class="dropdown">
			<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" data-boundary="window">Optionen</button>
			<div class="dropdown-menu">
				
				<a class="dropdown-item" target="_blank" href="<?= $config["urls"]["hydrantapp_home"] . "/inspection/view/". $data->getUuid() . "/file"; ?>">PDF anzeigen</a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="<?= $config["urls"]["hydrantapp_home"] . "/inspection/edit/". $data->getUuid(); ?>">Bearbeiten</a>
				<div class="dropdown-divider"></div>
				<button type="button" class="dropdown-item" data-toggle="modal" data-target="#confirmDelete<?= $data->getUuid(); ?>">Löschen</button>
			</div>
		</div>
		<?php
		createDialog('confirmDelete' . $data->getUuid(), "Prüfbericht wirklich löschen?", null, "delete", $data->getUuid());
		?>
	</td>
</tr>