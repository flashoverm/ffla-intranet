<?php
global $userDAO;

$user = null;
if($data->getUser() != null){
	$user = $userDAO->getUserByUUID($data->getUser());
}
?>
<tr>
	<td class="text-center"><span class='d-none'><?= strtotime($data->getTimestamp()) ?></span><?= date($config ["formats"] ["datetime"], strtotime($data->getTimestamp())); ?></td>
	<td class="text-center"><?= $data->getAction() ?></td>
	<td class="text-center"><?= $data->getMessage() ?></td>
	<td class="text-center">
		<?php 
		if ($user != null){
			echo $user->getFullNameWithEmail();
		} else {
			echo "-";
		}
		?>
	</td>
</tr>