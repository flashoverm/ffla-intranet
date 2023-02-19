<?php
if( ! isset($statisticData) || count($statisticData) < 1 ){
    showInfo("Keine Wachteilnahme seit Datenerfassung");
} else {
?>  
<div class="table-responsive">
	<table class="table table-bordered">
		<tbody>
			<?php
			foreach( array_keys($statisticData) as $year){
			    $minutes = $statisticData[$year][1];
			    if(isset($config["settings"]["eventHourlyRate"][$year])){
			        $rate = $config["settings"]["eventHourlyRate"][$year];
			    } else {
			        $rate = end($config["settings"]["eventHourlyRate"]);
			    }
			?>
    			<tr>
    				<th colspan="2"><?= $year ?></th>
    			</tr>
				<tr>
					<td>Anzahl Wachen</td>
					<td><?= $statisticData[$year][0]?></td>
				</tr>
				<tr>
					<td>Wachstunden</td>
					<td><?= floor($minutes/60) . ":" . sprintf("%02d", $minutes%60) . " h"?></td>
				</tr>
				<tr>
					<td>Wachverdienst (Hochrechnung bei <?= number_format($rate, 2) ?> €/Stunde - Abweichung möglich)</td>
					<td><?= ($minutes/60*$rate) . " €"?></td>
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