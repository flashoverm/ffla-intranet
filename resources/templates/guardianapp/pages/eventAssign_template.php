<form onsubmit="showLoader()" action="" method="post">
	
	<div class="form-group">
		<label class="mt-1">Personalauswahl (nur Personal aus  <?= $currentUser->getEngine()->getName() ?>):</label>
		<select class="form-control" name="user" id="user" required="required" onchange="getUser()">
			<option value="new">Neuer Eintrag:</option>
			<?php foreach ( $user as $option ) : ?>
			<option value="<?=  $option->getUuid(); ?>"><?= $option->getFullNameLastNameFirstWithMail(); ?></option>
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
					required="required" name="firstname" id="firstname" readOnly
					placeholder="Person auswählen">
			</div>
			<div class="form-group">
				<label>Nachname:</label> <input type="text" class="form-control"
					required="required" name="lastname" id="lastname" readOnly
					placeholder="Person auswählen">
			</div>
			<div class="form-group">
				<label>E-Mail:</label> <input type="email" class="form-control"
					required="required" name="email" id="email" readOnly
					placeholder="Person auswählen">
			</div>
			<div class="form-group">
				<label>Löschzug:</label> <input type="text" class="form-control"
					required="required" name="engine" id="engine" readOnly
					placeholder="Person auswählen">
			</div>
	      </div>
	    </div>
	  </div>
	</div>
	
	<?php
	if (isset ( $event )) {
		echo "<a href='" . $config["urls"]["guardianapp_home"] . "/events/view/" . $event->getUuid() . "' class=\"btn btn-outline-primary\">Zurück</a>";
	}
	?>
	<input type="submit" value="Einteilen" class="btn btn-primary">

</form>

<script>
var xhr = getXmlHttpRequestObject();

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
	engine.value = null;
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
        	engine.value = response.engine.name;
        	 
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
