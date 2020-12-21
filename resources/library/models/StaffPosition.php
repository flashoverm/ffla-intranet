<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="staffposition")
 */
class StaffPosition extends BaseModel {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected $uuid;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $position;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected $listIndex;
	
	
	function __construct($uuid, $position, $listIndex) {
		$this->uuid = $uuid;
		$this->position = $position;
		$this->listIndex = $listIndex;
	}
	
	
	/**
	 * @return mixed
	 */
	public function getUuid() {
		return $this->uuid;
	}

	/**
	 * @return mixed
	 */
	public function getPosition() {
		return $this->position;
	}

	/**
	 * @return mixed
	 */
	public function getListIndex() {
		return $this->listIndex;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid($uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $position
	 */
	public function setPosition($position) {
		$this->position = $position;
	}

	/**
	 * @param mixed $listIndex
	 */
	public function setListIndex($listIndex) {
		$this->listIndex = $listIndex;
	}

}