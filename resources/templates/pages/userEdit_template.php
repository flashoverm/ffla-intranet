<form onsubmit="showLoader()" action="" method="post">

	<div class="form-group">
		<label>Vorname:</label> <input type="text" class="form-control"
			required="required" name="firstname" id="firstname"
			placeholder="Vorname eingeben"
			<?php
			if(isset($user)){
				echo "value='" . $user->firstname . "'";
			} else if(isset($_POST['firstname'])){
				echo "value='" . $_POST['firstname'] . "'";
			}
			?>
			>
	</div>
	<div class="form-group">
		<label>Nachname:</label> <input type="text" class="form-control"
			required="required" name="lastname" id="lastname"
			placeholder="Nachname eingeben"
			<?php
			if(isset($user)){
				echo "value='" . $user->lastname . "'";
			} else if(isset($_POST['lastname'])){
				echo "value='" . $_POST['lastname'] . "'";
			}
			?>
			>
	</div>
	<div class="form-group">
		<label>E-Mail:</label> <input type="text" class="form-control"
			required="required" name="useremail" id="useremail"
			placeholder="E-Mail eingeben"
			<?php
			if(isset($user)){
				echo " value='" . $user->email . "'";
			} else if(isset($_POST['useremail'])){
				echo " value='" . $_POST['useremail'] . "'";
			} else {
				echo "value=''";
			}
			?>
			>
	</div>
	<?php 
	if( ! isset($user)){
	?>	
		<div class="form-group">
			<label>Passwort:</label> 
			<div class="input-group mb-3">
				<input type="password" class="form-control"
					required="required" name="userpassword" id="userpassword"
					placeholder="Passwort eingeben" autocomplete="new-password"
					<?php
					if(isset($_POST['userpassword'])){
						echo "value='" . $_POST['userpassword'] . "'";
					} else {
						echo "value=''";
					}
					?>
					>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary" type="button" onclick="generateAndShowPassword()" >Passwort generieren</button>
					</div>
			</div>
		</div>
		<div class="form-group">
			<label>Passwort wiederholen:</label> <input type="password"
				class="form-control" required="required" name="userpassword2"
				id="userpassword2" placeholder="Passwort wiederholen"
				<?php
				if(isset($_POST['userpassword2'])){
					echo "value='" . $_POST['userpassword2'] . "'";
				} else {
					echo "value=''";
				}
				?>
				>
		</div>
	<?php 
	}
	?>
	<div class="form-group">
		<label>Löschzug:</label>  
			<select class="form-control" name="engine" required="required">
			<option value="" disabled selected>Löschzug auswählen</option>
			<?php foreach ( $engines as $option ) :
				if(isset($_POST['engine']) && $option->uuid == $_POST['engine']){?>
					<option value="<?php echo $option->uuid; ?>" selected><?php echo $option->name; ?></option>
				<?php } else if ( isset($user) && $user->engine == $option->uuid) { ?>
					<option value="<?php echo $option->uuid; ?>" selected><?php echo $option->name; ?></option>
				<?php } else { ?>
					<option value="<?php echo $option->uuid; ?>"><?php echo $option->name; ?></option>
				<?php }
			endforeach; ?>
		</select>
	</div>
	
	<?php 
	if( current_user_has_privilege(PORTALADMIN)){
	?>
	<div class="form-group">
		<label>Rechte:</label>  
		<input type="hidden" id="updateRights" name="updateRights" value="true"> 
		<div class="table-responsive">
			<table class="table table-striped table-sm" data-toggle="table" >
				<tbody>
				<?php
				$users_privileges = null;
				if(isset($user)){
					$users_privileges = get_users_privileges_array($user->uuid);
				}
				foreach ( $privileges as $row ) {
				?>
					<tr>
						<td><?= $row->privilege ?></td>
						<td class="text-center">
							<div class="custom-control custom-checkbox mb-1">
							  <input type="checkbox" class="custom-control-input" id="priv_<?= $row->privilege ?>" name="priv_<?= $row->privilege ?>"
							  <?php
							  if( (isset($user) && in_array($row->privilege, $users_privileges)) || isset($_POST['priv_' . $row->privilege ])){
							  	echo "checked";
							  }
							  ?>
							  >
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
	<?php
	}
	if(current_user_has_privilege(PORTALADMIN)){
	?>
		<a class="btn btn-outline-primary" href="<?= $config["urls"]["intranet_home"] ?>/users/">Zurück</a>
	<?php } ?>
	<input type="submit" class="btn btn-primary"
	<?php 
	if(isset($user)){
		echo "value='Aktualisieren'";
	} else {
		echo "value='Anlegen'";
	}
	?>
	>
</form>
<script>

function generateAndShowPassword(){
	var password = generatePassword();

	var userpassword = document.getElementById('userpassword');
	var userpassword2 = document.getElementById('userpassword2');

	userpassword.value = password;
	userpassword.type = "text";
	userpassword2.value = password;
	userpassword2.type = "text";
}


</script>