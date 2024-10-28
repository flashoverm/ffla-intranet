<form onsubmit="showLoader()" action="" method="post">

	<div class="form-group">
		<label>Löschzug:</label>  
		<select class="form-control" name="engine" required="required" 
		<?php if(isset($engine)){
			echo "disabled";
		}
		?>		
		>
			<option value="" disabled selected>Löschzug auswählen</option>
			<?php foreach ( $engines as $option ) :
				if(isset($_POST['engine']) && $option->getUuid() == $_POST['engine']){?>
					<option value="<?php echo $option->getUuid(); ?>" selected><?php echo $option->getName(); ?></option>
				<?php } else if ( isset($engine) && $engine->getUuid() == $option->getUuid()) { ?>
					<option value="<?php echo $option->getUuid(); ?>" selected><?php echo $option->getName(); ?></option>
				<?php } else { ?>
					<option value="<?php echo $option->getUuid(); ?>"><?php echo $option->getName(); ?></option>
				<?php }
			endforeach; ?>
		</select>
	</div>

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
						<td><?= $row->getPrivilege() ?> - <?= $row->getDescription() ?></td>
						<td class="text-center">
							<div class="custom-control custom-checkbox mb-1">
							  <input type="checkbox" class="custom-control-input" id="priv_<?= $row->getUuid() ?>" name="priv_<?= $row->getUuid() ?>"
							  <?php
							  if( 	(isset($engine) && $user->hasPrivilegeForEngineByName($engine, $row->getPrivilege())) 
							  		|| isset($_POST['priv_' . $row->getUuid() ]) 
							  		|| ( empty($_POST) &&  $row->getIsDefault() )
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
	<input type="submit" class="btn btn-primary" value='Speichern' id='save'>
	<a class="btn btn-outline-primary" href="<?= $config["urls"]["intranet_home"] ?>/users/admin/<?= $user->getUuid() ?>">Zurück</a>
</form>