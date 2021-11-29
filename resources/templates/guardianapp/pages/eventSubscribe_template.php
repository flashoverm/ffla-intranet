<form onsubmit="showLoader()" action="" method="post">
	<input type="hidden" class="form-control" name="user_uuid" id="user_uuid"
		<?php
		if(isset($currentUser)){
			echo "value='" . $currentUser->getUuid() . "' readonly";
		}
		?>
		>
	
	<div class="form-group">
		<label>Vorname:</label> <input type="text" class="form-control"
			required="required" name="firstname" id="firstname"
			placeholder="Vorname eingeben"
			<?php
			if(isset($currentUser)){
				echo "value='" . $currentUser->getFirstname() . "' readonly";
			}
			?>
			>
	</div>
	<div class="form-group">
		<label>Nachname:</label> <input type="text" class="form-control"
			required="required" name="lastname" id="lastname"
			placeholder="Nachname eingeben"
			<?php
			if(isset($currentUser)){
				echo "value='" . $currentUser->getLastname() . "' readonly";
			}
			?>
			>
	</div>
	<div class="form-group">
		<label>E-Mail:</label> <input type="email" class="form-control"
			required="required" name="email" id="email"
			placeholder="E-Mail eingeben"
			<?php
			if(isset($currentUser)){
				echo "value='" . $currentUser->getEmail() . "' readonly";
			}
			?>
			>
	</div>
	<div class="form-group">
		<label>Löschzug:</label> 
		<select class="form-control" name="engine" required="required"
		<?php
			if(isset($currentUser)){
				echo "readonly";
			}
		?>		
		>
			<option value="" disabled selected>Löschzug auswählen</option>
			<?php foreach ( $engines as $option ) : 
				if ( isset($currentUser) && $currentUser->getEngine()->getUuid() == $option->getUuid()) { ?>
					<option value="<?=  $option->getUuid(); ?>" selected><?= $option->getName(); ?></option>
				<?php } else { ?>
					<option value="<?=  $option->getUuid(); ?>"><?= $option->getName(); ?></option>
				<?php }
			endforeach; ?>
		</select>
	</div>
	<?php
	if($event->getStaffConfirmation()){
		echo "<p>Sie werden per E-Mail informiert, sobald der Wachbeauftragte ihre Teilnahme bestätigt hat.</p>";
	} else {
		?>
		<div class='custom-control custom-checkbox custom-checkbox-big'>
			<input type='checkbox' class='custom-control-input' name='informMe' id='informMe'> 
			<label class="custom-control-label custom-control-label-big" for='informMe'>Benachrichtung an eigene E-Mail-Adresse senden</label>
		</div>
		<?php 
	}
	?>

	
	<?php
	if ($event->getUuid()) {
		echo "<a href='" . $config["urls"]["guardianapp_home"] . "/events/view/" . $event->getUuid() . "' class=\"btn btn-outline-primary\">Zurück</a>";
	}
	?>
	<input type="submit" value="Eintragen" class="btn btn-primary">

</form>
