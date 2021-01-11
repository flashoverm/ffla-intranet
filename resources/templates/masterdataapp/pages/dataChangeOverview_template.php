<?php
if ( ! count ( $declined ) && ! count ( $open ) && ! count ( $done ) ) {
	showInfo ( "Keine Stammdatenänderungen offen oder angefragt" );
}

if ( count ( $open ) ) {
?>
<h3 class="my-3">Offene Anfragen</h3>
<div class="table-responsive">
	<table class="table table-hover table-striped" data-toggle="table" data-pagination="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center" >Erstelldatum</th>
				<th data-sortable="true" class="text-center" >Typ</th>
				<th data-sortable="true" class="text-center" >Neuer Wert</th>
				<th data-sortable="true" class="text-center" >Änderung bei</th>
				<th class="text-center">Anmerkungen</th>
				<th class=""></th>
				<th class=""></th>
			</tr>
		</thead>
		<tbody>
			<?php
			$index = 0;
			foreach ( $open as $row ) {
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
					<a class="btn btn-primary btn-sm mr-1" href="<?= $config["urls"]["masterdataapp_home"] . "/datachangerequests/".$row->getUuid() ."/edit" ?>">Bearbeiten</a>
				</td>
				<td class="text-center">
					<form method="post" action="" class="mb-0">
						<input type="hidden" name="withdraw" value="<?= $row->getUuid() ?>"/>
						<input type="submit" value="Zurückziehen" class="btn btn-primary btn-sm"/>
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

if ( count ( $done ) || count ( $declined ) ) {
 
	if (  count ( $open ) ) {
	?>
		<button class="btn btn-outline-primary mt-3 mb-2" type="button" data-toggle="collapse" data-target="#done">
		    Bearbeitete Änderungen
		</button>
	<?php
	}
	?>
<div id="done" class="collapse  
<?php 
if ( ! count ( $open ) ) {
	echo " show ";
}
?>">
	<h3 class="mb-3 mt-3">Änderungen durchgeführt</h3>
	<div class="table-responsive">
		<table class="table table-striped" data-toggle="table" data-pagination="true">
			<thead>
				<tr>
					<th data-sortable="true" class="text-center" >Erstelldatum</th>
					<th data-sortable="true" class="text-center" >Typ</th>
					<th data-sortable="true" class="text-center" >Neuer Wert</th>
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
					<th data-sortable="true" class="text-center" >Erstelldatum</th>
					<th data-sortable="true" class="text-center" >Typ</th>
					<th data-sortable="true" class="text-center" >Neuer Wert</th>
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
    