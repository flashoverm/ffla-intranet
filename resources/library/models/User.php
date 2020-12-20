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
	protected $uuid;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $email;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $password;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $firstname;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $lastname;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Engine")
	 * @ORM\JoinColumn(name="engine", referencedColumnName="uuid")
	 */
	protected $engine;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $locked;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $deleted;
	
	/**
	 * @ORM\Column(name="employer_address", type="string")
	 */
	protected $employerAddress;
	
	/**
	 * @ORM\Column(name="employer_mail", type="string")
	 */
	protected $employerMail;
	
	/**
	 * @ORM\ManyToMany(targetEntity="Privilege", inversedBy="users")
	 * @ORM\JoinTable(name="user_privilege",
	 * 		joinColumns={@ORM\JoinColumn(name="user", referencedColumnName="uuid")},
	 * 		inverseJoinColumns={@ORM\JoinColumn(name="privilege", referencedColumnName="uuid")}
	 * )
	 */
	protected $privileges;
	/**
	 * @return mixed
	 */
	public function getUuid() {
		return $this->uuid;
	}
	
	/**
	 * @return mixed
	 */
	public function getEmail() {
		return strtolower( $this->email );
	}
	
	/**
	 * @return mixed
	 */
	public function getPassword() {
		return $this->password;
	}
	
	/**
	 * @return mixed
	 */
	public function getFirstname() {
		return $this->firstname;
	}
	
	/**
	 * @return mixed
	 */
	public function getLastname() {
		return $this->lastname;
	}
	
	/**
	 * @return mixed
	 */
	public function getEngine() {
		return $this->engine;
	}
	
	/**
	 * @return mixed
	 */
	public function getLocked() {
		return $this->locked;
	}
	
	/**
	 * @return mixed
	 */
	public function getDeleted() {
		return $this->deleted;
	}
	
	/**
	 * @return mixed
	 */
	public function getEmployerAddress() {
		return $this->employerAddress;
	}
	
	/**
	 * @return mixed
	 */
	public function getEmployerMail() {
		return strtolower( $this->employerMail );
	}
	
	/**
	 * @return mixed
	 */
	public function getPrivileges() {
		if($this->privileges == NULL){
			return array();
		}
		return $this->privileges;
	}
	
	/**
	 * @param mixed $uuid
	 */
	public function setUuid($uuid) {
		$this->uuid = $uuid;
	}
	
	/**
	 * @param mixed $email
	 */
	public function setEmail($email) {
		$this->email = strtolower( $email );
	}
	
	/**
	 * @param mixed $password
	 */
	public function setPassword($password) {
		$this->password = $password;
	}
	
	/**
	 * @param mixed $firstname
	 */
	public function setFirstname($firstname) {
		$this->firstname = $firstname;
	}
	
	/**
	 * @param mixed $lastname
	 */
	public function setLastname($lastname) {
		$this->lastname = $lastname;
	}
	
	/**
	 * @param mixed $engine
	 */
	public function setEngine($engine) {
		$this->engine = $engine;
	}
	
	/**
	 * @param mixed $locked
	 */
	public function setLocked($locked) {
		$this->locked = $locked;
	}
	
	/**
	 * @param mixed $deleted
	 */
	public function setDeleted($deleted) {
		$this->deleted = $deleted;
	}
	
	/**
	 * @param mixed $employerAddress
	 */
	public function setEmployerAddress($employerAddress) {
		$this->employerAddress = $employerAddress;
	}
	
	/**
	 * @param mixed $employerMail
	 */
	public function setEmployerMail($employerMail) {
		$this->employerMail = strtolower( $employerMail );
	}
	
	/**
	 * @param mixed $privileges
	 */
	public function setPrivileges($privileges) {
		$this->privileges = $privileges;
	}
	
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function hasPrivilegeByName($privilegeName){
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
	
	public function addPrivilege($privilege){
		if($this->hasPrivilegeByName($privilege->getPrivilege())){
			return;
		}
		$this->privileges[] = $privilege;
	}
	
	public function removePrivilege($privilege){
		if( ! $this->hasPrivilegeByName($privilege->privilege())){
			return;
		}
		$this->privileges->detach($privilege);
	}
	
	public function resetPrivileges($newPrivileges){
		$this->privileges = $newPrivileges;
	}
	
	public function getFullName(){
		return $this->firstname . " " . $this->lastname;
	}
	
	public function getFullNameWithEngine(){
		return $this->getFullName() . " (" . $this->engine->getName() . ")";
	}
	
	public function getFullNameWithEmail(){
		return $this->getFullName() . " (" . $this->email . ")";
	}
	
	public function setUserData($firstname, $lastname, $email, $engine, $employerAddress, $employerMail){
		$this->setFirstname($firstname);
		$this->setLastname($lastname);
		$this->setEmail($email);
		$this->setEmployerAddress($employerAddress);
		$this->setEmployerMail($employerMail);
		$this->setEngine($engine);
	}
	
	public function toJson(){
		$vars = get_object_vars($this);
		$vars['engine'] = json_decode($this->engine->toJson());
		return json_encode($vars, JSON_UNESCAPED_UNICODE);
	}
}

?>