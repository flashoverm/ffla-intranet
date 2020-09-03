<?php 
require_once realpath(dirname(__FILE__) . "/../../../config.php");

if(isset($inspection) || isset($hydrants)){
	
	if (! count ( $hydrants )) {
		showInfo ( "Keine Hydranten ausgewählt" );
	} else {
	?>
	<script>
		document.body.style.width='27.7cm';
		document.body.classList.remove("landscape");
		$('html,body').scrollTop(0);
	</script>
	<div class='landscape' style='padding-bottom: 10mm'>
		<?php
		require_once TEMPLATES_PATH . "/hydrantapp/pages/inspectionDetails/inspectionTable.php";
		?>
	</div>
 	<div class='landscape' id="mapplaceholder" style='padding-top: 10mm'>
		<?php		
		createHydrantGoogleMap($hydrants, true, true, '100%', '100%', true);
		?>
	</div>
	<?php
	}
}
?>