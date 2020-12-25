<?php
if (! count ( $hydrants )) {
    showInfo ( "Keine Hydranten für diese Straße gefunden" );
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
				<th data-sortable="true" class="text-center">Löschzug</th>
				<th data-sortable="true" class="text-center">Typ</th>
				<th class="text-center">Anzeigen</th>
				<?php if($currentUser->hasPrivilegeByName(Privilege::HYDRANTADMINISTRATOR)){
				    echo '<th class="text-center">Bearbeiten</th>';
				}?>
			</tr>
		</thead>
		<tbody>
    
    <?php
    foreach ( $hydrants as $row ) {
        ?>
				<tr>
				<td class="text-center"><?= $row->getHy(); ?></td>
				<td class="text-center"><?= $row->getFid(); ?></td>
				<td class="text-center"><?= $row->getStreet(); ?></td>
				<td class="text-center"><?= $row->getDistrict(); ?></td>
				<td class="text-center"><?= $row->getEngine()->getName(); ?></td>
				<td class="text-center"><?= $row->getType(); ?></td>

				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["hydrantapp_home"] . "/". $row->getHy(); ?>">Anzeigen</a>
				</td>
				<?php if($currentUser->hasPrivilegeByName(Privilege::HYDRANTADMINISTRATOR)){
				    echo '<td>
                            <a class="btn btn-primary btn-sm" href="' . $config["urls"]["hydrantapp_home"] . "/". $row->getHy() . '/edit">Bearbeiten</a>
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
<br>

<?php 

    if($mapURL != null){
        echo "<img id='map' class='rounded mx-auto' width='" . $config["mapView"]["widewidth"] . "' src='" . $mapURL . "' style='display: none;'>";
    }
    createHydrantGoogleMap($hydrants, true, true, $config["mapView"]["height"] . "px", $config["mapView"]["widewidth"] . "px");
}
?>

