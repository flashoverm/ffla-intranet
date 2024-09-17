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
				<th data-sortable="true" class="text-center">Haupt-Löschzug</th>
				<th data-sortable="true" class="text-center">E-Mail</th>
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