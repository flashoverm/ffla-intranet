<?php
global $config;
?>
<tr>
	<td class="text-center"><span class='d-none'><?= strtotime($data->getDate()) ?></span><?= date($config ["formats"] ["date"], strtotime($data->getDate())); ?></td>
	<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($data->getStartTime())); ?></td>
	<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($data->getEndTime())); ?></td>
	<td class="text-center"><?= $data->getDescription() ?></td>
	
	<?php if(!empty($options['showUserData'])){ ?>
		<td><?= $data->getUser()->getFullName() ?></td>
		<td><?= $data->getUser()->getEngine()->getName() ?></td>
	<?php } ?>
	<?php if(!empty($options['showReason'])){ ?>
		<td><?= $data->getReason() ?></td>
	<?php } ?>
	<?php if(!empty($options['showLastUpdate'])){
		if($data->getLastUpdate() == NULL){
			echo '<td class="text-center"><span class="d-none">0</span>-</td>';
		} else {
	?>
		<td class="text-center"><span class='d-none'><?= strtotime($data->getLastUpdate()) ?></span><?= date($config ["formats"] ["date"], strtotime($data->getLastUpdate())); ?></td>
	<?php
		}
	} ?>
	<?php if(!empty($options['showUserOptions'])){ ?>
		<td class="text-center">
			<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["employerapp_home"] . "/confirmations/" . $data->getUuid() . "/edit" ?>">Bearbeiten</a>
		</td>
		<td class="text-center">
			<form method="post" action="" class="mb-0">
				<input type="hidden" name="withdraw" value="<?= $data->getUuid() ?>"/>
				<input type="submit" value="Zurückziehen" class="btn btn-primary btn-sm"/>
			</form>
		</td>
	<?php } ?>
	<?php if(!empty($options['showAdminOptions'])){ ?>
	
		<td class="text-center">
			<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">Optionen</button>
				<div class="dropdown-menu">
					<form class="mb-0" method="post" action="" onsubmit="showLoader()" >
						<input type="hidden" name="confirmation" id="confirmation" value="<?= $data->getUuid() ?>"/>
						<input type="submit" name="accept" value="Annehmen"  class="dropdown-item"/>
						<div class="dropdown-divider"></div>
						<button type="button" class="dropdown-item" data-toggle='modal' data-target='#confirmDeleteStaff<?= $data->getUuid() ?>'>Ablehnen</button>
					</form>
				</div>
			</div>
			<div class='modal' id='confirmDeleteStaff<?= $data->getUuid() ?>'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<form method="post" action="">
							<div class='modal-header'>
								<h4 class='modal-title'>Grund für Ablehnung</h4>
								<button type='button' class='close' data-dismiss='modal'>&times;</button>
							</div>
							<div class='modal-body'>
								<div class="form-group">
									<input type="text" required="required" placeholder="Grund für Ablehnung" class="form-control" 
									name="reason" id="reason" >
								</div>
							</div>
							<div class='modal-footer'>
								<input type="hidden" name="confirmation" id="confirmation" value="<?= $data->getUuid() ?>"/>
								<input type='submit' name="decline" value='Ablehnen' class='btn btn-primary'/>
								<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</td>
	<?php } ?>
	<?php if(!empty($options['showViewConfirmation'])){ ?>
		<td class="text-center">
			<a class="btn btn-primary btn-sm" target="_blank" href="<?= $config["urls"]["employerapp_home"] . "/confirmations/".$data->getUuid() ."/file" ?>">Nachweis anzeigen</a>
		</td>
	<?php } ?>
</tr>