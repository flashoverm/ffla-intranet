<form onsubmit="showLoader()" action="" method="post">

	<div class="row">
		<div class="col">
			<div class="form-group">
				<label>HY Nr.:</label><input id="hy" name="hy" class="form-control" 
					type="number" required placeholder="HY Nr."
					<?php if(isset($hydrant)){
						echo ' value="' . $hydrant->getHy() . '" disabled'; 
					}?>
					>
			</div>
		</div>
		
		<div class="col">
			<div class="form-group">
				<label>FID Nr.:</label><input id="fid" name="fid" class="form-control" 
					type="number" required placeholder="HY Nr."
					<?php if(isset($hydrant)){
					    echo ' value="' . $hydrant->getFid() . '"'; 
					}?>
					>
			</div>
		</div>
	</div>
		
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label>Breitengrad (Lat):</label><input id="lat" name="lat" class="form-control" type="text" 
					required placeholder="00.00000000" pattern="^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,8}"
					<?php if(isset($hydrant)){
					    echo ' value="' . $hydrant->getLat() . '"'; 
					}?>
					>
			</div>
		</div>
		
		<div class="col">
			<div class="form-group">
				<label>Längengrad (Lng):</label><input id="lng" name="lng" class="form-control" type="text" 
					required placeholder="00.00000000" pattern="^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,8}"
					<?php if(isset($hydrant)){
					    echo ' value="' . $hydrant->getLng() . '"'; 
					}?>
					>
			</div>
		</div>
	</div>	
	
	<div class="row">
		<div class="col">
        	<div class="form-group">
        		<label>Straße:</label> <input type="text" required
        			class="form-control" name="street" id="street"
        			placeholder="Straße eingeben"
        			<?php if(isset($hydrant)){
					    echo ' value="' . $hydrant->getStreet() . '"'; 
					}?>
					>
        	</div>
        </div>
        <div class="col">
        	<div class="form-group">
        		<label>Stadtteil:</label> <input type="text" required
        			class="form-control" name="district" id="district"
        			placeholder="Stadtteil eingeben"
        			<?php if(isset($hydrant)){
					    echo ' value="' . $hydrant->getDistrict() . '"'; 
					}?>
					>
        	</div>
        </div>
	</div>
	
	<div class="form-group">
		<label>Hydranten-Typ:</label> <input type="text" required
			class="form-control" name="type" id="type"
			placeholder="Typ eingeben"
			<?php if(isset($hydrant)){
                echo ' value="' . $hydrant->getType() . '"'; 
			}?>>
	</div>
        	
	<div class="form-group">
		<label>Zuständiger Löschzug:</label> 
		<select class="form-control" name="engine" id="engine" required="required" onchange="setEngineHid()">
			<option value="" disabled selected>Löschzug auswählen</option>
			<?php foreach ( $engines as $option ) : 
			if(isset($hydrant) && $option->getUuid() == $hydrant->getEngine()->getUuid()){?>
			   	<option selected="selected" value="<?=  $option->getUuid();	?> "><?= $option->getName(); ?></option>
			<?php }else{ ?>
			   <option value="<?=  $option->getUuid();	?> "><?= $option->getName(); ?></option>
			<?php } 
			endforeach; ?>
		</select>
		<input type="hidden" class="form-control" name="engine_hid" id="engine_hid">
	</div>
	
	<div class="form-group">
		<div class="custom-control custom-checkbox custom-checkbox-big">
			<input type="checkbox" class="custom-control-input" id="checkbyff" name="checkbyff" 
			<?php if(!isset($hydrant) || $hydrant->getCheckByFF()){
			    echo "checked";
			}?>
			>
			<label class="custom-control-label custom-control-label-big" for='checkbyff'>Prüfung durch Feuerwehr Landshut</label> 
		</div>
	</div>
	<div class="form-group">
		<div class="custom-control custom-checkbox custom-checkbox-big">
			<input type="checkbox" class="custom-control-input" id="operating" name="operating" 
			<?php if(!isset($hydrant) || $hydrant->getOperating()){
			    echo "checked";
			}?>
			>
			<label class="custom-control-label custom-control-label-big" for='operating'>In Betrieb</label> 
		</div>
	</div>
	
	<a class="btn btn-outline-primary" href="<?= $config ["urls"] ["hydrantapp_home"] ?>/all">Zurück</a>

	<input type="submit" class="btn btn-primary"
	<?php if(isset($hydrant)){
	    echo 'value="Aktualisieren"'; 
	} else {
	    echo 'value="Anlegen"';
	}?>
	>
	
</form>

<script>

$(function() {
    var availableTags = <?php echo json_encode($hydrantDAO->getDistrictList()); ?>;
    $("#district").autocomplete({
        source: availableTags
    });
});

$(function() {
    var availableTags = <?php echo json_encode($hydrantDAO->getStreetList()); ?>;
    $("#street").autocomplete({
        source: availableTags
    });
});

</script>
