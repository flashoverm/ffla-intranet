<?php
if ( ! count ( $open ) ) {
	showInfo ( "Keine Anträge offen" );
} else {
?>
<h3 class="mb-3 mt-3">Offene Anfragen</h3>
<div class="table-responsive">
	<table class="table table-striped" data-toggle="table" data-pagination="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">Datum</th>
				<th data-sortable="true" class="text-center">Beginn</th>
				<th data-sortable="true" class="text-center">Ende</th>
				<th data-sortable="true" class="text-center">Einsatz</th>
				<th data-sortable="true" class="text-center">Antragsteller</th>
				<th data-sortable="true" class="text-center">Löschzug</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $open as $row ) {
			$user = $userDAO->getUserByUUID($row->user);
		?>
			<tr>
				<td class="text-center"><span class='d-none'><?= strtotime($row->date) ?></span><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->start_time)); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->end_time)); ?></td>
				<td class="text-center"><?= $row->description ?></td>
				<td><?= $user->getFullName() ?></td>
				<td><?= $user->getEngine()->getName() ?></td>
				<td class="text-center">
					<div class="dropdown">
						<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">Optionen</button>
						<div class="dropdown-menu">
							<form class="mb-0" method="post" action="" onsubmit="showLoader()" >
								<input type="hidden" name="confirmation" id="confirmation" value="<?= $row->uuid ?>"/>
								<input type="submit" name="accept" value="Annehmen"  class="dropdown-item"/>
								<div class="dropdown-divider"></div>
								<button type="button" class="dropdown-item" data-toggle='modal' data-target='#confirmDeleteStaff<?= $row->uuid ?>'>Ablehnen</button>
							</form>
						</div>
					</div>
				</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
</div>
<?php
foreach ( $open as $row ) {
?>
	<div class='modal' id='confirmDeleteStaff<?= $row->uuid ?>'>
		<div class='modal-dialog'>
			<div class='modal-content'>
				<form method="post" action="">
					<div class='modal-header'>
						<h4 class='modal-title'>Grund für Ablehnung</h4>
						<button type='button' class='close' data-dismiss='modal'>&times;</button>
					</div>
					<div class='modal-body'>
						<div class="form-group">
							<input type="text" required="required" placeholder="Grund für Ablehnung" class="form-control" 
							name="reason" id="reason" >
						</div>
					</div>
					<div class='modal-footer'>
						<input type="hidden" name="confirmation" id="confirmation" value="<?= $row->uuid ?>"/>
						<input type='submit' name="decline" value='Ablehnen' class='btn btn-primary'/>
						<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php 
}
?>

<?php
}

if ( count ( $accepted ) ) {
?> 
<button class="btn btn-outline-primary mt-3 mb-2" type="button" data-toggle="collapse" data-target="#accepted">
    Akzeptierte Anfragen
</button>
<div id="accepted" class="collapse  
<?php 
if ( ! count ( $open ) ) {
	echo " show ";
}
?>">
	<h3 class="mb-3 mt-3">Akzeptierte Anfragen</h3>
	<div class="table-responsive">
		<table class="table table-striped" data-toggle="table" data-pagination="true">
			<thead>
				<tr>
					<th data-sortable="true" class="text-center">Datum</th>
					<th data-sortable="true" class="text-center">Beginn</th>
					<th data-sortable="true" class="text-center">Ende</th>
					<th data-sortable="true" class="text-center">Einsatz</th>
					<th data-sortable="true" class="text-center">Antragsteller</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $accepted as $row ) {
				$user = $userDAO->getUserByUUID($row->user);
			?>
				<tr>
					<td class="text-center"><span class='d-none'><?= strtotime($row->date) ?></span><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>
					<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->start_time)); ?></td>
					<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->end_time)); ?></td>
					<td class="text-center"><?= $row->description ?></td>
					<td class="text-center"><?= $user->getFullName() ?></td>
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
    