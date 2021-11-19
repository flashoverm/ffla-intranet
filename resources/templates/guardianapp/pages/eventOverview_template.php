<ul class="nav nav-tabs mb-3">
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'current'){ echo "active"; } ?>" href="<?= $config["urls"]["guardianapp_home"] ?>/events">Aktuelle Wachen</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'past'){ echo "active"; } ?>" href="<?= $config["urls"]["guardianapp_home"] ?>/events/past">Vergangene Wachen</a>
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
	} else {
		$options = array(
				'showOccupation' => true,
				'showDelete' => true,
				'showPublic' => true,
		);
	}
	
	render(TEMPLATES_PATH . "/guardianapp/elements/event_table.php", $events, $options);
}
?>