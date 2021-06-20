<ul class="nav nav-tabs mb-3">
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'open'){ echo "active"; } ?>" href="<?= $config["urls"]["masterdataapp_home"] ?>/datachangerequests/process">Offene Anfragen</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'done'){ echo "active"; } ?>" href="<?= $config["urls"]["masterdataapp_home"] ?>/datachangerequests/process/done">Abgeschlossene Anfragen</a>
	</li>
	<li class="nav-item">
		<a class="nav-link <?php if($tab == 'declined'){ echo "active"; } ?>" href="<?= $config["urls"]["masterdataapp_home"] ?>/datachangerequests/process/declined">Abgelehnte Anfragen</a>
	</li>
</ul>

<?php
if ( ! count ( $dataChangeRequests ) ) {
	showInfo ( "Keine Stammdatenänderungen vorhanden" );
} else {
	
	if($tab == 'open'){

		$options = array(
			'showUserData' => true,
			'showAdminOptions' => true,
		);
		
		renderDataChangeTable($dataChangeRequests, $options);
		
	} else {
		$options = array(
				'showUserData' => true,
		);
		
		renderDataChangeTable($dataChangeRequests, $options);
		
	}
}
?>

<script>
function copyToClipboard(uuid) {
	var r = document.createRange();
	r.selectNode(document.getElementById("value" + uuid));
	console.log(document.getElementById("value" + uuid).value); 
	window.getSelection().removeAllRanges();
	window.getSelection().addRange(r);
	document.execCommand('copy');
	window.getSelection().removeAllRanges();
	
	btn = document.getElementById("copy" + uuid);
	btn.className  = "btn btn-outline-success btn-sm mb-1";
	btn.firstChild.nodeValue = "Wert kopiert";
}
</script>
    