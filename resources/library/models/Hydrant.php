<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="hydrant")
 */
class Hydrant extends BaseModel {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected ?string $uuid;
	
	/**
	 * @ORM\Column(type="int")
	 */
	protected int $fid;
	
	/**
	 * @ORM\Column(type="int")
	 */
	protected int $hy;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $street;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $district;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $type;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected bool $checkByFF;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected bool $operating;
	
	/**
	 * @ORM\Column(type="decimal")
	 */
	protected float $lat;
	
	/**
	 * @ORM\Column(type="decimal")
	 */
	protected float $lng;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Engine")
	 * @ORM\JoinColumn(name="engine", referencedColumnName="uuid")
	 */
	protected ?Engine $engine;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $map;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected int $cycle;
	
	
	protected $lastCheck;
	

	/**
	 * @return mixed
	 */
	public function getUuid() : ?string {
		return $this->uuid;
	}

	/**
	 * @return mixed
	 */
	public function getFid() : int {
		return $this->fid;
	}

	/**
	 * @return mixed
	 */
	public function getHy() : int {
		return $this->hy;
	}

	/**
	 * @return mixed
	 */
	public function getStreet() : ?string {
		return $this->street;
	}

	/**
	 * @return mixed
	 */
	public function getDistrict() : ?string {
		return $this->district;
	}

	/**
	 * @return mixed
	 */
	public function getType() : ?string {
		return $this->type;
	}

	/**
	 * @return mixed
	 */
	public function getCheckByFF() : bool {
		return $this->checkByFF;
	}

	/**
	 * @return mixed
	 */
	public function getOperating() : bool {
		return $this->operating;
	}

	/**
	 * @return mixed
	 */
	public function getLat() : float {
		return $this->lat;
	}

	/**
	 * @return mixed
	 */
	public function getLng() : float {
		return $this->lng;
	}

	/**
	 * @return mixed
	 */
	public function getEngine() : ?Engine {
		return $this->engine;
	}

	/**
	 * @return mixed
	 */
	public function getMap() : ?string {
		return $this->map;
	}

	/**
	 * @return mixed
	 */
	public function getCycle() : int {
		return $this->cycle;
	}
	
	/**
	 * @return mixed
	 */
	public function getLastCheck() {
		return $this->lastCheck;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid(?string $uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $fid
	 */
	public function setFid(int $fid) {
		$this->fid = $fid;
	}

	/**
	 * @param mixed $hy
	 */
	public function setHy(int $hy) {
		$this->hy = $hy;
	}

	/**
	 * @param mixed $street
	 */
	public function setStreet(?string $street) {
		$this->street = $street;
	}

	/**
	 * @param mixed $district
	 */
	public function setDistrict(?string $district) {
		$this->district = $district;
	}

	/**
	 * @param mixed $type
	 */
	public function setType(?string $type) {
		$this->type = $type;
	}

	/**
	 * @param mixed $checkByFF
	 */
	public function setCheckByFF(bool $checkByFF) {
		$this->checkByFF = $checkByFF;
	}

	/**
	 * @param mixed $operating
	 */
	public function setOperating(bool $operating) {
		$this->operating = $operating;
	}

	/**
	 * @param mixed $lat
	 */
	public function setLat(float $lat) {
		$this->lat = $lat;
	}

	/**
	 * @param mixed $lng
	 */
	public function setLng(float $lng) {
		$this->lng = $lng;
	}

	/**
	 * @param mixed $engine
	 */
	public function setEngine(?Engine $engine) {
		$this->engine = $engine;
	}

	/**
	 * @param mixed $map
	 */
	public function setMap(?string $map) {
		$this->map = $map;
	}

	/**
	 * @param mixed $cycle
	 */
	public function setCycle(int $cycle) {
		$this->cycle = $cycle;
	}
	
	/**
	 * @param mixed $cycle
	 */
	public function setLastCheck($lastCheck) {
		$this->lastCheck = $lastCheck;
	}
	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct() {
		parent::__construct();
		$this->checkByFF = true;
		$this->cycle = 0;
		$this->district = NULL;
		$this->engine = NULL;
		$this->fid = 0;
		$this->hy = 0;
		$this->lastCheck = NULL;
		$this->lat = 0.0;
		$this->lng = 0.0;
		$this->map = NULL;
		$this->operating = true;
		$this->street = NULL;
		$this->type = NULL;
		$this->uuid = NULL;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function setHydrantData(int $hy, int $fid, float $lat, float $lng, ?string $street, ?string $district, ?string $type, ?Engine $engine, bool $checkbyff, bool $operating) {
		$this->setHy($hy);
		$this->setFid($fid);
		$this->setLat($lat);
		$this->setLng($lng);
		$this->setStreet($street);
		$this->setDistrict($district);
		$this->setType($type);
		$this->setEngine($engine);
		$this->setCheckByFF($checkbyff);
		$this->setOperating($operating);
	}
}