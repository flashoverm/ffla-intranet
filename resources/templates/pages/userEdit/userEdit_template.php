<form onsubmit="showLoader()" action="" method="post">
<?php
	include "userForm.php";
		
	if($currentUser && $currentUser->hasPrivilegeByName(Privilege::PORTALADMIN)){
	?>
		<a class="btn btn-outline-primary" href="<?= $config["urls"]["intranet_home"] ?>/users/">Zurück</a>
	<?php 
	} 
	?>
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