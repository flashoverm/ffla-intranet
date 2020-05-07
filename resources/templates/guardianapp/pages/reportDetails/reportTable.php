<div class="table-responsive">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th colspan="3">
					<?= get_eventtype($report->type)->type ?>
					<?php
					if(get_eventtype($report->type)->type == "Sonstige Wache"){
						echo ": " . $report->type_other;
					}
					?>
				</th>
			</tr>
			<tr>
				<th colspan="1">Titel</th>
				<td colspan="2"><?= $report->title ?></td>
			</tr>
			<tr>
				<th colspan="1">Datum</td>
				<th colspan="1">Wachbeginn</td>
				<th colspan="1">Ende</td>
			</tr>
			<tr>
				<td colspan="1"><?= date($config ["formats"] ["date"], strtotime($report->date)); ?></td>
				<td colspan="1"><?= date($config ["formats"] ["time"], strtotime($report->start_time)); ?></td>
				<td colspan="1"><?= date($config ["formats"] ["time"], strtotime($report->end_time)); ?></td>
			</tr>
			<?php if($report->ilsEntry){
				echo '<tr><td colspan="3">Wache durch ILS angelegt</td></tr>';
			}
			?>
			<tr>
				<th colspan="1">Vorkommnisse</td>
				<?php if($report->noIncidents){
					echo '<td colspan="2">Keine</td>';
				} else {
					echo '<td colspan="2">Siehe Bericht</td>';
				}
			?>
			</tr>
			<tr>
				<th colspan="3">Bericht</td>
			</tr>
			<tr>
				<td colspan="3"><?= $report->report; ?></td>
			</tr>
		</tbody>
	</table>
</div>
	<?php 
	foreach ( $units as $entry ) {
	?>
	<div class="table-responsive">
		<table class="table table-bordered">
			<tr>
			<?php 
			if($entry->unit != null && ! $entry->unit == "Stationäre Wache"){
			?>	
				<th colspan="2"><?= $entry->unit ?></th>
				<td><?= $entry->km ?> km</th>
			</tr>
			<?php 
			} else {
			?>
				<th colspan="3"><?= $entry->unit ?></th>
			<?php 
			}?>
			<tr>
				<th>Datum (Einheit)</td>
				<th>Wachbeginn (Einheit)</td>
				<th>Ende (Einheit)</td>
			</tr>
			<tr>
				<td><?= date($config ["formats"] ["date"], strtotime($entry->date)); ?></td>
				<td><?= date($config ["formats"] ["time"], strtotime($entry->beginn)); ?></td>
				<td><?= date($config ["formats"] ["time"], strtotime($entry->end)); ?></td>
			</tr>
			<tr>
				<th>Funktion</th>
				<th>Name</th>
				<th>Löschzug</th>
			</tr>
			<?php 
			foreach ( $entry->staffList as $staff ) {
			?>
			<tr>
				<td><?= get_staffposition($staff->position)->position; ?></td>
				<td><?= $staff->name; ?></td>
				<td><?= get_engine($staff->engine)->name; ?></td>
			</tr>
			<?php } ?>
			</tbody>
		</table>
	</div>
	<?php } ?>
<div class="table-responsive">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th>Zuständiger Löschzug</th>
				<td><?= get_engine($report->engine)->name ?></td>
			</tr>
			<tr>
				<th>Ersteller</th>
				<td><?= $report->creator ?></td>
			</tr>
		</tbody>
	</table>
</div>