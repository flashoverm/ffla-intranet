<?php 
function createUnitCard($number, ReportUnit $unit = null) {
?>

<div id="unit<?= $number ?>" class="card" <?php if(!isset($unit)) { echo 'style="display:none;"'; } ?>>
	<div class="card-header">
		<h5 class="mb-0">
			<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?= $number ?>">
			<?php if(isset($unit)){ 
			    
			    echo  $unit->getUnitName(); 
			} ?>
			</button>
		</h5>
	</div>
    <div class="collapse unittemplateBody" id="collapse<?= $number ?>" data-parent="#unitlist">
    	<div class="card-body">
    		<div class="row form-group">
    			<div class="col-sm">
    				<label>Datum:</label>
        			<input class="form-control bg-white" id="unit<?= $number ?>date" name="unit<?= $number ?>date" 
        				disabled type="date" required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}"
        				<?php if(isset($unit)){ echo "value='" . $unit->getDate() . "'"; } ?>
        				>
        		</div>
        		<div class="col-sm">
        			<label>Wachbeginn:</label>
        			<input class="form-control  bg-white" id="unit<?= $number ?>start" name="unit<?= $number ?>start" 
        				disabled type="time" required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])"
        				<?php if(isset($unit)){ echo "value='" . timeToHm ($unit->getStartTime()) . "'"; } ?>
        				>
        		</div>
        		<div class="col-sm">
        			<label>Ende:</label>
        			<input class="form-control bg-white" id="unit<?= $number ?>end" name="unit<?= $number ?>end" 
        				disabled type="time" required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])"
        				<?php if(isset($unit)){ echo "value='" . timeToHm ($unit->getEndTime()) . "'"; } ?>
        				>
        		</div>
        	</div>
        	<input id="unit<?= $number ?>unit" name="unit<?= $number ?>unit" type="hidden" 
        	<?php if(isset($unit)){ echo "value='" . $unit->getUnitName() . "'"; } ?>
        		>
        	<input id="unit<?= $number ?>km" name="unit<?= $number ?>km" type="hidden"
        	<?php if(isset($unit)){ echo "value='" . $unit->getKm() . "'"; } ?>
        		>
        	<input id="unit<?= $number ?>datefield" name="unit<?= $number ?>datefield" type="hidden" 
        	<?php if(isset($unit)){ echo "value='" . $unit->getDate() . "'"; } ?>
        		>
        	<input id="unit<?= $number ?>startfield" name="unit<?= $number ?>startfield" type="hidden" 
        	<?php if(isset($unit)){ echo "value='" . timeToHm ($unit->getStartTime()) . "'"; } ?>
        		>
        	<input id="unit<?= $number ?>endfield" name="unit<?= $number ?>endfield" type="hidden" 
        	<?php if(isset($unit)){ echo "value='" . timeToHm ($unit->getEndTime()) . "'"; } ?>
        		>
        		
        	<label>Personal:</label>
        	<div class="personalContainer">
        		<?php 
        		createUnitStaff($number,0);
        		if(isset ($unit)){
        		    $j = 0;
            		foreach($unit->getStaff() as $staff){
            		    $j++;
            		    createUnitStaff($number, $j, $staff);
            		}
        		}
            	?>

            </div>
        	<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#addUnitModal" onclick="initializeModalEdit(<?= $number ?>)">Bearbeiten</button>
    		<button type="button" class="btn btn-outline-primary btn-sm" onclick="removeUnit(<?= $number ?>)">Entfernen</button>
    	</div>
    </div>
</div>

<?php 
}

function createUnitStaff($unitNumber, $staffNumber, ReportStaff $staff = null) {
    global $staffpositions, $engines;
?>
<div class="row form-group unitpersonaltemplate" <?php if(!isset($staff)) { echo 'style="display:none;"'; } ?>>
	<div class="col-sm">
		<select class="form-control bg-white" id="unit<?= $unitNumber ?>function<?= $staffNumber ?>" 
			disabled name="unit<?= $unitNumber ?>function<?= $staffNumber ?>" required="required" id="positionfunction">
			<option value="" selected>Funktion auswählen</option>
			<?php foreach ( $staffpositions as $option ) : ?>
			<option value="<?=  $option->getUuid(); ?>"
				<?php if(isset($staff) && $staff->getPosition()->getUuid() == $option->getUuid()){ echo "selected"; } ?>>
				<?= $option->getPosition(); ?>
			</option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="col-sm">
		<input class="form-control bg-white" id="unit<?= $unitNumber ?>name<?= $staffNumber ?>" 
			disabled name="unit<?= $unitNumber ?>name<?= $staffNumber ?>" type="text"" 
			<?php if(isset($staff)){ echo "value='" . $staff->getName() . "'"; } ?>
        	>
	</div>
	<div class="col-sm">
		<select class="form-control bg-white" id="unit<?= $unitNumber ?>engine<?= $staffNumber ?>" 
			disabled name="unit<?= $unitNumber ?>engine<?= $staffNumber ?>" required="required" id="positionengine">
			<option value="" selected>Löschzug auswählen</option>
			<?php foreach ( $engines as $option ) : ?>
			<option value="<?=  $option->getUuid(); ?>"
				<?php if(isset($staff) && $staff->getEngine()->getUuid() == $option->getUuid()){ echo "selected"; } ?>>
				<?= $option->getName(); ?>
			</option>
			<?php endforeach; ?>
		</select>
	</div>
	
	<input id="unit<?= $unitNumber ?>function<?= $staffNumber ?>field" name="unit<?= $unitNumber ?>function<?= $staffNumber ?>field" type="hidden" 
	<?php if(isset($staff)){ echo "value='" . $staff->getPosition()->getUuid() . "'"; } ?>
        >
    <input id="unit<?= $unitNumber ?>name<?= $staffNumber ?>field" name="unit<?= $unitNumber ?>name<?= $staffNumber ?>field" type="hidden" 
    <?php if(isset($staff)){ echo "value='" . $staff->getName() . "'"; } ?>
        >
    <input id="unit<?= $unitNumber ?>engine<?= $staffNumber ?>field" name="unit<?= $unitNumber ?>engine<?= $staffNumber ?>field" type="hidden" 
    <?php if(isset($staff)){ echo "value='" . $staff->getEngine()->getUuid() . "'"; } ?>
        >
</div>
<?php
}
?>
