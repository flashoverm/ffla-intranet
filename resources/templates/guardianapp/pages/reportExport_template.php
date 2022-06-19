<form action="" method="post">
    <div class="row">
    	<div class="col">
        	<div class="form-group">
        		<label>Typ:</label> <select class="form-control" name="type" id="type">
        		    	<option value="-1" selected>Alle Wachen</option>
        		    	<option value="-2">Alle Theaterwachen</option>
        		    <?php foreach ( $eventtypes as $eventtype ) :
        		    if(isset($type) && $eventtype->getUuid() == $type) {?>
        					<option value="<?= $eventtype->getUuid(); ?>" selected><?= $eventtype->getType(); ?></option>
        				<?php } else {?>
        					<option value="<?= $eventtype->getUuid(); ?>"><?= $eventtype->getType(); ?></option>
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
	<div>
    <?php
    if (! count ( $reports )) {
        showInfo ( "Es sind keine Wachberichte vorhanden" );
    } else {
    	$columns = array(
    			array( "label" => "Datum", "sort" => ReportDAO::ORDER_DATE),
    			array( "label" => "Beginn", "sort" => ReportDAO::ORDER_START),
    			array( "label" => "Ende", "sort" => ReportDAO::ORDER_END),
    			array( "label" => "Typ", "sort" => ReportDAO::ORDER_TYPE),
    			array( "label" => "Titel", "sort" => ReportDAO::ORDER_TITLE),
    			array( "label" => "Zuständig", "sort" => ReportDAO::ORDER_ENGINE),
    			array( "label" => "Freigabe", "sort" => ReportDAO::ORDER_APPROVED),
    	);
    	$columns[] = array();
    	
    	renderTable(
    			TEMPLATES_PATH . "/guardianapp/elements/report_row.php",
    			$columns,
    			$reports,
    			array('showApproval' => true, "hideSearch" => true)
    	);
        ?>
       </div>
    <p>
    	<input type="submit" name="csv" class="btn btn-primary" value='Wachen exportieren'>
    	<input type="submit" name="invoice" class="btn btn-primary" value='Rechungsdaten exportieren'>
    	<input type="submit" name="csvlist" class="btn btn-primary" value='Wachen exportieren (Liste)'>
    </p>
</form>

<?php
}
?>