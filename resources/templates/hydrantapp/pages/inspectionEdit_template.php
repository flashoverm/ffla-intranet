<form onsubmit="showLoader()" action="" method="post">
    <table class="table table-bordered">
    	<tbody>
    		<tr>
    			<th>Datum</th>
    			<th>Fahrzeug (optional)</th>
    		</tr>
    		<tr>
    			<td class="th-td-small">			
    				<div class="form-group" style="margin-bottom: 0 !important;">
                		<input type="date" required="required" 
                		placeholder="TT.MM.JJJJ" title="TT.MM.JJJJ" class="form-control" 
                		name="date" id="date"
                		<?php  if(isset($inspection)){
                		    echo " value='" . $inspection->date . "'";
                		}?>
                		required pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
        			</div>
        		</td>
        			<td class="th-td-small">        	
        				<div class="form-group" style="margin-bottom: 0 !important;">
                		<input type="text" class="form-control" name="vehicle" id="vehicle" 
                		<?php  if(isset($inspection)){
        		              echo "value='" . $inspection->vehicle . "'";
               	        }?>
                		placeholder="Fahrzeugbezeichnung eingeben">
            		</div>
            	</td>
    		</tr>
    		<tr>
    			<th colspan="2">Name(n)</th>
    		</tr>
    		<tr>
    			<td colspan="2" class="th-td-small">	
    				<div class="form-group" style="margin-bottom: 0 !important;">
                		<input type="text" class="form-control" required="required" name="name" id="name" 
                		<?php  if(isset($inspection)){
                		    echo "value='" . $inspection->name . "'";
                       	}?>
                		placeholder="Name(n) eingeben">
					</div>
				</td>
    		</tr>
    		<tr>
    			<td colspan="2" style="padding:0">
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
                                  			<?php
                                  			if($count == 0){
                                  			   echo $criteria[$count][1];
                                  			} else {
                                  			    echo $criteria[$count][0] . ": " . $criteria[$count][1];
                                  			}
                                  			?>
                                		</div>
                                	</div>
                            	</th>
                                <?php
                                }
                                ?>
                                <th class="th-td-small text-center">
									<button type="button" class="btn btn-sm btn-primary" onClick="addHydrantOverview()">+</button>
                    			</th>
                        </tr>
                    	</thead>
                    	<tbody id="table-body">
                    	<?php 
                    		if(isset($inspection)){
                    		    foreach ($inspection->hydrants as $hydrant) {
                    		        ?>
                					<tr id="hydrant<?= $hydrant->idx ?>">
                            			<td class="th-td-small align-middle text-center" id="hlfd"><?= $hydrant->idx ?></td>
                            			<td class="th-td-small align-middle" style="min-width: 6rem">
                            				<input id="h<?= $hydrant->idx ?>hy" name="h<?= $hydrant->idx ?>hy" class="form-control form-control-sm" type="number" 
                            				value="<?= $hydrant->hy ?>" placeholder="HY Nr.">
                            			</td>
                            			<td class="th-td-small">
                                        	<div class="custom-control custom-radio d-flex justify-content-center">
                        						<input checked type="radio" class="custom-control-input" id="h<?= $hydrant->idx ?>typeu" name="h<?= $hydrant->idx ?>type" 
                        						<?php  if($hydrant->type == 'overfloor') { echo "checked"; } ?>
                        						value="overfloor">
                        						<label class="custom-control-label custom-control-label-table" for="h<?= $hydrant->idx ?>typeu">&nbsp;</label>
                        					</div> 
                                        </td>
                            			<td class="th-td-small">
                                           	<div class="custom-control custom-radio d-flex justify-content-center">
                                    			<input type="radio" class="custom-control-input" id="h<?= $hydrant->idx ?>typeo" name="h<?= $hydrant->idx ?>type" 
                          						<?php  if($hydrant->type == 'underfloor') { echo "checked"; } ?>                      			
                                    			value="underfloor">
                                    			<label class="custom-control-label custom-control-label-table" for="h<?= $hydrant->idx ?>typeo">&nbsp;</label>
                        					</div>
                                        </td>
                            			<?php
                                        for ($count = 0; $count < sizeof($criteria); $count ++) {
                                        ?>
                                        	<td class="th-td-small">
                                        		<div class="custom-control custom-checkbox d-flex justify-content-center">
                                                	<input type="checkbox" class="custom-control-input" 
                                                	
                                                	<?php  if($hydrant->criteria[$count]->value == true) { echo "checked"; } ?>     
                                                	                 			
                                                	id='h<?= $hydrant->idx ?>c<?= $count ?>' name='h<?= $hydrant->idx ?>c<?= $count ?>'>
                                                	<label for='h<?= $hydrant->idx ?>c<?= $count ?>' class="custom-control-label custom-control-label-table">&nbsp;</label>
                                                </div>
                                        	</td>
                                        <?php
                                        }
                                        ?>
                                       <td class="th-td-small">
                                       		<button type="button" class="btn btn-primary btn-sm" id="h<?= $hydrant->idx ?>rem" onclick="removeHydrantOverview(<?= $hydrant->idx ?>)">X</button>
                                        </td>
                            		</tr>
                    		        <?php 
                    		    }
                    		}
                    		?>
                    		<tr id="hydrantrowtemplate" style="display:none;">
                    			<td class="th-td-small align-middle text-center" id="hlfd">1</td>
                    			<td class="th-td-small align-middle" style="min-width: 6rem">
                    				<input id="hy" name="hy" class="form-control form-control-sm" type="number" placeholder="HY Nr.">
                    			</td>
                    			<td class="th-td-small">
                                	<div class="custom-control custom-radio d-flex justify-content-center">
                						<input checked type="radio" class="custom-control-input" id="typeu" name="type" value="overfloor">
                						<label class="custom-control-label custom-control-label-table" for="typeu">&nbsp;</label>
                					</div> 
                                </td>
                    			<td class="th-td-small">
                                   	<div class="custom-control custom-radio d-flex justify-content-center">
                            			<input type="radio" class="custom-control-input" id="typeo" name="type" value="underfloor">
                            			<label class="custom-control-label custom-control-label-table" for="typeo">&nbsp;</label>
                					</div>
                                </td>
                    			<?php
                                for ($count = 0; $count < sizeof($criteria); $count ++) {
                                ?>
                                	<td class="th-td-small">
                                		<div class="custom-control custom-checkbox d-flex justify-content-center">
                                        	<input type="checkbox" class="custom-control-input" id='tc<?= $count ?>' name='tc<?= $count ?>'>
                                        	<label for='tc<?= $count ?>' class="custom-control-label custom-control-label-table">&nbsp;</label>
                                        </div>
                                	</td>
                                <?php
                                }
                                ?>
                               <td class="th-td-small">
                               		<button type="button" class="btn btn-primary btn-sm" id="rem" onclick="removeHydrantOverview()">X</button>
                                </td>
                    		</tr>
                    	</tbody>
                    </table>
            	</td>
		</tr>
		<tr>
			<th colspan="2">Hinweise</th>
		</tr>
		<tr>
			<td colspan="2" class="th-td-small">    
				<div class="form-group" style="margin-bottom: 0 !important;">
            		<textarea class="form-control" name="notes" id="notes" 
            		placeholder="Hinweise"><?php
            		if(isset($inspection)){ echo $inspection->notes; }
            		?></textarea>
    			</div>
			</td>
		</tr>
	</tbody>
</table>
    
    

    <input type="hidden" id="uuid" name="uuid" 
    <?php if(isset($inspection) && $inspection->uuid != "") {
        echo "value='" . $inspection->uuid . "'"; 
    } ?>
    >
    <input type="hidden" id="maxidx" name="maxidx" 
    <?php  
    if(isset($inspection)) { 
        echo "value ='" . $inspection->getMaxIdx() . "'"; 
    } else {
        echo "value ='0'"; 
    }
    ?>
    >
	<a class="btn btn-primary" href="<?= $config ["urls"] ["hydrantapp_home"] ?>/inspection">Zurück</a>
    <div class="float-right">
        <button type="button" class="btn btn-primary"  onclick="addHydrantOverview()">Hydrant hinzufügen</button>
        
        <div style="display:none;">
				<input type='submit' id="reportSubmit"/>
		</div>
		<button type="button" id="submitReport" class="btn btn-primary" onClick='checkHydrantCount()'>Abschicken</button>
		<div class='modal fade' id='confirmSubmit'>
			<div class='modal-dialog'>
				<div class='modal-content'>
		
					<div class='modal-header'>
						<h4 class='modal-title'>Prüfbericht abschicken</h4>
						<button type='button' class='close' data-dismiss='modal'>&times;</button>
					</div>
					<div class='modal-body'>Es wurde nur ein Hydrant hinzugefügt. Prüfbericht trotzdem abschicken?<br><br>Weitere geprüfte Hydranten können mit dem Button "Hydrant hinzufügen" oder dem "+" zu diesem Bericht hinzugefügt werden.</div>
					<div class='modal-footer'>
						<button type='button' class='btn btn-primary' onClick='processReportForm()'>Abschicken</button>
						<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>Abbrechen</button>
					</div>
				</div>
			</div>
		</div>
    </div>
</form>
<script>

<?php  
if(isset($inspection)) {
    echo "var hy_count = " . $inspection->getCount() . ";";
    echo "var hy_max_idx = " . $inspection->getMaxIdx() . ";"; 
} else {
    echo "var hy_count = 0;"; 
    echo "var hy_max_idx = 0;"; 
}
?>

var cCount = <?php echo sizeof($criteria)?>;

rotateCorrection();
showHideElement('submitReport', false);

function checkHydrantCount() {
	if(hy_count < 2){
		 $("#confirmSubmit").modal() 
	} else {
		processReportForm();
	}
}

function processReportForm() {
	closeOneModal('confirmSubmit');
    
    var reportSubmit = document.getElementById('reportSubmit');
    reportSubmit.click();
}

function rotateCorrection(){

	for(i = -2; i < cCount; i++) {
		var dc = document.getElementById("dc" + i);
		var doc = document.getElementById("doc" + i);
		
		doc.style.width = dc.offsetHeight + 'px';
		doc.style.height = dc.offsetWidth + 'px';
		doc.style.margin = "auto";		
	}	
}

function addHydrantOverview(){
	hy_count ++;
	hy_max_idx ++;

	var maxIdx = document.getElementById("maxidx");
	maxIdx.value = hy_max_idx;

	var template = document.getElementById("hydrantrowtemplate");
	
	var newHydrant =  template.cloneNode(true);
	newHydrant.id = "hydrant" + hy_max_idx;
	newHydrant.style.display = null;

	var fields = newHydrant.getElementsByTagName("input");
	var tag = "";
	for(i = 0;i < fields.length; i++) {
		if(fields[i].id.startsWith("tc")){
			tag = "h" + hy_max_idx + "c" + fields[i].id.substring(2);
			fields[i].id = tag;
			fields[i].name = tag;
		}
		if(fields[i].id == "hy"){
			fields[i].id = "h" + hy_max_idx + "hy";
			fields[i].name = "h" + hy_max_idx + "hy";
			fields[i].required = true;
		}
		if(fields[i].id == "typeo"){
			fields[i].id = "h" + hy_max_idx + "typeo";
			fields[i].name = "h" + hy_max_idx + "type";
		}
		if(fields[i].id == "typeu"){
			fields[i].id = "h" + hy_max_idx + "typeu";
			fields[i].name = "h" + hy_max_idx + "type";
		}
	}

	fields = newHydrant.getElementsByTagName("label");
	for(i = 0;i < fields.length; i++) {
		if(fields[i].htmlFor.startsWith("tc")){
			tag = "h" + hy_max_idx + "c" + fields[i].htmlFor.substring(2);
			fields[i].htmlFor = tag;
		}
		if(fields[i].htmlFor == "typeo"){
			fields[i].htmlFor = "h" + hy_max_idx + "typeo";
		}
		if(fields[i].htmlFor == "typeu"){
			fields[i].htmlFor = "h" + hy_max_idx + "typeu";
		}
	}
	fields = newHydrant.getElementsByTagName("select");
	for(i = 0;i < fields.length; i++) {
		if(fields[i].id == "type"){
			fields[i].id = "h" + hy_max_idx + "type";
			fields[i].name = "h" + hy_max_idx + "type";
			break;
		}
	}
	fields = newHydrant.getElementsByTagName("td");
	for(i = 0;i < fields.length; i++) {
		if(fields[i].id == "hlfd"){
			fields[i].id = "h" + hy_max_idx + "lfd";
			fields[i].name = "h" + hy_max_idx + "lfd";
			fields[i].textContent = hy_max_idx;
			break;
		}
	}
	fields = newHydrant.getElementsByTagName("button");
	for(i = 0;i < fields.length; i++) {
		if(fields[i].id == "rem"){
			fields[i].id = "h" + hy_max_idx + "rem";
			fields[i].setAttribute('onclick','removeHydrantOverview(' + hy_max_idx + ')')
			break;
		}
	}

	template.parentNode.insertBefore(newHydrant, template);
	showHideElement('submitReport', true);
}

function removeHydrantOverview(id){
	if(hy_count > 0 ) {
		var element = document.getElementById('hydrant' + id);
		if(element != null){
			element.parentNode.removeChild(element);
			hy_count --;
			if(hy_count < 1){
				showHideElement('submitReport', false);
			}
		}	
	} else {
		console.log("No hydrants to remove left");
	}
	//alert 
}


</script>
