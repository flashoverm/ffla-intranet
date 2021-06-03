<div class="form-group">
	<label>Vorname:</label> <input type="text" class="form-control"
		required="required" name="firstname" id="firstname"
		placeholder="Vorname eingeben"
		<?php
		if(isset($user)){
			echo "value='" . $user->getFirstname() . "'";
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
			echo "value='" . $user->getLastname() . "'";
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
			echo " value='" . $user->getEmail() . "'";
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
		<select class="form-control" name="engine" required="required" 
		<?php if(isset($userSelfEdit)){
			echo "disabled";
		}
		?>		
		>
		<option value="" disabled selected>Löschzug auswählen</option>
		<?php foreach ( $engines as $option ) :
			if(isset($_POST['engine']) && $option->getUuid() == $_POST['engine']){?>
				<option value="<?php echo $option->getUuid(); ?>" selected><?php echo $option->getName(); ?></option>
			<?php } else if ( isset($user) && $user->getEngine()->getUuid() == $option->getUuid()) { ?>
				<option value="<?php echo $option->getUuid(); ?>" selected><?php echo $option->getName(); ?></option>
			<?php } else { ?>
				<option value="<?php echo $option->getUuid(); ?>"><?php echo $option->getName(); ?></option>
			<?php }
		endforeach; ?>
	</select>
	<?php if(isset($userSelfEdit)){
		echo '<small class="form-text text-muted">Der Löschzug kann nur durch einen Administrator geändert werden</small>';
	}
	?>	
</div>

<?php if( isset($user) ){ ?>
<div class="form-group">
	<label>Zusätzliche Löschzüge/Einheiten:</label>  
	<?php
	if(count($user->getAdditionalEngines()) > 0 ) {
	?>
	<div class="table-responsive">
		<table class="table table-bordered">
			<?php foreach( $user->getAdditionalEngines() as $additinalEngine ) { ?>
			<tr>
				<td><?= $additinalEngine->getName() ?></td>
				<?php if( ! isset($userSelfEdit) ){ ?>
				<td>			
					<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#removeAdditionalEngine<?= $additinalEngine->getUuid() ?>">Entfernen</button>
    			</td>
    			<td>
    				<a type="button" class="btn btn-outline-primary btn-sm" href='<?= $config["urls"]["intranet_home"] ?>/users/<?= $user->getUuid() ?>/privilege/<?= $additinalEngine->getUuid() ?>'>Rechte bearbeiten</a>
    			</td>
    			<?php } ?>
			</tr>
			<?php } ?>
		</table>
	</div>
	<?php } ?>
	<small class="form-text text-muted">Keine zusätzlichen Löschzüge/Einheiten vorhanden</small>
	<?php if( ! isset($userSelfEdit) ){ ?>
	<div>
		<a type="button" class="btn btn-outline-primary btn-sm" href='<?= $config["urls"]["intranet_home"] ?>/users/<?= $user->getUuid() ?>/privilege'>Zusätzliche(n) Löschzug/Einheit hinzufügen</a>
	</div>
	<?php } ?>
</div>
<?php } ?>

<br>
<div class="row">
	<div class="col">
		<div class="form-group">
			<label>Anschrift Arbeitgeber</label>
			<textarea rows="3" class="form-control" name="employerAddress" id="employerAddress" placeholder="Anschrift Arbeitgeber"
			><?php if(isset($user) && $user->getEmployerAddress() != null) { echo $user->getEmployerAddress(); } ?></textarea>
		</div>  
	</div>
	<div class="col">
		<label>&nbsp;</label>
		<textarea rows=3" disabled class="form-control">Arbeitgeber&#10;Straße Hausnr.&#10;Postleitzahl Ort</textarea>
	</div>
</div>
<div class="form-group">
	<label>E-Mail Arbeitgeber (Arbeitgeberbestätigungen werden direkt an diese Adresse übermittelt)</label> 
	<input type="email" class="form-control" name="employerMail" id="employerMail"
		placeholder="E-Mail des Arbeitgebers eingeben"
		<?php
		if(isset($user) && $user->getEmployerMail() != null){
			echo " value='" . $user->getEmployerMail() . "'";
		}
		?>
		>
</div>
<br>