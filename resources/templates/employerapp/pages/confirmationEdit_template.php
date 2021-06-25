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
					echo "value='" . timeToHm ($confirmation->getStartTime()) . "'";
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
					echo "value='" . timeToHm ($confirmation->getEndTime()) . "'";
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
			<textarea type="text" class="form-control" name="reason" id="reason" readonly><?= $confirmation->getReason() ?></textarea>
		</div>
	<?php
		}
	}
	?>
	<a class="btn btn-outline-primary" href="<?= $config ["urls"] ["employerapp_home"] ?>/confirmations/">Zurück</a>
	
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
</script>