<?php

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
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $declined as $row ) {
		?>
			<tr>
				<td class="text-center"><span class='d-none'><?= strtotime($row->date) ?></span><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->start_time)); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->end_time)); ?></td>
				<td class="text-center"><?= $row->description ?></td>
				<td><?= $row->reason ?></td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["employerapp_home"] . "/confirmations/".$row->uuid ."/edit" ?>">Bearbeiten</a>
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
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $open as $row ) {
		?>
			<tr>
				<td class="text-center"><span class='d-none'><?= strtotime($row->date) ?></span><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->start_time)); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->end_time)); ?></td>
				<td class="text-center"><?= $row->description ?></td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["employerapp_home"] . "/confirmations/".$row->uuid ."/edit" ?>">Bearbeiten</a>
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
					<td class="text-center"><span class='d-none'><?= strtotime($row->date) ?></span><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>
					<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->start_time)); ?></td>
					<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->end_time)); ?></td>
					<td class="text-center"><?= $row->description ?></td>
					<td class="text-center">
						<a class="btn btn-primary btn-sm" target="_blank" href="<?= $config["urls"]["employerapp_home"] . "/confirmations/".$row->uuid ."/file" ?>">Nachweis anzeigen</a>
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
    