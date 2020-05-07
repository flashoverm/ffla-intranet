<?php 

function createDialog($id, $title, $name, $additionalValueName = null, $additionalValue = null, $positiveButton="Ja", $negativeButton="Abbrechen", $text = null){
	echo "
	<div class='modal fade' id='" . $id . "'>
		<div class='modal-dialog'>
			<div class='modal-content'>

				<div class='modal-header'>
					<h4 class='modal-title'>" . $title . "</h4>
					<button type='button' class='close' data-dismiss='modal'>&times;</button>
				</div>";
	            if($text != null ){
	                echo "<div class='modal-body'>" . $text . "</div>";
            	}
            	echo "<div class='modal-footer'>
					<form action='' method='post' style='margin-bottom: 0px;'>";
					if($additionalValueName != null && $additionalValue != null){
						echo "<input type='hidden' name='" . $additionalValueName . "' value='" . $additionalValue . "' />";
					}
					echo "<input type='submit' ";
					if($name != null && $name != ""){
						echo "name='" . $name . "' "; 
					}
					echo "value='" . $positiveButton . "' class='btn btn-primary' />
						<button type='button' class='btn btn-outline-primary' data-dismiss='modal'>" . $negativeButton .  "</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	";
}


?>