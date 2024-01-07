<form id="reportForm" onsubmit="showLoader()" method="post" >
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label>Datum:</label> <input type="date" required
				placeholder="TT.MM.JJJJ" title="TT.MM.JJJJ"	class="form-control"
				name="date" id="date"
				<?php
				if(isset($confirmation) ){
					echo "value='" . $confirmation->getDate() . "'";
				}?>
				required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label>Einsatzbeginn:</label> <input type="time" required
				placeholder="--:--" title="--:--" class="form-control"
				name="start" id="start"
				<?php
				if(isset($confirmation) ){
					echo "value='" . DateFormatUtil::timeToHm ($confirmation->getStartTime()) . "'";
				}?>
				required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
			</div>
		</div>
		<div class="col">
			<div class="form-group">
				<label>Einsatzende:</label> <input type="time" required
				placeholder="--:--" title="--:--" class="form-control"
				<?php
				if(isset($confirmation) ){
					echo "value='" . DateFormatUtil::timeToHm ($confirmation->getEndTime()) . "'";
				}?>
				name="end" id="end" required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
			</div>
		</div>
	</div>
	<div class="form-group">
		<label>Einsatz:</label> <input type="text" required
			class="form-control" name="description" id="description"
			<?php
			if(isset($confirmation)){
				echo "value='" . $confirmation->getDescription() . "'";
			}?>
			placeholder="Einsatz-Beschreibung eingeben">
	</div>
	<div class="custom-control custom-checkbox custom-checkbox-big">
		<input type="checkbox" class="custom-control-input" name="assign" id="assign"
		<?php if(!isset($confirmation) || (isset($confirmation) && $confirmation->getAssignedTo() != null)) {
		    echo "checked";
		} ?>
		>
		<label class="custom-control-label custom-control-label-big" for="assign">
			Einem Einheits-/Gruppenführer zur Freigabe zuweisen (nicht dem Geschäftszimmer)
		</label>
	</div>
	<div class="form-group">
		<label>Zuständiger Einheits-/Gruppenführer:</label>
		<select class="form-control" name="assignedTo" id="assignedTo" required
			<?php if(isset($confirmation) && $confirmation->getAssignedTo() == null) {
			    echo "disabled";
			} ?>
		>
				<?php 
				$engineConfirmationManager = $userDAO->getUsersByAllEnginesAndPrivilege(
				    $currentUser->getEngine()->getUuid(), $currentUser->getUuid(), Privilege::ENGINECONFIRMATIONMANAGER);
				if(count($engineConfirmationManager) > 0){
				    ?>
					<option value="" disabled selected>Bitte auswählen</option>
					<?php
				} else {
				    ?>
					<option value="" disabled selected>Keine Benutzer vorhanden</option>
					<?php
				}
				foreach ( $engineConfirmationManager as $user ) :
				    if(isset($confirmation)
				        && $confirmation->getAssignedTo() != null
				        && $user->getUuid() == $confirmation->getAssignedTo()->getUuid()) {?>
						<option value="<?= $user->getUuid(); ?>" selected><?= $user->getFullNameWithEngine(); ?></option>
					<?php } else {?>
						<option value="<?= $user->getUuid(); ?>"><?= $user->getFullNameWithEngine(); ?></option>
					<?php }
				endforeach; ?>
			</select>
	</div>
	<?php
	if( isset($confirmation) ){
	?>
		<div class="form-group">
			<label>Status:</label> <input type="text"
				class="form-control" name="state" id="state" readonly
				<?= "value='" . Confirmation::CONFIRMATION_STATES[$confirmation->getState()] . "'" ?>
			>
		</div>
		<?php
		if( $confirmation->getReason() != null ){
		?>
		<div class="form-group">
			<label>Begründung Ablehnung:</label>
			<textarea type="text" class="form-control" name="reason" id="reason" readonly>
				<?= $confirmation->getReason() ?>
			</textarea>
		</div>
	<?php
		}
	}
	?>
	<a class="btn btn-outline-primary" href="<?= $config ["urls"] ["employerapp_home"] ?>/confirmations/overview">
		Zurück
	</a>
	
	<input type="submit" class="btn btn-primary" id="submitConfirmation"
	<?php
	if(isset($confirmation) && $confirmation->getState() == Confirmation::OPEN){
		echo " value='Antrag aktualisieren'";
	} else {
		echo " value='Beantragen'";
	}
	?>
	>
</form>

<script>
	if(!isDateSupported()){
		var dateElement = document.getElementById("date");
		var date = new Date(dateElement.value);
		var dateString = ('0' + date.getDate()).slice(-2) + '.'
        + ('0' + (date.getMonth()+1)).slice(-2) + '.'
        + date.getFullYear();

        dateElement.value = dateString;
	}
	
	$("#assign").on( "change", function() {
		var checked = $(this).is(':checked');
		var dropdown = $("#assignedTo");
		if(checked){
			dropdown.prop("disabled", false);
		} else {
			dropdown.prop("disabled", true);
		}
		
	});
	
</script>
