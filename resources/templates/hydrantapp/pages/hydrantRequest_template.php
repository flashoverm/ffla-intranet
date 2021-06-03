<form onsubmit="showLoader()" action="" method="post">
	<div class="form-group">
		<label>Hydrant ID (HY):</label> <input type="number" class="form-control"
			required="required" name="hy" id="hy"
			placeholder="ID des Hydranten eingeben">
	</div>
	<input type="hidden" name="location" id="location" value=""/>
	<input type="submit" value="Hydrant anzeigen" class="btn btn-primary" id="submit">
</form>

<form class="mt-4" onsubmit="showLoader()" action="" method="post">
	<div class="form-group">
		<label>Suche nach Straße:</label> <input type="text" class="form-control"
			required="required" name="street" id="street"
			placeholder="Name der Straße">
	</div>
	<input type="hidden" name="locationStreet" id="locationStreet" value=""/>
	<input type="submit" value="Hydranten suchen" class="btn btn-primary" id="submitStreet">
</form>

<form class="mt-4" onsubmit="showLoader()" action="" method="post">
	<div class="form-group">
		<label>Suche nach Löschzug:</label> <select class="form-control" name="engine" required="required">
			<option value="" disabled selected>Löschzug auswählen</option>
			<?php foreach ( $engines as $option ) : ?>
				<option value="<?= $option->getUuid(); ?>"><?= $option->getName(); ?></option>
			<?php endforeach; ?>			
		</select>
	</div>
	<input type="hidden" name="locationEngine" id="locationEngine" value=""/>
	<input type="submit" value="Hydranten suchen" class="btn btn-primary" id="submitEngine">
</form>

<div class="custom-control custom-checkbox custom-checkbox-big mt-4">
	<input type="checkbox" class="custom-control-input" id="showLocation" name="showLocation" onClick="getLocation()">
	<label class="custom-control-label custom-control-label-big" for="showLocation" 
			id="labelShowLocation">Eigene Position auf Karte anzeigen</label>
</div>

<script>

$(function() {
    var availableTags = <?php echo json_encode($hydrantDAO->getStreetList()); ?>;
    $("#street").autocomplete({
        source: availableTags
    });
});
	
var x = document.getElementById("labelShowLocation");

function getLocation() {
	if (document.getElementById("showLocation").checked == true){
	    if (navigator.geolocation) {
	    	showHideSubmit();
	        navigator.geolocation.getCurrentPosition(showPosition, showError);
	    } else {
	        x.innerHTML = "Eigener Standord wird nicht unterstützt";
	    }
	}
}

function showHideSubmit(){
    var submit = document.getElementById("submit");
    var submitStreet = document.getElementById("submitStreet");
    var submitEngine = document.getElementById("submitEngine");
    
    if (submit.style.display === "none") {
        hideLoader();
    	submit.style.display = "block";
    	submitStreet.style.display = "block";
    	submitEngine.style.display = "block";
    } else {
        showLoader();
    	submit.style.display = "none";
    	submitStreet.style.display = "none";
    	submitEngine.style.display = "none";
    }
}
 
function showPosition(position) {
	showHideSubmit();
	
	document.getElementById("location").value = position.coords.latitude + "," + position.coords.longitude;
	document.getElementById("locationStreet").value = position.coords.latitude + "," + position.coords.longitude;
	document.getElementById("locationEngine").value = position.coords.latitude + "," + position.coords.longitude;
	
    x.innerHTML = "Eigene Position anzeigen (Position ermittelt)";
    
}

function showError(error) {
	showHideSubmit();
	document.getElementById("showLocation").checked = false;
    switch(error.code) {
        case error.PERMISSION_DENIED:
            x.innerHTML = "Eigene Position anzeigen (Keine Berechtigung für Standortzugriff)"
            break;
        case error.POSITION_UNAVAILABLE:
            x.innerHTML = "Eigene Position anzeigen (Standort nicht verfügbar)"
            break;
        case error.TIMEOUT:
            x.innerHTML = "Eigene Position anzeigen (Standort konnte nicht ermittelt werden - Timeout)"
            break;
        case error.UNKNOWN_ERROR:
            x.innerHTML = "Eigene Position anzeigen (Unbekannter Fehler)"
            break;
    }
} 
</script> 