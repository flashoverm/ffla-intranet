<?php

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
				<th data-sortable="true" class="text-center">Letzte Anmeldung</th>
				<th class="text-center"></th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $user as $row ) {
		?>
			<tr>
				<td class="text-center"><?= $row->getFirstname(); ?></td>
				<td class="text-center"><?= $row->getLastname(); ?></td>
				<td class="text-center"><?= $row->getEngine()->getName() ?></td>
				<td class="text-center"><?= $row->getEmail(); ?></td>
				<td class="text-center">
					<?php
					if($row->getPassword() != null){
						if (! $row->getLocked()) {
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
					<?php if( $row->getLastLogin() != null ) { ?>
						<span class='d-none'><?= strtotime($row->getLastLogin()) ?></span><?= date($config ["formats"] ["datetime"], strtotime($row->getLastLogin())); ?>
					<?php } else {
						echo "Nie";
					} ?>
				</td>
				<td>
					<div class="dropdown">
						<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" data-boundary="window">Optionen</button>
						<div class="dropdown-menu">

							<a class="dropdown-item" href="<?= $config["urls"]["intranet_home"] . "/users/admin/" . $row->getUuid() ?>">Bearbeiten</a>
							<div class="dropdown-divider"></div>
							<?php
							if($row->getPassword() != null){
								?>
								<form method="post" action="">
								<?php
								if (! $row->getLocked()) {
									echo "<input type=\"hidden\" name=\"disable\" id=\"disable\" value='" . $row->getUuid() . "'/>";
									echo "<input type=\"submit\" value=\"Sperren\"  class=\"dropdown-item\"/>";
								} else {
									echo "<input type=\"hidden\" name=\"enable\" id=\"enable\" value='" . $row->getUuid() . "'/>";
									echo "<input type=\"submit\" value=\"Freigeben\"  class=\"dropdown-item\"/>";
								}
								?>
								</form>
							<?php
							}
							?>
							<form method="post" action="">
								<input type="hidden" name="delete" id="delete" value="<?= $row->getUuid() ?>"/>
								<input type="submit" value="Löschen"  class="dropdown-item"/>
							</form>
							<div class="dropdown-divider"></div>
							<?php
							if($row->getPassword() != null){
							?>
								<button type="button" class="dropdown-item" data-toggle="modal" data-target="#confirmReset<?= $row->getUuid(); ?>">Passwort zurücksetzen</button>
							<?php 
							} else {
							?>
								<button type="button" class="dropdown-item" data-toggle="modal" data-target="#confirmSet<?= $row->getUuid(); ?>">Passwort hinzufügen</button>
							<?php 
							}
							?>
							<div class="dropdown-divider"></div>
							<a class="dropdown-item" href="<?= $config["urls"]["intranet_home"] . "/administration/logbook?user=" . $row->getUuid() ?>">Logbuch</a>
						</div>
					</div>
					<?php
					if($row->getPassword() != null){
						createDialog('confirmReset' . $row->getUuid(), "Passwort wirklich zurücksetzen?", null, "resetpw", $row->getUuid());
					} else {
						createDialog('confirmSet' . $row->getUuid(), "Passwort für diesen Benutzer anlegen und Anmeldung freischalten?", null, "setpw", $row->getUuid());
					}
					?>
							<script>
        	console.log($('.table-responsive'));
            $('.table-responsive').on('show.bs.dropdown', function () {
            	 console.log("Do");
                 $('.table-responsive').css( "overflow", "inherit" );
            });
            
            $('.table-responsive').on('hide.bs.dropdown', function () {
                 $('.table-responsive').css( "overflow", "auto" );
            });
        </script>
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