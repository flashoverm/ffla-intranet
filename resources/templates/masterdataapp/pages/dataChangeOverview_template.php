<ul class="nav nav-tabs mb-3">
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'open'){ echo "active"; } ?>" href="<?= $config["urls"]["masterdataapp_home"] ?>/datachangerequests">Offene Anfragen/Rückfragen</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'done'){ echo "active"; } ?>" href="<?= $config["urls"]["masterdataapp_home"] ?>/datachangerequests/done">Abgeschlossene Anfragen</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'declined'){ echo "active"; } ?>" href="<?= $config["urls"]["masterdataapp_home"] ?>/datachangerequests/declined">Abgelehnte Anfragen</a>
	</li>
</ul>

<?php
if ( ! ( count ( $dataChangeRequests ) || ( isset($furtherRequest) && count ( $furtherRequest ) ) ) ) {
	showInfo ( "Keine Stammdatenänderungen vorhanden" );
} else {
	
	if($tab == 'open'){
		
		//Show additional further request table if present
		if(count ( $furtherRequest ) ){

			echo '<h4>Rückfragen</h4>';
			echo '<h6 class="text-muted">Inhalt der Rückfrage wird unter "Bearbeiten" angezeigt</h6>';
			
			$options = array(
				'showUserOptions' => true,
			);
			
			renderDataChangeTable($furtherRequest, $options);
			
			if( count ( $dataChangeRequests ) ){
				echo '<h4 class="my-3">Offene Anfragen</h4>';
			}
		}
		
		if(count ( $dataChangeRequests ) ){
			
			$options = array(
					'showUserOptions' => true,
			);
			
			renderDataChangeTable($dataChangeRequests, $options);
		}
		
	} else {
		$options = array();
		
		renderDataChangeTable($dataChangeRequests, $options);
		
	}
}




$declined = $dataChangeRequests;
$furtherRequest = array();

if ( ! count ( $furtherRequest ) && ! count ( $declined ) ) {
	showInfo ( "Keine Stammdatenänderungen offen oder angefragt" );
}

if ( count ( $furtherRequest ) ) {
	?>
<h3 class="my-3">Anfragen mit Rückfragen</h3>
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
			foreach ( $furtherRequest as $row ) {
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
						createDialog('showComment' . $row->getUuid(), "Anmerkungen", null, null, null, null, "Schließen", nl2br($row->getComment()));
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
