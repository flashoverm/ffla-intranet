<?php

if(isset($_GET ['year'])){
    $year = $_GET ['year'];
} else {
    $year = date("Y");
}

if(isset($config["settings"]["eventHourlyRate"][$year])){
    $rate = $config["settings"]["eventHourlyRate"][$year];
} else {
    $rate = end($config["settings"]["eventHourlyRate"]);
}

showInfo("Hochrechnung bei " . number_format($rate, 2) . " €/Stunde - Abweichung möglich) ");
?>
<div class="form-group">
	<select class="form-control" name="year" id="year" onchange="loadStatistics()">
		<option value="" disabled selected>Jahr auswählen</option>
		<?php foreach ( $years as $year ) :
		if(
		    (isset($_GET ['year']) && $_GET ['year'] == $year )
		    || ( ! isset($_GET ['year']) && $year == date("Y") )
		){?>
				<option value="<?= $year; ?>" selected><?= $year; ?></option>
		<?php } else {?>
				<option value="<?= $year; ?>"><?= $year; ?></option>
		<?php }
		endforeach; ?>
	</select>
</div>
<div class="table-responsive">
	<table class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true">
		<thead>
			<tr>
				<th data-sortable="true">Vorname</th>
				<th data-sortable="true">Nachname</th>
				<th data-sortable="true">Anzahl</th>
				<th data-sortable="true">Stunden</th>
				<th data-sortable="true">Verdienst (€)</th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( array_keys($statisticData) as $userUuid ) {
			    $minutes = $statisticData[$userUuid][2];

            ?>
				<tr>
					<td><?= $statisticData[$userUuid][0]->getFirstname(); ?></td>
					<td><?= $statisticData[$userUuid][0]->getLastname(); ?></td>
					<td><?= $statisticData[$userUuid][1]; ?></td>
					<td><span style="display:none"><?=$minutes ?></span><?= floor($minutes/60) . ":" . sprintf("%02d", $minutes%60) ?></td>
					<td><span style="display:none"><?=$minutes?></span><?= number_format($minutes/60*$rate, 2, ",", "")?></td>
				</tr>
            <?php
	        }
            ?>
		</tbody>
	</table>
</div>

<script>
	function loadStatistics(){
		var selectedYear = document.getElementById('year');
        window.location = "<?= $config["urls"]["guardianapp_home"] ?>/reports/enginestatistics/" + selectedYear.value; // redirect
	}
</script>