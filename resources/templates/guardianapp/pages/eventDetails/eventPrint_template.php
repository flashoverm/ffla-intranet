<?php 
require_once realpath(dirname(__FILE__) . "/../../../../config.php");

if(isset($event)){
?>
<html>
	<?php require_once TEMPLATES_PATH . "/head.php";?>
	
	<script type="text/javascript">
	window.print();
	window.onfocus=function(){ window.close();}
	</script>
	
	<body class="print">
		<div>
		<div class="row">
			<div class="col">
				<img class="img-fluid d-block" style="height:80px; margin-bottom:1rem" src="<?= $config["urls"]["intranet_home"] ?>/images/layout/logo_bw.png">
			</div>
		
    		<div class="col d-flex align-items-center justify-content-center">
    				<h2><?= get_eventtype($event->type)->type ?></h2>
    		</div>
    		<div class="col">
			</div>
    	</div>
		
		<?php require_once 'eventTable.php';?>
				</tbody>
			</table>
			<table class="table table-bordered">
				<tbody>
					<tr>
						<th>Wachlink</th>
						<th>Bericht erstellen</th>
					</tr>

					<tr>
						<td><img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?= urlencode($config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/".$event->uuid) ?>&choe=UTF-8" title="Wachlink" /></td>
						<td><img src="https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=<?= urlencode($config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/".$event->uuid) ?>&choe=UTF-8" title="Wachlink" /></td>
					</tr>
					<tr>
						<td><?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/events/<br>".$event->uuid ?></td>
						<td><?= $config ["urls"] ["base_url"] . $config ["urls"] ["guardianapp_home"] . "/reports/new/<br>".$event->uuid ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>
<?php } ?>