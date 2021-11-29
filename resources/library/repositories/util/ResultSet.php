<?php

class ResultSet {
	
	const PAGE_PARAM = "page";
	const PAGESIZE_PARAM = "pageSize";
	const SEARCH_PARAM = "search";
	
	protected ?array $data;
	
	protected int $overallSize;
	
	protected int $pageSize;
	
	protected int $page;
	
	protected ?string $sorteyBy;
	protected bool $desc;
	
	protected ?string $searchTerm;
	
	/**
	 * @return mixed
	 */
	public function getSorteyBy() {
		return $this->sorteyBy;
	}

	/**
	 * @return mixed
	 */
	public function getDesc() {
		return $this->desc;
	}

	/**
	 * @param mixed $sorteyBy
	 */
	public function setSorteyBy($sorteyBy) {
		$this->sorteyBy = $sorteyBy;
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
		}
	}
	
	public function getFrom(){
		return (($this->page -1) * $this->pageSize) + 1;
	}
	
	public function getTo(){
		return $this->page * $this->pageSize;
	}
	
	public function renderOnSearchPagination($position){
		return ($position >= self::getFrom() && $position <= self::getTo());
	}
	
	public function isSearch(){
		return $this->searchTerm != null && $this->searchTerm != "";
	}
}

?>