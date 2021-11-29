<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="search")
 */
class Search extends BaseModel {
	
	protected string $uuid;
	
	protected string $tableName;
	
	protected string $json;

	
	/**
	 * @return NULL
	 */
	public function getUuid() {
		return $this->uuid;
	}

	/**
	 * @return mixed
	 */
	public function getTableName() {
		return $this->tableName;
	}

	/**
	 * @return mixed
	 */
	public function getJson() {
		return $this->json;
	}

	/**
	 * @param NULL $uuid
	 */
	public function setUuid($uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $tableName
	 */
	public function setTableName($tableName) {
		$this->tableName = $tableName;
	}

	/**
	 * @param mixed $json
	 */
	public function setJson($json) {
		$this->json = $json;
	}
		
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct($uuid, $tableName, $json) {
		parent::__construct();
		$this->uuid = $uuid;
		$this->tableName = $tableName;
		$this->json = $json;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function jsonSerialize() {
		return [
				'uuid' => $this->uuid,
				'tableName' => $this->tableName,
				'json' => $this->json,
		];
	}
}