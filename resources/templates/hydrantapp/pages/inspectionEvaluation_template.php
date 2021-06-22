<div class="form-group">
	<label>Jahr:</label>
	<select class="form-control" name="user" id="user" required="required" onchange="location.href=this.options[this.selectedIndex].value">
		<?php foreach ( $years as $option ) {
		if($option == $year){?>
		   	<option value="<?= $config ["urls"] ["hydrantapp_home"] . "/inspection/evaluation/" .  $option ?>" selected><?= $option ?></option>
		<?php }else{ ?>
		   <option value="<?= $config ["urls"] ["hydrantapp_home"] . "/inspection/evaluation/" .  $option ?>"><?= $option ?></option>
		<?php } 
		} ?>
		</select> 
</div>

<table class="table table-bordered">
	<thead>
		<tr>
			<th>Löschzug</th>
			<th class="text-right">Geprüfte Hydranten im Jahr <?= $year ?></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$sum = 0;
		foreach ( $engineEntries as $entry ) { 
			$sum += $entry['count'];
		?>
		<tr>
			<td><?= $entry['engine'] ?></td>
			<td class="text-right"><?= $entry['count'] ?></td>
		</tr>
		<?php } ?>

	</tbody>
	<tfoot>
		<tr>
			<th>Gesamt</th>
			<th  class="text-right"><?= $sum ?></th>
		</tr>
	</tfoot>
</table>
