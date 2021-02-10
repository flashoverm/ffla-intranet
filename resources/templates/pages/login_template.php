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
	<div class="form-group">
		<input type="submit" value="Einloggen" class="btn btn-primary">
	</div>
	<div class="form-group">
		<a class="btn btn-primary btn-sm" href='<?= $config["urls"]["intranet_home"] ?>/users/password/forgot'>Passwort vergessen</a>
	</div>
	<div class="form-group">
		<button class="btn btn-outline-primary btn-sm" type="button" data-toggle="collapse" data-target="#description">Wie erhalte ich einen Zugang?</button>
	</div>

	
	<div class="collapse" id="description">
	  <div class="card card-body">
			<p>Um Zugang zum Intranet der Feuerwehr Landshut zu erhalten, wende dich bitte per E-Mail unter Angabe des vollen Namens und des Löschzugs an die Portal-Administration 
			(<a href="mailto:intranet.feuerwehr-landshut@thral.de?subject=Fehlerbericht%20Intranet" >intranet.feuerwehr-landshut@thral.de</a>).
			Vorraussetzung ist, dass du aktives Mitglied der Feuerwehr der Stadt Landshut bist.</p> 
			<p>Für erweiterte Berechtigungen im Portal (z.B. Verwaltung von Wachen und -berichten oder Hydrantenprüfungen) ist eine Bestätigung deiner 
			Zugführung oder der Feuerwehrverwaltung nötig. In diesem Fall muss diese die zusätzlichen Berechtigungen beim Administrator beantragen.</p>
			<p>Die Zugangsdaten werden per E-Mail an dich übermittelt.</p>
		</div>
	</div>
</form>

