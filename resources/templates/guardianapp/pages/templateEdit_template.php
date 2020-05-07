<?php
if (! $isAdmin) {
	showAlert ( "Kein Administrator angemeldet - <a href=\"" . $config["urls"]["guardianapp_home"] . "/events\" class=\"alert-link\">Zurück</a>" );
} else {
	?>
	<form action="" method="post">
		<div class="form-group">
		<label>Wachtyp:</label> <select class="form-control" name="type" id="type" onchange="loadTemplate()">
				<option value="" disabled selected>Wachtyp auswählen</option>
				<?php foreach ( $eventtypes as $type ) :
					if(isset($_GET ['eventtype']) && $_GET ['eventtype'] == $type->uuid){?>
						<option value="<?= $type->uuid; ?>" selected><?= $type->type; ?></option>
				<?php } else {?>
						<option value="<?= $type->uuid; ?>"><?= $type->type; ?></option>
				<?php }
				endforeach; ?>
			</select>
		</div>    
		<?php 
		$staffId = 0;
		if(isset($template)){
		?>
		<div class="form-group">
			<label>Benötigtes Wachpersonal:</label>
			<div class="table-responsive">
				<table class="table table-bordered">
					<tbody id="staffContainer">
						<tr>
							<th>Funktion</th>
							<th class="py-0 text-center align-middle" style="width:  8%">
								<button type="button" class="btn btn-sm btn-primary" onClick="eventAddStaff()">+</button>
							</th>
						</tr>
						
						<tr id="staffEntryTemplate" style="display:none;">
							<td class="p-0">
									<select class="select-cornered" name="">
										<option value="" disabled selected>Funktion auswählen</option>
										<?php foreach ( $staffpositions as $option ) : 
										?>
											<option value="<?=  $option->uuid; ?>"><?= $option->position; ?></option>
										<?php
										endforeach; 
							            ?>
									</select>
							</td>
							<td class="p-0 text-center align-middle" style="width:  8%">
								<button type="button" class="btn btn-sm btn-primary">X</button>
							</td>
						</tr>
						
						<?php
						foreach ( $template as $entry ) {
							$staffId = $staffId +1;
							?>
							<tr id="staffEntry<?= $staffId; ?>">
								<td class="p-0">
										<select class="select-cornered" name="<?= $entry->template ?>" required="required" id="<?= $entry->template ?>">
											<option value="" disabled selected>Funktion auswählen</option>
											<?php foreach ( $staffpositions as $option ) : 
												if($option->uuid == $entry->uuid){
											?>
													<option selected value="<?=  $option->uuid ?>"><?= $option->position ?></option>
											<?php 
												} else {
											?>
													<option value="<?=  $option->uuid ?>"><?= $option->position ?></option>
											<?php
												}
											endforeach; 
								            ?>
										</select>
								</td>
								<td class="p-0 text-center align-middle" style="width:  8%">								
									<button type="button" class="btn btn-sm btn-primary" onClick="eventRemoveStaff(<?= $staffId ?>)">X</button>
								</td>
							</tr>
						<?php
						}
						?>					
					</tbody>
				</table>
				<input type="hidden" id="positionCount" name="positionCount" value="<?= $staffId ?>">
			</div>	
		</div>
		<input type="submit" class="btn btn-primary" value="Speichern">
		<?php 
		}
		?>
	</form>
<?php
} 
?>

<script type='text/javascript'>

	var absolutCount = <?= $staffId; ?>;
	var positionCount = <?= $staffId; ?>;

	function loadTemplate(){
		var selectedType = document.getElementById('type');
		selectedType.value
        window.location = "<?= $config["urls"]["guardianapp_home"] ?>/templates/" + selectedType.value; // redirect
	}

	function eventAddStaff(){
		positionCount += 1;
	
		var thisPositionCount = positionCount;
		
		var container = document.getElementById("staffContainer");
	
		var template = document.getElementById("staffEntryTemplate");
		
		var newPosition =  template.cloneNode(true);
		
		newPosition.id = "staffEntry" + positionCount;
		newPosition.style.display = null;
		
		var select = newPosition.getElementsByTagName("select");
		select[0].id = "staff" + positionCount;
		select[0].name = "staff" + positionCount;
		select[0].required = true;
		
		var removeButton = newPosition.getElementsByTagName("button");
		removeButton[0].onclick = function(){
				eventRemoveStaff(thisPositionCount);
			};
	
		var positionCountInput = document.getElementById("positionCount");
		positionCountInput.value = positionCount;
	
		absolutCount = absolutCount +1;
			
		container.appendChild(newPosition);
	
		return select[0];
	}
	
	function eventRemoveStaff(id){			
		var staffElement = document.getElementById("staffEntry"+id);
		if(staffElement != null){
			staffElement.parentNode.removeChild(staffElement);
			absolutCount = absolutCount -1;
			
			if(absolutCount < 1){
				setStaffAlert(true)
			}
		}
	
	}

</script>