<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User {
	
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
	 * @return mixed
	 */
	public function getUuid() {
		return $this->uuid;
	}

	/**
	 * @return mixed
	 */
	public function getEmail() {
		return $this->email;
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
		return $this->employerMail;
	}

	/**
	 * @return mixed
	 */
	public function getPrivileges() {
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
		$this->email = $email;
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
		$this->employerMail = $employerMail;
	}

}

?>