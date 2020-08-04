<?php 
require_once 'reportTable.php';
?>

<div class="table-responsive">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th>Freigabe durch Wachbeauftragten</th>
				<td>
					<?php if(!$report->managerApproved){ 
						echo "Bericht wurde nicht vom zuständigen Wachbeauftragten überprüft";
					} else {
						echo "Bericht wurde vom Wachbeauftragten überprüft und freigegeben";
					} ?>
				</td>
			</tr>
			<tr>
				<th>EMS-Eintrag</th>
				<td>
					<?php if(!$report->emsEntry){ 
						echo "Bericht ist nicht in EMS angelegt";
					} else {
						echo "Bericht ist in EMS angelegt";
					} ?>
				</td>
			</tr>
			<?php if(isset($report->event)){ ?>
			<tr>
				<th>Aus Wache generiert</th>
				<td><a href="<?= $config["urls"]["guardianapp_home"] . "/events/".$report->event ?>">Zur Wache</a></td>
			</tr>	
			<?php } ?>
		</tbody>
	</table>
</div>

<a href='<?=$config["urls"]["guardianapp_home"] ?>/reports' class='btn btn-outline-primary'>Zurück</a>
<div class="dropdown float-right">
	<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">Berichts-Optionen</button>
	<div class="dropdown-menu">
		<a class="dropdown-item" target="_blank" href="<?= $config["urls"]["guardianapp_home"] . "/reports/file/". $report->uuid; ?>">PDF anzeigen</a>
		<a class="dropdown-item" target="_blank" href="<?= $config["urls"]["guardianapp_home"] . "/reports/file/". $report->uuid . "&force=true"; ?>">PDF neu erzeugen</a>
		<div class="dropdown-divider"></div>
	
	<?php if(!$report->emsEntry){ ?>
		<a href="#" class="dropdown-item" data-toggle='modal' data-target='#confirmEms'>Bericht in EMS angelegt</a>
	<?php } else { ?>
		<a href="#" class="dropdown-item" data-toggle='modal' data-target='#removeEms'>Bericht in EMS entfernt</a>
	<?php } ?>
		<div class="dropdown-divider"></div>
	<?php if(!$report->managerApproved){ ?>
		<a href="#" class="dropdown-item" data-toggle='modal' data-target='#managerApprove'>Bericht freigeben</a>
	<?php } else { ?>
		<a href="#" class="dropdown-item" data-toggle='modal' data-target='#managerApproveRemove'>Freigabe entfernen</a>
	<?php } ?>
		<div class="dropdown-divider"></div>
		<a class="dropdown-item" href="<?= $config["urls"]["guardianapp_home"] . "/reports/". $report->uuid . "/edit"; ?>">Bearbeiten</a>
		<a href="#" class="dropdown-item" data-toggle='modal' data-target='#confirmDelete'>Löschen</a>
	</div>
</div>
	
<?php 
createDialog('managerApproveRemove', "Freigabe für diesen Bericht entfernen?", "managerApproveRemove");
createDialog('managerApprove', "Bericht für Abrechnung freigeben?", "managerApprove");
createDialog('confirmEms', "Wurde die Wache in EMS angelegt?", "emsEntry");
createDialog('removeEms', "Wurde der Eintrag dieser Wache aus EMS entfernt/nicht angelegt?", "emsEntryRemoved");
createDialog('confirmDelete', "Bericht wirklich löschen?", "delete");
?>