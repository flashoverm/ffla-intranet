<form action="" method="post" onsubmit="showLoader()">

	<div class="form-group">
		<label class="mt-1">Benutzer:</label>
		<select class="form-control" name="user" id="user" required="required" onchange="getUsersPrivileges()">
			<option value="" disabled selected>Benutzer auswählen</option>
			<?php foreach ( $user as $option ) : ?>
			<option value="<?=  $option->uuid; ?>"><?= $option->lastname . ", " . $option->firstname . " (" . $option->email . ")" ?></option>
			<?php endforeach; ?>
		</select> 
	</div>
	
	<div class="form-group">
		<div class="table-responsive">
			<table class="table table-striped" data-toggle="table">
				<tbody>
				<?php
					foreach ( $privileges as $row ) {
				?>
					<tr>
						<td><?= $row->privilege ?></td>
						<td class="text-center">
							<div class="custom-control custom-checkbox mb-1">
							  <input type="checkbox" class="custom-control-input" id="priv_<?= $row->privilege ?>" name="priv_<?= $row->privilege ?>" disabled>
							  <label class="custom-control-label custom-control-label-table" for="priv_<?= $row->privilege ?>">&nbsp;</label>
							</div>
						</td>
					</tr>
				<?php
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
	
	<input type="submit" class="btn btn-primary" value='Speichern' id='save' disabled>
</form>

<script type='text/javascript'>

function getUsersPrivileges()
{
    var save = document.getElementById("save");
    save.style.disabled = true;
    
    var inputs = document.getElementsByTagName("input");
    for(var i = 0; i < inputs.length; i++){
    	inputs[i].disabled = true;
    }
    
    if(xhr.readyState == 4 || xhr.readyState == 0) 
    {
	    var user = document.getElementById("user");
	    xhr.open('GET', '<?= $config["urls"]["intranet_home"] ?>/ajax/privileges/' + user.value, true);
	    xhr.setRequestHeader("Content-Type","text/plain");
	    xhr.onreadystatechange = setUserPrivileges;
	    xhr.send(null);
    }
}

function setUserPrivileges(){
    if(xhr.readyState == 4) 
    {
    
    	if(xhr.status == 200){
	    	
        	var response = eval('(' + xhr.responseText + ')' );

    	    var save = document.getElementById("save");
    	    save.style.disabled = false;
        	
            var inputs =  document.getElementsByTagName("input");
            for(var i = 0; i < inputs.length; i++){
            	if(inputs[i].id.startsWith("priv_")){
                	if(response.indexOf(inputs[i].id.substring(5, inputs[i].id.length))  == -1 ){
                		inputs[i].checked = false;
                	} else {
                		inputs[i].checked = true;
                	}
            	}
            	inputs[i].disabled = false;
            }
    	} else {
	    	var button = document.getElementById("loadTemplate");
	    	button.innerHTML = "Vorschlag nicht gefunden";
    	}
    }
}

var xhr = getXmlHttpRequestObject();

function getXmlHttpRequestObject()
{
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

