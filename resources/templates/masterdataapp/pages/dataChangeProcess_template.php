<?php
if ( ! count ( $open ) ) {
	showInfo ( "Keine Anträge offen" );
} else {
?>
<h3 class="mb-3 mt-3">Offene Anfragen</h3>
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
				<td class="text-center"><span class='d-none'><?= strtotime($row->getCreateDate()) ?></span><?= date($config ["formats"] ["date"], strtotime($row->getCreateDate())); ?></td>
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
						createDialog('showComment' . $row->getUuid(), "Anmerkungen", null, null, null, null, "Schließen", $row->getComment());
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
</div>

<?php
}

    