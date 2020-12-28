<?php
if(!isset($events) ){
	//Disabled
} else if ( ! count ( $events )) {
	showInfo ( "Es sind keine öffentlichen Wachen vorhanden" );
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
				<th data-sortable="true" class="text-center">Belegung</th>
				<th class="text-center">Details</th>
			</tr>
		</thead>
		<tbody>
			
	<?php
	foreach ( $events as $row ) {
		?>
				<tr>
				<td class="text-center"><?= date($config ["formats"] ["date"], strtotime($row->getDate())); ?></td>
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
				<td class="text-center"><?= $row->getEngine()->getName(); ?></td>
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
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["guardianapp_home"] . "/events/" . $row->getUuid() ?>">Details</a>
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