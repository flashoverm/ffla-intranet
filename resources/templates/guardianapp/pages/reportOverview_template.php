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
				<th data-sortable="true" class="text-center">Wachbeginn</th>
				<th data-sortable="true" class="text-center">Ende</th>
				<th data-sortable="true" class="text-center">Typ</th>
				<th data-sortable="true" class="text-center">Titel</th>
				<th data-sortable="true" class="text-center">Zuständig</th>
				<th data-sortable="true" class="text-center">Vorkomnisse</th>
				<th data-sortable="true" class="text-center">Freigabe</th>
				<th data-sortable="true" class="text-center">EMS</th>
				<th class="text-center">Bericht</th>
			</tr>
		</thead>
		<tbody>
	<?php
		foreach ( $reports as $row ) {
		    ?>
				<tr>
				<td class="text-center"><span class='d-none'><?= strtotime($row->date) ?></span><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->start_time)); ?></td>
				<td class="text-center">
	<?php
		if ($row->end_time != 0) {
		    echo date($config ["formats"] ["time"], strtotime($row->end_time));
		} else {
			echo " - ";
		}
		?></td>
				<td class="text-center"><?= get_eventtype($row->type)->type; ?></td>
				<td class="text-center"><?= $row->title; ?></td>
				<td class="text-center"><?= get_engine($row->engine)->name; ?></td>
				<td class="text-center">
					<?php
					if($row->noIncidents){
					    echo " keine ";
					} else {
					    echo " siehe Bericht ";
					}
					?>
				</td>
				<td class="text-center">
					<?php
					if($row->managerApproved){
					    echo " &#10003; ";
					} else {
						echo " &ndash; ";
					}
					?>
				</td>
				<td class="text-center">
					<?php
					if($row->emsEntry){
					    echo " &#10003; ";
					} else {
						echo "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#confirmEms" . $row->uuid . "'>EMS</button>";
						createDialog("confirmEms" . $row->uuid, "Wurde die Wache in EMS angelegt?", null, "emsEntry", $row->uuid);
					}
					?>
				</td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?=$config["urls"]["guardianapp_home"] . "/reports/".$row->uuid ?>">Bericht</a>
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