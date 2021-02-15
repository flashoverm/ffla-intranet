<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User extends BaseModel {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected ?string $uuid;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $email;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $password;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $firstname;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $lastname;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Engine")
	 * @ORM\JoinColumn(name="engine", referencedColumnName="uuid")
	 */
	protected ?Engine $engine;
	
	protected array $additionalEngines;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected bool $locked;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected bool $deleted;
	
	/**
	 * @ORM\Column(name="employer_address", type="string")
	 */
	protected ?string $employerAddress;
	
	/**
	 * @ORM\Column(name="employer_mail", type="string")
	 */
	protected ?string $employerMail;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Privilege", inversedBy="users")
	 * @ORM\JoinTable(name="user_privilege",
	 * 		joinColumns={@ORM\JoinColumn(name="user", referencedColumnName="uuid")},
	 * 		inverseJoinColumns={@ORM\JoinColumn(name="privilege", referencedColumnName="uuid")}
	 * )
	 */
	protected array $privileges;
	
	
	/**
	 * @return NULL
	 */
	public function getUuid() : ?string {
		return $this->uuid;
	}

	/**
	 * @return NULL
	 */
	public function getEmail() : ?string {
		return strtolower($this->email);
	}

	/**
	 * @return NULL
	 */
	public function getPassword() : ?string {
		return $this->password;
	}

	/**
	 * @return NULL
	 */
	public function getFirstname() : ?string {
		return $this->firstname;
	}

	/**
	 * @return NULL
	 */
	public function getLastname() : ?string {
		return $this->lastname;
	}

	/**
	 * @return NULL
	 */
	public function getEngine() : ?Engine {
		if( isset( $_SESSION["setEngine"] ) ){
			return $_SESSION["setEngine"];
		}
		return $this->engine;
	}
	
	/**
	 * @return NULL
	 */
	public function getMainEngine() : ?Engine {
		return $this->engine;
	}
	
	/**
	 * @return NULL
	 */
	public function getAdditionalEngines() : array {
		return $this->additionalEngines;
	}

	/**
	 * @return boolean
	 */
	public function getLocked() : bool {
		return $this->locked;
	}

	/**
	 * @return boolean
	 */
	public function getDeleted() : bool {
		return $this->deleted;
	}

	/**
	 * @return NULL
	 */
	public function getEmployerAddress() : ?string {
		return $this->employerAddress;
	}

	/**
	 * @return NULL
	 */
	public function getEmployerMail() : ?string {
		return strtolower($this->employerMail);
	}

	/**
	 * @return array
	 */
	public function getPrivileges() : array {
		return $this->privileges;
	}

	/**
	 * @param NULL $uuid
	 */
	public function setUuid(?string $uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param NULL $email
	 */
	public function setEmail(?string $email) {
		$this->email = strtolower($email);
	}

	/**
	 * @param NULL $password
	 */
	public function setPassword(?string $password) {
		$this->password = $password;
	}

	/**
	 * @param NULL $firstname
	 */
	public function setFirstname(?string $firstname) {
		$this->firstname = $firstname;
	}

	/**
	 * @param NULL $lastname
	 */
	public function setLastname(?string $lastname) {
		$this->lastname = $lastname;
	}

	/**
	 * @param NULL $engine
	 */
	public function setEngine(?Engine $engine) {
		$this->engine = $engine;
	}
	
	/**
	 * @param NULL array
	 */
	public function setAdditionalEngines(array $additionalEngines) {
		$this->additionalEngines = $additionalEngines;
	}

	/**
	 * @param boolean $locked
	 */
	public function setLocked(bool $locked) {
		$this->locked = $locked;
	}

	/**
	 * @param boolean $deleted
	 */
	public function setDeleted(bool $deleted) {
		$this->deleted = $deleted;
	}

	/**
	 * @param NULL $employerAddress
	 */
	public function setEmployerAddress(?string $employerAddress) {
		$this->employerAddress = $employerAddress;
	}

	/**
	 * @param NULL $employerMail
	 */
	public function setEmployerMail(?string $employerMail) {
		$this->employerMail = strtolower ($employerMail);
	}

	/**
	 * @param array $privileges
	 */
	public function setPrivileges(array $privileges) {
		$this->privileges = $privileges;
	}

	public function __construct() {
		parent::__construct();
		$this->uuid = NULL;
		$this->email = NULL;
		$this->password = NULL;
		$this->firstname = NULL;
		$this->lastname = NULL;
		$this->engine = NULL;
		$this->additionalEngines = array();
		$this->locked = false;
		$this->deleted = false;
		$this->employerAddress = NULL;
		$this->employerMail = NULL;
		$this->privileges = array();
	}
	
	
		
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function addAdditionalEngines($additionalEngine){
		$this->additionalEngines = $additionalEngine;
	}
	
	public function hasPrivilegeByName(string $privilegeName) : bool{
		if($this->privileges == null){
			return false;
		}
		foreach($this->privileges as $usersPriv){
			if($usersPriv->getPrivilege() == $privilegeName){
				return true;
			}
		}
		return false;
	}
	
	public function addPrivilege(Privilege $privilege){
		if($this->hasPrivilegeByName($privilege->getPrivilege())){
			return false;
		}
		$this->privileges[] = $privilege;
		return true;
	}
	
	public function removePrivilege(Privilege $privilege){
		if( ! $this->hasPrivilegeByName($privilege->privilege())){
			return false;
		}
		$this->privileges->detach($privilege);
		return true;
	}
	
	public function resetPrivileges(array $newPrivileges){
		$this->privileges = $newPrivileges;
	}
	
	public function getFullName() : string {
		return $this->firstname . " " . $this->lastname;
	}
	
	public function getFullNameWithEngine() : string {
		return $this->getFullName() . " (" . $this->engine->getName() . ")";
	}
	
	public function getFullNameWithEmail() : string {
		return $this->getFullName() . " (" . $this->email . ")";
	}
	
	public function setUserData(string $firstname, string $lastname, string $email, Engine $engine, string $employerAddress, string $employerMail){
		$this->setFirstname($firstname);
		$this->setLastname($lastname);
		$this->setEmail($email);
		$this->setEmployerAddress($employerAddress);
		$this->setEmployerMail($employerMail);
		$this->setEngine($engine);
	}
	
	public function toJson() : string{
		$vars = get_object_vars($this);
		$vars['engine'] = json_decode($this->engine->toJson());
		return json_encode($vars, JSON_UNESCAPED_UNICODE);
	}
}

?>