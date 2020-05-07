<form onsubmit="showLoader()"
	action="<?= $config["urls"]["guardianapp_home"] . "/events/".$event->uuid."/subscribe/".$staffUUID ?>"
	method="post">
	<div class="form-group">
		<label>Vorname:</label> <input type="text" class="form-control"
			required="required" name="firstname" id="firstname"
			placeholder="Vorname eingeben">
	</div>
	<div class="form-group">
		<label>Nachname:</label> <input type="text" class="form-control"
			required="required" name="lastname" id="lastname"
			placeholder="Nachname eingeben">
	</div>
	<div class="form-group">
		<label>E-Mail:</label> <input type="email" class="form-control"
			required="required" name="email" id="email"
			placeholder="E-Mail eingeben">
	</div>
	<div class="form-group">
		<label>Löschzug:</label> 
		<select class="form-control" name="engine" required="required">
			<option value="" disabled selected>Löschzug auswählen</option>
			<?php foreach ( $engines as $option ) : ?>
			<option value="<?=  $option->uuid; ?>"><?= $option->name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<?php
	if($event->staff_confirmation){
		echo "<p>Sie werden per E-Mail informiert, sobald der Wachbeauftragte ihre Teilnahme bestätigt hat.</p>";
	} else {
		?>
		<div class='custom-control custom-checkbox custom-checkbox-big'>
			<input type='custom-control-input' class='form-check-input' name='informMe' id='informMe'> 
			<label class="custom-control-label custom-control-label-big" for='informMe'>Benachrichtung an eigene E-Mail-Adresse senden</label>
		</div>
		<?php 
	}
	?>

	
	<?php
	if (isset ( $event->uuid )) {
		echo "<a href='" . $config["urls"]["guardianapp_home"] . "/events/" . $event->uuid . "' class=\"btn btn-outline-primary\">Zurück</a>";
	}
	?>
	<input type="submit" value="Eintragen" class="btn btn-primary">

</form>
