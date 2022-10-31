<?php 
require_once 'reportTable.php';
?>

<div class="table-responsive">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th>Freigabe durch Wachbeauftragten</th>
				<td>
					<?php if(!$report->getManagerApproved()){ 
						echo "Bericht wurde nicht vom zuständigen Wachbeauftragten überprüft";
					} else {
						echo "Bericht wurde vom Wachbeauftragten überprüft und freigegeben";
					} ?>
				</td>
			</tr>
			<?php if($report->getManagerApprovedDate() != null && $currentUser->hasPrivilegeByName(Privilege::FFADMINISTRATION)){ ?>
			<tr>
				<th>Freigegeben am</th>
				<td>
					<?= date($config ["formats"] ["datetime"], strtotime($report->getManagerApprovedDate())); ?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<th>EMS-Eintrag</th>
				<td>
					<?php if(!$report->getEmsEntry()){ 
						echo "Bericht ist nicht in EMS angelegt";
					} else {
						echo "Bericht ist in EMS angelegt";
					} ?>
				</td>
			</tr>
			<?php if($report->getEventUuid() != NULL){ ?>
			<tr>
				<th>Aus Wache generiert</th>
				<td><a href="<?= $config["urls"]["guardianapp_home"] . "/events/view/".$report->getEventUuid() ?>">Zur Wache</a></td>
			</tr>	
			<?php } ?>
		</tbody>
	</table>
</div>

<a href='<?=$config["urls"]["guardianapp_home"] ?>/reports/overview' class='btn btn-outline-primary'>Zurück</a>
<div class="dropdown float-right">
	<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">Berichts-Optionen</button>
	<div class="dropdown-menu">
		<a class="dropdown-item" target="_blank" href="<?= $config["urls"]["guardianapp_home"] . "/reports/view/". $report->getUuid() . "/file"; ?>">PDF anzeigen</a>
		<a class="dropdown-item" target="_blank" href="<?= $config["urls"]["guardianapp_home"] . "/reports/view/". $report->getUuid() . "/file&force=true"; ?>">PDF neu erzeugen</a>
		<div class="dropdown-divider"></div>
	<?php if($currentUser->hasPrivilegeByName(Privilege::EVENTMANAGER)) { ?>
		<?php if(!$report->getEmsEntry()){ ?>
			<a href="#" class="dropdown-item" data-toggle='modal' data-target='#confirmEms'>Bericht in EMS angelegt</a>
		<?php } else { ?>
			<a href="#" class="dropdown-item" data-toggle='modal' data-target='#removeEms'>Bericht in EMS entfernt</a>
		<?php } ?>
			<div class="dropdown-divider"></div>
		<?php if(!$report->getManagerApproved()){ ?>
			<a href="#" class="dropdown-item" data-toggle='modal' data-target='#managerApprove'>Bericht freigeben</a>
		<?php } else { ?>
			<a href="#" class="dropdown-item" data-toggle='modal' data-target='#managerApproveRemove'>Freigabe entfernen</a>
		<?php } ?>
			<div class="dropdown-divider"></div>
	<?php } ?> 
			<?php if( ! $report->hasOldStaffFormat() ) {?>
			<a class="dropdown-item" href="<?= $config["urls"]["guardianapp_home"] . "/reports/edit/". $report->getUuid(); ?>">Bearbeiten</a>
			<?php } else { ?>
			    <span class="dropdown-item">Bearbeiten nicht möglich!</span>
			<?php  } ?>
		<?php if($currentUser->hasPrivilegeByName(Privilege::EVENTMANAGER)) { ?>
			<a href="#" class="dropdown-item" data-toggle='modal' data-target='#confirmDelete'>Löschen</a>
		<?php } ?> 
	</div>
</div>
	
<?php 
createDialog('managerApproveRemove', "Freigabe für diesen Bericht entfernen?", "managerApproveRemove");
createDialog('managerApprove', "Bericht für Abrechnung freigeben?", "managerApprove");
createDialog('confirmEms', "Wurde die Wache in EMS angelegt?", "emsEntry");
createDialog('removeEms', "Wurde der Eintrag dieser Wache aus EMS entfernt/nicht angelegt?", "emsEntryRemoved");
createDialog('confirmDelete', "Bericht wirklich löschen?", "delete");
?>