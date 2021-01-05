<?php
if ( ! count ( $declined ) && ! count ( $open ) && ! count ( $accepted ) ) {
	showInfo ( "Keine Arbeitgebernachweise offen oder angefragt" );
}
	
if ( count ( $declined ) ) {
?>
<h3 class="my-3">Abgelehnte Anfragen</h3>
<div class="table-responsive">
	<table class="table table-striped" data-toggle="table" data-pagination="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">Datum</th>
				<th data-sortable="true" class="text-center">Beginn</th>
				<th data-sortable="true" class="text-center">Ende</th>
				<th data-sortable="true" class="text-center">Einsatz</th>
				<th data-sortable="true" class="text-center">Grund für Ablehnung</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $declined as $row ) {
		?>
			<tr>
				<td class="text-center"><span class='d-none'><?= strtotime($row->getDate()) ?></span><?= date($config ["formats"] ["date"], strtotime($row->getDate())); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->getStartTime())); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->getEndTime())); ?></td>
				<td class="text-center"><?= $row->getDescription() ?></td>
				<td><?= $row->getReason() ?></td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["employerapp_home"] . "/confirmations/".$row->getUuid() ."/edit" ?>">Bearbeiten</a>
				</td>
				<td class="text-center">
					<form method="post" action="" class="mb-0">
						<input type="hidden" name="withdraw" value="<?= $row->getUuid() ?>"/>
						<input type="submit" value="Zurückziehen" class="btn btn-primary btn-sm"/>
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

if ( count ( $open ) ) {
?>
<h3 class="my-3">Offene Anfragen</h3>
<div class="table-responsive">
	<table class="table table-striped" data-toggle="table" data-pagination="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">Datum</th>
				<th data-sortable="true" class="text-center">Beginn</th>
				<th data-sortable="true" class="text-center">Ende</th>
				<th data-sortable="true" class="text-center">Einsatz</th>
				<th></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $open as $row ) {
		?>
			<tr>
				<td class="text-center"><span class='d-none'><?= strtotime($row->getDate()) ?></span><?= date($config ["formats"] ["date"], strtotime($row->getDate())); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->getStartTime())); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->getEndTime())); ?></td>
				<td class="text-center"><?= $row->getDescription() ?></td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["employerapp_home"] . "/confirmations/".$row->getUuid() ."/edit" ?>">Bearbeiten</a>
				</td>
				<td class="text-center">
					<form method="post" action="" class="mb-0">
						<input type="hidden" name="withdraw" value="<?= $row->getUuid() ?>"/>
						<input type="submit" value="Zurückziehen" class="btn btn-primary btn-sm"/>
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

if ( count ( $accepted ) ) {
 
	if ( count ( $declined ) ||  count ( $open ) ) {
	?>
		<button class="btn btn-outline-primary mt-3 mb-2" type="button" data-toggle="collapse" data-target="#accepted">
		    Arbeitgebernachweise
		</button>
	<?php
	}
	?>
<div id="accepted" class="collapse  
<?php 
if ( ! count ( $declined ) &&  ! count ( $open ) ) {
	echo " show ";
}
?>">
	<h3 class="mb-3 mt-3">Arbeitgebernachweise</h3>
	<div class="table-responsive">
		<table class="table table-striped" data-toggle="table" data-pagination="true">
			<thead>
				<tr>
					<th data-sortable="true" class="text-center">Datum</th>
					<th data-sortable="true" class="text-center">Beginn</th>
					<th data-sortable="true" class="text-center">Ende</th>
					<th data-sortable="true" class="text-center">Einsatz</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $accepted as $row ) {
			?>
				<tr>
					<td class="text-center"><span class='d-none'><?= strtotime($row->getDate()) ?></span><?= date($config ["formats"] ["date"], strtotime($row->getDate())); ?></td>
					<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->getStartTime())); ?></td>
					<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->getEndTime())); ?></td>
					<td class="text-center"><?= $row->getDescription() ?></td>
					<td class="text-center">
						<a class="btn btn-primary btn-sm" target="_blank" href="<?= $config["urls"]["employerapp_home"] . "/confirmations/".$row->getUuid() ."/file" ?>">Nachweis anzeigen</a>
					</td>
				</tr>
			<?php
			}
			?>
			</tbody>
		</table>
	</div>
</div>
<?php	
}
?>
    