<?php include 'reportEditUnit_template.php'; ?>
<?php include 'reportCard_template.php'; ?>

<form id="reportForm" onsubmit="showLoader()" method="post" >
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label>Datum:</label> <input type="date" required
				placeholder="TT.MM.JJJJ" title="TT.MM.JJJJ"	class="form-control" 
				name="date" id="date" 
				<?php
				if(isset($report) ){
					echo "value='" . $report->getDate() . "'";
				}?>
				required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label>Wachbeginn:</label> <input type="time" required
				placeholder="--:--" title="--:--" class="form-control" 
				name="start" id="start" 
				<?php
				if(isset($report) ){
					echo "value='" . timeToHm ($report->getStartTime()) . "'";
				}?>
				required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label>Wachende:</label> <input type="time" required
				placeholder="--:--" title="--:--" class="form-control" 
				<?php
				if(isset($report) && $report->getEndTime() != null ){
					echo "value='" . timeToHm ($report->getEndTime()) . "'";
				}?>
				name="end" id="end" required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
			</div>
		</div>
	</div>
	
	<div class="form-group">
		<label>Typ:</label> <select class="form-control" name="type" id="type" onchange="showHideTypeOther()">
			<?php foreach ( $eventtypes as $type ) : 	
			if(isset($report) && $type->getUuid() == $report->getType()->getUuid()) {
			    ?>
				<option value="<?= $type->getUuid(); ?>" selected><?= $type->getType(); ?></option>
			<?php } else {?>
				<option value="<?= $type->getUuid(); ?>"><?= $type->getType(); ?></option>
			<?php }
			endforeach; ?>
		</select>
	</div>
	
	<div class="form-group" id="groupTypeOther" 
	<?php if(isset($report) && $report->getTypeOther() == null){ echo 'style="display:none;"'; }?>>
		<label>Sonstiger Wachtyp:</label> <input type="text" required="required"
			class="form-control" name="typeOther" id="typeOther"
			<?php
			if(isset($report) && $report->getTypeOther() != null){
				echo "value='" . $report->getTypeOther() . "'";
			}?>
			placeholder="Wachtyp eingeben">
	</div>
		
	<div class="form-group">
		<label>Titel (optional):</label> <input type="text"
			class="form-control" name="title" id="title"
		    <?php
		    if(isset($report) && $report->getTitle() != null){
		    	echo "value='" . $report->getTitle() . "'";
			}?>
			placeholder="Titel eingeben">
	</div>
	<div class="form-group">
		<label>Zuständiger Löschzug/Verwaltung:</label> <select
			class="form-control" name="engine" id="engine" required="required">
			<option value="" disabled selected>Bitte auswählen</option>
			<?php foreach ( $engines as $option ) :
			if(isset($report) && $option->getUuid() == $report->getEngine()->getUuid()){
			?>
				<option value="<?=  $option->getUuid(); ?> " selected><?= $option->getName(); ?></option>
			<?php 
			}else if(!isset($report) && isset($currentUser) && $option->getUuid() == $currentUser->getEngine()->getUuid()){
				?>
			   	<option selected value="<?= $option->getUuid(); ?>"><?= $option->getName(); ?></option>
			    <?php 
			}else{
		    ?>
			<option value="<?=  $option->getUuid(); ?> "><?= $option->getName(); ?></option>
			<?php } endforeach; ?>
		</select>
	</div>
		
	<div class="custom-control custom-checkbox custom-checkbox-big">
		<input type="checkbox" class="custom-control-input" name="ilsEntry" id="ilsEntry" 
		<?php if(isset($report) && $report->getIlsEntry()) { echo "checked"; } ?>
		> <label class="custom-control-label custom-control-label-big" for="ilsEntry">Wache durch ILS angelegt</label>
	</div>
	
	<div class="custom-control custom-checkbox custom-checkbox-big">
		<input type="checkbox" class="custom-control-input" name="noIncidents" id="noIncidents"
		<?php if(isset($report) && $report->getNoIncidents()) { echo "checked"; } ?>
		> <label class="custom-control-label custom-control-label-big" for="noIncidents">Keine Vorkomnisse</label>
	</div>
	
	<div class="form-group">
		<label>Bericht:</label>
		<textarea class="form-control" name="report" id="report" placeholder="Bericht"
		><?php if(isset($report)) { echo $report->getReportText(); } ?></textarea>
	</div>
	
	<div class="form-group">
		<label>Ersteller:</label> <input type="text" required="required"
			class="form-control"
			<?php
			if(isset($report)){
				echo "value='" . $report->getCreator()->getFullName() . "'";
			} else if(isset($currentUser)) {
			     echo "value='" . $currentUser->getFullName() . "'";
			}?>
			name="creatorName" id="creatorName" disabled>
	</div>
	<input type="hidden" class="form-control"
		<?php
		if(isset($report)){
		    echo "value='" . $report->getCreator()->getUuid() . "'";
		} else if(isset($currentUser)) {
		    echo "value='" . $currentUser->getUuid() . "'";
		}
		?>
		name="creator" id="creator">
	
	<p class="h6">Wachpersonal hinzufügen:</p>
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUnitModal" onClick="initializeModal()">Ohne Fahrzeug</button>
	<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addUnitModal" onClick="initializeModalVehicle()">Mit Fahrzeug</button>
	<p>

	<div id="unitlist">
		<?php 
		createUnitCard(0);
		$i = 0;
		if(isset ($report)){
			foreach($report->getUnits() as $unit){
		        $i++;
		        createUnitCard($i, $unit);
		    }
		}
		?>
	</div>
	
	<p>
	<div>
	    <?php if(isset($report) && $report->getUuid() != null){
	    	echo '<a class="btn btn-outline-primary" href=' . $config["urls"]["guardianapp_home"] . '/reports/' . $report->getUuid() . ">Zurück</a>";
    	}
    	
    	if(isset($report) && $report->getUuid() != null){
            ?>
    	    <input type="submit" class="btn btn-primary" id="submitReport" <?php if(!isset($i) || $i == 0){ echo 'style="display:none;"'; } ?> value='Aktualisieren'>
    	    <?php 
    	} else{ ?>
    	    <div style="display:none;">
				<input type='submit' id="reportSubmit"/>
			</div>
    	    <button type="button" id="submitReport" class="btn btn-primary" data-toggle="modal" data-target="#confirmSubmit" <?php if(!isset($i) || $i == 0){ echo 'style="display:none;"'; } ?>>Abschicken</button>
    	    <div class='modal fade' id='confirmSubmit'>
				<div class='modal-dialog'>
					<div class='modal-content'>
		
						<div class='modal-header'>
							<h4 class='modal-title'>Bericht absenden</h4>
							<button type='button' class='close' data-dismiss='modal'>&times;</button>
						</div>
			            <div class='modal-body'>Bitte überprüfen Sie alle eingebenen Daten.<br>Ist das Wachende korrekt eingetragen (tatsächliches Ende der Wachveranstaltung)?</div>
			            <div class='modal-footer'>
							<button type='button' class='btn btn-primary' onClick='processReportForm()'>Abschicken</button>
							<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
						</div>
					</div>
				</div>
			</div>
    	<?php } ?>
	</div>

</form>

<script src="<?= $config["urls"]["intranet_home"] ?>/js/date.js" type="text/javascript"></script>
<script src="<?= $config["urls"]["intranet_home"] ?>/js/reportEdit.js" type="text/javascript"></script>

<script type="text/javascript">

showHideTypeOther();

var reportUnitCount = <?= $i ?>;

openUnit(reportUnitCount);

var currentPosition = 1;

if(!isDateSupported()){
		var dateElement = document.getElementById("date");
		var date = new Date(dateElement.value);		
		var dateString = ('0' + date.getDate()).slice(-2) + '.'
        + ('0' + (date.getMonth()+1)).slice(-2) + '.'
        + date.getFullYear();

        dateElement.value = dateString;
}

function processReportForm() {
	closeOneModal('confirmSubmit');
    
    var reportSubmit = document.getElementById('reportSubmit');
    reportSubmit.click();
}


</script>
