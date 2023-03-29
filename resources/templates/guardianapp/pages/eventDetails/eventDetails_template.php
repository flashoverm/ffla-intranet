<?php

$relevant = false;
$dateNow = getdate();
$now = strtotime( $dateNow['year']."-".$dateNow['mon']."-".($dateNow['mday']) );

if( ! $event->isCanceled()){
    if(strtotime($event->getDate()) >= $now){
        $relevant = true;
    } else {
        showInfo("Diese Wache hat bereits stattgefunden, Personaländerungen nicht mehr möglich - <a href='" . $config["urls"]["guardianapp_home"] . "/reports/new/" . $event->getUuid() . "'>Bericht erstellen</a>");
    }
} else {
    showInfo("Diese Wache wurde abgesagt, Personaländerungen nicht mehr möglich");
}


if ($isCreator) {
	if($relevant){
		showInfo ( "Du bist Ersteller dieser Wache - <a href='" . $config["urls"]["guardianapp_home"] . "/events/edit/" . $event->getUuid() . "'>Bearbeiten</a>" );
	} else {
		showInfo ( "Du bist Ersteller dieser Wache" );
	}
		
	if($otherEngine != null){
		showInfo("Diese Wache ist " . $otherEngine->getName() . " zugewiesen");
	}
}

	require_once 'eventTable.php';
	?>
		<table class="table table-bordered">
			<tbody>
				<tr>
				<td colspan="3">
					<b>Link:&nbsp;</b> 
					<p id="link"><?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/view/".$event->getUuid(); ?></p>
					<button id="btnCpy" onClick='copyToClipBoard()' class='btn btn-primary btn-sm'>Link kopieren</button>
					<a target='_blank' class='btn btn-primary btn-sm' href='<?= $config["urls"]["guardianapp_home"] . "/events/view/" .  $event->getUuid(); ?>/print'>Wache drucken</a>
					<a href='<?= $config ["urls"] ["guardianapp_home"] . '/events/calender/' . $event->getUuid(); ?>' target="_blank" class='btn btn-primary btn-sm'>Kalendereintrag</a>
				</td>
			</tr>
			<?php if( ! $event->isCanceled()){ ?>
			<tr>
				<th colspan="1">Bericht erstellen</th>
				<td colspan="2"><a href='<?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/" . $event->getUuid() ?>' target='_blank'><?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/" . $event->getUuid() ?></a></td>
			</tr>
			<?php
	        }
	        
			if($currentUser && $guardianUserController->isUserAllowedToEditEvent( $currentUser, $event->getUuid()) ){
			?>
			    <tr>
			         <th colspan="1">Erstellt von</th>
			         <td colspan="2"><?= $event->getCreator()->getFullName() ?></td>
				</tr>
    			<?php 
    			if( $event->isCanceled() ){
    			?>
    			    <tr>
        		         <th colspan="1">Abgesagt von</th>
        		         <td colspan="2"><?php if($event->getCanceledBy() != null) { echo $event->getCanceledBy()->getFullName(); } else { echo " - "; } ?></td>
        			</tr>
				<?php
    			}
			}
			if( $event->isCanceled() && $event->getCancelationReason() != null){
		    ?>
			    <tr>
    		         <th colspan="1">Grund der Absage</th>
    		         <td colspan="2"><?= $event->getCancelationReason() ?></td>
    			</tr>
			<?php
			}
		    ?>
		</tbody>
	</table>

	<?php
	if($currentUser){
	?>
	    <form action='<?= $config["urls"]["guardianapp_home"] . "/events/view/" . $event->getUuid() ?>' method='post'>
			<a href='<?= $config["urls"]["guardianapp_home"] . "/events/overview" ?>' class='btn btn-outline-primary'>Zurück</a>
			<div class='float-right'>
			<?php
			
			    if( ! $event->getPublished()){
			    	if( $guardianUserController->isUserAllowedToEditEvent($currentUser, $event->getUuid()) and $relevant) {?>
		                <span class='d-inline-block' data-toggle='tooltip' title='Andere Züge über Wache informieren'>
					  		<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#confirmPublish<?= $event->getUuid() ?>'>Veröffentlichen</button>
		                </span>
						<?php 
						createDialog('confirmPublish' . $event->getUuid(), "Wache veröffentlichen <br>(E-Mail an alle Wachbeauftragen)?", 'publish');
						?>
		            <?php 
			        } else {
			            echo "<button type='button' class='btn btn-outline-primary mr-1' disabled='disabled' >Wache ist nicht öffentlich</button>";
			        }
				} else {
				        echo "<button type='button' class='btn btn-outline-primary mr-1' disabled='disabled' >Wache ist öffentlich</button>";
			    }
				
				if( ! $event->isCanceled() && $relevant ){
				?>
				    <button type='button' class='btn btn-primary' data-toggle='modal' data-target='#confirmCancel'>Absagen</button>
	    			<div class='modal' id='confirmCancel'>
        				<div class='modal-dialog'>
        					<div class='modal-content'>
        						<form method="post" action="">
        							<div class='modal-header'>
        								<h4 class='modal-title'>Wache absagen?</h4>
        								<button type='button' class='close' data-dismiss='modal'>&times;</button>
        							</div>
        							<div class='modal-body'>
        								<div class="form-group">
        									<input type="text" required="required" placeholder="Grund eingeben" class="form-control" 
        									name="reason" id="reason" >
        								</div>
        								<div><small>Absage kann nicht rückgängig gemacht werden!</small></div>
        							</div>
        							<div class='modal-footer'>
        								<input type='submit' name="request" value='Ja' class='btn btn-primary'/>
        								<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
        							</div>
        						</form>
        					</div>
        				</div>
        			</div>
				<?php
				}
				
				if( ! $event->isCanceled() && $guardianUserController->isUserAllowedToEditEvent($currentUser, $event->getUuid()) ) { ?>
					<a class='btn btn-primary' href='<?= $config["urls"]["guardianapp_home"] ?>/events/edit/<?= $event->getUuid() ?>'>Bearbeiten</a>
				<?php } ?>
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