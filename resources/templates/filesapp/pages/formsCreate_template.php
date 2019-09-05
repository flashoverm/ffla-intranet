<form onsubmit="showLoader()" action="" method="post" enctype="multipart/form-data">
	<div class="form-group">
		<label>Bezeichnung</label> <input type="text" class="form-control"
			required="required" name="description" id="description"
			placeholder="Bezeichnung der Datei">
	</div>
	<div class="form-group">
    	<input type="file" class="form-control-file" name="upload" id="upload" required="required">
	</div>
	<input class="btn btn-primary" type="submit" value="Hochladen">
	<a href='<?= $config["urls"]["filesapp_home"]?>/forms/admin' class="btn btn-primary">Zurück</a>
</form>