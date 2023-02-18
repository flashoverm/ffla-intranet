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
	<table class="table table-bordered">
		<tbody>
			<?php 
			foreach ( $engines as $engine ) { 
			    if( isset($statisticData[$engine->getUuid()]) ){
			?>
			<tr>
				<th colspan="2"><?= $engine->getName() ?></th>
			</tr>
			<tr>
				<td>Anzahl Wachen</td>
				<td><?= $statisticData[$engine->getUuid()][0]?></td>
			</tr>
			<tr>
				<td>Wachpersonal Gesamt</td>
				<td><?= $statisticData[$engine->getUuid()][1]?></td>
			</tr>
			<tr>
				<td>Wachstunden</td>
				<td>
				<?php 
				    $minutes = $statisticData[$engine->getUuid()][2];
				    echo floor($minutes/60) . ":" . sprintf("%02d", $minutes%60) . " h";
				 ?>
				 </td>
			</tr>
			<?php
    			}
			}
			?>
		</tbody>
	</table>
</div>

<script>
	function loadStatistics(){
		var selectedYear = document.getElementById('year');
        window.location = "<?= $config["urls"]["guardianapp_home"] ?>/reports/statistics/" + selectedYear.value; // redirect
	}
</script>