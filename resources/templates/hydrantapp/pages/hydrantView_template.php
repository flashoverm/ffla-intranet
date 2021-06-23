<?php 
if (isset($hydrant)){   
	?>
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th>Ort</th>
				<td><?= $hydrant->getStreet() . ", " . $hydrant->getDistrict() ?></td>
			</tr>			
			<tr>
				<th>Löschzug</th>
				<td><?= $hydrant->getEngine()->getName(); ?></td>
			</tr>
			<tr>
				<th>Typ</th>
				<td><?= $hydrant->getType() ?></td>
			</tr>
			<?php
			if(!$hydrant->getCheckByFF()){
			?>
			<tr>
				<th>Prüfung durch</th>
				<td>Stadtwerke Landshut</td>
			</tr>
			<?php
			}
			if(!$hydrant->getOperating()){
			?>
			<tr>
				<th>Achtung</th>
				<td>Hydrant ist nicht in Betrieb</td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>

<?php if($currentUser->hasPrivilegeByName(Privilege::HYDRANTADMINISTRATOR)){ ?>
	<div class='float-right'>
		<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["hydrantapp_home"] . "/". $hydrant->getHy() . '/edit' ?>">Bearbeiten</a>
	</div>
<?php } ?>
<div class="custom-control custom-checkbox d-inline-flex mb-3">
  <input type="checkbox" class="custom-control-input" id="toogleMaps">
  <label class="custom-control-label" for="toogleMaps">Verwende Google Maps</label>
</div>

	<?php 
    if($mapURL != null){
        echo "<img id='map' class='rounded mx-auto' width='" . $config["mapView"]["width"] . "' src='" . $mapURL . "' style='display: block;'>";
    }
    createHydrantGoogleMap(array($hydrant), false, true, $config["mapView"]["height"] . "px", $config["mapView"]["widewidth"] . "px");
    
}
?>