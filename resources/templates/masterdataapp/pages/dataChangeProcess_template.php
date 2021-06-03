<?php
if ( ! count ( $open ) ) {
	showInfo ( "Keine Stammdatenänderungen offen oder angefragt" );
}

if ( count ( $open ) ) {
?>
<h3 class="my-3">Offene Anfragen</h3>
<div class="table-responsive">
	<table class="table table-striped" data-toggle="table" data-pagination="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">Erstelldatum</th>
				<th data-sortable="true" class="text-center">Typ</th>
				<th data-sortable="true" class="text-center">Neuer Wert</th>
				<th data-sortable="true" class="text-center">Änderung bei</th>
				<th data-sortable="true" class="text-center">Antragsteller</th>
				<th data-sortable="true" class="text-center">Löschzug</th>
				<th class="text-center">Anmerkungen</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $open as $row ) {
		?>
			<tr>
				<td class="text-center"><span class='d-none'><?= strtotime($row->getCreateDate()) ?></span><?= date($config ["formats"] ["datetime"], strtotime($row->getCreateDate())); ?></td>
				<td class="text-center"><?= DataChangeRequest::DATATYPE_TEXT[$row->getDataType()] ?></td>
				<td class="text-center" id="value<?= $row->getUuid() ?>"><?= $row->getNewValue() ?></td>
				<td class="text-center">
					<?php 
					if($row->getPerson() != NULL ){
						echo $row->getPerson();
					} else {
						echo "Antragsteller";
					}
					?>
				</td>
				<td><?= $row->getUser()->getFullName() ?></td>
				<td><?= $row->getUser()->getEngine()->getName() ?></td>
				<td class="text-center">
					<?php 
					if($row->getComment() != null){
					?>
						<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#showComment<?= $row->getUuid()?>">Anmerkungen</button><br>
						<button type="button" class="btn btn-outline-primary btn-sm mt-1" id="copy<?= $row->getUuid() ?>" onclick="copyToClipboard('<?= $row->getUuid()?>')">Wert kopieren</button>
					<?php
						createDialog('showComment' . $row->getUuid(), "Anmerkungen", null, null, null, null, "Schließen", nl2br($row->getComment()));
					} else {
						echo "Keine";
					}
					?>
				</td>
				<td class="text-center">
					<div class="dropdown">
						<button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown">Optionen</button>
						<div class="dropdown-menu">
							<form class="mb-0" method="post" action="" onsubmit="showLoader()" >
								<input type="hidden" name="datachangerequest" id="datachangerequest" value="<?= $row->getUuid() ?>"/>
								<input type="submit" name="done" value="Umgesetzt"  class="dropdown-item"/>
								<div class="dropdown-divider"></div>
								<button type="button" class="dropdown-item" data-toggle='modal' data-target='#sendRequest<?= $row->getUuid() ?>'>Rückfrage</button>
								<div class="dropdown-divider"></div>
								<input type="submit" name="declined" value="Ablehnen"  class="dropdown-item"/>
							</form>
						</div>
					</div>
				</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
	<?php
	foreach ( $open as $row ) {
	?>
		<div class='modal' id='sendRequest<?= $row->getUuid() ?>'>
			<div class='modal-dialog'>
				<div class='modal-content'>
					<form method="post" action="">
						<div class='modal-header'>
							<h4 class='modal-title'>Rückfrage zu Änderungsantrag</h4>
							<button type='button' class='close' data-dismiss='modal'>&times;</button>
						</div>
						<div class='modal-body'>
							<div class="form-group">
								<input type="text" required="required" placeholder="Rückfrage eingeben" class="form-control" 
								name="requesttext" id="requesttest" >
							</div>
						</div>
						<div class='modal-footer'>
							<input type="hidden" name="datachangerequest" id="datachangerequest" value="<?= $row->getUuid() ?>"/>
							<input type='submit' name="request" value='Absenden' class='btn btn-primary'/>
							<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	<?php 
	}
	?>
</div>

<?php
}

if ( count ( $done ) || count ( $declined ) ) {
?>
	<button class="btn btn-outline-primary mt-3 mb-2" type="button" data-toggle="collapse" data-target="#done">
	    Bearbeitete Änderungen
	</button>
<div id="done" class="collapse">
	<h3 class="mb-3 mt-3">Änderungen durchgeführt</h3>
	<div class="table-responsive">
		<table class="table table-striped" data-toggle="table" data-pagination="true">
			<thead>
				<tr>
					<th data-sortable="true" class="text-center">Erstelldatum</th>
					<th data-sortable="true" class="text-center">Typ</th>
					<th data-sortable="true" class="text-center">Neuer Wert</th>
					<th data-sortable="true" class="text-center">Änderung bei</th>
					<th data-sortable="true" class="text-center">Antragsteller</th>
					<th data-sortable="true" class="text-center">Löschzug</th>
					<th class="text-center">Anmerkungen</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $done as $row ) {
			?>
				<tr>
					<td class="text-center"><span class='d-none'><?= strtotime($row->getCreateDate()) ?></span><?= date($config ["formats"] ["datetime"], strtotime($row->getCreateDate())); ?></td>
					<td class="text-center"><?= DataChangeRequest::DATATYPE_TEXT[$row->getDataType()] ?></td>
					<td class="text-center"><?= $row->getNewValue() ?></td>
					<td class="text-center">
						<?php 
						if($row->getPerson() != NULL ){
							echo $row->getPerson();
						} else {
							echo "Antragsteller";
						}
						?>
					</td>
					<td><?= $row->getUser()->getFullName() ?></td>
					<td><?= $row->getUser()->getEngine()->getName() ?></td>
					<td class="text-center">
						<?php 
						if($row->getComment() != null){
						?>
							<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#showComment<?= $row->getUuid()?>">Anmerkungen</button>
						<?php
							createDialog('showComment' . $row->getUuid(), "Anmerkungen", null, null, null, null, "Schließen", nl2br($row->getComment()));
						} else {
							echo "Keine";
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
	
	if ( count ( $declined ) ) {
		?>
	<h3 class="my-3">Abgelehnte Änderungen</h3>
	<div class="table-responsive">
		<table class="table table-striped" data-toggle="table" data-pagination="true">
			<thead>
				<tr>
					<th data-sortable="true" class="text-center">Erstelldatum</th>
					<th data-sortable="true" class="text-center">Typ</th>
					<th data-sortable="true" class="text-center">Neuer Wert</th>
					<th data-sortable="true" class="text-center">Änderung bei</th>
					<th data-sortable="true" class="text-center">Antragsteller</th>
					<th data-sortable="true" class="text-center">Löschzug</th>
					<th class="text-center">Anmerkungen</th>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $declined as $row ) {
			?>
				<tr>
					<td class="text-center"><span class='d-none'><?= strtotime($row->getCreateDate()) ?></span><?= date($config ["formats"] ["datetime"], strtotime($row->getCreateDate())); ?></td>
					<td class="text-center"><?= DataChangeRequest::DATATYPE_TEXT[$row->getDataType()] ?></td>
					<td class="text-center"><?= $row->getNewValue() ?></td>
					<td class="text-center">
						<?php 
						if($row->getPerson() != NULL ){
							echo $row->getPerson();
						} else {
							echo "Antragsteller";
						}
						?>
					</td>
					<td><?= $row->getUser()->getFullName() ?></td>
					<td><?= $row->getUser()->getEngine()->getName() ?></td>
					<td class="text-center">
						<?php 
						if($row->getComment() != null){
						?>
							<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#showComment<?= $row->getUuid()?>">Anmerkungen</button>
						<?php
							createDialog('showComment' . $row->getUuid(), "Anmerkungen", null, null, null, null, "Schließen", nl2br($row->getComment()));
						} else {
							echo "Keine";
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
</div>
<?php
}
?>
<script>
function copyToClipboard(uuid) {
	var r = document.createRange();
	r.selectNode(document.getElementById("value" + uuid));
	console.log(document.getElementById("value" + uuid).value); 
	window.getSelection().removeAllRanges();
	window.getSelection().addRange(r);
	document.execCommand('copy');
	window.getSelection().removeAllRanges();
	
	btn = document.getElementById("copy" + uuid);
	btn.className  = "btn btn-outline-success btn-sm mt-1";
	btn.firstChild.nodeValue = "Wert kopiert";
}
</script>
    