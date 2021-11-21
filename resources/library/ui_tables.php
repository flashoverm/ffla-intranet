<?php
function renderEventTable($events, $options = array()) {
	global $currentUser, $guardianUserController;
?>
	<div class="table-responsive">
		<table class="table table-hover table-striped" data-toggle="table" data-pagination="true" data-search="true">
			<thead>
				<tr>
					<th data-sortable="true" class="text-center">Datum</th>
					<th data-sortable="true" class="text-center">Beginn</th>
					<th data-sortable="true" class="text-center">Ende</th>
					<th data-sortable="true" class="text-center">Typ</th>
					<th data-sortable="true" class="text-center">Titel</th>
					<th data-sortable="true" class="text-center">Zuständig</th>
					<?php 
					if(!empty($options['showOccupation'])){
					?>
						<th data-sortable="true" class="text-center">Belegung</th>
					<?php 
					}
					if(!empty($options['showPublic'])){
					?>
					<th data-sortable="true" class="text-center">Öffentlich</th>
					<?php
					}
					?>
					<th></th>
					<?php
					if( !empty($options['showDelete']) &&
							$guardianUserController->isUserAllowedToEditSomeEvent($currentUser)){
						echo"<th></th>";
					}
					?>
				</tr>
			</thead>
			<tbody>
			<?php 
			foreach ( $events as $event ) {
				renderEventRow($event, $options);
			}
			?>
			</tbody>
		</table>
	</div>
<?php	 
}

function renderEventRow($event, $options = array()){
	global $guardianUserController, $config, $currentUser;
?>
	<tr>
		<td class="text-center"><span class='d-none'><?= strtotime($event->getDate()) ?></span><?= date($config ["formats"] ["date"], strtotime($event->getDate())); ?></td>
		<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($event->getStartTime())); ?></td>
		<td class="text-center">
			<?php
			if ($event->getEndTime() != 0) {
				echo date($config ["formats"] ["time"], strtotime($event->getEndTime()));
			} else {
				echo " &ndash; ";
			}
			?>
		</td>
		<td class="text-center"><?= $event->getType()->getType() ?></td>
		<td class="text-center"><?= $event->getTitle(); ?></td>
		<td class="text-center"><?= $event->getEngine()->getName(); ?></td>
		<?php 
		if(isset($options['showOccupation']) && $options['showOccupation']){
		?>
			<td class="text-center">
				<?php 
				if($event->isEventFull()){
					echo '<font color="green">' . $event->getOccupancy() . '</font>';
				} else {
					echo '<font color="red">' . $event->getOccupancy() . '</font>';
				}
			    ?>
			</td>
		<?php
		}
		if(!empty($options['showPublic'])){
		?>
		<td class="text-center">
			<?php
			if($event->getPublished()){
			    echo " &#10003; ";
			} else {
			    echo " - ";
			}
			?>
		</td>
		<?php
		}
		?>
		<td class="text-center">
			<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["guardianapp_home"] . "/events/view/".$event->getUuid() ?>">Anzeigen</a>
		</td>
		<?php
		if( !empty($options['showDelete']) && 
			$guardianUserController->isUserAllowedToEditEvent($currentUser, $event->getUuid())){
		?>
			<td class="text-center">
				<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#confirmDelete<?= $event->getUuid(); ?>">Löschen</button>
				<?php 
				createDialog('confirmDelete' . $event->getUuid(), "Wache wirklich löschen?", null, "delete", $event->getUuid());
				?>	
			</td>
		<?php 
		}
		?>
	</tr>
<?php
}




function renderConfirmationTable($confirmations, $options = array()) {
?>
	<div class="table-responsive">
		<table class="table table-hover table-striped" data-toggle="table" data-pagination="true" data-search="true">
			<thead>
				<tr>
					<th data-sortable="true" class="text-center">Datum</th>
					<th data-sortable="true" class="text-center">Beginn</th>
					<th data-sortable="true" class="text-center">Ende</th>
					<th data-sortable="true" class="text-center">Einsatz</th>
					<?php if(!empty($options['showUserData'])){ ?>
						<th data-sortable="true" class="text-center">Antragsteller</th>
						<th data-sortable="true" class="text-center">Löschzug</th>
					<?php } ?>
					<?php if(!empty($options['showReason'])){ ?>
						<th data-sortable="true" class="text-center">Grund für Ablehnung</th>
					<?php } ?>
					<?php if(!empty($options['showLastUpdate'])){ ?>
						<th data-sortable="true" class="text-center">Geändert</th>
					<?php } ?>
					<?php if(!empty($options['showUserOptions'])){ ?>
						<th></th>
						<th></th>
					<?php } ?>
					<?php if(!empty($options['showAdminOptions']) || !empty($options['showViewConfirmation'])){ ?>
						<th></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $confirmations as $confirmation ) {
				renderConfirmationRow($confirmation, $options);
			}
			?>
			</tbody>
		</table>
	</div>
<?php
}

function renderConfirmationRow($confirmation, $options = array()){
	global $config;
?>
	<tr>
		<td class="text-center"><span class='d-none'><?= strtotime($confirmation->getDate()) ?></span><?= date($config ["formats"] ["date"], strtotime($confirmation->getDate())); ?></td>
		<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($confirmation->getStartTime())); ?></td>
		<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($confirmation->getEndTime())); ?></td>
		<td class="text-center"><?= $confirmation->getDescription() ?></td>
		
		<?php if(!empty($options['showUserData'])){ ?>
			<td><?= $confirmation->getUser()->getFullName() ?></td>
			<td><?= $confirmation->getUser()->getEngine()->getName() ?></td>
		<?php } ?>
		<?php if(!empty($options['showReason'])){ ?>
			<td><?= $confirmation->getReason() ?></td>
		<?php } ?>
		<?php if(!empty($options['showLastUpdate'])){
			if($confirmation->getLastUpdate() == NULL){
				echo '<td class="text-center"><span class="d-none">0</span>-</td>';
			} else {
		?>
			<td class="text-center"><span class='d-none'><?= strtotime($confirmation->getLastUpdate()) ?></span><?= date($config ["formats"] ["date"], strtotime($confirmation->getLastUpdate())); ?></td>
		<?php
			}
		} ?>
		<?php if(!empty($options['showUserOptions'])){ ?>
			<td class="text-center">
				<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["employerapp_home"] . "/confirmations/edit/" . $confirmation->getUuid() ?>">Bearbeiten</a>
			</td>
			<td class="text-center">
				<form method="post" action="" class="mb-0">
					<input type="hidden" name="withdraw" value="<?= $confirmation->getUuid() ?>"/>
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
							<input type="hidden" name="confirmation" id="confirmation" value="<?= $confirmation->getUuid() ?>"/>
							<input type="submit" name="accept" value="Annehmen"  class="dropdown-item"/>
							<div class="dropdown-divider"></div>
							<button type="button" class="dropdown-item" data-toggle='modal' data-target='#confirmDeleteStaff<?= $confirmation->getUuid() ?>'>Ablehnen</button>
						</form>
					</div>
				</div>
				<div class='modal' id='confirmDeleteStaff<?= $confirmation->getUuid() ?>'>
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
									<input type="hidden" name="confirmation" id="confirmation" value="<?= $confirmation->getUuid() ?>"/>
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
				<a class="btn btn-primary btn-sm" target="_blank" href="<?= $config["urls"]["employerapp_home"] . "/confirmations/view/".$confirmation->getUuid() ."/file" ?>">Nachweis anzeigen</a>
			</td>
		<?php } ?>
	</tr>
<?php
}




function renderDataChangeTable($dataChangeRequests, $options = array()) {
	?>
	<div class="table-responsive">
		<table class="table table-hover table-striped" data-toggle="table" data-pagination="true">
			<thead>
				<tr>
					<th data-sortable="true" class="text-center" >Erstellt</th>
					<th data-sortable="true" class="text-center" >Typ</th>
					<th data-sortable="true" class="text-center" >Neuer Wert</th>
					<th data-sortable="true" class="text-center" >Änderung bei</th>
					<?php if(!empty($options['showUserData'])){ ?>
						<th data-sortable="true" class="text-center">Antragsteller</th>
						<th data-sortable="true" class="text-center">Löschzug</th>
					<?php } ?>
					<?php if(!empty($options['showLastUpdate'])){ ?>
						<th data-sortable="true" class="text-center">Geändert</th>
					<?php } ?>
					<th class="text-center">
						Anmerkungen<?php if(!empty($options['showRequest'])){
							echo "/Rückfrage";
						}?>
					</th>
					<?php if(!empty($options['showUserOptions'])){ ?>
						<th></th>
						<th></th>
					<?php } ?>
					<?php if(!empty($options['showAdminOptions'])){ ?>
						<th></th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
			<?php foreach ( $dataChangeRequests as $dataChangeRequest ) {
				renderDataChangeRow($dataChangeRequest, $options);
			}
			?>
			</tbody>
		</table>
	</div>
<?php
}

function renderDataChangeRow(DataChangeRequest $dataChangeRequest, $options = array()){
	global $config;
?>
	<tr>
		<td class="text-center"><span class='d-none'><?= strtotime($dataChangeRequest->getCreateDate()) ?></span><?= date($config ["formats"] ["datetime"], strtotime($dataChangeRequest->getCreateDate())); ?></td>
		<td class="text-center"><?= DataChangeRequest::DATATYPE_TEXT[$dataChangeRequest->getDataType()] ?></td>
		<td class="text-center" id="value<?= $dataChangeRequest->getUuid() ?>"><?= $dataChangeRequest->getNewValue() ?></td>
		<td class="text-center">
			<?php 
			if($dataChangeRequest->getPerson() != NULL ){
				echo $dataChangeRequest->getPerson();
			} else {
				echo "Antragsteller";
			}
			?>
		</td>
		
		<?php if(!empty($options['showUserData'])){ ?>
			<td><?= $dataChangeRequest->getUser()->getFullName() ?></td>
			<td><?= $dataChangeRequest->getUser()->getEngine()->getName() ?></td>
		<?php } ?>
		
		<?php if(!empty($options['showLastUpdate'])){
			if($dataChangeRequest->getLastUpdate() == NULL){
				echo '<td class="text-center"><span class="d-none">0</span>-</td>';
			} else {
		?>
			<td class="text-center"><span class='d-none'><?= strtotime($dataChangeRequest->getLastUpdate()) ?></span><?= date($config ["formats"] ["date"], strtotime($dataChangeRequest->getLastUpdate())); ?></td>
		<?php
			}
		} ?>
		<td class="text-center">
			<?php 
			if($dataChangeRequest->getComment() != null){
			?>
				<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#showComment<?= $dataChangeRequest->getUuid()?>">Anmerkungen</button><br>
			<?php
				createDialog('showComment' . $dataChangeRequest->getUuid(), "Anmerkungen", null, null, null, null, "Schließen", nl2br($dataChangeRequest->getComment()));
			} else {
				echo "Keine<br>";
			}
			if(!empty($options['showRequest'])){
				if($dataChangeRequest->getFurtherRequest() != null){
				?>
					<button type="button" class="btn btn-outline-primary btn-sm mt-1" data-toggle="modal" data-target="#showRequest<?= $dataChangeRequest->getUuid()?>">Rückfrage</button><br>
				<?php
					createDialog('showRequest' . $dataChangeRequest->getUuid(), "Rückfrage", null, null, null, null, "Schließen", nl2br($dataChangeRequest->getFurtherRequest()));
				} else {
					echo "Keine";
				}
			}
			?>
		</td>
		<?php if(!empty($options['showUserOptions'])){ ?>
			<td class="text-center">
				<a class="btn btn-primary btn-sm mr-1" href="<?= $config["urls"]["masterdataapp_home"] . "/datachangerequests/edit/".$dataChangeRequest->getUuid() ?>">Bearbeiten</a>
			</td>
			<td class="text-center">
				<form method="post" action="" class="mb-0">
					<input type="hidden" name="withdraw" value="<?= $dataChangeRequest->getUuid() ?>"/>
					<input type="submit" value="Zurückziehen" class="btn btn-primary btn-sm"/>
				</form>
			</td>
		<?php } ?>
		<?php if(!empty($options['showAdminOptions'])){ ?>
		
			<td class="text-center">
				<button type="button" class="btn btn-outline-primary btn-sm mb-1" id="copy<?= $dataChangeRequest->getUuid() ?>" onclick="copyToClipboard('<?= $dataChangeRequest->getUuid()?>')">Wert kopieren</button>
				
				<div class="dropdown">
					<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">Optionen</button>
					<div class="dropdown-menu">
						<form class="mb-0" method="post" action="" onsubmit="showLoader()" >
							<input type="hidden" name="datachangerequest" id="datachangerequest" value="<?= $dataChangeRequest->getUuid() ?>"/>
							<input type="submit" name="done" value="Umgesetzt"  class="dropdown-item"/>
							<div class="dropdown-divider"></div>
							<button type="button" class="dropdown-item" data-toggle='modal' data-target='#sendRequest<?= $dataChangeRequest->getUuid() ?>'>Rückfrage</button>
							<div class="dropdown-divider"></div>
							<input type="submit" name="declined" value="Ablehnen"  class="dropdown-item"/>
						</form>
					</div>
				</div>
				<div class='modal' id='sendRequest<?= $dataChangeRequest->getUuid() ?>'>
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
									<input type="hidden" name="datachangerequest" id="datachangerequest" value="<?= $dataChangeRequest->getUuid() ?>"/>
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
<?php
}
