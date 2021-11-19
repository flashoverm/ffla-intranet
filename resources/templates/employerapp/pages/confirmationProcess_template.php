<ul class="nav nav-tabs mb-3">
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'open'){ echo "active"; } ?>" href="<?= $config["urls"]["employerapp_home"] ?>/confirmations/process">Offene Anfragen</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'accepted'){ echo "active"; } ?>" href="<?= $config["urls"]["employerapp_home"] ?>/confirmations/process/accepted">Akzeptierte Anfragen</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'declined'){ echo "active"; } ?>" href="<?= $config["urls"]["employerapp_home"] ?>/confirmations/process/declined">Abgelehnte Anfragen</a>
	</li>
</ul>

<?php
if ( ! count ( $confirmations ) ) {
	showInfo ( "Keine Anträge vorhanden" );
} else {
	
	if($tab == 'open'){
		$options = array(
				'showUserData' => true,
				'showAdminOptions' => true,
				'showLastUpdate' => true,
		);
	} else if($tab == 'declined'){
		$options = array(
				'showReason' => true,
				'showUserData' => true,
				'showLastUpdate' => true,
		);
	} else {
		$options = array(
				'showUserData' => true,
				'showViewConfirmation' => true,
				'showLastUpdate' => true,
		);
	}

	render(TEMPLATES_PATH . "/employerapp/elements/confirmation_table.php", $confirmations, $options);
}

    