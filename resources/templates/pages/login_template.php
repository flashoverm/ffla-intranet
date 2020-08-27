<form onsubmit="showLoader()" action="" method="post">
	<div class="form-group">
		<label>E-Mail:</label> <input type="text" class="form-control"
			required="required" name="email" id="email"
			placeholder="E-Mail-Adresse eingeben">
	</div>
	<div class="form-group">
		<label>Passwort:</label>
		<div class="input-group mb-3">
			<input type="password" class="form-control" required="required" name="password" id="password" placeholder="Passwort eingeben">
			<div class="input-group-prepend">
				<div class="input-group-text">
					<input type="checkbox" onchange="tooglePassword('password')">
					<div class="ml-1">Klartext</div>
	   			</div>
			</div>
		</div>
	</div>
	<input type="submit" value="Einloggen" class="btn btn-primary">
</form>

