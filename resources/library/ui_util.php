<?php 

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
            	echo "<div class='modal-footer'>
					<form action='' method='post' style='margin-bottom: 0px;'>";
					if($additionalValueName != null && $additionalValue != null){
						echo "<input type='hidden' name='" . $additionalValueName . "' value='" . $additionalValue . "' />";
					}
					echo "<input type='submit' ";
					if($name != null && $name != ""){
						echo "name='" . $name . "' "; 
					}
					echo "value='" . $positiveButton . "' class='btn btn-primary' />
						<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>" . $negativeButton .  "</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	";
}


function createHydrantGoogleMap($hydrants, $visable){
	global $config;
	?>
	<div class="dynamic-map rounded mx-auto mt-5" id="dynamic-map" 
	<?php
	if($visable){
		echo "style='display: block;'";
	} else {
		echo "style='display: none;'";
	}
	?>
	></div>
	
	<style>
	.dynamic-map {
		height: <?= $config['mapView']['height'] ?>px;
		width: <?= $config['mapView']['widewidth'] ?>px;
	}
	</style>
	<script>
	var checkbox = document.getElementById("toogleMaps");
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

	var locations = [
	    <?php
    	foreach ( $hydrants as $row ) {
    	 	echo "['" . $row->hy . "'," . $row->lat . "," . $row->lng . "],";
    	}
	    ?>
	  ];

	var map;
	function initMap() {
		var center = new google.maps.LatLng(<?= $config['mapView']['defaultcoordinates'] ?>);
		var mapOptions = {
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
				title: locations[i][0]
			});
			google.maps.event.addListener(marker, 'click', (function(marker, i) {
				return function() {
					infowindow.setContent("<a href='<?= $config["urls"]["hydrantapp_home"]?>/" + locations[i][0] + "'>Hy: " + locations[i][0] + "&nbsp;</a>");
					infowindow.open(map, marker);
				}
			})(marker, i));
			bounds.extend(marker.getPosition());
			
			
		}
		map.fitBounds(bounds);
		}
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=<?= $config['mapView']['apiKey']?>&callback=initMap" async defer></script>
	<?php 	
}

?>