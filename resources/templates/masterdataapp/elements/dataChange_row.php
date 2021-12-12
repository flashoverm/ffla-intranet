<?php
global $config;
?>
<tr>
	<td class="text-center"><span class='d-none'><?= strtotime($data->getCreateDate()) ?></span><?= date($config ["formats"] ["datetime"], strtotime($data->getCreateDate())); ?></td>
	<td class="text-center"><?= DataChangeRequest::DATATYPE_TEXT[$data->getDataType()] ?></td>
	<td class="text-center" id="value<?= $data->getUuid() ?>"><?= $data->getNewValue() ?></td>
	<td class="text-center">
		<?php 
		if($data->getPerson() != NULL ){
			echo $data->getPerson();
		} else {
			echo "Antragsteller";
		}
		?>
	</td>
	
	<?php if(!empty($options['showUserData'])){ ?>
		<td><?= $data->getUser()->getFullName() ?></td>
		<td><?= $data->getUser()->getEngine()->getName() ?></td>
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
	<td class="text-center">
		<?php 
		if($data->getComment() != null){
		?>
			<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#showComment<?= $data->getUuid()?>">Anmerkungen</button><br>
		<?php
			createDialog('showComment' . $data->getUuid(), "Anmerkungen", null, null, null, null, "Schließen", nl2br($data->getComment()));
		} else {
			echo "Keine<br>";
		}
		if(!empty($options['showRequest'])){
			if($data->getFurtherRequest() != null){
			?>
				<button type="button" class="btn btn-outline-primary btn-sm mt-1" data-toggle="modal" data-target="#showRequest<?= $data->getUuid()?>">Rückfrage</button><br>
			<?php
				createDialog('showRequest' . $data->getUuid(), "Rückfrage", null, null, null, null, "Schließen", nl2br($data->getFurtherRequest()));
			} else {
				echo "Keine";
			}
		}
		?>
	</td>
	<?php if(!empty($options['showUserOptions'])){ ?>
		<td class="text-center">
			<a class="btn btn-primary btn-sm mr-1" href="<?= $config["urls"]["masterdataapp_home"] . "/datachangerequests/edit/" . $data->getUuid() ?>">Bearbeiten</a>
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
			<button type="button" class="btn btn-outline-primary btn-sm mb-1" id="copy<?= $data->getUuid() ?>" onclick="copyToClipboard('<?= $data->getUuid()?>')">Wert kopieren</button>
			
			<div class="dropdown">
				<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">Optionen</button>
				<div class="dropdown-menu">
					<form class="mb-0" method="post" action="" onsubmit="showLoader()" >
						<input type="hidden" name="datachangerequest" id="datachangerequest" value="<?= $data->getUuid() ?>"/>
						<input type="submit" name="done" value="Umgesetzt"  class="dropdown-item"/>
						<div class="dropdown-divider"></div>
						<button type="button" class="dropdown-item" data-toggle='modal' data-target='#sendRequest<?= $data->getUuid() ?>'>Rückfrage</button>
						<div class="dropdown-divider"></div>
						<input type="submit" name="declined" value="Ablehnen"  class="dropdown-item"/>
					</form>
				</div>
			</div>
			<div class='modal' id='sendRequest<?= $data->getUuid() ?>'>
				<div class='modal-dialog'>
					<div class='modal-content'>
						<form method="post" action="">
							<div class='modal-header'>
								<h4 class='modal-title'>Rückfrage zu Änderungsantrag</h4>
								<button type='button' class='close' data-dismiss='modal'>&times;</button>
							</div>
							<div class='modal-body'>
								<div class="form-group">
									<input type="text" required="required" placeholder="Rückfrage eingeben" class="form-control" 
									name="requesttext" id="requesttext" >
								</div>
							</div>
							<div class='modal-footer'>
								<input type="hidden" name="datachangerequest" id="datachangerequest" value="<?= $data->getUuid() ?>"/>
								<input type='submit' name="request" value='Absenden' class='btn btn-primary'/>
								<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</td>
	<?php } ?>
</tr>