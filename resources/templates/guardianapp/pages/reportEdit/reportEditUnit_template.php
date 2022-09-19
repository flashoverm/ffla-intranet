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
									<select class="form-control" name="positionfunction1" required="required" id="positionfunction1">
										<option value="" disabled selected>Funktion auswählen</option>
										<?php foreach ( $staffpositions as $option ) : ?>
										<option value="<?=  $option->getUuid(); ?>"><?= $option->getPosition(); ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>
							<div class="col">
								<div class="form-group">
									<input type="text" placeholder="Name" class="form-control positionuser" required="required"
										name="positionuser1" id="positionuser1">
									<input type="hidden" class="form-control positionuseruuid"
										name="positionuseruuid1" id="positionuseruuid1">
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

<style>
.ui-autocomplete {
z-index: 10000;
}
</style>

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

$( function() {
    $( ".positionuser" ).autocomplete({
      source: function( request, response ) {
        $.ajax({
          url: "<?= $config["urls"]["intranet_home"] ?>/ajax/userTypeahead",
          dataType: "json",
          data: {
            name: request.term
          },
          success: function( data ) {
            response($.map(data, function (value, key) {
                return {
                    label: value.name,
                    value: value.uuid
                }
            }));
          }
        });
      },
      minLength: 3,
      select: function(event, ui) {
      	console.log(event);
      	console.log(ui.item.label);
      	console.log(ui.item.value);
        // place the person.given_name value into the textfield called 'select_origin'...
        //$('#search').val(ui.item.first_name);
        // and place the person.id into the hidden textfield called 'link_origin_id'. 
        //$('#link_origin_id').val(ui.item.id);
       }
    });
});

</script>