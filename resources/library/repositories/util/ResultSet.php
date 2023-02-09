<?php

class ResultSet implements Countable, Iterator {
	
	const PAGE_PARAM = "page";
	const PAGESIZE_PARAM = "pageSize";
	const SEARCH_PARAM = "search";
	const SORTBY_PARAM = "sortby";
	const SORTORDER_PARAM = "sortorder";
	const SORTORDER_DESC = "DESC";
	const SORTORDER_ASC = "ASC";
	const SHOWALL_PARAM = "showAll";
	
	private $pointer = 0;
	
	protected ?array $data;
	
	protected int $overallSize;
	
	protected int $pageSize;
	
	protected int $page;
	
	protected ?string $sortedBy;
	protected bool $desc;
	
	protected ?string $searchTerm;
	
	protected bool $showAll;
		
	/**
	 * @return mixed
	 */
	public function getShowAll() {
		return $this->showAll;
	}

	/**
	 * @param mixed $showAll
	 */
	public function setShowAll($showAll) {
		$this->showAll = $showAll;
	}

	/**
	 * @return mixed
	 */
	public function getSortedBy() {
		return $this->sortedBy;
	}

	/**
	 * @return mixed
	 */
	public function getDesc() {
		return $this->desc;
	}

	/**
	 * @param mixed $sortedBy
	 */
	public function setSortedBy($sortedBy) {
		$this->sortedBy = $sortedBy;
	}

	/**
	 * @param mixed $desc
	 */
	public function setDesc($desc) {
		$this->desc = $desc;
	}

	/**
	 * @return mixed
	 */
	public function getPageSize() {
		return $this->pageSize;
	}

	/**
	 * @param mixed $pageSize
	 */
	public function setPageSize($pageSize) {
		$this->pageSize = $pageSize;
	}

	/**
	 * @return mixed
	 */
	public function getData() : array {
		return $this->data;
	}

	/**
	 * @return int
	 */
	public function getOverallSize() : int {
		return $this->overallSize;
	}

	/**
	 * @return int
	 */
	public function getPage() : int {
		return $this->page;
	}
	
	/**
	 * @return mixed
	 */
	public function getSearchTerm() {
		return $this->searchTerm;
	}

	/**
	 * @param mixed $searchTerm
	 */
	public function setSearchTerm($searchTerm) {
		$this->searchTerm = $searchTerm;
	}

	/**
	 * @param mixed $data
	 */
	public function setData(array $data) {
		$this->data = $data;
	}

	/**
	 * @param int $overallSize
	 */
	public function setOverallSize(int $overallSize) {
		$this->overallSize = $overallSize;
	}

	/**
	 * @param int $page
	 */
	public function setPage(int $page) {
		$this->page = $page;
	}

	public function count() {
	    return count($this->getData());
	}
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct() {
		$this->data = null;
		$this->overallSize = 0;
		$this->pageSize = 10;
		$this->page = 1;
		$this->searchTerm = null;
		$this->sortedBy = null;
		$this->desc = false;
		$this->showAll = false;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function initializeFromGetParams(?array $getParams){
		if($getParams != null){
			if(isset($getParams[self::PAGE_PARAM])){
				$this->page = $getParams[self::PAGE_PARAM];
			}
			if(isset($getParams[self::PAGESIZE_PARAM])){
				$this->pageSize = $getParams[self::PAGESIZE_PARAM];
			}
			if(isset($getParams[self::SEARCH_PARAM])){
				$this->searchTerm = $getParams[self::SEARCH_PARAM];
			}
			if(isset($getParams[self::SORTBY_PARAM])){
				$this->sortedBy = $getParams[self::SORTBY_PARAM];
			}
			if(isset($getParams[self::SHOWALL_PARAM])){
				$this->showAll = true;
			}
			if(isset($getParams[self::SORTORDER_PARAM])){
				if($getParams[self::SORTORDER_PARAM] == self::SORTORDER_DESC){
					$this->setDesc(true);
				} else if($getParams[self::SORTORDER_PARAM] == self::SORTORDER_ASC){
					$this->setDesc(false);
				}
			}
		}
	}
	
	public function getFrom(){
	    if($this->pageSize == -1){
	        return 0;
	    }
		return (($this->page -1) * $this->pageSize) + 1;
	}
	
	public function getTo(){
	    if($this->pageSize == -1){
	        return PHP_INT_MAX;
	    }
		return $this->page * $this->pageSize;
	}
	
	public function renderOnSearchPagination($position){
		return ($position >= self::getFrom() && $position <= self::getTo());
	}
	
	public function isSearch(){
		return $this->searchTerm != null && $this->searchTerm != "";
	}
	
	public function isSortedBy(string $sortedBy){
		return $this->sortedBy != null && $this->sortedBy == $sortedBy;
	}
	
	public function isSorted(){
		return $this->sortedBy != null && $this->sortedBy != "";
	}
	
    public function next(){
        $this->pointer++;
    }

    public function valid(){
        return $this->pointer < $this->count();
    }

    public function current(){
        return $this->data[$this->pointer];
    }

    public function rewind(){
        $this->pointer = 0;
    }

    public function key(){
        return $this->pointer;
    }

}

?>