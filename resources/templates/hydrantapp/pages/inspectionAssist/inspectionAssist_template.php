<div id="error" style="display:none" class="alert alert-danger" role="alert">In den Hydrant-Tabs sind eventuell Pflichtfelder, die nicht ausgefüllt wurden</div>

<ul class="nav nav-tabs" id="tabnav" role="tablist">

	<li class="nav-item" id="navtemplate" style="display:none;">
		<a class="nav-link" id="tabnav" data-toggle="tab" href="#hydrant" role="tab">Hydrant</a>
	</li>	
	<li class="nav-item">
		<a class="nav-link" data-toggle="tab" href="#end" role="tab">Abschließen</a>
	</li>
</ul>

<form id="assistform" onsubmit="showLoader()" action="<?= $config["urls"]["hydrantapp_home"] ?>/inspection/edit" method="post">

	<div class="tab-content">
		<?php 
		require_once (TEMPLATES_PATH . "/hydrantapp/pages/inspectionAssist/ic_hydrantView.php");
		?>
		<div class="tab-pane" id="end" role="tabpanel">
        	<div class="row">
        		<div class="col">
        			<div class="form-group">
                		<label>Datum:</label> <input type="date" required="required" 
                		placeholder="TT.MM.JJJJ" title="TT.MM.JJJJ" class="form-control" 
                		name="date" id="date"
                		required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
                	</div>
        		</div>
        		<div class="col">		
                	<div class="form-group">
                		<label>Fahrzeug (optional):</label>
                		<input type="text" class="form-control" name="vehicle" id="vehicle" placeholder="Fahrzeugbezeichnung eingeben">
                	</div>
        		</div>
        	</div>
        	<div class="form-group">
        		<label>Name:</label>
        		<input type="text" class="form-control" required="required" name="name" id="name" placeholder="Name(n) eingeben">
        	</div>
       		<div class="form-group">
        		<label>Hinweise:</label>
        		<textarea class="form-control" name="notes" id="notes"
        			placeholder="Hinweise"></textarea>
        	</div>
        	<input type="hidden" id="maxidx" name="maxidx" value="0">
        	<input type="hidden" id="assistant" name="assistant" value="1">
        	<button onclick="submitForm()" class="btn btn-primary">Assistent abschließen</button>
			<input type="submit" value="Asssistent abschließen" style="display:none" class="btn btn-primary">
		</div>
	</div>

</form>

<script>

var hy_count = 0;
var hy_max_idx = 0;

addHydrant();
toTab('hydrant' + 1);

$('#tabnav a').on('click', function (e) {
	  e.preventDefault()
	  $(this).tab('show')
	});

function submitForm(){
	var form = document.getElementById("assistform");
	var valid = form.checkValidity();
	if(valid){
		form.find(':submit').click();
	} else {
		var errorMsg = document.getElementById('error').style.display="block";
	}
}

function toTab(tab){
	$('#tabnav a[href="#' + tab + '"]').tab('show');
}

function nextTab(){
	var next = getHyTabId();
	next ++;
	for(var i=next; i<=hy_max_idx; i++){
		var elem = document.getElementById('hydrant' + i);
		if(document.getElementById('hydrant' + i) != null){
			toTab('hydrant' + i);
			return;
		}
	}
	toTab('end');
}

function getActiveTabPane(){
	var cur = document.getElementsByClassName('tab-pane fade show active')[0];
	return cur;
}

function getHyTabId(){
	var curID = getActiveTabPane().id;
	if(curID.indexOf('hydrant') !== -1){
		return curID.replace('hydrant', '');
	} else if(curID == 'data'){
		return 0;
	}
	return -1;
}

function toogleFaultView() {
	var cur = getHyTabId();
	var faults = document.getElementById("h" + cur + "faults");
	if (faults.style.display === "none") {
		faults.style.display = "block";
	} else {
		faults.style.display = "none";
	}
}

function switchHydrantType() {
	var cur = getHyTabId();
	var over = document.getElementById("h" + cur + "typeo");
	var noOver = [2,3,4,5,6,7,8,9,10,11,13,14,15,17,20];
	if(over.checked == false){		
		for(i = 0;i < noOver.length; i++) {
			var fieldid = "h" + cur + "divc" + noOver[i];
			showHide(fieldid, false);
		}
	} else {
		for(i = 0;i < noOver.length; i++) {
			var fieldid = "h" + cur + "divc" + noOver[i];
			showHide(fieldid, true);
		}
	}
}

function showHide(fieldid, show){
	var field = document.getElementById(fieldid);
	if (show == false) {
		field.style.display = "block";
	} else {
		field.style.display = "none";
	}
}


function addHydrant() {
	hy_count ++;
	hy_max_idx ++;

	var maxIdx = document.getElementById("maxidx");
	maxIdx.value = hy_max_idx;
	
	var template = document.getElementById("hydranttemplate");
	
	var newHydrant =  template.cloneNode(true);
	newHydrant.id = "hydrant" + hy_max_idx;
	newHydrant.style.display = null;

	var fields = newHydrant.getElementsByTagName("input");
	var tag = "";
	for(i = 0;i < fields.length; i++) {
		if(fields[i].id.startsWith("tc")){
			tag = "h" + hy_max_idx + "c" + fields[i].id.substring(2);
			fields[i].id = tag;
			fields[i].name = tag;
		}
		if(fields[i].id == "hy"){
			fields[i].id = "h" + hy_max_idx + "hy";
			fields[i].name = "h" + hy_max_idx + "hy";
			fields[i].required = true;
		}
		if(fields[i].id == "fid"){
			fields[i].id = "h" + hy_max_idx + "fid";
			fields[i].name = "h" + hy_max_idx + "fid";
		}
		if(fields[i].id == "typeo"){
			fields[i].id = "h" + hy_max_idx + "typeo";
			fields[i].name = "h" + hy_max_idx + "type";
		}
		if(fields[i].id == "typeu"){
			fields[i].id = "h" + hy_max_idx + "typeu";
			fields[i].name = "h" + hy_max_idx + "type";
		}
	}

	fields = newHydrant.getElementsByTagName("label");
	for(i = 0;i < fields.length; i++) {
		if(fields[i].htmlFor.startsWith("tc")){
			tag = "h" + hy_max_idx + "c" + fields[i].htmlFor.substring(2);
			fields[i].htmlFor = tag;
		}
		if(fields[i].htmlFor == "typeo"){
			fields[i].htmlFor = "h" + hy_max_idx + "typeo";
		}
		if(fields[i].htmlFor == "typeu"){
			fields[i].htmlFor = "h" + hy_max_idx + "typeu";
		}
	}

	fields = newHydrant.getElementsByTagName("div");
	for(i = 0;i < fields.length; i++) {
		if(fields[i].id.startsWith("divc")){
			fields[i].id = "h" + hy_max_idx + "divc" + fields[i].id.substring(4);
		}
		if(fields[i].id == "faults"){
			fields[i].id = "h" + hy_max_idx + "faults";
		}
		if(fields[i].id == "fidnotok"){
			fields[i].id = "h" + hy_max_idx + "fidnotok";
		}
	}

	fields = newHydrant.getElementsByTagName("select");
	for(i = 0;i < fields.length; i++) {
		if(fields[i].id == "type"){
			fields[i].id = "h" + hy_max_idx + "type";
			fields[i].name = "h" + hy_max_idx + "type";
			break;
		}
	}
	
	fields = newHydrant.getElementsByTagName("button");
	for(i = 0;i < fields.length; i++) {
		if(fields[i].id == "rem"){
			fields[i].id = "h" + hy_max_idx + "rem";
			fields[i].setAttribute('onclick','removeHydrant(' + hy_max_idx + ')')
			break;
		}
	}

	template.parentNode.insertBefore(newHydrant, template);

	var navtemplate = document.getElementById('navtemplate');

	var newNav = navtemplate.cloneNode(true);
	newNav.id = "navelem" + hy_max_idx;;
	newNav.style.display = null;

	var newNavChild = newNav.children[0];
	newNavChild.id = "tabnav" + hy_max_idx;
	newNavChild.href = "#hydrant" + hy_max_idx;
	newNavChild.textContent = "Hydrant " + hy_max_idx;

	navtemplate.parentNode.insertBefore(newNav, navtemplate);
}

function removeHydrant(id) {
	var element = document.getElementById('hydrant' + id);
	if(element != null){
		nextTab();
		element.parentNode.removeChild(element);

		var nav = document.getElementById('navelem' + id);
		if(nav != null){
			nav.parentNode.removeChild(nav);

			hy_count --;
		}
	}	
}

var xhr = getXmlHttpRequestObject();

function getXmlHttpRequestObject() {
    if(window.XMLHttpRequest) {
        return new XMLHttpRequest();
    } 
    else if(window.ActiveXObject) {
        return new ActiveXObject("Microsoft.XMLHTTP");
    } 
    else {
        alert('Ajax funktioniert bei Ihnen nicht!');
    }
}

function getHydrantTab(no){
	return document.getElementById('hydrant' + no);
}

function getHydrantField(no, fieldname){
	return getHydrantTab(no).querySelector('#' + fieldname);
}

function getHyToFid() {
	if(xhr.readyState == 4 || xhr.readyState == 0) {
        var id = getHyTabId();
	    var fidField = getHydrantField(id, "h" + id + "fid");
	    
	    xhr.open('GET', '<?= $config["urls"]["intranet_home"] ?>/ajax/hy/'+ fidField.value + "/field/" + id, true);
	    xhr.setRequestHeader("Content-Type","text/plain");
	    xhr.onreadystatechange = setHy;
	    xhr.send(null);
    }
}

function setHy(){
    if(xhr.readyState == 4) {

    	if(xhr.status == 200){

        	var response = eval( '(' + xhr.responseText + ')' );
        	var errField = getHydrantField(response.fieldid, "h" + response.fieldid + "fidnotok");
		    var hyfield = getHydrantField(response.fieldid, "h" + response.fieldid + "hy");

		    if(response.status == 'ok'){
			    hyfield.value = response.hy;

	        	errField.style.display = "none";
		    } else if(response.status == 'forbidden'){
	        	errField.style.display = "block";
	        	hyfield.value = "";
		    } else if(response.status == 'notfound'){
	        	hyfield.value = "";
		    }
    	}
    }
}


</script>