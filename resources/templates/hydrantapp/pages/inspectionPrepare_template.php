<?php

function generateTableRow($row, $rowIdPrefix = 'candidate'){
	?>
	<tr id='<?= $rowIdPrefix . $row->hy; ?>'>
	<td class="text-center"><?= $row->hy; ?></td>
		<td class="text-center"><?= $row->fid; ?></td>
		<td class="text-center"><?= $row->street; ?></td>
		<td class="text-center"><?= $row->district; ?></td>	
		<?php if(!isset($row->lastcheck)) {?>
			<td class="text-center">Nie</td>
		<?php } else { ?>			
			<td class="text-center"><span class='d-none'><?= strtotime($row->date) ?></span><?= date($config ["formats"] ["date"], strtotime($row->lastcheck)); ?></td>
		<?php } ?>
		
		<td class="text-center"><?= $row->cycle; ?></td>

		<td class="text-center">
			<button class="btn btn-primary btn-sm" id='btn<?= $rowIdPrefix . $row->hy; ?>' onclick="planForInspection('<?= $row->hy; ?>')">Hinzufügen</button>
		</td>
	</tr>
<?php 
}

if (! count ( $hydrants )) {
	showInfo ( "Keine Hydranten gefunden" );
} else {
	?>

<div class="row">
	<div class="col">
		<h2 class="">Prüfkandidaten</h2>
	</div>
	<div class="col">
		<div class="float-right">
			<button class="btn btn-primary btn-sm" type="button" data-toggle="collapse" data-target="#description">Erläuterung</button>
		</div>
	</div>
</div>

<div class="collapse" id="description">
  <div class="card card-body">
		<p>Klicke in der Tabelle auf "Hinzufügen" oder auf der Karte auf den Marker mit der entsprechenden Nummer (HY) um einen Hydranten für eine Hydrantenprüfung zu planen. 
		Die ausgewählten Hydranten werden in der Tabelle unten dargestellt.
		Ausgewählte Hydranten können mit "Entfernen" oder mit erneutem Klick auf der Marker in der Karte wieder entfernt werden.</p> 
		<p>Per Klick auf "Prüfung planen" wird eine Karte mit den ausgewählten Hydranten erstellt, die dann ausgedruckt werden kann.</p>
	</div>
</div>

<div class="table-responsive">
	<table class="table table-striped" data-toggle="table" data-pagination="true" data-search="true" id="candidates">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">HY</th>
				<th data-sortable="true" class="text-center">FID</th>
				<th data-sortable="true" class="text-center">Straße</th>
				<th data-sortable="true" class="text-center">Stadtteil</th>
				<th data-sortable="true" class="text-center">Zuletzt geprüft</th>
				<th data-sortable="true" class="text-center">Zyklus (Jahre)</th>
				<th class="text-center">Prüfung</th>
			</tr>
		</thead>
		<tbody>
    
    <?php
    foreach ( $hydrants as $row ) {
		generateTableRow($row);
	}
	?>
		</tbody>
	</table>
</div>
<div>
<?php 
    createHydrantGoogleMap($hydrants, true, false);
}
?>
</div>

<h2 class="mt-5 mb-2">Ausgewählte Hydranten</h2>

<form method="post" target="_blank">

	<div class="table-responsive form-group" >
		<table class="table table-striped" data-toggle="table" id="selected">
			<thead>
				<tr>
					<th data-sortable="true" class="text-center">HY</th>
					<th data-sortable="true" class="text-center">FID</th>
					<th data-sortable="true" class="text-center">Straße</th>
					<th data-sortable="true" class="text-center">Stadtteil</th>
					<th data-sortable="true" class="text-center">Zuletzt geprüft</th>
					<th data-sortable="true" class="text-center">Zyklus (Jahre)</th>
					<th class="text-center">Entfernen</th>
				</tr>
			</thead>
			
			<tbody id='planedList'>
			</tbody>
		</table>
	</div>

	<div id="buttonRow">
		<input type="submit" class="btn btn-primary" value="Karte anzeigen" formaction="<?= $config["urls"]["hydrantapp_home"] ?>/inspection/plan">
	</div>
</form>

<script>

var redIcon = "<?= $config["urls"]["intranet_home"] ?>/images/layout/map-icon-alt_sm.png";
var greenIcon = "<?= $config["urls"]["intranet_home"] ?>/images/layout/map-icon-green-alt_sm.png";

$('#candidates').on('all.bs.table', function (name, args) {
	
	  $("#candidates tr button").each(function (i, row) {
		  var hyNr = row.id.substring(12);
		  updateButtonStyle(hyNr, row);
	  });
	  
});

function setListener(){
	for (i = 0; i < markerList.length; i++) {  
		if( ! (typeof markerList[i] == 'undefined') ){
			markerList[i].addListener('click', function(e) {
				planForInspection(this.hy);
			  });
		}
	}
}

function alreadyPlaned(hy){
	var planed = document.getElementById('planed' + hy);
	if(planed != null){
		return true;
	}
	return false;
}

function planForInspection(hy){
	if(alreadyPlaned(hy)){
		alert("Hydrant bereits ausgewählt");
		return;
	}

	var row = document.getElementById('candidate' + hy);
	if(row == null){
		row = document.getElementById('data' + hy);	
	}
	var planed = row.cloneNode(true);
	planed.id = 'planed' + hy;
	var planedButton = planed.getElementsByTagName('button')[0];
	planedButton.id = 'btnplaned' + hy;
	planedButton.innerHTML = "Entfernen";
	planedButton.setAttribute("onclick", "unplanForInspection('"+ hy +"');");

	var input = document.createElement("input");
	input.classList.add('d-none');
	input.name = 'hydrants[]';
	input.value = hy;
	planed.appendChild(input);

	var planedList = document.getElementById('planedList');
	planedList.appendChild(planed);

	var rowButton = row.getElementsByTagName('button')[0];
	if(rowButton.id != 'btndata' + hy){
		updateButtonStyle(hy, rowButton);
	}

	markerList[hy].setIcon(greenIcon);
	google.maps.event.clearListeners(markerList[hy], 'click');
	markerList[hy].addListener('click', function(e) {
		unplanForInspection(this.hy);
	  });

	 var emptyRow = planedList.getElementsByClassName('no-records-found')[0];
	 if(emptyRow != null){
		 emptyRow.remove();
	 }
}

function unplanForInspection(hy){
	var planed = document.getElementById('planed' + hy);
	planed.parentNode.removeChild(planed);

	var row = document.getElementById('candidate' + hy);
	if(row != null){
		var rowButton = row.getElementsByTagName('button')[0];
		updateButtonStyle(hy, rowButton);
	}

	markerList[hy].setIcon(redIcon);
	google.maps.event.clearListeners(markerList[hy], 'click');
	markerList[hy].addListener('click', function(e) {
		planForInspection(this.hy);
	  });

	var planedList = document.getElementById('planedList');
	if( ! planedList.hasChildNodes()){
		console.log("last elem removed");
	}
	
}

function updateButtonStyle(hy, button){
	if(alreadyPlaned(hy)){
		button.innerHTML = "Entfernen";
		button.setAttribute("onclick", "unplanForInspection('"+ hy +"');");
		button.classList.add("btn-success");
	} else {
		button.innerHTML = "Hinzufügen";
		button.setAttribute("onclick", "planForInspection('"+ hy +"');");
		button.classList.remove("btn-success");
	}
}

</script>

<table class="d-none">
<?php
foreach ( $hydrants as $row ) {
	generateTableRow($row, 'data');
}
?>
</table>
