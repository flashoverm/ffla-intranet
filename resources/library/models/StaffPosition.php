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
	protected ?string $uuid;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $position;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected int $listIndex;
	
	
	/**
	 * @return mixed
	 */
	public function getUuid() : ?string {
		return $this->uuid;
	}

	/**
	 * @return mixed
	 */
	public function getPosition() : ?string {
		return $this->position;
	}

	/**
	 * @return mixed
	 */
	public function getListIndex() : int {
		return $this->listIndex;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid(?string $uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $position
	 */
	public function setPosition(?string $position) {
		$this->position = $position;
	}

	/**
	 * @param mixed $listIndex
	 */
	public function setListIndex(int $listIndex) {
		$this->listIndex = $listIndex;
	}

	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct(?string $uuid, ?string $position, int $listIndex) {
		$this->uuid = $uuid;
		$this->position = $position;
		$this->listIndex = $listIndex;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function jsonSerialize() {
		return [
				'uuid' => $this->uuid,
				'position' => $this->position,
				'listIndex' => $this->listIndex,
		];
	}
}