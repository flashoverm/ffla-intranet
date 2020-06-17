<?php
require_once LIBRARY_PATH . '/db_engines.php';
require_once LIBRARY_PATH . '/db_user.php';
require_once LIBRARY_PATH . '/db_staffpositions.php';

$relevant = false;
$dateNow = getdate();
$now = strtotime( $dateNow['year']."-".$dateNow['mon']."-".($dateNow['mday']) );

if(strtotime($event->date) >= $now){
	$relevant = true;
} else {
    showInfo("Diese Wache hat bereits stattgefunden, Bearbeitung nicht mehr möglich - <a href='" . $config["urls"]["guardianapp_home"] . "/reports/new/" . $event->uuid . "'>Bericht erstellen</a>");
}

if ($isCreator) {
	if($relevant){
		showInfo ( "Du bist Ersteller dieser Wache - <a href='" . $config["urls"]["guardianapp_home"] . "/events/" . $event->uuid . "/edit'>Bearbeiten</a>" );
	} else {
		showInfo ( "Du bist Ersteller dieser Wache" );
	}
		
	if($otherEngine != null){
		showInfo("Diese Wache ist " . $otherEngine->name . " zugewiesen");
	}
}

?>

<div class="table-responsive">
	<table class="table table-bordered">
		<tbody>
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
				<th colspan="3"><?= $event->title; ?></th>
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
					if ($entry->user == NULL and $relevant) {
						
						if(isset($_SESSION['intranet_userid']) && is_user_manager_or_creator($event->uuid, $_SESSION['intranet_userid'])){
							echo "<a class='btn btn-primary btn-sm' href='" . $config["urls"]["guardianapp_home"] . "/events/" . $event->uuid . "/assign/" . $entry->uuid . "'>Einteilen</a>";
							
						} else {
							echo "<a class='btn btn-primary btn-sm' href='" . $config["urls"]["guardianapp_home"] . "/events/" . $event->uuid . "/subscribe/" . $entry->uuid . "'>Eintragen</a>";
						}
					}
					
					
					if ($entry->user != NULL and isset($_SESSION['intranet_userid']) and is_user_manager_or_creator($event->uuid, $_SESSION['intranet_userid']) and $relevant and $event->staff_confirmation) {
						if($entry->unconfirmed){
						?>
    						<button type='button' class='btn btn-primary btn-sm' data-toggle='modal' data-target='#confirmConfirmation<?= $entry->uuid ?>'>Bestätigen</button>
    						<?php 
    						createDialog('confirmConfirmation' . $entry->uuid, "Personal wirklich bestätigen?", null, "confirmstaffid", $entry->uuid);
        					?>
					<?php 	} else { ?>
							<button type='button' class='btn btn-outline-primary btn-sm' disabled>Bestätigt</button>
					<?php 	}
					} 
					
					if ($entry->user != NULL and isset($_SESSION['intranet_userid']) and (is_user_manager_or_creator($event->uuid, $_SESSION['intranet_userid']) || $entry->user == $_SESSION['intranet_userid']) and $relevant) {?>
						<button type='button' class='btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#confirmUnscribe<?= $entry->uuid ?>'>Austragen</button>
						<?php 
						createDialog('confirmUnscribe' . $entry->uuid, "Personal wirklich austragen?", null, "removestaffid", $entry->uuid);
        			} ?>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<td colspan="3">
					<b>Link:&nbsp;</b> 
					<p id="link"><?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/".$event->uuid; ?></p>
					<button id="btnCpy" onClick='copyToClipBoard()' class='btn btn-primary btn-sm'>Link kopieren</button>
					<a href='<?= $config ["urls"] ["guardianapp_home"] . '/events/' . $event->uuid . "/calender"; ?>' target="_blank" class='btn btn-primary btn-sm'>Kalendereintrag</a>
					
				</td>
			</tr>
			<?php
			if(userLoggedIn() && (is_user_manager_or_creator($event->uuid, $_SESSION['intranet_userid']) || current_user_has_privilege(EVENTADMIN))){
			    $creator = get_user($event->creator);
			?>
			    <tr>
			         <th colspan="1">Erstellt von</th>
			         <td colspan="2"><?= $creator->firstname . " " . $creator->lastname ?></td>
				</tr>
			<?php 
			}
			?>
			<tr>
				<th colspan="1">Bericht erstellen</th>
				<td colspan="2"><a href='<?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/".$event->uuid; ?>' target='_blank'><?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/".$event->uuid; ?></a></td>
			</tr>
		</tbody>
	</table>
	<?php
	if($loggedIn){
	    echo "<form action='" . $config["urls"]["guardianapp_home"] . "/events/" . $event->uuid . "' method='post'>
                  <a href='" . $config["urls"]["guardianapp_home"] . "/events' class='btn btn-outline-primary'>Zurück</a>
				  <div class='float-right'>";
	    
	    if(!$event->published){
	        if(is_user_manager_or_creator($event->uuid, $_SESSION['intranet_userid']) and $relevant) {?>
	          	<a class='btn btn-primary' href='<?= $config["urls"]["guardianapp_home"] ?>/events/<?= $event->uuid ?>/edit'>Bearbeiten</a>
                <span class='d-inline-block' data-toggle='tooltip' title='Andere Züge über Wache informieren'>
			  		<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#confirmPublish<?= $event->uuid ?>'>Veröffentlichen</button>
                </span>
				<?php 
				createDialog('confirmPublish' . $event->uuid, "Wache veröffentlichen <br>(E-Mail an alle Wachbeauftragen)?", 'publish');
				?>
            <?php 
	        } else {
	            echo "<button type='button' class='btn btn-outline-primary ml-1' disabled='disabled' >Wache ist nicht öffentlich</button>";   
	        }
		} else {
			if(is_user_manager_or_creator($event->uuid, $_SESSION['intranet_userid']) and $relevant) {
				echo "<a class='btn btn-primary' href='" . $config["urls"]["guardianapp_home"] . "/events/" . $event->uuid . "/edit'>Bearbeiten</a>";
			}
		    echo "<button type='button' class='btn btn-outline-primary ml-1' disabled='disabled' >Wache ist öffentlich</button>";

		}
	    echo "</div></form>";
	}
	?>
</div>
<script>

function copyToClipBoard(){
	link = document.getElementById("link");
	el = document.createElement('textarea');
	el.value = link.childNodes[0].nodeValue;
	el.setAttribute('readonly', '');
	el.style.position = 'absolute';
	el.style.left = '-9999px';
	document.body.appendChild(el);
	el.select();
	document.execCommand('copy');
	document.body.removeChild(el);

	btn = document.getElementById("btnCpy");
	btn.className  = "btn btn-outline-success btn-sm";
	btn.firstChild.nodeValue = "Link kopiert";
}

</script>