<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="event")
 */
class Event extends BaseModel {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected ?string $uuid;
	
	protected $date;
	
	protected $starTime;
	
	protected $endTime;
	
	protected ?EventType $type;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $typeOther;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $title;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $comment;
	
	protected ?Engine $engine;
	
	protected ?User $creator;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected bool $published;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected bool $staffConfirmation;
	
	protected ?User $deletedBy;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $hash;
	

	/**
	 * @return NULL
	 */
	public function getUuid() : ?string {
		return $this->uuid;
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
	public function getStarTime() {
		return $this->starTime;
	}

	/**
	 * @return mixed
	 */
	public function getEndTime() {
		return $this->endTime;
	}

	/**
	 * @return NULL
	 */
	public function getType() : ?EventType {
		return $this->type;
	}

	/**
	 * @return mixed
	 */
	public function getTypeOther() : ?string {
		return $this->typeOther;
	}

	/**
	 * @return mixed
	 */
	public function getTitle() : ?string {
		return $this->title;
	}

	/**
	 * @return mixed
	 */
	public function getComment() : ?string {
		return $this->comment;
	}

	/**
	 * @return NULL
	 */
	public function getEngine() : ?Engine {
		return $this->engine;
	}

	/**
	 * @return mixed
	 */
	public function getCreator() : ?User {
		return $this->creator;
	}

	/**
	 * @return mixed
	 */
	public function getPublished() : bool {
		return $this->published;
	}

	/**
	 * @return mixed
	 */
	public function getStaffConfirmation() : bool {
		return $this->staffConfirmation;
	}

	/**
	 * @return mixed
	 */
	public function getDeletedBy() : ?User {
		return $this->deletedBy;
	}

	/**
	 * @return mixed
	 */
	public function getHash() : ?string {
		return $this->hash;
	}

	/**
	 * @param NULL $uuid
	 */
	public function setUuid(?string $uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * @param mixed $starTime
	 */
	public function setStarTime($starTime) {
		$this->starTime = $starTime;
	}

	/**
	 * @param mixed $endTime
	 */
	public function setEndTime($endTime) {
		$this->endTime = $endTime;
	}

	/**
	 * @param NULL $type
	 */
	public function setType(?EventType $type) {
		$this->type = $type;
	}

	/**
	 * @param mixed $typeOther
	 */
	public function setTypeOther(?string $typeOther) {
		$this->typeOther = $typeOther;
	}

	/**
	 * @param mixed $title
	 */
	public function setTitle(?string $title) {
		$this->title = $title;
	}

	/**
	 * @param mixed $comment
	 */
	public function setComment(?string $comment) {
		$this->comment = $comment;
	}

	/**
	 * @param NULL $engine
	 */
	public function setEngine(?Engine $engine) {
		$this->engine = $engine;
	}

	/**
	 * @param mixed $creator
	 */
	public function setCreator(?User $creator) {
		$this->creator = $creator;
	}

	/**
	 * @param mixed $published
	 */
	public function setPublished(bool $published) {
		$this->published = $published;
	}

	/**
	 * @param mixed $staffConfirmation
	 */
	public function setStaffConfirmation(bool $staffConfirmation) {
		$this->staffConfirmation = $staffConfirmation;
	}

	/**
	 * @param mixed $deletedBy
	 */
	public function setDeletedBy(?User $deletedBy) {
		$this->deletedBy = $deletedBy;
	}

	/**
	 * @param mixed $hash
	 */
	public function setHash(?string $hash) {
		$this->hash = $hash;
	}
	
	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct() {
		parent::__construct();
		$this->checkByFF = true;
		$this->cycle = 0;
		$this->district = NULL;
		$this->engine = NULL;
		$this->fid = 0;
		$this->hy = 0;
		$this->lastCheck = NULL;
		$this->lat = 0.0;
		$this->lng = 0.0;
		$this->map = NULL;
		$this->operating = true;
		$this->street = NULL;
		$this->type = NULL;
		$this->uuid = NULL;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
}