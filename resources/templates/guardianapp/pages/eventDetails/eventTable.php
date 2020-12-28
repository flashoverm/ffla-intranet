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
			<tr>
				<th>Funktion</th>
				<th>Personal</th>
				<th></th>
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
							if($event->getStaffConfirmation() && $entry->getUnconfirmed()){
								echo "<br><i>Bestätigung ausstehend</i>";
							}
						}
					?>
				</td>
				<td><?php
					if( ! isset($print) ){
						if ($entry->getUser() == NULL and $relevant) {
							if(isset($_SESSION['intranet_userid']) && $eventController->isUserManagerOrCreator($currentUser->getUuid(), $event->getUuid())){
								echo "<a class='btn btn-primary btn-sm' href='" . $config["urls"]["guardianapp_home"] . "/events/" . $event->getUuid() . "/assign/" . $entry->getUuid() . "'>Einteilen</a>";
							}
							?>
							<a class='btn btn-primary btn-sm' href='<?= $config["urls"]["guardianapp_home"] . "/events/" . $event->getUuid() . "/subscribe/" . $entry->getUuid() ?>'>Eintragen</a>
							<?php
						}
						
						if ($entry->getUser() != NULL and isset($_SESSION['intranet_userid']) and $eventController->isUserManagerOrCreator($currentUser->getUuid(), $event->getUuid()) and $relevant and $event->getStaffConfirmation()) {
							if($entry->getUnconfirmed()){
							?>
	    						<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#confirmConfirmation<?= $entry->getUuid() ?>'>Bestätigen</button>
	    						<?php 
	    						createDialog('confirmConfirmation' . $entry->getUuid(), "Personal wirklich bestätigen?", null, "confirmstaffid", $entry->getUuid());
	        					} else { 
	        					?>
								<button type='button' class='btn btn-outline-primary btn-sm' disabled>Bestätigt</button>
								<?php
						 	}
						} 
						
						if($entry->getUser() != NULL && isset($_SESSION['intranet_userid']) && $relevant){
							
							if ($entry->getUser()->getUuid() == $_SESSION['intranet_userid']) {
								// Remove by user himself
								?>
								<button type='button' class='btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#confirmUnscribeByUser<?= $entry->getUuid() ?>'>Austragen</button>
								<?php 
								createDialog('confirmUnscribeByUser' . $entry->getUuid(), "Personal wirklich austragen?", null, "removestaffbyuserid", $entry->getUuid());
							} else if ($eventController->isUserManagerOrCreator($currentUser->getUuid(), $event->getUuid())) {
		        				// Remove by manager
		        				?>
								<button type='button' class='btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#confirmUnscribe<?= $entry->getUuid() ?>'>Entfernen</button>
								<?php 
								createDialog('confirmUnscribe' . $entry->getUuid(), "Personal wirklich austragen?", null, "removestaffid", $entry->getUuid());
		        			}
						}
					} ?>
				</td>
			</tr>
			<?php
			}
			?>

	