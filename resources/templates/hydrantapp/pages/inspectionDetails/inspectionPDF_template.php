<?php 
require_once realpath(dirname(__FILE__) . "/../../../../config.php");

if(isset($inspection) || isset($hydrants)){
?>
<html>
	<?php 
		require_once TEMPLATES_PATH . "/head.php";
		setPrintToLandscape();
	?>

	<body class="print-landscape">
		<div>
		<div class="row">
			<div class="col">
				<img class="img-fluid d-block" style="height:80px; margin-bottom:1rem" src="<?= $config["urls"]["intranet_home"] ?>/images/layout/logo_bw.png">
			</div>
		
    		<div class="col-6 d-flex align-items-center justify-content-center">
    				<h2>Hydranten-Prüfbericht</h2>
    		</div>
    		<div class="col">
			</div>
    	</div>
		
		<?php require_once 'inspectionTable.php';?>
		
		</div>
	</body>
</html>
<?php } ?>