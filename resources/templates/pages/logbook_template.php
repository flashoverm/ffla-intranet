<?php

if (! count ( $logbook )) {
	showInfo ( "Es ist kein Eintrag vorhanden" );
} else {
?>
<div class="table-responsive">
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th class="text-center">Datum/Uhrzeit</th>
				<th class="text-center">Action-Code</th>
				<th class="text-center">Nachricht</th>
				<th class="text-center">Angemeldeter Benutzer</th>
			</tr>
		</thead>
		<tbody>
		<?php
		foreach ( $logbook as $row ) {
			$user = null;
			if($row->user != null){
				$user = get_user($row->user);
			}
			$entry = $row->message;
		?>
			<tr>
				<td class="text-center"><span class='d-none'><?= strtotime($row->timestamp) ?></span><?= date($config ["formats"] ["datetime"], strtotime($row->timestamp)); ?></td>
				<td class="text-center"><?= $row->action; ?></td>
				<td class="text-center"><?= $entry ?></td>
				<td class="text-center">
					<?php 
					if ($user != null){
						echo $user->firstname . " " . $user->lastname . " (" . $user->email . ")";
					} else {
						echo "-";
					}
					?>
				</td>
			</tr>
		<?php
		}
		?>
		</tbody>
	</table>
</div>
<nav>
	<ul class="pagination justify-content-center">
  	<?php
  	$pages = ceil (get_logbook_count()/$resultSize);
  	if($currentPage > 1){
  		echo '<li class="page-item"><a class="page-link" href="' . $config["urls"]["intranet_home"] . '/logbook/page/' . ($currentPage-1) . '"><</a></li>';
  	}
    for($i=1; $i<=( $pages ); $i++){    	
    	echo '<li class="page-item';
    	if($i == $currentPage){
    		echo ' active';
    	}
    	echo '"><a class="page-link" href="' . $config["urls"]["intranet_home"] . '/logbook/page/' . $i . '">' . $i . '</a></li>';
  	}
  	if($currentPage < $pages){
  		echo '<li class="page-item"><a class="page-link" href="' . $config["urls"]["intranet_home"] . '/logbook/page/' . ($currentPage+1) . '">></a></li>';
  	}
  	?>
	</ul>
</nav>

<?php
}
?>

<br>

<form method="post" >
	<input type="submit" name="purge" value="Log leeren" class="btn btn-primary">
</form>