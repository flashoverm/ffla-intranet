<form onsubmit="showLoader()" action="" method="post">
<?php 
	include "userForm.php";
?>
	<div class="form-group">
		<label>Rechte:</label>  
		<input type="hidden" id="updateRights" name="updateRights" value="true"> 
		<div class="table-responsive">
			<table class="table table-striped table-sm" data-toggle="table" >
				<tbody>
				<?php
				foreach ( $privileges as $row ) {
				?>
					<tr>
						<td><?= $row->getPrivilege() ?></td>
						<td class="text-center">
							<div class="custom-control custom-checkbox mb-1">
							  <input type="checkbox" class="custom-control-input" id="priv_<?= $row->getUuid() ?>" name="priv_<?= $row->getUuid() ?>"
							  <?php
							  if( 	(isset($user) && $currentUser->hasPrivilegeByName($row->getPrivilege())) 
							  		|| isset($_POST['priv_' . $row->getUuid() ]) 
							  		|| (! isset($user) && $_SERVER['REQUEST_METHOD'] === 'GET' && $row->getIsDefault() )
							  ){
							  	echo "checked";
							  }
							  ?>
							  >
							  <label class="custom-control-label custom-control-label-table" for="priv_<?= $row->getUuid() ?>">&nbsp;</label>
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
	<br>
	<?php
	if($currentUser->hasPrivilegeByName(Privilege::PORTALADMIN)){
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