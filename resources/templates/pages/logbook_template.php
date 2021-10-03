<?php
if ( ! isset($logbook) || ! count ( $logbook )) {
	showInfo ( "Es ist kein Eintrag vorhanden" );
} else {
?>
<div class="table-responsive">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th class="text-center">Datum/Uhrzeit</th>
				<th class="text-center">Action-Code</th>
				<th class="text-center">Nachricht</th>
				<th class="text-center">Angemeldeter Benutzer</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $logbook as $row ) {
			$user = null;
			if($row->getUser() != null){
				$user = $userDAO->getUserByUUID($row->getUser());
			}
		?>
			<tr>
				<td class="text-center"><span class='d-none'><?= strtotime($row->getTimestamp()) ?></span><?= date($config ["formats"] ["datetime"], strtotime($row->getTimestamp())); ?></td>
				<td class="text-center"><?= $row->getAction() ?></td>
				<td class="text-center"><?= $row->getMessage() ?></td>
				<td class="text-center">
					<?php 
					if ($user != null){
						echo $user->getFullNameWithEmail();
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
$pageLink = $config["urls"]["intranet_home"] . '/logbook/page/';
renderPagination($pageLink, $logbookDAO->getLogbookEntryCount(), $currentPage, $resultSize);

}
?>

<br>

<form method="post" >
	<input type="submit" name="purge" value="Log leeren" class="btn btn-primary">
</form>