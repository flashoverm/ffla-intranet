<div class="table-responsive">
	<table class="table table-bordered">
		<tbody>
			<tr>
				<th colspan="1">Titel</th>
				<td colspan="2"><?= $event->title; ?></td>
			</tr>
			<tr>
				<th>Datum</th>
				<th>Wachbeginn</th>
				<th>Ende</th>
			</tr>
			<tr>				
				<td><?= date($config ["formats"] ["date"], strtotime($event->date)); ?></td>
				<td><?= date($config ["formats"] ["time"], strtotime($event->start_time)); ?></td>
				<td><?php
				if ($event->end_time != 0) {
				    echo date($config ["formats"] ["time"], strtotime($event->end_time));
				} else {
					echo " - ";
				}
				?></td>
			</tr>
			<tr>
				<td colspan="3"><?= $event->comment; ?></td>
			</tr>
			<tr>
				<th>Funktion</th>
				<th>Personal</th>
				<th></th>
			</tr>
			
			<?php
				foreach ( $staff as $entry ) {
					if ($entry->user != NULL) {
						$user = get_user ( $entry->user );
						$engine = get_engine ( $user->engine );
						$name = $user->firstname . " " . $user->lastname . " (" . $engine->name . ")";
					}
					?>
			<tr>
				<td><?= get_staffposition($entry->position)->position; ?></td>
				<td><?php 
						if($entry->user != NULL){
							echo $name; 
							if($event->staff_confirmation && $entry->unconfirmed){
								echo "<br><i>Bestätigung ausstehend</i>";
							}
						}
					?>
				</td>
				<td><?php
					if( ! isset($print) ){
						if ($entry->user == NULL and $relevant) {
							if(isset($_SESSION['intranet_userid']) && is_user_manager_or_creator($event->uuid, $_SESSION['intranet_userid'])){
								echo "<a class='btn btn-primary btn-sm' href='" . $config["urls"]["guardianapp_home"] . "/events/" . $event->uuid . "/assign/" . $entry->uuid . "'>Einteilen</a>";
							}
							?>
							<a class='btn btn-primary btn-sm' href='<?= $config["urls"]["guardianapp_home"] . "/events/" . $event->uuid . "/subscribe/" . $entry->uuid ?>'>Eintragen</a>
							<?php
						}
						
						if ($entry->user != NULL and isset($_SESSION['intranet_userid']) and is_user_manager_or_creator($event->uuid, $_SESSION['intranet_userid']) and $relevant and $event->staff_confirmation) {
							if($entry->unconfirmed){
							?>
	    						<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#confirmConfirmation<?= $entry->uuid ?>'>Bestätigen</button>
	    						<?php 
	    						createDialog('confirmConfirmation' . $entry->uuid, "Personal wirklich bestätigen?", null, "confirmstaffid", $entry->uuid);
	        					} else { 
	        					?>
								<button type='button' class='btn btn-outline-primary btn-sm' disabled>Bestätigt</button>
								<?php
						 	}
						} 
						
						if($entry->user != NULL and isset($_SESSION['intranet_userid']) && $relevant){
							
							if ($entry->user == $_SESSION['intranet_userid']) {
								// Remove by user himself
								?>
								<button type='button' class='btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#confirmUnscribeByUser<?= $entry->uuid ?>'>Austragen</button>
								<?php 
								createDialog('confirmUnscribeByUser' . $entry->uuid, "Personal wirklich austragen?", null, "removestaffbyuserid", $entry->uuid);
		        			} else if (is_user_manager_or_creator($event->uuid, $_SESSION['intranet_userid'])) {
		        				// Remove by manager
		        				?>
								<button type='button' class='btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#confirmUnscribe<?= $entry->uuid ?>'>Entfernen</button>
								<?php 
								createDialog('confirmUnscribe' . $entry->uuid, "Personal wirklich austragen?", null, "removestaffid", $entry->uuid);
		        			}
						}
					} ?>
				</td>
			</tr>
			<?php
			}
			?>

	