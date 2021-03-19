<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_privilege")
 */
class UserPrivilege extends BaseModel {
	
	protected ?Engine $engine;
	
	protected ?Privilege $privilege;
	
	protected ?string $userUuid;
	
	
	/**
	 * @return NULL
	 */
	public function getEngine() : ?Engine {
		return $this->engine;
	}

	/**
	 * @return NULL
	 */
	public function getPrivilege() : ?Privilege {
		return $this->privilege;
	}

	/**
	 * @return NULL
	 */
	public function getUserUuid() : ?string {
		return $this->userUuid;
	}

	/**
	 * @param NULL $engine
	 */
	public function setEngine(Engine $engine) {
		$this->engine = $engine;
	}

	/**
	 * @param NULL $privilege
	 */
	public function setPrivilege(Privilege $privilege) {
		$this->privilege = $privilege;
	}

	/**
	 * @param NULL $userUuid
	 */
	public function setUserUuid(string $userUuid) {
		$this->userUuid = $userUuid;
	}

	public function __construct(Engine $engine, Privilege $privilege, string $userUuid) {
		$this->engine = $engine;
		$this->privilege = $privilege;
		$this->userUuid = $userUuid;
	}
	
}