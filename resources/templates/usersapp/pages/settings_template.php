<form action="" method="post">
    <div class="table-responsive">
    	<table class="table table-striped table-bordered">
    		<tbody>
    		<?php
    		$currentCategory = "";
			foreach ( $settings as $row ) {
			    if($currentCategory != $row->getCategory()){
			        $currentCategory = $row->getCategory();
			        echo "<tr><th colspan='2'>" . $currentCategory . "</th></tr>";
			    }
    		?>
    			<tr>
    				<td><?= $row->getDescription() ?></td>
    				<td class="text-center">
    					<div class="custom-control custom-checkbox mb-1">
    					  <input type="checkbox" class="custom-control-input" id="setting_<?= $row->getKey() ?>" name="setting_<?= $row->getKey() ?>"
    					  <?php
    					  if( 	($user->isSettingSet($row->getKey())) 
    					           || isset($_POST['setting_' . $row->getKey() ]) 
    					  ){
    					  	echo "checked";
    					  }
    					  ?>
    					  >
    					  <label class="custom-control-label custom-control-label-table" for="setting_<?= $row->getKey() ?>">&nbsp;</label>
    					</div>
    				</td>					
    			</tr>
    		<?php
    			}
    		?>
    		</tbody>
    	</table>
    </div>
    <div>
    	<input type="hidden" name="updateSettings" value="true">
    	<input type="submit" class="btn btn-primary" value='Speichern'>
    </div>
</form>