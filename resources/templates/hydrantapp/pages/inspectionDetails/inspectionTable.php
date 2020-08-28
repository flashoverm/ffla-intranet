<table class="table table-bordered">
	<tbody>
		<tr>
			<th class="th-td-padding text-left">Datum</th>
			<th class="th-td-padding text-left">Löschzug</th>
			<th class="th-td-padding text-left">Name(n)</th>
			<th class="th-td-padding text-left">Fahrzeug</th>
		</tr>
		<tr>
			<td class="th-td-padding"><?= isset($inspection) ? date($config ["formats"] ["date"], strtotime($inspection->date)) : "&nbsp;" ?></td>
			<td class="th-td-padding"><?= isset($inspection) ? get_engine($inspection->engine)->name : "&nbsp;" ?></td>
			<td class="th-td-padding"><?= isset($inspection) ? $inspection->name : "&nbsp;" ?></td>
			<td class="th-td-padding">
					<?php 
					if(isset($inspection)){
						if($inspection->vehicle == "") {
							echo " - ";
						} else {
							echo $inspection->vehicle;
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
                          	<th class="th-td-small">
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
                		    foreach ($inspection->hydrants as $hydrant) {
                		        ?>
        						<tr id="hydrant<?= $hydrant->idx ?>">
                        			<td class="th-td-small align-middle text-center" id="hlfd"><?= $hydrant->idx ?></td>
                        			<td class="th-td-small align-middle text-center" ><?= $hydrant->hy ?></td>
                        			<td class="th-td-small align-middle text-center"> 
                    					<?php  if($hydrant->type == 'overfloor') { echo "<b>X</b>"; } ?>
                                    </td>
                        			<td class="th-td-small align-middle text-center">
                      					<?php  if($hydrant->type == 'underfloor') { echo "<b>X</b>"; } ?>                      			
                                    </td>
                        			<?php
                                    for ($count = 0; $count < sizeof($criteria); $count ++) {
                                    ?>
                                    	<td class="th-td-small align-middle text-center">
                                            <?php  if($hydrant->criteria[$count]->value == true) { echo "<b>X</b>"; } ?>                      			
                                    	</td>
                                    <?php
                                    }
                                    ?>
                        		</tr>
                		        <?php 
                		    }
                		}
                		?> 
                	</tbody>
                </table>
            </td>
		</tr>
		<tr>
			<th colspan="4" class="th-td-padding text-left">Hinweise</th>
		</tr>
		<tr>
			<td colspan="4" class="th-td-padding">
			<?php
			if(isset($inspection)){
				if($inspection->notes == "") {
					echo "Keine";
				} else {
					echo nl2br($inspection->notes);
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

	for(i = -2; i < cCount; i++) {
		var dc = document.getElementById("dc" + i);
		var doc = document.getElementById("doc" + i);
		
		doc.style.width = dc.offsetHeight + 'px';
		doc.style.height = dc.offsetWidth + 'px';
		doc.style.margin = "0.15rem auto";		
	}	
}

</script>
