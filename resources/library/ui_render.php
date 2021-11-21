<?php 

function render($template, $data, $options = array()){
	global $config;
	
	include $template;
}

function renderPagination($entryCount, $currentPage, $pageSize){
		
	$pageDisplayNumber = 5;
	$pages = ceil ($entryCount/$pageSize);
	?>
	<nav>
		<ul class="pagination justify-content-center">
	  	<?php
	  	if($currentPage > 1){
	  		echo '<li class="page-item"><a class="page-link" href="' . getCurrentUrlWithPage($currentPage-1) . '"><</a></li>';
	  	}
	  	for($i=max(1, $currentPage-$pageDisplayNumber);  ($i<=( $pages ) && $i<=($currentPage+$pageDisplayNumber) ); $i++){    	
	    	echo '<li class="page-item';
	    	if($i == $currentPage){
	    		echo ' active';
	    	}
	    	echo '"><a class="page-link" href="' . getCurrentUrlWithPage($i) . '">' . $i . '</a></li>';
	  	}
	  	if($currentPage < $pages){
	  		echo '<li class="page-item"><a class="page-link" href="' . getCurrentUrlWithPage($currentPage+1) . '">></a></li>';
	  	}
	  	?>
		</ul>
	</nav>

<?php	
}

function getCurrentUrlWithPage($page){
	$pageParam = "page";
	
	$urlParts = parse_url($_SERVER['REQUEST_URI']);
	$path = $urlParts['path'];
	
	if (isset($urlParts['query'])) {
		$queryArray = [];
		parse_str($urlParts['query'], $queryArray);
		
		$queryArray[$pageParam] = $page;
		return $path . "?" . http_build_query($queryArray);
	}
	
	return $path .= '?' . $pageParam . "=" . $page;
}

?>