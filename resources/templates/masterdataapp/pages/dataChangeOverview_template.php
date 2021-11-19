<ul class="nav nav-tabs mb-3">
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'open'){ echo "active"; } ?>" href="<?= $config["urls"]["masterdataapp_home"] ?>/datachangerequests">Offene Anfragen/Rückfragen</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'done'){ echo "active"; } ?>" href="<?= $config["urls"]["masterdataapp_home"] ?>/datachangerequests/done">Abgeschlossene Anfragen</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'declined'){ echo "active"; } ?>" href="<?= $config["urls"]["masterdataapp_home"] ?>/datachangerequests/declined">Abgelehnte Anfragen</a>
	</li>
</ul>

<?php
if ( ! ( count ( $dataChangeRequests ) || ( isset($furtherRequest) && count ( $furtherRequest ) ) ) ) {
	showInfo ( "Keine Stammdatenänderungen vorhanden" );
} else {
	
	if($tab == 'open'){
		
		//Show additional further request table if present
		if(count ( $furtherRequest ) ){

			echo '<h4>Rückfragen</h4>';
			echo '<h6 class="text-muted">Inhalt der Rückfrage wird unter "Bearbeiten" angezeigt</h6>';
			
			$options = array(
				'showUserOptions' => true,
			);
			
			render(TEMPLATES_PATH . "/masterdataapp/elements/dataChange_table.php", $furtherRequest, $options);
			
			if( count ( $dataChangeRequests ) ){
				echo '<h4 class="my-3">Offene Anfragen</h4>';
			}
		}
		
		if(count ( $dataChangeRequests ) ){
			
			$options = array(
					'showUserOptions' => true,
			);
			
			render(TEMPLATES_PATH . "/masterdataapp/elements/dataChange_table.php", $dataChangeRequests, $options);
		}
		
	} else {
		$options = array();
		
		render(TEMPLATES_PATH . "/masterdataapp/elements/dataChange_table.php", $dataChangeRequests, $options);
		
	}
}
