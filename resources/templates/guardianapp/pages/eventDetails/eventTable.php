<div class="table-responsive">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th colspan="1">Titel</th>
				<td colspan="2"><?= $event->getTitle(); ?></td>
			</tr>
			<tr>
				<th>Datum</th>
				<th>Wachbeginn</th>
				<th>Ende</th>
			</tr>
			<tr>				
				<td><?= date($config ["formats"] ["date"], strtotime($event->getDate())); ?></td>
				<td><?= date($config ["formats"] ["time"], strtotime($event->getStartTime())); ?></td>
				<td><?php
				if ($event->getEndTime() != 0) {
				    echo date($config ["formats"] ["time"], strtotime($event->getEndTime()));
				} else {
					echo " - ";
				}
				?></td>
			</tr>
			<tr>
				<td colspan="3"><?= $event->getComment(); ?></td>
			</tr>
		</tbody>
	</table>
	
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th>Funktion</th>
				<th>Personal</th>
				<?php
				if( ! isset($print) ){
					if($currentUser && $guardianUserController->isUserAllowedToEditEvent($currentUser, $event->getUuid())){
						echo "<td></td>";
					}
					echo "<td></td>";
				} ?>
			</tr>
			
			<?php
				foreach ( $event->getStaff() as $entry ) {
					if ($entry->getUser() != NULL) {
						$name = $entry->getUser()->getFullNameWithEngine();
					}
					?>
			<tr>
				<td><?= $entry->getPosition()->getPosition() ?></td>
				<td>
					<?php 
					if ($entry->getUser() != NULL) {
						echo $entry->getUser()->getFullNameWithEngine();
					}
					?>
				</td>
				<?php
				if( ! isset($print) && $currentUser && $guardianUserController->isUserAllowedToEditEvent($currentUser, $event->getUuid())){
					echo "<td>";
					if($entry->getUser()){
						if($event->getStaffConfirmation() && $entry->getUnconfirmed()){
						?>
							<button class='btn btn-outline-primary btn-sm mb-1' disabled>Bestätigung ausstehend</button>
						<?php
						} else if($event->getStaffConfirmation() && ! $entry->getUnconfirmed()){
						?>
							<button class='btn btn-outline-success btn-sm mb-1' disabled>Bestätigt</button>
						<?php
						}
						if($entry->getUserAcknowledged()){
						?>
							<button class='btn btn-outline-success btn-sm mb-1' disabled>Zur Kenntnis genommen</button>
						<?php
						}
					}
					echo "</td>";
				}
				?>

				<?php
				if( ! isset($print) && $relevant){
					echo "<td>";
						if ( ! $entry->getUser()) {
							if(SessionUtil::userLoggedIn() && $eventController->isUserManagerOrCreator($currentUser->getUuid(), $event->getUuid())){
							?>
								<a class='btn btn-primary btn-sm mb-1' href='<?= $config["urls"]["guardianapp_home"] . "/events/" . $event->getUuid() . "/assign/" . $entry->getUuid() ?>'>Einteilen</a>
							<?php
							}
							?>
							<a class='btn btn-primary btn-sm mb-1' href='<?= $config["urls"]["guardianapp_home"] . "/events/" . $event->getUuid() . "/subscribe/" . $entry->getUuid() ?>'>Eintragen</a>
							<?php
						} else if(SessionUtil::userLoggedIn()){

							if ($eventController->isUserManagerOrCreator($currentUser->getUuid(), $event->getUuid()) && $event->getStaffConfirmation() && $entry->getUnconfirmed()) { ?>
		    					<button type='button' class='btn btn-primary btn-sm mb-1' data-toggle='modal' data-target='#confirmConfirmation<?= $entry->getUuid() ?>'>Bestätigen</button>
		    					<?php 
		    					createDialog('confirmConfirmation' . $entry->getUuid(), "Personal wirklich bestätigen?", null, "confirmstaffid", $entry->getUuid());
							} 
							if ($entry->getUser()->getUuid() == SessionUtil::getCurrentUserUUID()) {	// Remove by user himself ?>
								<button type='button' class='btn btn-outline-primary btn-sm mb-1' data-toggle='modal' data-target='#confirmUnscribeByUser<?= $entry->getUuid() ?>'>Austragen</button>
								<?php 
								createDialog('confirmUnscribeByUser' . $entry->getUuid(), "Personal wirklich austragen?", null, "removestaffbyuserid", $entry->getUuid());
								
							} else if ($eventController->isUserManagerOrCreator($currentUser->getUuid(), $event->getUuid())) {	// Remove by manager ?>
								<button type='button' class='btn btn-outline-primary btn-sm mb-1' data-toggle='modal' data-target='#confirmUnscribe<?= $entry->getUuid() ?>'>Entfernen</button>
								<?php 
								createDialog('confirmUnscribe' . $entry->getUuid(), "Personal wirklich austragen?", null, "removestaffid", $entry->getUuid());
		        			}
		        			
		        			if ($currentUser->getUuid() == $entry->getUser()->getUuid() && ! $entry->getUserAcknowledged()) { ?>
		    					<button type='button' class='btn btn-primary btn-sm mb-1' data-toggle='modal' data-target='#acknowledge<?= $entry->getUuid() ?>'>Zur Kenntnis nehmen</button>
		    					<?php 
		    					createDialog('acknowledge' . $entry->getUuid(), "Wachteilnahme zur Kenntnis nehmen?", null, "acknowledgeID", $entry->getUuid());
							}
						}
						echo "</td>";
					} ?>
				
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>

	