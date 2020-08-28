<?php
if (! count ( $hydrants )) {
	showInfo ( "Keine Hydranten ausgewählt" );
} else {
	
	//createHydrantGoogleMap($hydrants, true, true);
}
?>
<style>
.dynamic-map {
	height: 100%;
	width: 100%;
}
</style>

<?php 
global $hydrant_criteria;

$variables = array(
	'title' => "Prüfbericht",
	'criteria' => $hydrant_criteria,
	'hydrants' => 	$hydrants
	);

renderContentFile($config["apps"]["hydrant"], "inspectionDetails/inspectionPDF_template.php", $variables);
?>