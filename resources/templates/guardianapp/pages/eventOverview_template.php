<ul class="nav nav-tabs mb-3">
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'current'){ echo "active"; } ?>" href="<?= $config["urls"]["guardianapp_home"] ?>/events/overview">Aktuelle Wachen</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'subscribed'){ echo "active"; } ?>" href="<?= $config["urls"]["guardianapp_home"] ?>/events/subscribed">Eingetragene Wachen</a>
	</li>
	<?php 
	if ($currentUser->hasPrivilegeByName(Privilege::EVENTMANAGER) ){
	?>
	    <li class="nav-item">
	    	<a class="nav-link <?php if($tab == 'unconfirmed'){ echo "active"; } ?>" href="<?= $config["urls"]["guardianapp_home"] ?>/events/unconfirmed">Bestätigung ausstehend</a>
	    </li>
	<?php
	}
	?>
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'past'){ echo "active"; } ?>" href="<?= $config["urls"]["guardianapp_home"] ?>/events/past">Vergangene Wachen</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'canceled'){ echo "active"; } ?>" href="<?= $config["urls"]["guardianapp_home"] ?>/events/canceled">Abgesagte Wachen</a>
	</li>
</ul>

<?php
if (!isset($events) || ! count ( $events ) ) {
	showInfo ( "Keine Wachen vorhanden" );
} else {
	
	if($tab == 'past'){ 
		$options = array(
				'showPublic' => true,
		);
	} else if($tab == 'canceled'){
	    $options = array(
	        'showPublic' => true,
	    );
	} else {
		$options = array(
			'showOccupation' => true,
			'showPublic' => true,
		);
	}
	
	render(TEMPLATES_PATH . "/guardianapp/elements/event_table.php", $events, $options);
}
?>