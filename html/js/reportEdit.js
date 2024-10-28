
function showHideTypeOther(){
	var type = document.getElementById("type");
	var selectedType = type.options[type.selectedIndex].text;

    var groupTypeOther = document.getElementById("groupTypeOther");
    var typeOther = document.getElementById("typeOther");
	
	if(selectedType == "Sonstige Wache"){
		typeOther.setAttribute("required", "");
		groupTypeOther.style.display = "block";
	} else {
		typeOther.removeAttribute("required");
		groupTypeOther.style.display = "none";
	}
}


function addUnit(){
	
	var unitNoField = document.getElementById("unitNo");
	if(unitNoField.value != ""){
		var unitNo = unitNoField.value;
	} else {
		reportUnitCount += 1;
		var unitNo = reportUnitCount;
	}

	var form = document.getElementById("addUnitForm");

	var unit = document.getElementById("unit").value;
	var km = document.getElementById("km").value;
	var unitdate = form.querySelector("#unitdate").value;
	var unitstart = form.querySelector("#unitstart").value;
	var unitend = form.querySelector("#unitend").value;

	var template = document.getElementById("unit0");
	var newUnit = template.cloneNode(true);

	newUnit.id = "unit" + unitNo;
	newUnit.style.display = null;	

	var inputs = newUnit.getElementsByTagName("input");
	nameField(inputs[0], "unit" + unitNo + "date");
	inputs[0].value = unitdate;
	nameField(inputs[1], "unit" + unitNo + "start");
	inputs[1].value = unitstart;
	nameField(inputs[2], "unit" + unitNo + "end");
	inputs[2].value = unitend;
	nameField(inputs[3], "unit" + unitNo + "unit");
	inputs[3].value = unit;
	nameField(inputs[4], "unit" + unitNo + "km");
	inputs[4].value = km;
	
	nameField(inputs[5], "unit" + unitNo + "datefield");
	inputs[5].value = unitdate;
	nameField(inputs[6], "unit" + unitNo + "startfield");
	inputs[6].value = unitstart;
	nameField(inputs[7], "unit" + unitNo + "endfield");
	inputs[7].value = unitend;

	var personalContainer = newUnit.getElementsByClassName("personalContainer");
	
	for (i = 1; i <= reportPositionCount; i++) {
		var personalTemplate = newUnit.getElementsByClassName("unitpersonaltemplate");

		var newPersonal = personalTemplate[0].cloneNode(true);
		newPersonal.style.display = null;	

		var position = form.querySelector("#position" + i);
				
		var selects = newPersonal.getElementsByTagName("select");
		
		nameField(selects[0], "unit" + unitNo + "function" + i)
		selects[0].selectedIndex = position.querySelector("#positionfunction" + i).selectedIndex;
		selects[0].value = position.querySelector("#positionfunction" + i).value;

		nameField(selects[1], "unit" + unitNo + "user" + i)
		selects[1].innerHTML = position.querySelector("#positionuser" + i).innerHTML;
		selects[1].value = position.querySelector("#positionuser" + i).value;
		$(selects[1]).find('[value="'+ selects[1].value + '"]').attr("selected","selected");
				
		var inputs = newPersonal.getElementsByTagName("input");

		nameField(inputs[0], "unit" + unitNo + "engine" + i);
		inputs[0].value = position.querySelector("#positionengine" + i).selectedOptions[0].innerHTML;

		nameField(inputs[1], "unit" + unitNo + "function" + i + "field");
		inputs[1].value = position.querySelector("#positionfunction" + i).value;
		
		nameField(inputs[2], "unit" + unitNo + "user" + i + "field");
		inputs[2].value = position.querySelector("#positionuser" + i).value;
		
		nameField(inputs[3], "unit" + unitNo + "engine" + i + "field");
		inputs[3].value = position.querySelector("#positionengine" + i).value;

		personalContainer[0].appendChild(newPersonal);
	}

	var cardbody = newUnit.getElementsByClassName("unittemplateBody");
	cardbody[0].id = "collapse" + unitNo;

	var buttons = newUnit.getElementsByTagName("button");
	buttons[0].setAttribute("data-target", "#collapse" + unitNo);

	if(km == ""){
		var headerString = unit;
	} else {
		var headerString = unit + " (" + km + " km)";
	}
	buttons[0].appendChild(document.createTextNode(headerString));

	buttons[1].setAttribute("onclick", "initializeModalEdit("+ unitNo +");");
	buttons[2].setAttribute("onclick", "removeUnit("+ unitNo +");");

	if(unitNoField.value != ""){
		var unitElement = document.getElementById("unit" + unitNo);
		insertAfter(newUnit, unitElement);
		unitElement.parentNode.removeChild(unitElement);
	} else {
		var container = document.getElementById("unitlist");
		container.appendChild(newUnit);
	}

	buttons[0].click();
	
	displaySubmitButton();	
}

function openUnit(unitnumber) {
	var unit = document.getElementById("unit" + unitnumber);
	var buttons = unit.getElementsByTagName("button");
	buttons[0].click();
}

function displaySubmitButton(){
	
	if(reportUnitCount == 1){
		var submitButton = document.getElementById("submitReport");
		submitButton.style.display = "";
		var div = document.getElementById("submitPlaceholder");			
	}
}

function removeUnit(number){
	var unit = document.getElementById("unit" + number);
	unit.parentNode.removeChild(unit);
}

function nameField(field, name){
	field.name = name;
	field.id = name;
}


function addReportStaffPosition(){
	reportPositionCount += 1;
	
	var container = document.getElementById("staffContainer");

	var position1 = document.getElementById("position1");
	var newPosition =  position1.cloneNode(true);
	newPosition.id = "position" + reportPositionCount;
	if(newPosition.querySelector("#positionfunction1").value != ""){
		newPosition.querySelector("#positionfunction1").value = "";
	}
	newPosition.querySelector("#positionfunction1").name = "positionfunction" + reportPositionCount;
	newPosition.querySelector("#positionfunction1").id = "positionfunction" + reportPositionCount;
	
	if(newPosition.querySelector("#positionengine1").value != ""){
		newPosition.querySelector("#positionengine1").value = "";
	}
	newPosition.querySelector("#positionengine1").id = "positionengine" + reportPositionCount;
	
	resetUserSelect(newPosition.querySelector("#positionuser1"));
	newPosition.querySelector("#positionuser1").name = "positionuser" + reportPositionCount;
	newPosition.querySelector("#positionuser1").id = "positionuser" + reportPositionCount;
	
	
	container.appendChild(newPosition);
}

function removeLastReportStaffPosition(){
	if(reportPositionCount != 1){
		var lastStaffRow = document.getElementById("position"+reportPositionCount);
		lastStaffRow.parentNode.removeChild(lastStaffRow);
		reportPositionCount -= 1;
	}
}

function clearUnitForm(){
	var form = document.getElementById("addUnitForm");

	while(reportPositionCount > 1){
		removeLastReportStaffPosition();
	}
	

	form.reset();

	var unit = document.getElementById("unit");
	var km = document.getElementById("km");
	unit.disabled = false;
	km.disabled = false;
	
	var unitNo = document.getElementById("unitNo");
	unitNo.value = "";

	var addButton = document.getElementById("addUnit");
	addButton.value = "Hinzufügen";
	
	resetUserSelect(form.querySelector("#positionuser1"));

}

function resetUserSelect(userSelect){
	userSelect.innerHTML = "";
    var optDef = document.createElement('option');
    optDef.innerHTML = "Löschzug auswählen";
    optDef.selected = true;
    optDef.disabled = true;
    userSelect.appendChild(optDef);
}

function insertAfter(newNode, referenceNode) {
    referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
}



function initializeModal(){

	initializeModalVehicle();
	
	var vehicleRow = document.getElementById("vehiclerow");
	var unit = document.getElementById("unit");
	var km = document.getElementById("km");
	

	unit.value = stationString;
	unit.disabled = true;
	km.disabled = true;
	vehicleRow.style.display = 'none';	
}

function initializeModalVehicle(){

	var vehicleRow = document.getElementById("vehiclerow");
	var unit = document.getElementById("unit");
	var km = document.getElementById("km");
	

	unit.value = '';
	unit.disabled = false;
	km.disabled = false;
	vehicleRow.style.display = 'flex';	
	
	var date = document.getElementById("date").value;
	var start = document.getElementById("start").value;
	var end = document.getElementById("end").value;

	var form = document.getElementById("addUnitForm");

	if(date != ""){
		form.querySelector("#unitdate").value = date;
	}
	if(start != ""){
		form.querySelector("#unitstart").value = start;
	}
	if(end != ""){
		form.querySelector("#unitend").value = end;
	}
}

function initializeModalEdit(unitnumber){

	var unit = document.getElementById("unit" + unitnumber + "unit");
	var km = document.getElementById("unit" + unitnumber + "km");
	var unitDate = document.getElementById("unit" + unitnumber + "date");
	var unitStart = document.getElementById("unit" + unitnumber + "start");
	var unitEnd = document.getElementById("unit" + unitnumber + "end");
	
	var modalVehicleRow = document.getElementById("vehiclerow");
	var modalUnit = document.getElementById("unit");
	var modalKm = document.getElementById("km");
	var modalUnitDate = document.getElementById("unitdate");
	var modalUnitStart = document.getElementById("unitstart");
	var modalUnitEnd = document.getElementById("unitend");

	modalUnitDate.value = unitDate.value;
	modalUnitStart.value = unitStart.value;
	modalUnitEnd.value = unitEnd.value;

	if(km.value || unit.value != stationString){
		modalUnit.value = unit.value;
		modalUnit.disabled = false;
		modalKm.disabled = false;
		modalKm.value = km.value;
		modalVehicleRow.style.display = 'flex';	
	} else {
		modalUnit.value = stationString;
		modalUnit.disabled = true;
		modalKm.disabled = true;
		modalKm.value = '';
		modalVehicleRow.style.display = 'none';
	}

	var positionNo = 1;
	addExistingStaffPosition(unitnumber, positionNo);

	while(positionfunction = document.getElementById("unit" + unitnumber + "function" + (positionNo+1)) ) {
		positionNo ++;
		addReportStaffPosition();
		
		addExistingStaffPosition(unitnumber, positionNo);
	}
	
	var unitNo = document.getElementById("unitNo");
	unitNo.value = unitnumber;
}

function addExistingStaffPosition(unitnumber, positionNo) {
	var positionfunction = document.getElementById("unit" + unitnumber + "function" + positionNo);
	var positionuser = document.getElementById("unit" + unitnumber + "user" + positionNo);
	var positionengine = document.getElementById("unit" + unitnumber + "engine" + positionNo + "field");

	var position = form.querySelector("#position" + positionNo);
	//position.querySelector("#positionuser" + positionNo).value = positionuser.value;
	position.querySelector("#positionfunction" + positionNo).selectedIndex = positionfunction.selectedIndex;
	
	var engineSelect = 	position.querySelector("#positionengine" + positionNo);
	engineSelect.value = positionengine.value;
	
	var userSelect = position.querySelector("#positionuser" + positionNo);
	userSelect.innerHTML = positionuser.innerHTML;

	
}
	