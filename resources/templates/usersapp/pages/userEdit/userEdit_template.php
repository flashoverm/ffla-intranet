<form onsubmit="showLoader()" action="" method="post">
<?php
	include "userForm.php";
		
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