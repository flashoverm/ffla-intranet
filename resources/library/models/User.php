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
	
	protected $lastLogin;
		
	/**
	 * @ORM\Column(name="employer_address", type="string")
	 */
	protected ?string $employerAddress;
	
	/**
	 * @ORM\Column(name="employer_mail", type="string")
	 */
	protected ?string $employerMail;
	
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
		if( getCurrentUserUUID() == $this->uuid && isset( $_SESSION["setEngine"] ) ){
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
	 * @return mixed
	 */
	public function getLastLogin() {
		return $this->lastLogin;
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
	 * @param mixed $lastLogin
	 */
	public function setLastLogin($lastLogin) {
		$this->lastLogin = $lastLogin;
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
		$this->lastLogin = NULL;
		$this->employerAddress = NULL;
		$this->employerMail = NULL;
		$this->privileges = array();
	}
	
	
		
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function hasEngine(Engine $engine){
		if($this->getMainEngine()->getUuid() == $engine->getUuid()){
			return true;
		}
		return $this->hasAdditionalEngine($engine);
	}
	
	public function hasAdditionalEngine(Engine $engine){
		foreach( $this->getAdditionalEngines() as $additionalEngine){
			if($additionalEngine->getUuid() == $engine->getUuid()){
				return true;
			}
		}
		return false;
	}
	
	public function addAdditionalEngine(Engine $additionalEngine){
		$this->additionalEngines[] = $additionalEngine;
	}
	
	public function removeAdditionalEngine(Engine $additionalEngine){
		$this->clearPrivilegesForEngine($additionalEngine);
		
		if (($key = array_search($additionalEngine, $this->additionalEngines)) !== false) {
			unset($this->additionalEngines[$key]);
		}
	}
	
	public function hasPrivilegeByName(string $privilegeName) : bool{
		return $this->hasPrivilegeForEngineByName($this->getEngine(), $privilegeName);
	}
	
	public function hasPrivilegeForEngineByName(Engine $engine, string $privilegeName){
		if($this->privileges == null){
			return false;
		}
		foreach($this->privileges as $usersPriv){
			if($usersPriv->getEngine()->getUuid() == $engine->getUuid()
					&& $usersPriv->getPrivilege()->getPrivilege() == $privilegeName){
				return true;
			}
		}
		return false;
	}
	
	public function addPrivilegeForEngine(Engine $engine, Privilege $privilege){
		if($this->hasPrivilegeForEngineByName($engine, $privilege->getPrivilege())){
			return false;
		}
		$this->privileges[] = new UserPrivilege($engine, $privilege, $this->uuid);
		return true;
	}
	
	public function resetPrivilegeForEngine(Engine $engine, $newPrivileges){
		$this->clearPrivilegesForEngine($engine);
		foreach($newPrivileges as $newPrivilege){
			$this->addPrivilegeForEngine($engine, $newPrivilege);
		}
	}
	
	public function clearPrivilegesForEngine(Engine $engine){
		foreach($this->privileges as $index => $usersPriv){
			if($usersPriv->getEngine()->getUuid() == $engine->getUuid()){
				unset($this->privileges[$index]);
			}
		}
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
	
	public function jsonSerialize() {
		return [
				'uuid' => $this->uuid,
				'email' => $this->email,
				'firstname' => $this->firstname,
				'lastname' => $this->lastname,
				'engine' => $this->engine,
				'additionalEngines' => $this->additionalEngines,
				'locked' => $this->locked,
				'deleted' => $this->deleted,
				'lastLogin' => $this->lastLogin,
				'employerAddress' => $this->employerAddress,
				'employerMail' => $this->employerMail,
				'privileges' => $this->privileges,
				
		];
	}
}

?>