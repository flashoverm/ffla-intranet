<?php

function showAlert($message) {
	echo "<div class=\"alert alert-danger\" role=\"alert\">" . $message . "</div>";
}

function showSuccess($message) {
	echo "<div class=\"alert alert-success\" role=\"alert\">" . $message . "</div>";
}

function showInfo($message) {
	echo "<div class=\"alert alert-secondary\" role=\"alert\">" . $message . "</div>";
}

function setPrintToLandscape(){ ?>
	<style>
		@media print{
			@page {
				size: auto;   /* auto is the initial value */
				size: A4 landscape;
				margin: 0;  /* this affects the margin in the printer settings */
			}
		}
	</style>
<?php
}

function createDialog($id, $title, $name, $additionalValueName = null, $additionalValue = null, $positiveButton="Ja", $negativeButton="Abbrechen", $text = null){
	echo "
	<div class='modal fade' id='" . $id . "'>
		<div class='modal-dialog'>
			<div class='modal-content'>

				<div class='modal-header'>
					<h4 class='modal-title'>" . $title . "</h4>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
				</div>";
	            if($text != null ){
	                echo "<div class='modal-body'>" . $text . "</div>";
            	}
            	echo "<form action='' method='post' style='margin-bottom: 0px;'>
						<div class='modal-footer'>";
					if($additionalValueName != null && $additionalValue != null){
						echo "<input type='hidden' name='" . $additionalValueName . "' value='" . $additionalValue . "' />";
					}
					if($positiveButton != NULL){
						echo "<input type='submit' ";
						if($name != null && $name != ""){
							echo "name='" . $name . "' ";
						}
						echo "value='" . $positiveButton . "' class='btn btn-primary' />";
					}
					echo "<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>" . $negativeButton .  "</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	";
}


function createHydrantGoogleMap($hydrants, $visable, $markerListener, $height, $width, $replaceWithImage = false){
	global $config;
	?>
	<div class="dynamic-map rounded mx-auto" id="dynamic-map" 
	<?php
	if($visable){
		echo "style='display: block; height: " . $height . ";'";
	} else {
		echo "style='display: none; height: " . $height . ";'";
	}
	?>
	>
	</div>

	<script>
	var checkbox = document.getElementById("toogleMaps");
	if(checkbox != null){
		checkbox.addEventListener( 'change', function() {
			var googleMap = document.getElementById("dynamic-map");
			var staticMap = document.getElementById("map");
		    if(this.checked) {
		    	googleMap.style.display = 'block';
		    	staticMap.style.display = 'none';
		    } else {
		    	googleMap.style.display = 'none';
		    	staticMap.style.display = 'block';
		    }
		});
	}

	var markerList = [];
	var locations = [
	    <?php
	    foreach ( $hydrants as $row ) {
    	 	echo "['" . $row->getHy() . "'," . $row->getLat() . "," . $row->getLng() . "],";
    	}
	    ?>
	  ];

	var map;
	function initMap() {
		var center = new google.maps.LatLng(<?= $config['mapView']['defaultcoordinates'] ?>);
		var mapOptions = {
				  styles: [{
			            featureType: "poi",
			            elementType: "labels",
			            stylers: [{ visibility: "off" }]
					}],
				  zoom: <?= $config['mapView']['zoom'] ?>,
				  center: center
				};
		var infowindow = new google.maps.InfoWindow();
		map = new google.maps.Map(document.getElementById('dynamic-map'), mapOptions);
		var bounds = new google.maps.LatLngBounds();
		google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
			  if (this.getZoom() > <?= $config['mapView']['zoom'] ?>) {
			    this.setZoom(<?= $config['mapView']['zoom'] ?>);
			  }
			});
	
		for (i = 0; i < locations.length; i++) {  
			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(locations[i][1], locations[i][2]),
				map: map,
				title: locations[i][0],
				<?php if( ! $config["settings"]["useDefaultMapMarker"] ) {?>
			    icon: '<?= $config["urls"]["intranet_home"] ?>/images/layout/map-icon-alt_sm.png',
				label: {color: 'white', fontSize: '11px', text: locations[i][0]},
				hy: locations[i][0],
		        zIndex: 0
				<?php } ?>
			});
			<?php
			if($markerListener == true){
			?>
				google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
						infowindow.setContent("<a href='<?= $config["urls"]["hydrantapp_home"]?>/view/" + locations[i][0] + "'>Hy: " + locations[i][0] + "&nbsp;</a>");
						infowindow.open(map, marker);
					}
				})(marker, i));
			<?php } ?>
			markerList[locations[i][0]] = marker;
			bounds.extend(marker.getPosition());
		}
		map.fitBounds(bounds);

		if(typeof setListener === "function"){
			setListener();
		}
		
		<?php if($replaceWithImage) {?>
		showLoader();
		google.maps.event.addListener(map, 'tilesloaded', function(){

			hideLoader();
		
			//Move label-div to higher layer
			var labelContainerA = $('.gm-style > div:first > div:first');
			labelContainerA.css('z-index', 100);
				
			html2canvas($( "#dynamic-map")[0], {
				useCORS: true
				}).then(canvas => {
					
					var map = $("#dynamic-map");
					var bdy = document.getElementById("mapplaceholder");
					var image = document.createElement("img");
					image.src = canvas.toDataURL("image/jpeg");
					bdy.appendChild(image);
				    map.toggleClass('d-none');
			});
		});	
		<?php } ?>
	}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?= $config['mapView']['apiKey']?>&callback=initMap" async defer></script>
	<?php 	
}

function createPagerAlertMap($alerts, $visable, $height, $width, $replaceWithImage = false){
    global $config;
    ?>
	<div class="dynamic-map rounded mx-auto" id="dynamic-map" 
	<?php
	if($visable){
	    echo "style='display: block; height: " . $height . ";'";
	} else {
	    echo "style='display: none; height: " . $height . ";'";
	}
	?>
	>
	</div>

	<script>
	var checkbox = document.getElementById("toogleMaps");
	if(checkbox != null){
		checkbox.addEventListener( 'change', function() {
			var googleMap = document.getElementById("dynamic-map");
			var staticMap = document.getElementById("map");
		    if(this.checked) {
		    	googleMap.style.display = 'block';
		    	staticMap.style.display = 'none';
		    } else {
		    	googleMap.style.display = 'none';
		    	staticMap.style.display = 'block';
		    }
		});
	}

	var markerList = [];
	var locations = [
	    <?php
	    foreach ( $alerts as $row ) {
	        echo "['" . $row->getFid() . "'," . $row->getLat() . "," . $row->getLng() . "," . $row->getAlerted() . ",'" . substr($row->getManufacturer(), 0, 2) . "'],";
    	}
	    ?>
	  ];

	var map;
	function initMap() {
		var center = new google.maps.LatLng(<?= $config['mapView']['defaultcoordinates'] ?>);
		var mapOptions = {
				  styles: [{
			            featureType: "poi",
			            elementType: "labels",
			            stylers: [{ visibility: "off" }]
					}],
				  zoom: <?= $config['mapView']['zoom'] ?>,
				  center: center
				};
		var infowindow = new google.maps.InfoWindow();
		map = new google.maps.Map(document.getElementById('dynamic-map'), mapOptions);
		var bounds = new google.maps.LatLngBounds();
		google.maps.event.addListenerOnce(map, 'bounds_changed', function(event) {
			  if (this.getZoom() > <?= $config['mapView']['zoom'] ?>) {
			    this.setZoom(<?= $config['mapView']['zoom'] ?>);
			  }
			});
	
		for (i = 0; i < locations.length; i++) {
			var marker = '<?= $config["urls"]["intranet_home"] ?>/images/layout/map-icon-alt_sm.png' 
			if(locations[i][3] == true){
				marker = '<?= $config["urls"]["intranet_home"] ?>/images/layout/map-icon-green-alt_sm.png' 
			}
			var marker = new google.maps.Marker({
				position: new google.maps.LatLng(locations[i][1], locations[i][2]),
				map: map,
				title: locations[i][0],
				<?php if( ! $config["settings"]["useDefaultMapMarker"] ) {?>
			    icon: marker,
				label: {color: 'white', fontSize: '11px', text: locations[i][4]},
				hy: locations[i][0],
		        zIndex: 0
				<?php } ?>
			});
			markerList[locations[i][0]] = marker;
			bounds.extend(marker.getPosition());
		}
		map.fitBounds(bounds);

		if(typeof setListener === "function"){
			setListener();
		}
		
		<?php if($replaceWithImage) {?>
		showLoader();
		google.maps.event.addListener(map, 'tilesloaded', function(){

			hideLoader();
		
			//Move label-div to higher layer
			var labelContainerA = $('.gm-style > div:first > div:first');
			labelContainerA.css('z-index', 100);
				
			html2canvas($( "#dynamic-map")[0], {
				useCORS: true
				}).then(canvas => {
					
					var map = $("#dynamic-map");
					var bdy = document.getElementById("mapplaceholder");
					var image = document.createElement("img");
					image.src = canvas.toDataURL("image/jpeg");
					bdy.appendChild(image);
				    map.toggleClass('d-none');
			});
		});	
		<?php } ?>
	}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?= $config['mapView']['apiKey']?>&callback=initMap" async defer></script>
	<?php 	
}

?>