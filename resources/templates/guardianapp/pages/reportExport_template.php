<form action="" method="post">
    <div class="row">
    	<div class="col">
        	<div class="form-group">
        		<label>Typ:</label> <select class="form-control" name="type" id="type">
        		    	<option value="-1" selected>Alle Wachen</option>
        		    <?php foreach ( $eventtypes as $type ) :
        				if(isset($type) && $type->uuid == $type) {?>
        					<option value="<?= $type->uuid; ?>" selected><?= $type->type; ?></option>
        				<?php } else {?>
        					<option value="<?= $type->uuid; ?>"><?= $type->type; ?></option>
        				<?php }
				    endforeach; ?>
        			</select>
        	</div>
        </div>
    	<div class="col">
    		<div class="form-group">
    			<label>Von:</label> <input type="date" required="required" 
    			placeholder="TT.MM.JJJJ" title="TT.MM.JJJJ" class="form-control" 
    			name="from" id="from" 
    			<?php
    			if(isset($from)){
    			    echo "value='" . $from . "'";
    			}?>
    			required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
    		</div>
    	</div>
    	<div class="col">
    		<div class="form-group">
    			<label>Bis:</label> <input type="date" required="required" 
    			placeholder="TT.MM.JJJJ" title="TT.MM.JJJJ" class="form-control" 
    			name="to" id="to" 
    			<?php
    			if(isset($to)){
    			    echo "value='" . $to . "'";
    			}?>
    			required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
    		</div>
    	</div>
    </div>
    <p>
    	<input type="submit" class="btn btn-primary" value='Filter anwenden'>
    </p>

    <?php
    if (! count ( $reports )) {
        showInfo ( "Es sind keine Wachberichte vorhanden" );
    } else {
        ?>
    <div class="table-responsive">
    	<table class="table table-striped" data-toggle="table" data-pagination="true">
    		<thead>
    			<tr>
    				<th data-sortable="true" class="text-center">Datum</th>
    				<th data-sortable="true" class="text-center">Wachbeginn</th>
    				<th data-sortable="true" class="text-center">Ende</th>
    				<th data-sortable="true" class="text-center">Typ</th>
    				<th data-sortable="true" class="text-center">Titel</th>
    				<th data-sortable="true" class="text-center">Zuständig</th>
    				<th data-sortable="true" class="text-center">Freigabe</th>
    				<th class="text-center">Bericht</th>
    			</tr>
    		</thead>
    		<tbody>
    	<?php
    		foreach ( $reports as $row ) {
    		    ?>
    				<tr>
    				<td class="text-center"><?= date($config ["formats"] ["date"], strtotime($row->date)); ?></td>
    				<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($row->start_time)); ?></td>
    				<td class="text-center">
    	<?php
    		if ($row->end_time != 0) {
    		    echo date($config ["formats"] ["time"], strtotime($row->end_time));
    		} else {
    			echo " - ";
    		}
    		?></td>
    				<td class="text-center"><?= get_eventtype($row->type)->type; ?></td>
    				<td class="text-center"><?= $row->title; ?></td>
    				<td class="text-center"><?= get_engine($row->engine)->name; ?></td>
    				<td class="text-center">
    					<?php
    					if($row->managerApproved){
    					    echo " &#10003; ";
    					} else {
    						echo " &ndash; ";
    					}
    					?>
    				</td>
    				<td class="text-center">
    					<a class="btn btn-primary btn-sm" href="<?=$config["urls"]["guardianapp_home"] . "/reports/".$row->uuid ?>">Bericht</a>
    				</td>
    			</tr>
    	<?php
    		}
    		?>
    			</tbody>
    	</table>
    </div>
    <p>
    	<input type="submit" name="csv" class="btn btn-primary" value='Wachen exportieren'>
    	<input type="submit" name="invoice" class="btn btn-primary" value='Rechungsdaten exportieren'>
    </p>
</form>

<?php
}
?>