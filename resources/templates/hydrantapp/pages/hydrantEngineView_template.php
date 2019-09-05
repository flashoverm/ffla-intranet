<?php
if (! count ( $hydrants )) {
    showInfo ( "Keine Hydranten für diesen Zug gefunden" );
} else {
    ?>

<div class="table-responsive">
	<table class="table table-striped" data-toggle="table" data-pagination="true"  data-search="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">HY</th>
				<th data-sortable="true" class="text-center">FID</th>
				<th data-sortable="true" class="text-center">Straße</th>
				<th data-sortable="true" class="text-center">Stadtteil</th>
				<th class="text-center">Typ</th>
				<th class="text-center">Anzeigen</th>
			</tr>
		</thead>
		<tbody>
    
    <?php
    foreach ( $hydrants as $row ) {
        ?>
				<tr>
				<td class="text-center"><?= $row->hy; ?></td>
				<td class="text-center"><?= $row->fid; ?></td>
				<td class="text-center"><?= $row->street; ?></td>
				<td class="text-center"><?= $row->district; ?></td>
				<td class="text-center"><?= $row->type; ?></td>

				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["hydrantapp_home"] . "/". $row->hy; ?>">Anzeigen</a>
				</td>
			</tr>
<?php
	}

?>
		</tbody>
	</table>
</div>
<?php 

    if($mapURL != null){
        echo "<img id='map' class='rounded mx-auto d-block mt-5' width='" . $config["mapView"]["widewidth"] . "' src='" . $mapURL . "'>";
    }
}
?>
