<?php 

function render($template, $data, $options = array()){
	include $template;
}

function renderPagination($pageLink, $entryCount, $currentPage, $pageSize){
	$pageDisplayNumber = 5;
	$pages = ceil ($entryCount/$pageSize);
	?>
	<nav>
		<ul class="pagination justify-content-center">
	  	<?php
	  	if($currentPage > 1){
	  		echo '<li class="page-item"><a class="page-link" href="' . $pageLink . ($currentPage-1) . '"><</a></li>';
	  	}
	  	for($i=max(1, $currentPage-$pageDisplayNumber);  ($i<=( $pages ) && $i<=($currentPage+$pageDisplayNumber) ); $i++){    	
	    	echo '<li class="page-item';
	    	if($i == $currentPage){
	    		echo ' active';
	    	}
	    	echo '"><a class="page-link" href="' . $pageLink . $i . '">' . $i . '</a></li>';
	  	}
	  	if($currentPage < $pages){
	  		echo '<li class="page-item"><a class="page-link" href="' . $pageLink . ($currentPage+1) . '">></a></li>';
	  	}
	  	?>
		</ul>
	</nav>

<?php	
}

?>