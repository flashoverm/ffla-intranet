<?php 

function render($template, $data, $options = array()){
	global $config;

	include $template;
}

function renderPagination(ResultSet $resultSet){
	if( ! $resultSet->isSearch()){
		$pageDisplayNumber = 5;
		$pages = ceil ($resultSet->getOverallSize()/$resultSet->getPageSize());
		?>
		<nav>
			<?php renderPageSizeSelection($resultSet) ?>
			<ul class="pagination justify-content-end">
		  	<?php
		  	if($resultSet->getPage() > 1){
		  		echo '<li class="page-item"><a class="page-link" href="' . getCurrentUrlWithQueryParam(ResultSet::PAGE_PARAM, $resultSet->getPage()-1) . '"><</a></li>';
		  	}
		  	for($i=max(1, $resultSet->getPage()-$pageDisplayNumber);  ($i<=( $pages ) && $i<=($resultSet->getPage()+$pageDisplayNumber) ); $i++){    	
		    	echo '<li class="page-item';
		    	if($i == $resultSet->getPage()){
		    		echo ' active';
		    	}
		    	echo '"><a class="page-link" href="' . getCurrentUrlWithQueryParam(ResultSet::PAGE_PARAM, $i) . '">' . $i . '</a></li>';
		  	}
		  	if($resultSet->getPage() < $pages){
		  		echo '<li class="page-item"><a class="page-link" href="' . getCurrentUrlWithQueryParam(ResultSet::PAGE_PARAM, $resultSet->getPage()+1) . '">></a></li>';
		  	}
		  	?>
			</ul>
		</nav>
	<?php
	}
}

function renderPageSizeSelection(ResultSet $resultSet){
	$clearedFromPage = getCurrentUrlWithoutQueryParam(ResultSet::PAGE_PARAM);
?>
	<div class="float-left pagination-detail">
		<span class="pagination-info">
			Zeige Zeile <?= $resultSet->getFrom() ?> bis <?= $resultSet->getTo() ?> von <?= $resultSet->getOverallSize() ?> Zeilen.
		</span>
		<span class="page-list">
			<span class="btn-group dropdown dropup">
				<button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
				<span class="page-size">
					<?= $resultSet->getPageSize()?>
				</span>
				<span class="caret"></span>
				</button>
				<div class="dropdown-menu">
					<a class="dropdown-item <?php if($resultSet->getPageSize() == 10){ echo "active"; } ?>" href="<?= setQueryParam($clearedFromPage, ResultSet::PAGESIZE_PARAM, 10) ?>">10</a>
					<a class="dropdown-item <?php if($resultSet->getPageSize() == 25){ echo "active"; } ?>" href="<?= setQueryParam($clearedFromPage, ResultSet::PAGESIZE_PARAM, 25) ?>">25</a>
					<a class="dropdown-item <?php if($resultSet->getPageSize() == 50){ echo "active"; } ?>" href="<?= setQueryParam($clearedFromPage, ResultSet::PAGESIZE_PARAM, 50) ?>">50</a>
					<a class="dropdown-item <?php if($resultSet->getPageSize() == 100){ echo "active"; } ?>" href="<?= setQueryParam($clearedFromPage, ResultSet::PAGESIZE_PARAM, 100) ?>">100</a>
				</div>
			</span>
			Zeilen pro Seite.
		</span>
	</div>

<?php	
}

function renderSearch(ResultSet $resultSet){
?>
	<form method="GET">
		<div class="input-group search float-right my-2">
			<input type="text" class="form-control" id="search" placeholder="Suchen" name="search" value="<?= $resultSet->getSearchTerm() ?>">
			<div class="input-group-append">
				<button class="btn btn-primary" type="submit">Suchen</button>
			</div>
		</div>	
	</form>

<?php	
}

function renderTableDescription(ResultSet $resultSet){
	if($resultSet->isSearch()){
	?> 
		<div class="buttons-toolbar">
			<a class="btn btn-primary search-reset" href="<?= getCurrentUrlWithoutQueryParam(ResultSet::SEARCH_PARAM) ?>">Suche abbrechen</a>
		</div>
		<script>
		document.body.addEventListener("onload", tableInit, true);
		
		function tableInit(){
			console.log("Done");
			$('#table').classList.remove("d-none"); 
		}
		</script>
		<table 
			class="table table-striped" 
			data-toggle="table" 
			data-pagination="true" 
			data-search="true" 
			data-toolbar=".buttons-toolbar"
			data-toolbar-align="right"
			data-on-load-success="onLoad()"
			data-search-text="<?= $resultSet->getSearchTerm() ?>"> 
	<?php
	} else {
		renderSearch($resultSet);
		?> <table class="table table-striped table-bordered"> <?php
	}
}

function getCurrentUrlWithQueryParam($paramName, $paramValue){
	return setQueryParam($_SERVER['REQUEST_URI'], $paramName, $paramValue);
}

function getCurrentUrlWithoutQueryParam($paramName){
	return unsetQueryParam($_SERVER['REQUEST_URI'], $paramName);
}

function unsetQueryParam($url, $paramName){
	return setQueryParam($url, $paramName, null);
}

function setQueryParam($url, $paramName, $paramValue){
	$urlParts = parse_url($url);
	$path = $urlParts['path'];
	
	if (isset($urlParts['query'])) {
		$queryArray = [];
		parse_str($urlParts['query'], $queryArray);
		
		if($paramValue == null && isset($queryArray[$paramName])){
			unset($queryArray[$paramName]);
		} else {
			$queryArray[$paramName] = $paramValue;
		}
		
		return $path . "?" . http_build_query($queryArray);
	}
	
	if($paramValue == null){
		return $path;
	}
	
	return $path .= '?' . $paramName . "=" . $paramValue;
}

?>