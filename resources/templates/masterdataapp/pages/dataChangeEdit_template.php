<form id="reportForm" onsubmit="showLoader()" method="post" >
	<div class="form-group">
		<label>Typ:</label> <select class="form-control" name="datatype" id="datatype">
				<?php foreach ( DataChangeRequest::DATATYPE_TEXT as $key => $datatype ) :
				if(isset($dataChangeRequest) && $dataChangeRequest->getDatatype() == $key) {?>
						<option value="<?= $key ?>" selected><?= $datatype ?></option>
					<?php } else {?>
						<option value="<?= $key ?>"><?= $datatype ?></option>
					<?php }
				endforeach; ?>
			</select>
	</div>
	<div class="form-group">
		<label>Neuer Wert (z.B. neue Adresse):</label> <input type="text" required
			class="form-control" name="newvalue" id="newvalue"
			<?php
			if(isset($dataChangeRequest)){
				echo "value='" . $dataChangeRequest->getNewValue() . "'";
			}?>
			placeholder="Neuen Wert (z.B. neue Adresse) eingeben">
	</div>
	<div class="form-group">
		<div class="custom-control custom-checkbox custom-checkbox-big">
			<input type="checkbox" class="custom-control-input" name="forme" id="forme" onclick="toogleForUser()"
			<?php if( ! isset($dataChangeRequest) || $dataChangeRequest->getPerson() == null) { echo "checked"; } ?>
			> <label class="custom-control-label custom-control-label-big" for="forme">Änderung bei meinen Daten</label>
		</div>
	</div>
	<div id="forpersonblock" class="form-group" <?php if( ! isset($dataChangeRequest) || $dataChangeRequest->getPerson() == null) { echo "style='display:none'"; } ?>>
		<label>Änderung für folgende Person (optional bei eigenen Daten):</label> <input type="text"
			class="form-control" name="forperson" id="forperson"
			<?php
			if(isset($dataChangeRequest)){
				echo "value='" . $dataChangeRequest->getPerson() . "'";
			}?>
			placeholder="Namen und Zug der Person eingeben">
	</div>
	<div class="form-group">
		<label>Anmerkungen:</label>
		<textarea class="form-control" name="comment" id="comment"
			placeholder="Anmerkungen"><?php
			if(isset($dataChangeRequest) && $dataChangeRequest->getComment() != null){
				echo $dataChangeRequest->getComment();
			}?></textarea>
	</div>
	<?php
	if( isset($dataChangeRequest) ){
	?>
		<div class="form-group">
			<label>Status:</label> <input type="text"
				class="form-control" name="state" id="state" readonly
				<?= "value='" . DataChangeRequest::CONFIRMATION_STATES[$dataChangeRequest->getState()] . "'" ?>
			>
		</div>
		<?php
	}
	?>
	<input type="submit" class="btn btn-primary" id="submitDataChangeRequest" 
	<?php
	if(isset($dataChangeRequest) && $dataChangeRequest->getState() == DataChangeRequest::OPEN){
		echo " value='Antrag aktualisieren'";
	} else {
		echo " value='Beantragen'";
	}
	?>
	>
</form>

<script>
function toogleForUser() {
  var checkBox = document.getElementById("forme");
  var text = document.getElementById("forpersonblock");
  var textfield = document.getElementById("forperson");

  if (checkBox.checked == true){
    text.style.display = "none";
    textfield.value = "";
  } else {
    text.style.display = "block";
  }
} 
</script>