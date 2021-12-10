<?php 
function addHydrantRow(InspectedHydrant $hydrant, $criteria, $idx){
	?>
	<tr id="hydrant<?= $idx ?>">
		<td class="th-td-small align-middle text-center" id="hlfd"><?= $idx ?></td>
		<td class="th-td-small align-middle text-center" ><?= $hydrant->getHydrant()->getHy() ?></td>
		<td class="th-td-small align-middle text-center"> 
			<?php  if($hydrant->getType() == 'overfloor') { echo "<b>X</b>"; } ?>
		</td>
		<td class="th-td-small align-middle text-center">
			<?php  if($hydrant->getType() == 'underfloor') { echo "<b>X</b>"; } ?>                      			
		</td>
		<?php
		$inspectionCriteria = $hydrant->getCriteria();
		for ($count = 0; $count < sizeof($criteria); $count ++) {
		?>
			<td class="th-td-small align-middle text-center">
				<?php  
				if(count($inspectionCriteria) > 0 && $inspectionCriteria[$count]['value'] == true){
						echo "<b>X</b>"; 
				} else {
					echo "&nbsp;";
				}?>                      			
			</td>
		<?php
		}
		?>
	</tr>
<?php 
}
?>

<table class="table table-bordered">
	<tbody>
		<tr>
			<th class="th-td-padding text-left th-td-small">Datum</th>
			<th class="th-td-padding text-left th-td-small">Löschzug</th>
			<th class="th-td-padding text-left th-td-small">Name(n)</th>
			<th class="th-td-padding text-left th-td-small">Fahrzeug</th>
		</tr>
		<tr>
			<td class="th-td-padding th-td-small"><?= isset($inspection) ? date($config ["formats"] ["date"], strtotime($inspection->getDate())) : "&nbsp;" ?></td>
			<td class="th-td-padding th-td-small"><?= isset($inspection) ? $inspection->getEngine()->getName() : "&nbsp;" ?></td>
			<td class="th-td-padding th-td-small"><?= isset($inspection) ? $inspection->getName() : "&nbsp;" ?></td>
			<td class="th-td-padding th-td-small">
					<?php 
					if(isset($inspection)){
						if($inspection->getVehicle() == "") {
							echo " - ";
						} else {
							echo $inspection->getVehicle();
						}
					}else{
						echo "&nbsp;";
					}
        			?>
			</td>
		</tr>
		<tr>
			<td colspan="4" style="padding:0">
                <table class="table table-sm table-bordered" style="margin-bottom:0">
                	<thead>
                		<tr>
                			<th class="th-td-small text-center">
                				Lfd.
                 			</th>
                			<th class="th-td-small text-center">
                				HY Nr.
                			</th>
                			<th class="th-td-small">
                				<div id="doc-2">
                					<div class="element-to-rotate" id="dc-2">
                					Unterflurhydrant	
                					</div>
                				</div>
                			</th>
                			<th class="th-td-small">
                				<div id="doc-1">
                    			    <div class="element-to-rotate" id="dc-1">
                    				Überflurhydrant
                    				</div>
                				</div>
                			</th>
                            <?php
                            for ($count = 0; $count < sizeof($criteria); $count ++) {
                                ?>
                          	<th class="th-td-small font-weight-normal">
                          		<div id="doc<?= $count ?>">
                              		<div class="element-to-rotate" id="dc<?= $count ?>">
                              			<?= $criteria[$count][0] . ": " . $criteria[$count][1] ?>
                            		</div>
                            	</div>
                        	</th>
                            <?php
                            }
                            ?>
                    </tr>
                	</thead>
                	<tbody id="table-body">
                	<?php 
                		if(isset($inspection)){
                		    foreach ($inspection->getInspectedHydrants() as $hydrant) {
                		    	addHydrantRow($hydrant, $criteria, $hydrant->getIndex());
                		    }
                		} else if (isset($hydrants)){
                			foreach ($hydrants as $key=>$hydrant) {
                				$inspectedHydrant = new InspectedHydrant();
                				$inspectedHydrant->setHydrant($hydrant);
                				addHydrantRow($inspectedHydrant, $criteria, $key+1);
                			}
                		}
                		?> 
                	</tbody>
                </table>
            </td>
		</tr>
		<tr>
			<th colspan="4" class="th-td-padding text-left th-td-small">Hinweise</th>
		</tr>
		<tr>
			<td colspan="4" class="th-td-padding th-td-small">
			<?php
			if(isset($inspection)){
				if($inspection->getNotes() == "") {
					echo "Keine";
				} else {
					echo nl2br($inspection->getNotes());
				}
			} else {
				echo "&nbsp;";
			}
			?>
			</td>
		</tr>
	</tbody>
</table>

<script>

var cCount = <?php echo sizeof($criteria)?>;

rotateCorrection();

function rotateCorrection(){
	hideLoader();

	for(i = -2; i < cCount; i++) {
		var dc = document.getElementById("dc" + i);
		var doc = document.getElementById("doc" + i);
		
		console.log(dc);
		
		doc.style.width = dc.offsetHeight + 'px';
		doc.style.height = dc.offsetWidth + 'px';
		doc.style.margin = "0.15rem auto";		
	}	
}

</script>
