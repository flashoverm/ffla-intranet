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
				<th data-sortable="true" class="text-center">Typ</th>
				<th class="text-center">Anzeigen</th>
				<?php if(current_user_has_privilege(HYDRANTADMINISTRATOR)){
				    echo '<th class="text-center">Bearbeiten</th>';
				}?>
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
				<?php if(current_user_has_privilege(HYDRANTADMINISTRATOR)){
				    echo '<td>
                            <a class="btn btn-primary btn-sm" href="' . $config["urls"]["hydrantapp_home"] . "/". $row->hy . '/edit">Bearbeiten</a>
                         </td>';
				}?>
			</tr>
<?php
	}

?>
		</tbody>
	</table>
</div>
<div class="custom-control custom-checkbox">
  <input type="checkbox" class="custom-control-input" id="toogleMaps" checked>
  <label class="custom-control-label" for="toogleMaps">Verwende Google Maps</label>
</div>

<?php 

    if($mapURL != null){
        echo "<img id='map' class='rounded mx-auto mt-5' width='" . $config["mapView"]["widewidth"] . "' src='" . $mapURL . "' style='display: none;'>";
    }
    createHydrantGoogleMap($hydrants, true);
}
?>


