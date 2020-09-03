<?php 
if (isset($hydrant)){   
	?>
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th class="th-td-padding text-left">Ort</th>
				<td class="th-td-padding"><?= $hydrant->street . ", " . $hydrant->district ?></td>
			</tr>			
			<tr>
				<th class="th-td-padding text-left">Löschzug</th>
				<td class="th-td-padding"><?= get_engine($hydrant->engine)->name; ?></td>
			</tr>
			<tr>
				<th class="th-td-padding text-left">Typ</th>
				<td class="th-td-padding"><?= $hydrant->type ?></td>
			</tr>
			<?php
			if(!$hydrant->checkbyff){
			?>
			<tr>
				<th class="th-td-padding text-left">Prüfung durch</th>
				<td class="th-td-padding">Stadtwerke Landshut</td>
			</tr>
			<?php
			}
			if(!$hydrant->operating){
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
	
<div class="custom-control custom-checkbox">
  <input type="checkbox" class="custom-control-input" id="toogleMaps">
  <label class="custom-control-label" for="toogleMaps">Verwende Google Maps</label>
</div>
<br>

	<?php 
    if($mapURL != null){
        echo "<img id='map' class='rounded mx-auto' width='" . $config["mapView"]["width"] . "' src='" . $mapURL . "' style='display: block;'>";
    }
    createHydrantGoogleMap(array($hydrant), false, true, $config["mapView"]["height"] . "px", $config["mapView"]["widewidth"] . "px");
    
}
?>