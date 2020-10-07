<form id="reportForm" onsubmit="showLoader()" method="post" >
	<div class="row">
		<div class="col">
			<div class="form-group">
				<label>Datum:</label> <input type="date" required
				placeholder="TT.MM.JJJJ" title="TT.MM.JJJJ"	class="form-control" 
				name="date" id="date" 
				<?php
				if(isset($confirmation) ){
					echo "value='" . $confirmation->date . "'";
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
					echo "value='" . timeToHm ($confirmation->start_time) . "'";
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
					echo "value='" . timeToHm ($confirmation->end_time) . "'";
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
				echo "value='" . $confirmation->description . "'";
			}?>
			placeholder="Einsatz-Beschreibung eingeben">
	</div>
	<?php
	if( isset($confirmation) ){
		global $confirmationStates;
	?>
		<div class="form-group">
			<label>Status:</label> <input type="text"
				class="form-control" name="state" id="state" readonly
				<?= "value='" . $confirmationStates[$confirmation->state] . "'" ?>
			>
		</div>
		<?php
		if( $confirmation->reason != null ){
		?>
		<div class="form-group">
			<label>Begründung Ablehnung:</label> 
			<textarea type="text" class="form-control" name="reason" id="reason" readonly><?= $confirmation->reason ?></textarea>
		</div>
	<?php
		}
	}
	?>
	<input type="submit" class="btn btn-primary" id="submitConfirmation" 
	<?php
	if(isset($confirmation) && $confirmation->state == ConfirmationState::Open){
		echo " value='Antrag aktualisieren'";
	} else {
		echo " value='Beantragen'";
	}
	?>
	>
</form>