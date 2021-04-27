<?php 
if (isset($hydrant)){   
	?>
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th class="th-td-padding text-left">Ort</th>
				<td class="th-td-padding"><?= $hydrant->getStreet() . ", " . $hydrant->getDistrict() ?></td>
			</tr>			
			<tr>
				<th class="th-td-padding text-left">Löschzug</th>
				<td class="th-td-padding"><?= $hydrant->getEngine()->getName(); ?></td>
			</tr>
			<tr>
				<th class="th-td-padding text-left">Typ</th>
				<td class="th-td-padding"><?= $hydrant->getType() ?></td>
			</tr>
			<?php
			if(!$hydrant->getCheckByFF()){
			?>
			<tr>
				<th class="th-td-padding text-left">Prüfung durch</th>
				<td class="th-td-padding">Stadtwerke Landshut</td>
			</tr>
			<?php
			}
			if(!$hydrant->getOperating()){
			?>
			<tr>
				<th class="th-td-padding text-left">Achtung</th>
				<td class="th-td-padding">Hydrant ist nicht in Betrieb</td>
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