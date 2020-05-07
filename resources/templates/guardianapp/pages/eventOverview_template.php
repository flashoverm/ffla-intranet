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
				<th class="text-center">Details</th>
				<?php
				if(is_user_manager_or_creator($events[0]->uuid, $_SESSION['guardian_userid'])){
					echo"<th class='text-center'>Löschen</th>";
				}
				?>
			</tr>
		</thead>
		<tbody>
			
	<?php
	foreach ( $events as $row ) {
		?>
				<tr>
				<td class="text-center" data-sort="<?= strtotime($row->date) ?>"><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>
				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->start_time)); ?></td>
				<td class="text-center">
	<?php
		if ($row->end_time != 0) {
		    echo date($config ["formats"] ["time"], strtotime($row->end_time));
		} else {
			echo " &ndash; ";
		}
		?></td>
				<td class="text-center"><?= get_eventtype($row->type)->type; ?></td>
				<td class="text-center"><?= $row->title; ?></td>
				<td class="text-center">
					<?php 
					if(is_event_full($row->uuid)){
					    echo '<font color="green">' . get_occupancy($row->uuid) . '</font>';
					} else {
					    echo '<font color="red">' . get_occupancy($row->uuid) . '</font>';
					}
				    ?>
				</td>
				<td class="text-center">
					<?php
					if($row->published){
					    echo " &#10003; ";
					} else {
					    echo " - ";
					}
					?>
				</td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["guardianapp_home"] . "/events/".$row->uuid ?>">Details</a>
				</td>
				<?php
				if(is_user_manager_or_creator($row->uuid, $_SESSION['guardian_userid'])){
					?>
					<td class="text-center">
					<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#confirmDelete<?= $row->uuid; ?>">Löschen</button>
					<?php 
					createDialog('confirmDelete' . $row->uuid, "Wache wirklich löschen?", null, "delete", $row->uuid);
					?>	
				</td>
					<?php 
				}
				?>

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
				<th class="text-center">Details</th>
			</tr>
		</thead>
		<tbody>
			
	<?php
	foreach ( $pastEvents as $row ) {
		?>
				<tr>
				<td class="text-center" data-sort="<?= strtotime($row->date) ?>"><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>
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
				<td class="text-center">
					<?php
					if($row->published){
					    echo " &#10003; ";
					} else {
					    echo " - ";
					}
					?>
				</td>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["guardianapp_home"] . "/events/".$row->uuid ?>">Details</a>
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