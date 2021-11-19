	<tr>
		<td class="text-center"><span class='d-none'><?= strtotime($data->getDate()) ?></span><?= date($config ["formats"] ["date"], strtotime($data->getDate())); ?></td>
		<td class="text-center"><?= date($config ["formats"] ["time"], strtotime($data->getStartTime())); ?></td>
		<td class="text-center">
			<?php
			if ($data->getEndTime() != 0) {
				echo date($config ["formats"] ["time"], strtotime($data->getEndTime()));
			} else {
				echo " &ndash; ";
			}
			?>
		</td>
		<td class="text-center"><?= $data->getType()->getType() ?></td>
		<td class="text-center"><?= $data->getTitle(); ?></td>
		<td class="text-center"><?= $data->getEngine()->getName(); ?></td>
		<?php 
		if(isset($options['showOccupation']) && $options['showOccupation']){
		?>
			<td class="text-center">
				<?php 
				if($data->isEventFull()){
					echo '<font color="green">' . $data->getOccupancy() . '</font>';
				} else {
					echo '<font color="red">' . $data->getOccupancy() . '</font>';
				}
			    ?>
			</td>
		<?php
		}
		if(!empty($options['showPublic'])){
		?>
		<td class="text-center">
			<?php
			if($data->getPublished()){
			    echo " &#10003; ";
			} else {
			    echo " - ";
			}
			?>
		</td>
		<?php
		}
		?>
		<td class="text-center">
			<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["guardianapp_home"] . "/events/".$data->getUuid() ?>">Anzeigen</a>
		</td>
		<?php
		if( !empty($options['showDelete']) && 
			$guardianUserController->isUserAllowedToEditEvent($currentUser, $data->getUuid())){
		?>
			<td class="text-center">
				<button type="button" class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#confirmDelete<?= $data->getUuid(); ?>">Löschen</button>
				<?php 
				createDialog('confirmDelete' . $data->getUuid(), "Wache wirklich löschen?", null, "delete", $data->getUuid());
				?>	
			</td>
		<?php 
		}
		?>
	</tr>