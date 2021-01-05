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
	
	const CONFIRMATION_STATES = array(
			
			1 => "Offen",
			2 => "Erledigt",
			3 => "Abgelehnt",
	);
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected ?string $uuid;
	
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
	
	protected ?User $lastAdvisor;

	
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
	public function getLastAdvisor() : ?User {
		return $this->lastAdvisor;
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
	 * @param NULL $user
	 */
	public function setUser($user) {
		$this->user = $user;
	}

	/**
	 * @param NULL $lastAdvisor
	 */
	public function setLastAdvisor($lastAdvisor) {
		$this->lastAdvisor = $lastAdvisor;
	}
	
	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct() {
		parent::__construct();
		$this->comment = NULL;
		$this->datatype = 0;
		$this->lastAdvisor = NULL;
		$this->newValue = NULL;
		$this->state = 0;
		$this->user = NULL;
		$this->uuid = NULL;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	
	
}