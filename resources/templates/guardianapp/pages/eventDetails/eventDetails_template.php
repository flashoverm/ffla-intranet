<?php

$relevant = false;
$dateNow = getdate();
$now = strtotime( $dateNow['year']."-".$dateNow['mon']."-".($dateNow['mday']) );

if(strtotime($event->getDate()) >= $now){
	$relevant = true;
} else {
    showInfo("Diese Wache hat bereits stattgefunden, Bearbeitung nicht mehr möglich - <a href='" . $config["urls"]["guardianapp_home"] . "/reports/new/" . $event->getUuid() . "'>Bericht erstellen</a>");
}

if ($isCreator) {
	if($relevant){
		showInfo ( "Du bist Ersteller dieser Wache - <a href='" . $config["urls"]["guardianapp_home"] . "/events/" . $event->getUuid() . "/edit'>Bearbeiten</a>" );
	} else {
		showInfo ( "Du bist Ersteller dieser Wache" );
	}
		
	if($otherEngine != null){
		showInfo("Diese Wache ist " . $otherEngine->getName() . " zugewiesen");
	}
}

	require_once 'eventTable.php';
	?>
				<tr>
				<td colspan="3">
					<b>Link:&nbsp;</b> 
					<p id="link"><?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/".$event->getUuid(); ?></p>
					<button id="btnCpy" onClick='copyToClipBoard()' class='btn btn-primary btn-sm'>Link kopieren</button>
					<a target='_blank' class='btn btn-primary btn-sm' href='<?= $config["urls"]["guardianapp_home"] . "/events/" .  $event->getUuid(); ?>/print'>Wache drucken</a>
					<a href='<?= $config ["urls"] ["guardianapp_home"] . '/events/' . $event->getUuid() . "/calender"; ?>' target="_blank" class='btn btn-primary btn-sm'>Kalendereintrag</a>
				</td>
			</tr>
			<tr>
				<th colspan="1">Bericht erstellen</th>
				<td colspan="2"><a href='<?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/" . $event->getUuid() ?>' target='_blank'><?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/" . $event->getUuid() ?></a></td>
			</tr>
			<?php
			if($currentUser && $guardianUserController->isUserAllowedToEditEvent( $currentUser, $event->getUuid()) ){
			?>
			    <tr>
			         <th colspan="1">Erstellt von</th>
			         <td colspan="2"><?= $event->getCreator()->getFullName() ?></td>
				</tr>
			<?php 
			}
			?>
		</tbody>
	</table>

	<?php
	if($currentUser){
	?>
	    <form action='<?= $config["urls"]["guardianapp_home"] . "/events/" . $event->getUuid() ?>' method='post'>
			<a href='<?= $config["urls"]["guardianapp_home"] . "/events" ?>' class='btn btn-outline-primary'>Zurück</a>
			<div class='float-right'>
			<?php
			    if( ! $event->getPublished()){
			    	if( $guardianUserController->isUserAllowedToEditEvent($currentUser, $event->getUuid()) and $relevant) {?>
			          	<a class='btn btn-primary' href='<?= $config["urls"]["guardianapp_home"] ?>/events/<?= $event->getUuid() ?>/edit'>Bearbeiten</a>
		                <span class='d-inline-block' data-toggle='tooltip' title='Andere Züge über Wache informieren'>
					  		<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#confirmPublish<?= $event->getUuid() ?>'>Veröffentlichen</button>
		                </span>
						<?php 
						createDialog('confirmPublish' . $event->getUuid(), "Wache veröffentlichen <br>(E-Mail an alle Wachbeauftragen)?", 'publish');
						?>
		            <?php 
			        } else {
			            echo "<button type='button' class='btn btn-outline-primary ml-1' disabled='disabled' >Wache ist nicht öffentlich</button>";   
			        }
				} else {
					if( $guardianUserController->isUserAllowedToEditEvent($currentUser, $event->getUuid()) and $relevant) {
						echo "<a class='btn btn-primary' href='" . $config["urls"]["guardianapp_home"] . "/events/" . $event->getUuid() . "/edit'>Bearbeiten</a>";
					}
				    echo "<button type='button' class='btn btn-outline-primary ml-1' disabled='disabled' >Wache ist öffentlich</button>";
		
				}
				?>
	    	</div>
	  	</form>
	<?php
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