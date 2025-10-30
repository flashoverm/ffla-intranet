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
			    $rate = ConfigUtil::getEventHourlyRate($year);
			    $yearlyLimit = ConfigUtil::getYearlyEventLimit($year);
			    $earnings = $minutes/60*$rate;
			    if ($earnings >= $yearlyLimit) {
			        $bgColor = 'lightcoral';
			    } elseif ($earnings > 0.8 * $yearlyLimit) {
			        $bgColor = 'lightyellow';
			    } else {
			        $bgColor = 'transparent';
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
					<td>Wachverdienst (Hochrechnung bei <?= number_format($rate, 2) ?> €/Stunde - Abweichung möglich).<br>Jährlicher Freibetrag <?=  $yearlyLimit ?> €</td>
					<td style="background-color: <?= $bgColor ?>;"><?=  number_format($earnings, 2, ",", "") . " €"?></td>
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