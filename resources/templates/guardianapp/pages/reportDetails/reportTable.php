<div class="table-responsive">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th colspan="3">
					<?= $report->getType()->getType() ?>
					<?php
					if($report->getType()->getType() == "Sonstige Wache"){
						echo ": " . $report->getTypeOther();
					}
					?>
				</th>
			</tr>
			<tr>
				<th colspan="1">Titel</th>
				<td colspan="2"><?= $report->getTitle() ?></td>
			</tr>
			<tr>
				<th colspan="1">Datum</td>
				<th colspan="1">Wachbeginn</td>
				<th colspan="1">Ende</td>
			</tr>
			<tr>
				<td colspan="1"><?= date($config ["formats"] ["date"], strtotime($report->getDate())); ?></td>
				<td colspan="1"><?= date($config ["formats"] ["time"], strtotime($report->getStartTime())); ?></td>
				<td colspan="1"><?= date($config ["formats"] ["time"], strtotime($report->getEndTime())); ?></td>
			</tr>
			<?php if($report->getIlsEntry()){
				echo '<tr><td colspan="3">Wache durch ILS angelegt</td></tr>';
			}
			?>
			<tr>
				<th colspan="1">Vorkommnisse</td>
				<?php if($report->getNoIncidents()){
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
				<td colspan="3"><?= $report->getReportText() ?></td>
			</tr>
		</tbody>
	</table>
</div>
	<?php 
	foreach ( $report->getUnits() as $unit ) {
	?>
	<div class="table-responsive">
		<table class="table table-bordered">
			<tr>
			<?php 
			if($unit->getUnitName() != null && ! $unit->getUnitName() == "Stationäre Wache"){
			?>	
				<th colspan="2"><?= $unit->getUnitName() ?></th>
				<td><?= $unit->getKm() ?> km</th>
			</tr>
			<?php 
			} else {
			?>
				<th colspan="3"><?= $unit->getUnitName() ?></th>
			<?php 
			}?>
			<tr>
				<th>Datum (Einheit)</td>
				<th>Wachbeginn (Einheit)</td>
				<th>Ende (Einheit)</td>
			</tr>
			<tr>
				<td><?= date($config ["formats"] ["date"], strtotime($unit->getDate())); ?></td>
				<td><?= date($config ["formats"] ["time"], strtotime($unit->getStartTime())); ?></td>
				<td><?= date($config ["formats"] ["time"], strtotime($unit->getEndTime())); ?></td>
			</tr>
			<tr>
				<th>Funktion</th>
				<th>Name</th>
				<th>Löschzug</th>
			</tr>
			<?php 
			foreach ( $unit->getStaff() as $staff ) {
			?>
			<tr>
				<td><?= $staff->getPosition()->getPosition() ?></td>
				<td><?= $staff->getName() ?></td>
				<td><?= $staff->getEngine()->getName() ?></td>
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
				<td><?= $report->getEngine()->getName() ?></td>
			</tr>
			<tr>
				<th>Ersteller</th>
				<td><?= $report->getCreator()->getFullName() ?></td>
			</tr>
			<?php if($report->getCreateDate() != null){ ?>
			<tr>
				<th>Erstellt am</th>
				<td>
					<?= date($config ["formats"] ["datetime"], strtotime($report->getCreateDate())); ?> Uhr
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>