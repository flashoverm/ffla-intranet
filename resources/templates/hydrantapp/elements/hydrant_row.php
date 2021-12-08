<?php
global $config, $currentUser;
?><tr>
	<td class="text-center"><?= $data->getHy(); ?></td>
	<td class="text-center"><?= $data->getFid(); ?></td>
	<td class="text-center"><?= $data->getStreet(); ?></td>
	<td class="text-center"><?= $data->getDistrict(); ?></td>
	
	<?php if(!empty($options['showEngine'])){ ?>
		<td class="text-center"><?= $data->getEngine()->getName(); ?></td>
	<?php } ?>
		
	<?php if(!empty($options['showOperating'])){ ?>
    	<?php if($currentUser->hasPrivilegeByName(Privilege::HYDRANTADMINISTRATOR)){
    	    echo '<td class="text-center">';
    	    if($data->getOperating()){
    	        echo " &#10003; ";
    	    } else {
    	        echo " &ndash; ";
    	    }
    	    echo '</td>';
    	}?>
	<?php } ?>
	
	<td class="text-center"><?= $data->getType(); ?></td>
	
	<?php if(!empty($options['showLastCheck'])){ ?>
    	<?php if( $data->getLastCheck() == NULL ) { ?>
    		<td class="text-center">Nie</td>
    	<?php } else { ?>			
    		<td class="text-center"><span class='d-none'><?= strtotime($data->getLastCheck()) ?></span><?= date($config ["formats"] ["date"], strtotime($data->getLastCheck())); ?></td>
    	<?php } ?>
	<?php } ?>
	
	<td class="text-center">
		<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["hydrantapp_home"] . "/view/". $data->getHy(); ?>">Anzeigen</a>
	</td>
	<?php if($currentUser->hasPrivilegeByName(Privilege::HYDRANTADMINISTRATOR)){
	    echo '<td>
                <a class="btn btn-primary btn-sm" href="' . $config["urls"]["hydrantapp_home"] . "/edit/". $data->getHy() . '">Bearbeiten</a>
             </td>';
	}?>
</tr>