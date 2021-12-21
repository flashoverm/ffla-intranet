<?php
global $config;
?>
<tr>
	<td class="text-center"><span class='d-none'><?= strtotime($data->getDate()) ?></span><?= date($config ["formats"] ["date"], strtotime($data->getDate())); ?></td>
	<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($data->getStartTime())); ?></td>
	<td class="text-center">
		<?php
		if ($data->getEndTime() != 0) {
			echo date($config ["formats"] ["time"], strtotime($data->getEndTime()));
		} else {
			echo " - ";
		} ?>
	</td>
	<td class="text-center"><?= $data->getType()->getType() ?></td>
	<td class="text-center"><?= $data->getTitle() ?></td>
	<td class="text-center"><?= $data->getEngine()->getName() ?></td>
	<td class="text-center">
		<?php
		if($data->getNoIncidents()){
		    echo " keine ";
		} else {
		    echo " siehe Bericht ";
		} ?>
	</td>
	<td class="text-center">
		<?php
		if($data->getManagerApproved()){
		    echo " &#10003; ";
		} else {
			echo " &ndash; ";
		} ?>
	</td>
	<?php if(!empty($options['showEMS'])){ ?>
	<td class="text-center">
		<?php
		if($data->getEmsEntry()){
		    echo " &#10003; ";
		} else {
			echo "<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#confirmEms" . $data->getUuid() . "' title='Wache als in EMS angelegt markieren'>EMS</button>";
			createDialog("confirmEms" . $data->getUuid(), "Wurde die Wache in EMS angelegt?", null, "emsEntry", $data->getUuid());
		} ?>
	</td>
	<?php } ?>
	<td class="text-center">
		<a class="btn btn-primary btn-sm" href="<?=$config["urls"]["guardianapp_home"] . "/reports/view/" . $data->getUuid() ?>">Anzeigen</a>
	</td>
</tr>