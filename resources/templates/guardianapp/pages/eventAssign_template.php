<form onsubmit="showLoader()" action="" method="post">
	
	<div class="form-group">
		<label class="mt-1">Personalauswahl (nur Personal aus  <?= $currentUser->getEngine()->getName() ?>):</label>
		<select class="form-control" name="user" id="user" required="required" onchange="getUser()">
			<option value="new">Neuer Eintrag:</option>
			<?php foreach ( $user as $option ) : ?>
			<option value="<?=  $option->getUuid(); ?>"><?= $option->getFullNameWithEmail(); ?></option>
			<?php endforeach; ?>
		</select> 
	</div>
	
	<div class="form-group">
	  <div class="card">
	    <div class="collapse show">
	      <div class="card-body">
	      
	      	<input type="hidden" class="form-control" name="user_uuid" id="user_uuid">
					
			<div class="form-group">
				<label>Vorname:</label> <input type="text" class="form-control"
					required="required" name="firstname" id="firstname"
					placeholder="Vorname eingeben">
			</div>
			<div class="form-group">
				<label>Nachname:</label> <input type="text" class="form-control"
					required="required" name="lastname" id="lastname"
					placeholder="Nachname eingeben">
			</div>
			<div class="form-group">
				<label>E-Mail:</label> <input type="email" class="form-control"
					required="required" name="email" id="email"
					placeholder="E-Mail eingeben">
			</div>
			<div class="form-group">
				<label>Löschzug:</label> 
				<select class="form-control" name="engine" id="engine" required="required" onchange="setEngineHid()">
					<option value="" disabled selected>Löschzug auswählen</option>
					<?php foreach ( $engines as $option ) : ?>
					<option value="<?=  $option->getUuid(); ?>"><?= $option->getName(); ?></option>
					<?php endforeach; ?>
				</select>
				<input type="hidden" class="form-control" name="engine_hid" id="engine_hid">
			</div>
	      </div>
	    </div>
	  </div>
	</div>
	
	<?php
	if (isset ( $event )) {
		echo "<a href='" . $config["urls"]["guardianapp_home"] . "/events/" . $event->getUuid() . "' class=\"btn btn-outline-primary\">Zurück</a>";
	}
	?>
	<input type="submit" value="Einteilen" class="btn btn-primary">

</form>

<script>
var xhr = getXmlHttpRequestObject();

//set engine uuid to hidden field because actual engine select is disabled
function setEngineHid(){
    var engine_hid = document.getElementById("engine_hid");
    var engine = document.getElementById("engine");

	engine_hid.value = engine.value;
}

function clearUser(){
    var user_uuid = document.getElementById("user_uuid");
    var firstname = document.getElementById("firstname");
    var lastname = document.getElementById("lastname");
    var email = document.getElementById("email");
    var engine = document.getElementById("engine");
    	    
    user_uuid.value = null;
	firstname.value = null;
	lastname.value = null;
	email.value = null;
	engine.value = "";

	firstname.readOnly  = false;
	lastname.readOnly  = false;
	email.readOnly  = false;
	engine.disabled  = false;
}

function getUser(){
    var user = document.getElementById("user");
	if(user.value != "new"){
	    if(xhr.readyState == 4 || xhr.readyState == 0) {
		    var user = document.getElementById("user");
		    xhr.open('GET', '<?= $config["urls"]["intranet_home"] ?>/ajax/user/' + user.value, true);
		    xhr.setRequestHeader("Content-Type","text/plain");
		    xhr.onreadystatechange = setUser;
		    xhr.send(null);
	    }
	} else {
		clearUser();
	}
}

function setUser(){
    if(xhr.readyState == 4) 
    {
    	if(xhr.status == 200){
	    	
        	var response = eval('(' + xhr.responseText + ')' );

		    clearUser();

		    var user_uuid = document.getElementById("user_uuid");
		    var firstname = document.getElementById("firstname");
		    var lastname = document.getElementById("lastname");
		    var email = document.getElementById("email");
		    var engine = document.getElementById("engine");
    	    
		    user_uuid.value = response.uuid;
        	firstname.value = response.firstname;
        	lastname.value = response.lastname;
        	email.value = response.email;
        	engine.value = response.engine.uuid;

        	setEngineHid();
            	
        	firstname.readOnly  = true;
        	lastname.readOnly  = true;
        	email.readOnly  = true;
        	engine.disabled  = true;
        	 
    	} else {
	    	var button = document.getElementById("loadTemplate");
	    	button.innerHTML = "Vorschlag nicht gefunden";
    	}
    }
}

function getXmlHttpRequestObject(){
    if(window.XMLHttpRequest) 
    {
        return new XMLHttpRequest();
    } 
    else if(window.ActiveXObject) 
    {
        return new ActiveXObject("Microsoft.XMLHTTP");
    } 
    else 
    {
        alert('Ajax funktioniert bei Ihnen nicht!');
    }
}
</script>
