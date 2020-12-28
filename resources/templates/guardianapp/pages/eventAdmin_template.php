<?php
if (!isset($events) || ! count ( $events ) ) {
    showInfo ( "Es sind keine Wachen offen" );
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
				<th data-sortable="true" class="text-center">Belegung</th>
				<th data-sortable="true" class="text-center">Öffentlich</th>
				<th data-sortable="true" class="text-center">Zugewiesen</th>
				<th class="text-center">Details</th>
				<th class="text-center">Löschen (DB)</th>
			</tr>
		</thead>
		<tbody>
			
	<?php
	foreach ( $events as $row ) {
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
				<td class="text-center"><?= $row->getTitle(); ?></td>
				<td class="text-center">
					<?php 
					if($row->isEventFull()){
					    echo '<font color="green">' . $row->getOccupancy() . '</font>';
					} else {
						echo '<font color="red">' . $row->getOccupancy() . '</font>';
					}
				    ?>
				</td>
				<td class="text-center">
					<?php
					if($row->getPublished()){
					    echo " &#10003; ";
					} else {
					    echo " - ";
					}
					?>
				</td>
				<td class="text-center"><?= $row->getEngine()->getName() ?></td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["guardianapp_home"] . "/events/".$row->getUuid() ?>">Details</a>
				</td>
				<td class="text-center">
					<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#confirmDeleteDB<?= $row->getUuid(); ?>">Löschen (DB)</button>
					<?php 
					createDialog('confirmDeleteDB' . $row->getUuid(), "Wache wirklich entgültig löschen?", null, "deletedb", $row->getUuid());
					?>					
				</td>
			</tr>
<?php
	}
?>
		</tbody>
	</table>
</div>
</p>
<?php 
}
if ( isset($pastEvents) && count ( $pastEvents )) {
?>
<button class="btn btn-outline-primary my-2" type="button" data-toggle="collapse" data-target="#pastevents">
    Vergangene Wachen
</button>

<div class="table-responsive collapse" id="pastevents">
	<table class="table table-striped" data-toggle="table" data-pagination="true"  data-search="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">Datum</th>
				<th data-sortable="true" class="text-center">Wachbeginn</th>
				<th data-sortable="true" class="text-center">Ende</th>
				<th data-sortable="true" class="text-center">Typ</th>
				<th data-sortable="true" class="text-center">Titel</th>
				<th data-sortable="true" class="text-center">Öffentlich</th>
				<th data-sortable="true" class="text-center">Zugewiesen</th>
				<th class="text-center">Details</th>
				<th class="text-center">Löschen</th>
				<th class="text-center">Löschen (DB)</th>
			</tr>
		</thead>
		<tbody>
			
	<?php
	foreach ( $pastEvents as $row ) {
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
				<td class="text-center"><?= $row->getTitle(); ?></td>
				<td class="text-center">
					<?php
					if($row->getPublished()){
					    echo " &#10003; ";
					} else {
					    echo " - ";
					}
					?>
				</td>
				<td class="text-center"><?= $row->getEngine()->getName();?></td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["guardianapp_home"] . "/events/".$row->getUuid() ?>">Details</a>
				</td>
				<td class="text-center">
					<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#confirmDelete<?= $row->getUuid(); ?>">Löschen</button>
					<?php 
					createDialog('confirmDelete' . $row->getUuid(), "Wache wirklich löschen?", null, "delete", $row->getUuid());
					?>	
				</td>
				<td class="text-center">
					<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#confirmDeleteDB<?= $row->getUuid(); ?>">Löschen (DB)</button>
					<?php 
					createDialog('confirmDeleteDB' . $row->getUuid(), "Wache wirklich entgültig löschen?", null, "deletedb", $row->getUuid());
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
if ( isset($deletedEvents) && count ( $deletedEvents )) {
    ?>
<button class="btn btn-outline-primary my-2" type="button" data-toggle="collapse" data-target="#deletedevents">
    Gelöschte Wachen
</button>

<div class="table-responsive collapse" id="deletedevents">
	<table class="table table-striped" data-toggle="table" data-pagination="true"  data-search="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">Datum</th>
				<th data-sortable="true" class="text-center">Wachbeginn</th>
				<th data-sortable="true" class="text-center">Ende</th>
				<th data-sortable="true" class="text-center">Typ</th>
				<th data-sortable="true" class="text-center">Titel</th>
				<th data-sortable="true" class="text-center">Öffentlich</th>
				<th data-sortable="true" class="text-center">Zugewiesen</th>
				<th data-sortable="true" class="text-center">Gelöscht von</th>
				<th class="text-center">Details</th>
				<th class="text-center">Löschen (DB)</th>
			</tr>
		</thead>
		<tbody>
			
	<?php
	foreach ( $deletedEvents as $row ) {
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
				<td class="text-center"><?= $row->getTitle(); ?></td>
				<td class="text-center">
					<?php
					if($row->getPublished()){
					    echo " &#10003; ";
					} else {
					    echo " - ";
					}
					?>
				</td>
				<td class="text-center"><?= $row->getEngine()->getName(); ?></td>
				<td class="text-center"><?= $row->getDeletedBy()->getEmail(); ?></td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["guardianapp_home"] . "/events/" . $row->getUuid() ?>">Details</a>
				</td>

				<td class="text-center">
					<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#confirmDeleteDB<?= $row->getUuid(); ?>">Löschen (DB)</button>
					<?php 
					createDialog('confirmDeleteDB' . $row->getUuid(), "Wache wirklich entgültig löschen?", null, "deletedb", $row->getUuid());
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

</div>