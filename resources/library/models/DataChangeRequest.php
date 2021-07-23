<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="datachangerequest")
 */
class DataChangeRequest extends BaseModel {
	
	const Address = 1;
	const PhonePrivate = 2;
	const PhoneBusiness = 3;
	const Mobile = 4;
	const EMailPrivate = 5;
	const EMailBusiness = 6;
	const Function = 7;
	const EngineUnit = 8;
	const Other = 9;
	
	const DATATYPE_TEXT = array (
			1 => "Adresse (Straße, Haus-Nr, PLZ, Ort)",
			2 => "Telefon (Privat)",
			3 => "Telefon (Geschäftlich)",
			4 => "Handynummer",
			5 => "E-Mail (Privat)",
			6 => "E-Mail (Geschäftlich)",
			7 => "Funktion (Kassier, Zugführer, ...)",
			8 => "Einheit (z.B. Ein-/Austritt Löschzug/KEZ/UG-ÖEL/Sanitäter/...)",
			9 => "Sonstige Daten",
	);
	
	const OPEN = 1;
	const DONE = 2;
	const DECLINED = 3;
	const REQUEST = 4;
	
	const CONFIRMATION_STATES = array(
			
			1 => "Offen",
			2 => "Erledigt",
			3 => "Abgelehnt",
			4 => "Rückfragen",
	);
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected ?string $uuid;
	
	protected $createDate;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected int $datatype; 
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected int $state;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $newValue;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $comment;
	
	protected ?User $user;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $person;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $furtherRequest;
	
	protected ?User $lastAdvisor;
	
	protected $lastUpdate;

	
	/**
	 * @return NULL
	 */
	public function getUuid() {
		return $this->uuid;
	}

	/**
	 * @return number
	 */
	public function getDatatype() {
		return $this->datatype;
	}

	/**
	 * @return number
	 */
	public function getState() {
		return $this->state;
	}

	/**
	 * @return NULL
	 */
	public function getNewValue() {
		return $this->newValue;
	}

	/**
	 * @return NULL
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * @return NULL
	 */
	public function getUser() : ?User {
		return $this->user;
	}
	
	/**
	 * @return NULL
	 */
	public function getPerson() : ?string {
		return $this->person;
	}
	
	/**
	 * @return NULL
	 */
	public function getFurtherRequest() : ?string {
		return $this->furtherRequest;
	}

	/**
	 * @return NULL
	 */
	public function getLastAdvisor() : ?User {
		return $this->lastAdvisor;
	}
	
	/**
	 * @return NULL
	 */
	public function getCreateDate() {
		return $this->createDate;
	}
	
	public function getLastUpdate() {
		return $this->lastUpdate;
	}

	/**
	 * @param NULL $uuid
	 */
	public function setUuid($uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param number $datatype
	 */
	public function setDatatype($datatype) {
		$this->datatype = $datatype;
	}

	/**
	 * @param number $state
	 */
	public function setState($state) {
		$this->state = $state;
	}

	/**
	 * @param NULL $newValue
	 */
	public function setNewValue($newValue) {
		$this->newValue = $newValue;
	}

	/**
	 * @param NULL $comment
	 */
	public function setComment($comment) {
		$this->comment = $comment;
	}
	
	/**
	 * @param NULL $comment
	 */
	public function setUser(?User $user) {
		$this->user = $user;
	}

	/**
	 * @param NULL $user
	 */
	public function setPerson(?string $person) {
		$this->person = $person;
	}
	
	/**
	 * @param NULL $user
	 */
	public function setFurtherRequest(?string $furtherRequest) {
		$this->furtherRequest = $furtherRequest;
	}

	/**
	 * @param NULL $lastAdvisor
	 */
	public function setLastAdvisor($lastAdvisor) {
		$this->lastAdvisor = $lastAdvisor;
	}
	
	/**
	 * @param NULL $created
	 */
	public function setCreateDate($createDate) {
		$this->createDate = $createDate;
	}
	
	public function setLastUpdate($lastUpdate) {
		$this->lastUpdate = $lastUpdate;
	}
	
	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct() {
		parent::__construct();
		$this->comment = NULL;
		$this->createDate = NULL;
		$this->datatype = 0;
		$this->lastAdvisor = NULL;
		$this->newValue = NULL;
		$this->state = 0;
		$this->person = NULL;
		$this->uuid = NULL;
		$this->user = NULL;
		$this->furtherRequest = NULL;
		$this->lastUpdate = NULL;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	function setDataChangeRequestData($dataType, $newValue, $comment, $person, $user){
		$this->setDatatype($dataType);
		$this->setNewValue($newValue);
		$this->setComment($comment);
		$this->setUser($user);
		$this->setPerson($person);
	}
	
}