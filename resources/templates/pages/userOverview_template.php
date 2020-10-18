<?php
require_once LIBRARY_PATH . '/db_engines.php';

if (! count ( $user )) {
	showInfo ( "Es ist kein Personal angelegt" );
} else {
?>
<div class="table-responsive">
	<table class="table table-striped" data-toggle="table" data-pagination="true"  data-search="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">Vorname</th>
				<th data-sortable="true" class="text-center">Nachname</th>
				<th data-sortable="true" class="text-center">Löschzug</th>
				<th data-sortable="true" class="text-center">E-Mail</th>
				<th data-sortable="true" class="text-center">Anmeldung</th>
				<th class="text-center"></th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $user as $row ) {
		?>
			<tr>
				<td class="text-center"><?= $row->firstname; ?></td>
				<td class="text-center"><?= $row->lastname; ?></td>
				<td class="text-center"><?= get_engine($row->engine)->name; ?></td>
				<td class="text-center"><?= $row->email; ?></td>
				<td class="text-center">
					<?php
					if($row->password != null){
						if (! $row->locked) {
							echo "Freigegeben";
						} else {
							echo "Gesperrt";
						}
					} else {
						echo "Ohne Anmeldung";
					}
					?>
				</td>
				<td class="text-center">
					<div class="dropdown">
						<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">Optionen</button>
						<div class="dropdown-menu">

							<a class="dropdown-item" href="<?= $config["urls"]["intranet_home"] . "/users/" . $row->uuid . "/edit"?>">Bearbeiten</a>
							<div class="dropdown-divider"></div>
							<form method="post" action="">
							<?php
							if($row->password != null){
								if (! $row->locked) {
									echo "<input type=\"hidden\" name=\"disable\" id=\"disable\" value='" . $row->uuid . "'/>";
									echo "<input type=\"submit\" value=\"Sperren\"  class=\"dropdown-item\"/>";
								} else {
									echo "<input type=\"hidden\" name=\"enable\" id=\"enable\" value='" . $row->uuid . "'/>";
									echo "<input type=\"submit\" value=\"Freigeben\"  class=\"dropdown-item\"/>";
								}
								echo "<input type=\"hidden\" name=\"delete\" id=\"delete\" value='" . $row->uuid . "'/>";
								echo "<input type=\"submit\" value=\"Löschen\"  class=\"dropdown-item\"/>";
							}
							?>
							</form>
							
							<div class="dropdown-divider"></div>
							<?php
							if($row->password != null){
							?>
								<button type="button" class="dropdown-item" data-toggle="modal" data-target="#confirmReset<?= $row->uuid; ?>">Passwort zurücksetzen</button>
							<?php 
							} else {
							?>
								<button type="button" class="dropdown-item" data-toggle="modal" data-target="#confirmSet<?= $row->uuid; ?>">Passwort hinzufügen</button>
							<?php 
							}
							?>
						</div>
					</div>
					<?php
					if($row->password != null){
						createDialog('confirmReset' . $row->uuid, "Passwort wirklich zurücksetzen?", null, "resetpw", $row->uuid);
					} else {
						createDialog('confirmSet' . $row->uuid, "Passwort für diesen Benutzer anlegen und Anmeldung freischalten?", null, "setpw", $row->uuid);
					}
					?>
				</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
</div>

<?php
}
?>
<a href='<?= $config["urls"]["intranet_home"]?>/users/new' class="btn btn-primary">Benutzer anlegen</a>
<a href='<?= $config["urls"]["intranet_home"]?>/users/import' class="btn btn-primary float-right">Benutzer importieren</a>
