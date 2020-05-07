<form onsubmit="showLoader()" action="" method="post">
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
		<label>Passwort:</label> <input type="password" class="form-control"
			required="required" name="password" id="password"
			placeholder="Passwort eingeben">
	</div>
	<div class="form-group">
		<label>Passwort wiederholen:</label> <input type="password"
			class="form-control" required="required" name="password2"
			id="password2" placeholder="Passwort wiederholen">
	</div>
	<div class="form-group">
		<label>Löschzug:</label>  
			<select class="form-control" name="engine" required="required">
			<option value="" disabled selected>Löschzug auswählen</option>
			<?php foreach ( $engines as $option ) : ?>
				<option value="<?php echo $option->uuid; ?>"><?php echo $option->name; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<input type="submit" value="Registrieren" class="btn btn-primary">
</form>