<div class="modal fade" id="addUnitModal" role="dialog">
	<div class="modal-dialog modal-lg">

		<div class="modal-content">
			<form id="addUnitForm">
				<div class="modal-header">
					<h4 class="modal-title">Einheit hinzufügen</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
	
				<div class="modal-body">
					<div class="row" id="vehiclerow">
						<div class="col">
							<div class="form-group">
								<label>Fahrzeug:</label> <input type="text"
									class="form-control" name="unit" id="unit" required="required"
									placeholder="Fahrzeug eingeben">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label>Kilometer:</label> <input type="number" required="required"
									class="form-control" name="km" id="km"
									placeholder="Gefahrene Kilometer">
							</div>
						</div>
					</div>
					</p>
					<div class="row">
						<div class="col">
							<div class="form-group">
								<label>Datum:</label> <input type="date" class="form-control" required="required" 
								placeholder="TT.MM.JJJJ" title="TT.MM.JJJJ"
								name="unitdate" id="unitdate" 
								required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label>Wachbeginn:</label> <input type="time" class="form-control" required="required"
								placeholder="--:--" title="--:--"
								name="unitstart" id="unitstart" required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
							</div>
						</div>
						<div class="col">
							<div class="form-group">
								<label>Ende:</label> <input type="time" class="form-control" required="required" 
								placeholder="--:--" title="--:--"
								name="unitend" id="unitend" required pattern="(0[0-9]|1[0-9]|2[0-3])(:[0-5][0-9])">
							</div>
						</div>
					</div>
					</p>
					<div class="form-group mb-0" id="staffContainer">
						<label>Personal:</label>
						<div class="btn-group btn-group-sm" role="group"
							style="float: right">
							<button type="button" class="btn btn-primary"
								onClick="removeLastReportStaffPosition()">&minus;</button>
							<span class="border-right"></span>
							<button type="button" class="btn btn-primary "
								onClick="addReportStaffPosition()">+</button>
						</div>
						</p>
						<div class="row" id="position1">
							<div class="col">
								<div class="form-group">
									<select class="form-control" name="positionfunction" required="required" id="positionfunction">
										<option value="" disabled selected>Funktion auswählen</option>
										<?php foreach ( $staffpositions as $option ) : ?>
										<option value="<?=  $option->uuid; ?>"><?= $option->position; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<input type="text" placeholder="Name" class="form-control" required="required"
										name="positionname" id="positionname">
								</div>
							</div>
							<div class="col" id="engineselect">
								<div class="form-group">
									<select class="form-control" name="positionengine" required="required"
										id="positionengine">
										<option value="" disabled selected>Löschzug auswählen</option>
										<?php foreach ( $engines as $option ) : ?>
										<option value="<?=  $option->uuid; ?>"><?= $option->name; ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<p class="text-right my-0 mx-0"><sub><em>* Mehr Personal kann mit + eingefügt werden</em></sub></p>
				</div>
				<div class="modal-footer">
					<input type="hidden" id="unitNo" value="">
					<input type="submit" class="btn btn-primary" id="addUnit" value="Hinzufügen">
					<button type="button" class="btn btn-default" onClick="clearUnitForm()" data-dismiss="modal">Abbrechen</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script type='text/javascript'>

    var form = document.getElementById('addUnitForm');
    if (form.attachEvent) {
        form.attachEvent("submit", processForm);
    } else {
        form.addEventListener("submit", processForm);
    }
    
    var reportPositionCount = 1;
    var reportEngine = "";
    var stationString = "Stationäre Wache"

	function processForm(e) {
	    if (e.preventDefault) e.preventDefault();
		
		addUnit();
  
		clearUnitForm();
	    
	    $('#addUnitModal').modal('hide');
	    return false;
	}

</script>