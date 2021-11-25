<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="token")
 */
class Token extends BaseModel {
	
	const MailVerification = 1;		//Action: Enable Login / do not delete user
	const ResetPassword = 2;		//Action: Send new password to users mail
	
	const TOKEN_TYPES = array(
			1 => "E-Mail-Verifikation",
			2 => "Passwort zurücksetzen",
	);
	
	//Minutes
	const TOKEN_VALIDITY = array(
			1 => 720,
			2 => 30,
	);
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected ?string $uuid;
	
	protected int $type;
	
	protected ?string $token;
	
	protected $validUntil;
	
	protected ?User $user;
	
	
	/**
	 * @return mixed
	 */
	public function getUuid() {
		return $this->uuid;
	}

	/**
	 * @return mixed
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @return mixed
	 */
	public function getToken() {
		return $this->token;
	}

	/**
	 * @return mixed
	 */
	public function getValidUntil() {
		return $this->validUntil;
	}

	/**
	 * @return mixed
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid($uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @param mixed $token
	 */
	public function setToken($token) {
		$this->token = $token;
	}

	/**
	 * @param mixed $validUntil
	 */
	public function setValidUntil($validUntil) {
		$this->validUntil = $validUntil;
	}

	/**
	 * @param mixed $user
	 */
	public function setUser($user) {
		$this->user = $user;
	}
	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct() {
		parent::__construct();
		$this->uuid = null;
		$this->validUntil = null;
		$this->token = null;
		$this->type = 0;
		$this->user = null;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function jsonSerialize() {
		return [
				'uuid' => $this->uuid,
				'validUntil' => $this->validUntil,
				'token' => $this->token,
				'type' => $this->type,
		];
	}
	
	function initialize(int $type, $user){
		$this->type = $type;
		$this->user = $user;
		
		$token = openssl_random_pseudo_bytes(16);
		
		$this->token = bin2hex($token);
		
		$validity = Token::TOKEN_VALIDITY[$this->getType()];
		$validUntilTime = strtotime('+ ' . $validity . ' minutes');
		
		$this->validUntil = date('Y-m-d H:i:s', $validUntilTime);
	}
	
	function isValid(){
		$now = date('Y-m-d H:i:s');
		if($this->validUntil > $now ){
			return true;
		}
		return false;
	}

}