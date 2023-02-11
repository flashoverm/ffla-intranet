<?php 

function render($template, $data, $options = array()){
	global $config;

	include $template;
}

function renderTable($rowTemplate, $columns, ResultSet $data, $options = array()){
	if ( ! isset($data) || ! count ( $data->getData() )) {
		showInfo ( "Es ist kein Eintrag vorhanden" );
	} else {
		?>
		<div class="table-responsive" id="table">
			<?php 
			renderTableDescription($data, $options);
			?>
			<thead>
				<tr>
				<?php
				foreach ( $columns as $column ) {
					if(isset($column['label'])){
						$sort = isset($column['sort']) ? $column['sort'] : null;
						renderTableHead($column['label'], $data, $sort);
					} else {
						echo "<th></th>";
					}
				}
				?>
				</tr>
			</thead>
			<tbody>
			<?php
			foreach ( $data->getData() as $row ) {
				render($rowTemplate, $row, $options);
			}
			?>
			</tbody>
			<script>
            	console.log($('.table-responsive'));
                $('.table-responsive').on('show.bs.dropdown', function () {
                     $('.table-responsive').css( "overflow", "inherit" );
                });
                
                $('.table-responsive').on('hide.bs.dropdown', function () {
                     $('.table-responsive').css( "overflow", "auto" );
                });
            </script>
			<?php
			renderTableClosing($data);
			?>
		</div>
		<?php 
		if( ! $data->isSearch() && ! $data->getShowAll() ){
			renderPagination($data);
		}
	}
}

function renderPagination(ResultSet $resultSet){
	global $url_prefix;
	
	$pageDisplayNumber = 5;
	if($resultSet->getPageSize() == -1){
	    $pages = 1;
	} else {
	    $pages = ceil ($resultSet->getOverallSize()/$resultSet->getPageSize());
	}
	
	?>
	<nav>
		<?php 
		renderPageSizeSelection($resultSet);
		?>
		<ul class="pagination justify-content-end">
	  	<?php
		  			  	    
    		  	if($resultSet->getPage() > 1){
    		  		echo '<li class="page-item"><a class="page-link" href="' . $url_prefix . getCurrentUrlWithQueryParam(ResultSet::PAGE_PARAM, $resultSet->getPage()-1) . '"><</a></li>';
    		  	}
    		  	for($i=max(1, $resultSet->getPage()-$pageDisplayNumber);  ($i<=( $pages ) && $i<=($resultSet->getPage()+$pageDisplayNumber) ); $i++){    	
    		    	echo '<li class="page-item';
    		    	if($i == $resultSet->getPage()){
    		    		echo ' active';
    		    	}
    		    	echo '"><a class="page-link" href="' . $url_prefix . getCurrentUrlWithQueryParam(ResultSet::PAGE_PARAM, $i) . '">' . $i . '</a></li>';
    		  	}
    		  	if($resultSet->getPage() < $pages){
    		  		echo '<li class="page-item"><a class="page-link" href="' . $url_prefix . getCurrentUrlWithQueryParam(ResultSet::PAGE_PARAM, $resultSet->getPage()+1) . '">></a></li>';
    		  	}
		  	?>
		</ul>
	</nav>
<?php
}

function renderPageSizeSelection(ResultSet $resultSet){
	global $url_prefix;
	
	$clearedFromPage = getCurrentUrlWithoutQueryParam(ResultSet::PAGE_PARAM);
?>
	<div class="float-left pagination-detail">
		<span class="pagination-info">
			<?php if($resultSet->getPageSize() == -1){
			    echo "Zeige alle " . $resultSet->getOverallSize() . " Zeilen";
			} else {
				echo "Zeige Zeile " . $resultSet->getFrom() . " bis " . min(array($resultSet->getTo(), $resultSet->getOverallSize())) . " von " . $resultSet->getOverallSize() . " Zeilen.";
			}
			?>
		</span>
		<span class="page-list">
			<span class="btn-group dropdown dropup">
				<button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown">
				<span class="page-size">
					<?= ($resultSet->getPageSize() == -1) ? "Alle" : $resultSet->getPageSize()?>
				</span>
				<span class="caret"></span>
				</button>
				<div class="dropdown-menu">
					<a class="dropdown-item <?php if($resultSet->getPageSize() == 10){ echo "active"; } ?>" href="<?= $url_prefix . setQueryParam($clearedFromPage, ResultSet::PAGESIZE_PARAM, 10) ?>">10</a>
					<a class="dropdown-item <?php if($resultSet->getPageSize() == 25){ echo "active"; } ?>" href="<?= $url_prefix . setQueryParam($clearedFromPage, ResultSet::PAGESIZE_PARAM, 25) ?>">25</a>
					<a class="dropdown-item <?php if($resultSet->getPageSize() == 50){ echo "active"; } ?>" href="<?= $url_prefix . setQueryParam($clearedFromPage, ResultSet::PAGESIZE_PARAM, 50) ?>">50</a>
					<a class="dropdown-item <?php if($resultSet->getPageSize() == 100){ echo "active"; } ?>" href="<?= $url_prefix . setQueryParam($clearedFromPage, ResultSet::PAGESIZE_PARAM, 100) ?>">100</a>
					<a class="dropdown-item <?php if($resultSet->getPageSize() == -1){ echo "active"; } ?>" href="<?= $url_prefix . setQueryParam($clearedFromPage, ResultSet::PAGESIZE_PARAM, -1) ?>">Alle</a>
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

function renderTableDescription(ResultSet $resultSet, $options){
	global $url_prefix;
	
	if($resultSet->isSearch() || $resultSet->getShowAll()){
	?> 
		<?php if( ! isset($options['hideSearch'] ) && ! $resultSet->getShowAll() ) { ?>
			<div class="buttons-toolbar">
				<a class="btn btn-primary search-reset" href="<?= $url_prefix . getCurrentUrlWithoutQueryParam(ResultSet::SEARCH_PARAM) ?>">Suche abbrechen</a>
			</div>
		<?php } ?> 
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
			<?php if( ! isset($options['hideSearch'] )) { ?>
				data-search="true"
				data-toolbar=".buttons-toolbar"
				data-toolbar-align="right"
				data-search-text="<?= $resultSet->getSearchTerm() ?>"
			<?php } ?> 
			data-on-load-success="onLoad()"
		> 
	<?php
	} else {
		if( ! isset($options['hideSearch'] )) {
			renderSearch($resultSet);
		}
	?> 
		<div class="bootstrap-table">
			<div class="fixed-table-container">
				<table 
					class="table table-striped table-bordered table-generic table-hover"
				> 
	<?php
	}
}

function renderTableClosing(ResultSet $resultSet){
	if($resultSet->isSearch() || $resultSet->getShowAll()){
	?>
		</table>
	<?php	
	} else {
	?>
				</table>
			</div>
		</div>
	<?php
	}
}

function renderTableHead(string $label, ResultSet $resultSet, ?string $orderBy=null){
	global $url_prefix;

	if($resultSet->isSearch() || $resultSet->getShowAll()){
		echo"<th "; 
		if($orderBy != null){ echo "data-sortable=\"true\""; }
		echo " >";
		echo $label;
		echo "</th>";
		return;
	}
	
	$innerClasses = "";
	$url = "";
	if($orderBy != null){
		$innerClasses .= "sortable ";
		
		if($resultSet->isSortedBy($orderBy)){
			
			if($resultSet->getDesc()){
				$url = getCurrentUrlWithoutQueryParam(ResultSet::SORTBY_PARAM);
				$url = unsetQueryParam($url, ResultSet::SORTORDER_PARAM);
				$innerClasses .= "desc";
				
			} else {
				$url = getCurrentUrlWithQueryParam(ResultSet::SORTBY_PARAM, $orderBy);
				$url = setQueryParam($url, ResultSet::SORTORDER_PARAM, ResultSet::SORTORDER_DESC);
				$innerClasses .= "asc";
			}
			
		} else {
			$url = getCurrentUrlWithQueryParam(ResultSet::SORTBY_PARAM, $orderBy);
			$url = setQueryParam($url, ResultSet::SORTORDER_PARAM, ResultSet::SORTORDER_ASC);
			$innerClasses .= "both";
		}
	}
	?>
	<th <?php echo "class='text-center'"; ?>>
		<?php if($orderBy != null){ ?>
		<a href="<?= $url_prefix . $url ?>">
		<?php } ?>
			<div class="th-inner <?= $innerClasses ?>">
				<?= $label ?>
			</div>
		<?php if($orderBy != null){ ?>
		</a>
		<?php } ?>
	</th>
	<?php
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