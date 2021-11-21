<ul class="nav nav-tabs mb-3">
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'open'){ echo "active"; } ?>" href="<?= $config["urls"]["employerapp_home"] ?>/confirmations/overview">Offene/Abgelehnte Anfragen</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'accepted'){ echo "active"; } ?>" href="<?= $config["urls"]["employerapp_home"] ?>/confirmations/accepted">Akzeptierte Anfragen</a>
	</li>
</ul>

<?php
if ( ! ( count ( $confirmations ) || ( isset($declined) && count ( $declined ) ) ) ) {
	showInfo ( "Keine Anträge vorhanden" );
} else {
	
	if($tab == 'open'){
		
		//Show additional declined table if present
		if(count ( $declined ) ){
			
			echo '<h4 class="my-3">Abgelehnte Anfragen</h4>';
			
			$options = array(
					'showReason' => true,
					'showUserOptions' => true,
			);
			
			render(TEMPLATES_PATH . "/employerapp/elements/confirmation_table.php", $declined, $options);
						
			if(count ( $confirmations ) ){
				echo '<h4 class="my-3">Offene Anfragen</h4>';
			}
		}
		
		if(count ( $confirmations ) ){
			
			$options = array(
					'showUserOptions' => true,
			);
			
			render(TEMPLATES_PATH . "/employerapp/elements/confirmation_table.php", $confirmations, $options);
		}
		
	} else {
		$options = array(
				'showViewConfirmation' => true,
		);
		
		render(TEMPLATES_PATH . "/employerapp/elements/confirmation_table.php", $confirmations, $options);
	}
}
    