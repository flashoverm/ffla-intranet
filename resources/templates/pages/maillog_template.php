<?php
if (!isset($mails) || ! count ( $mails ) ) {
	showInfo ( "Es sind keine Mails im Log" );
} else {
	global $maillogStates;
?>
<div class="table-responsive">
	<table class="table table-hover table-striped table-bordered">
		<thead>
			<tr>
				<!-- <th>#</th> -->
				<th>Datum</th>
				<th>Empfänger</th>
				<th>Betreff</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$index = 0;
			foreach ( $mails as $row ) {
			?>
			<tr data-toggle="collapse" data-target="#collapseme<?= $index ?>">
				<!-- <td><?= $row->RowNum ?></td> -->
				<td><?= date($config ["formats"] ["datetime"] . ":s", strtotime($row->timestamp)); ?></td>
				<td><?= $row->recipient ?></td>
				<td><?= $row->subject ?></td>
				<td><?= $maillogStates[$row->state] ?></td>
			</tr>
			<tr>
				<td class="collapse-td" colspan="5">
					<div class="collapse" id="collapseme<?= $index ?>">
						<div class="collapse-content">
							<?php 
							$body = nl2br($row->body);
								
								if($row->error != NULL){
									$body = $body . "<br><br><br><b>Fehlermeldung:</b><br>" . $row->error;
								}
								echo $body;
							?>
						</div>
					</div>
				</td>
			</tr>
			<?php
				$index ++;
			}
			?>
		</tbody>
	</table>
</div>
<nav>
  <ul class="pagination justify-content-center">
  	<?php
  	$pages = ceil (get_maillogs_count()/$resultSize);
  	if($currentPage > 1){
  		echo '<li class="page-item"><a class="page-link" href="' . $config["urls"]["intranet_home"] . '/maillog/page/' . ($currentPage-1) . '"><</a></li>';
  	}
    for($i=1; $i<=( $pages ); $i++){    	
    	echo '<li class="page-item';
    	if($i == $currentPage){
    		echo ' active';
    	}
    	echo '"><a class="page-link" href="' . $config["urls"]["intranet_home"] . '/maillog/page/' . $i . '">' . $i . '</a></li>';
  	}
  	if($currentPage < $pages){
  		echo '<li class="page-item"><a class="page-link" href="' . $config["urls"]["intranet_home"] . '/maillog/page/' . ($currentPage+1) . '">></a></li>';
  	}
  	?>
  </ul>
</nav>
<?php
}
?>

<br>

<form method="post" >
	<input type="submit" name="testmail" value="Testmail"  class="btn btn-primary">
	<input type="submit" name="purge" value="Log leeren" class="btn btn-primary">
</form>
