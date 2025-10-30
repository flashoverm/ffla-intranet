<?php

if(isset($_GET ['year'])){
    $year = $_GET ['year'];
} else {
    $year = date("Y");
}
$rate = ConfigUtil::getEventHourlyRate($year);
$yearlyLimit = ConfigUtil::getYearlyEventLimit($year);

showInfo("Hochrechnung bei " . number_format($rate, 2) . " €/Stunde - Abweichung möglich");
showInfo("Jährlicher Freibetrag " . $yearlyLimit . " €");
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
			    $earnings = $minutes/60*$rate;
			    if ($earnings >= $yearlyLimit) {
			        $bgColor = 'lightcoral'; // Light red
			    } elseif ($earnings > 0.8 * $yearlyLimit) {
			        $bgColor = 'lightyellow';
			    } else {
			        $bgColor = 'transparent'; // Default or no highlight
			    }
            ?>
				<tr>
					<td><?= $statisticData[$userUuid][0]->getFirstname(); ?></td>
					<td><?= $statisticData[$userUuid][0]->getLastname(); ?></td>
					<td><span class="d-none"><?= sprintf('%02d',$statisticData[$userUuid][1]) ?></span><?= $statisticData[$userUuid][1]; ?></td>
					<td><span class="d-none"><?= sprintf('%06d',$minutes) ?></span><?= floor($minutes/60) . ":" . sprintf("%02d", $minutes%60) ?></td>
					<td style="background-color: <?= $bgColor ?>;"><span class="d-none"><?= sprintf('%06d',$minutes) ?></span><?= number_format($earnings, 2, ",", "")?></td>
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