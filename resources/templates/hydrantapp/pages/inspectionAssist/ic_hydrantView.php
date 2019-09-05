
		<div class="tab-pane fade" id="hydranttemplate" role="tabpanel" style="display:none;">
			<div class="row">
				<div class="col">
        			<div class="form-group">
        				<label>HY:</label>
        				<input type="number" class="form-control" name="hy" id="hy" placeholder="HY-Nummer eingeben">
        			</div>
        		</div>
        		<div class="col">
        			<div class="form-group">
        				<label>Alternativ FID:</label>
        				<input type="number" class="form-control" name="fid" id="fid" placeholder="alternativ FID-Nummer eingeben" oninput="getHyToFid()">
        			</div>        		
        		</div>		
			</div>
			<div style="margin-bottom : 1rem; ">
    			<div class="custom-control custom-radio custom-control-inline">
    				<input checked type="radio" class="custom-control-input" id="typeu" name="type" value="overfloor" onchange="switchHydrantType()">
    				<label class="custom-control-label custom-control-label-big" for="typeu">Unterflurhydrant</label>
    			</div> 
            	<div class="custom-control custom-radio custom-control-inline">
                	<input type="radio" class="custom-control-input" id="typeo" name="type" value="underfloor"  onchange="switchHydrantType()">
                	<label class="custom-control-label custom-control-label-big" for="typeo">Überflurhydrant</label>
    			</div>
			</div>
			<div class='custom-control custom-checkbox custom-checkbox-big' id='divc<?= '0' ?>'>
				<input class="custom-control-input" type='checkbox' id='tc<?= '0' ?>' name='tc<?= '0' ?>' onClick="toogleFaultView()" checked>
				<label class="custom-control-label custom-control-label-big" for='tc<?= '0' ?>'><?= $criteria[0][1] ?></label> 
			</div>
			<div class='custom-control custom-checkbox custom-checkbox-big' id='divc<?= '1' ?>'>
				<input class="custom-control-input" type='checkbox' id='tc<?= '1' ?>' name='tc<?= '0' ?>' onClick="toogleFaultView()" checked>
				<label class="custom-control-label custom-control-label-big" for='tc<?= '1' ?>'><?= $criteria[1][0] . ": " . $criteria[1][1] ?></label> 
			</div>
			<div id="faults" style="display:none;">
    			<?php
    			for ($count = 2; $count < sizeof($criteria); $count++) {
    			?>
    			<div class='custom-control custom-checkbox custom-checkbox-big' id='divc<?= $count ?>'>
    				<input class="custom-control-input" type='checkbox' id='tc<?=$count ?>' name='tc<?= $count ?>'>
    				<label class="custom-control-label custom-control-label-big" for='tc<?= $count ?>'><?= $criteria[$count][0] . ": " . $criteria[$count][1] ?></label> 
    			</div>			
    			<?php 
    			}
    			?>
			</div>
			<div style="margin-top : 1.5rem;">
    			<button type="button" class="btn btn-primary" onclick="nextTab()">Weiter</button>
    			<div class="float-right">
        			<button type="button" class="btn btn-primary" id="rem" onclick="removeHydrant()">Hydrant entfernen</button>
        			<button type="button" class="btn btn-primary" onclick="addHydrant()">Hydrant hinzufügen</button>
				</div>			
			</div>				
		</div>

