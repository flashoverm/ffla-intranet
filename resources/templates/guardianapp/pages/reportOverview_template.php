<?php

if (! count ( $reports )) {
    showInfo ( "Es sind keine Wachberichte angelegt" );
} else {
    ?>
<div class="table-responsive">
	<table class="table table-striped" data-toggle="table" data-pagination="true"  data-search="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">Datum</th>
				<th data-sortable="true" class="text-center">Beginn</th>
				<th data-sortable="true" class="text-center">Ende</th>
				<th data-sortable="true" class="text-center">Typ</th>
				<th data-sortable="true" class="text-center">Titel</th>
				<th data-sortable="true" class="text-center">Zuständig</th>
				<th data-sortable="true" class="text-center">Vorkomnisse</th>
				<th data-sortable="true" class="text-center">Freigabe</th>
				<th data-sortable="true" class="text-center">EMS</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
	<?php
		foreach ( $reports as $row ) {
		    ?>
				<tr>
				<td class="text-center"><span class='d-none'><?= strtotime($row->getDate()) ?></span><?= date($config ["formats"] ["date"], strtotime($row->getDate())); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->getStartTime())); ?></td>
				<td class="text-center">
	<?php
		if ($row->getEndTime() != 0) {
			echo date($config ["formats"] ["time"], strtotime($row->getEndTime()));
		} else {
			echo " - ";
		}
		?></td>
				<td class="text-center"><?= $row->getType()->getType() ?></td>
				<td class="text-center"><?= $row->getTitle() ?></td>
				<td class="text-center"><?= $row->getEngine()->getName() ?></td>
				<td class="text-center">
					<?php
					if($row->getNoIncidents()){
					    echo " keine ";
					} else {
					    echo " siehe Bericht ";
					}
					?>
				</td>
				<td class="text-center">
					<?php
					if($row->getManagerApproved()){
					    echo " &#10003; ";
					} else {
						echo " &ndash; ";
					}
					?>
				</td>
				<td class="text-center">
					<?php
					if($row->getEmsEntry()){
					    echo " &#10003; ";
					} else {
						echo "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#confirmEms" . $row->getUuid() . "' title='Wache als in EMS angelegt markieren'>EMS</button>";
						createDialog("confirmEms" . $row->getUuid(), "Wurde die Wache in EMS angelegt?", null, "emsEntry", $row->getUuid());
					}
					?>
				</td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?=$config["urls"]["guardianapp_home"] . "/reports/view/" . $row->getUuid() ?>">Anzeigen</a>
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