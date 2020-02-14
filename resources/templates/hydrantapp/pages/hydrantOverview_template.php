<?php
if (! count ( $hydrants )) {
	showInfo ( "Keine Hydranten gefunden" );
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
				<th data-sortable="true" class="text-center">Löschzug</th>
				<th data-sortable="true" class="text-center">Typ</th>
				<?php if(userHasRight(HYDRANTADMINISTRATOR)){
				    echo '<th data-sortable="true" class="text-center">Betrieb</th>';
				}?>
				<th class="text-center">Anzeigen</th>
				<?php if(userHasRight(HYDRANTADMINISTRATOR)){
				    echo '<th class="text-center">Bearbeiten</th>';
				}?>
			</tr>
		</thead>
		<tbody>
    
    <?php
    foreach ( $hydrants as $row ) {
        ?>
				<tr>
				<td class="text-center"><?= $row->hy; ?></td>
				<td class="text-center"><?= $row->fid; ?></td>
				<td class="text-center"><?= $row->street; ?></td>
				<td class="text-center"><?= $row->district; ?></td>
				<td class="text-center"><?= get_engine($row->engine)->name ?></td>
				<td class="text-center"><?= $row->type; ?></td>
				<?php if(userHasRight(HYDRANTADMINISTRATOR)){
				    echo '<td class="text-center">';
				    if($row->operating){
				        echo " &#10003; ";
				    } else {
				        echo " &ndash; ";
				    }
				    echo '</td>';
				}?>
				<td class="text-center">
					<a class="btn btn-primary btn-sm" href="<?= $config["urls"]["hydrantapp_home"] . "/". $row->hy; ?>">Anzeigen</a>
				</td>
				<?php if(userHasRight(HYDRANTADMINISTRATOR)){
				    echo '<td>
                            <a class="btn btn-primary btn-sm" href="' . $config["urls"]["hydrantapp_home"] . "/". $row->hy . '/edit">Bearbeiten</a>
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
