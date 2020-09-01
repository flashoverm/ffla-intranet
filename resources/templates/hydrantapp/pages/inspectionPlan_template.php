<?php
if (! count ( $hydrants )) {
	showInfo ( "Keine Hydranten ausgewählt" );
} else {
?>
	<html>
		<?php 
		require_once TEMPLATES_PATH . "/head.php";
		setPrintToLandscape();
		?>
		<body>
			<div id="overlay" style="display:inline;">
		 		<div class="loader"></div>
		 	</div>
		 	
			<div class="print landscape">
				<?php
				global $hydrant_criteria;
				
				$variables = array(
						'title' => "Prüfbericht",
						'criteria' => $hydrant_criteria,
						'hydrants' => 	$hydrants
				);
				
				renderContentFile($config["apps"]["hydrant"], "inspectionDetails/inspectionPDF_template.php", $variables);
				?>
			</div>

		 	<div class="print landscape" id="mapplaceholder">
				<?php		
				createHydrantGoogleMap($hydrants, true, true, '100%', '100%', true);
				?>
			</div>

		</body>
	</html>
	<?php
}
?>