<?php
if (! count ( $hydrants )) {
    showInfo ( "Keine Hydranten für diesen Zug gefunden" );
} else {
    ?>

<div class="table-responsive">
	<table class="table table-striped" data-toggle="table" data-pagination="true"  data-search="true">
		<thead>
			<tr>
				<th data-sortable="true" class="text-center">HY</th>
				<th data-sortable="true" class="text-center">FID</th>
				<th data-sortable="true" class="text-center">Straße</th>
				<th data-sortable="true" class="text-center">Stadtteil</th>
				<th data-sortable="true" class="text-center">Typ</th>
				<th data-sortable="true" class="text-center">Geprüft am</th>
				<th class="text-center">Anzeigen</th>
				<?php if($currentUser->hasPrivilegeByName(Privilege::HYDRANTADMINISTRATOR)){
				    echo '<th class="text-center">Bearbeiten</th>';
				}?>
			</tr>
		</thead>
		<tbody>
    
    <?php
    foreach ( $hydrants as $row ) {
        ?>
				<tr>
				<td class="text-center"><?= $row->getHy(); ?></td>
				<td class="text-center"><?= $row->getFid(); ?></td>
				<td class="text-center"><?= $row->getStreet(); ?></td>
				<td class="text-center"><?= $row->getDistrict(); ?></td>
				<td class="text-center"><?= $row->getType(); ?></td>
				<?php if( $row->getLastCheck() == NULL ) {?>
					<td class="text-center">Nie</td>
				<?php } else { ?>			
					<td class="text-center"><span class='d-none'><?= strtotime($row->getLastCheck()) ?></span><?= date($config ["formats"] ["date"], strtotime($row->getLastCheck())); ?></td>
				<?php } ?>

				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["hydrantapp_home"] . "/". $row->getHy(); ?>">Anzeigen</a>
				</td>
				<?php if($currentUser->hasPrivilegeByName(Privilege::HYDRANTADMINISTRATOR)){
				    echo '<td>
                            <a class="btn btn-primary btn-sm" href="' . $config["urls"]["hydrantapp_home"] . "/". $row->getHy() . '/edit">Bearbeiten</a>
                         </td>';
				}?>
			</tr>
<?php
	}

?>
		</tbody>
	</table>
</div>

<?php 
}
?>


