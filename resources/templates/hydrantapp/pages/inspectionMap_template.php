<?php
if (! count ( $hydrants )) {
	showInfo ( "Keine Hydranten ausgewählt" );
} else {
 
    createHydrantGoogleMap($hydrants, true, true);
}
?>
<style>
.dynamic-map {
	height: 100%;
	width: 100%;
}
</style>
