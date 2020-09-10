<?php

if (! count ( $logbook )) {
	showInfo ( "Es ist kein Eintrag vorhanden" );
} else {
?>
<div class="table-responsive">
	<table class="table table-striped" data-toggle="table" data-pagination="true"  data-search="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">Datum/Uhrzeit</th>
				<th data-sortable="true" class="text-center">Action-Code</th>
				<th data-sortable="true" class="text-center">Nachricht</th>
				<th data-sortable="true" class="text-center">Angemeldeter Benutzer</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $logbook as $row ) {
			$user = null;
			if($row->user != null){
				$user = get_user($row->user);
			}
		?>
			<tr>
				<td class="text-center"><span class='d-none'><?= strtotime($row->timestamp) ?></span><?= date($config ["formats"] ["datetime"], strtotime($row->timestamp)); ?></td>
				<td class="text-center"><?= $row->action; ?></td>
				<td class="text-center"><?= logbookEnry($row->action, $row->object); ?></td>
				<td class="text-center">
					<?php 
					if ($user != null){
						echo $user->firstname . " " . $user->lastname . " (" . $user->email . ")";
					} else {
						echo "-";
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