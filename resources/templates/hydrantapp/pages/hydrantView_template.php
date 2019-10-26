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
		</tbody>
	</table>
		
	<?php 
    if($mapURL != null){
        echo "<img class='rounded mx-auto d-block' width='" . $config["mapView"]["width"] . "' src='" . $mapURL . "'>";
    }
}
?>
