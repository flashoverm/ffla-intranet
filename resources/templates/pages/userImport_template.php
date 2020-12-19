<form onsubmit="showLoader()" action="" method="post" enctype="multipart/form-data">
	<h3>Benutzer importieren*</h3>
	<div class="form-group">
		<label>Löschzug:</label> 
			<select class="form-control" name="engine" required="required">
			<option value="" disabled selected>Löschzug auswählen</option>
			<?php foreach ( $engines as $option ) : ?>
				<option value="<?= $option->getUuid(); ?>"><?= $option->getName(); ?></option>
			<?php endforeach; ?>
			
		</select>
	</div>
	<div class="form-group">
    	<input type="file" class="form-control-file" name="import" id="import" required="required">
	</div>
	<p>*Kein Header - Format Zeile: Vorname; Nachname; E-Mail</p>
	<input class="btn btn-primary" type="submit" value="Importieren">
	<a href='<?= $config["urls"]["intranet_home"]?>/users' class="btn btn-primary">Zurück</a>
</form>