<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="file")
 */
class File extends BaseModel {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected ?string $uuid;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $description;
	
	
	protected $date;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $filename;
	
	
	/**
	 * @return mixed
	 */
	public function getUuid() : ?string {
		return $this->uuid;
	}

	/**
	 * @return mixed
	 */
	public function getDescription() : ?string {
		return $this->description;
	}

	/**
	 * @return mixed
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @return mixed
	 */
	public function getFilename() : ?string {
		return $this->filename;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid(?string $uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $description
	 */
	public function setDescription(?string $description) {
		$this->description = $description;
	}

	/**
	 * @param mixed $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * @param mixed $filename
	 */
	public function setFilename(?string $filename) {
		$this->filename = $filename;
	}

	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct() {
		parent::__construct();
		$this->uuid = NULL;
		$this->date = NULL;
		$this->description = NULL;
		$this->filename = NULL;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function jsonSerialize() {
		return [
				'uuid' => $this->uuid,
				'date' => $this->date,
				'description' => $this->description,
				'filename' => $this->filename,
		];
	}
	
	public function setFileData(?string $description, $date, ?string $filename){
		$this->setDescription($description);
		$this->setDate($date);
		$this->setFilename($filename);
	}
	
}