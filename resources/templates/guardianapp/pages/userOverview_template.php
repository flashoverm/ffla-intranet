<?php
require_once LIBRARY_PATH . '/db_engines.php';

if (! $isAdmin) {
	showAlert ( "Kein Administrator angemeldet - <a href=\"" . $config["urls"]["guardianapp_home"] . "/events\" class=\"alert-link\">Zurück</a>" );
} else {
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
				<th data-sortable="true" class="text-center">Wachteilnahme</th>
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
			if ($row->available) {
					echo "Freigegeben";
				} else {
					echo "Gesperrt";
				}
			?>
				</td>
				<td class="text-center">
					<form method="post" action="">
			<?php
			if ($row->available) {
				echo "<input type=\"hidden\" name=\"disable\" id=\"disable\" value='" . $row->uuid . "'/>";
				echo "<input type=\"submit\" value=\"Sperren\"  class=\"btn btn-outline-primary btn-sm\"/>";
			} else {
				echo "<input type=\"hidden\" name=\"enable\" id=\"enable\" value='" . $row->uuid . "'/>";
				echo "<input type=\"submit\" value=\"Freigeben\"  class=\"btn btn-primary btn-sm\"/>";
			}
			?>
					</form>
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
<a href='<?= $config["urls"]["guardianapp_home"]?>/user/import' class="btn btn-primary">Benutzer importieren</a>
<?php
}
?>