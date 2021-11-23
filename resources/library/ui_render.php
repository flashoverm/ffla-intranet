<?php 

function render($template, $data, $options = array()){
	global $config;
	
	include $template;
}

function renderPagination($entryCount, $currentPage){
		
	$pageDisplayNumber = 5;
	$pages = ceil ($entryCount/10);
	?>
	<nav>
		<div class="float-left pagination-detail">
      <span class="pagination-info">
      Zeige Zeile 1 bis 10 von 362 Zeilen.
      </span><span class="page-list"><span class="btn-group dropdown dropup">
      
        <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
        <span class="page-size">
        10
        </span>
        <span class="caret"></span>
        </button>
        <div class="dropdown-menu"><a class="dropdown-item active" href="#">10</a><a class="dropdown-item " href="#">25</a><a class="dropdown-item " href="#">50</a><a class="dropdown-item " href="#">100</a></div></span> Zeilen pro Seite.</span></div>
		
		<ul class="pagination justify-content-end">
	  	<?php
	  	if($currentPage > 1){
	  		echo '<li class="page-item"><a class="page-link" href="' . getCurrentUrlWithQueryParam("page", $currentPage-1) . '"><</a></li>';
	  	}
	  	for($i=max(1, $currentPage-$pageDisplayNumber);  ($i<=( $pages ) && $i<=($currentPage+$pageDisplayNumber) ); $i++){    	
	    	echo '<li class="page-item';
	    	if($i == $currentPage){
	    		echo ' active';
	    	}
	    	echo '"><a class="page-link" href="' . getCurrentUrlWithQueryParam("page", $i) . '">' . $i . '</a></li>';
	  	}
	  	if($currentPage < $pages){
	  		echo '<li class="page-item"><a class="page-link" href="' . getCurrentUrlWithQueryParam("page", $currentPage+1) . '">></a></li>';
	  	}
	  	?>
		</ul>
	</nav>

<?php	
}

function renderSearch(){
?>
	<div class="float-right my-2">
		<form method="GET">
			<input class="form-control search-input" type="text" placeholder="Suchen" name="search">
		</form>
	</div>
<?php	
}

function getCurrentUrlWithQueryParam($paramName, $paramValue){
	
	$urlParts = parse_url($_SERVER['REQUEST_URI']);
	$path = $urlParts['path'];
	
	if (isset($urlParts['query'])) {
		$queryArray = [];
		parse_str($urlParts['query'], $queryArray);
		
		$queryArray[$paramName] = $paramValue;
		return $path . "?" . http_build_query($queryArray);
	}
	
	return $path .= '?' . $paramName . "=" . $paramValue;
}

?>