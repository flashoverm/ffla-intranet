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
	protected $uuid;
	
	/**
	 * @ORM\Column(type="int")
	 */
	protected $fid;
	
	/**
	 * @ORM\Column(type="int")
	 */
	protected $hy;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $street;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $district;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $type;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $checkByFF;
	
	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $operating;
	
	/**
	 * @ORM\Column(type="decimal")
	 */
	protected $lat;
	
	/**
	 * @ORM\Column(type="decimal")
	 */
	protected $lng;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Engine")
	 * @ORM\JoinColumn(name="engine", referencedColumnName="uuid")
	 */
	protected $engine;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $map;
	
	/**
	 * @ORM\Column(type="smallint")
	 */
	protected $cycle;
	
	
	protected $lastCheck;
	
	
	
	function __construct() {
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
	public function getFid() {
		return $this->fid;
	}

	/**
	 * @return mixed
	 */
	public function getHy() {
		return $this->hy;
	}

	/**
	 * @return mixed
	 */
	public function getStreet() {
		return $this->street;
	}

	/**
	 * @return mixed
	 */
	public function getDistrict() {
		return $this->district;
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
	public function getCheckByFF() {
		return $this->checkByFF;
	}

	/**
	 * @return mixed
	 */
	public function getOperating() {
		return $this->operating;
	}

	/**
	 * @return mixed
	 */
	public function getLat() {
		return $this->lat;
	}

	/**
	 * @return mixed
	 */
	public function getLng() {
		return $this->lng;
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
	public function getMap() {
		return $this->map;
	}

	/**
	 * @return mixed
	 */
	public function getCycle() {
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
	public function setUuid($uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $fid
	 */
	public function setFid($fid) {
		$this->fid = $fid;
	}

	/**
	 * @param mixed $hy
	 */
	public function setHy($hy) {
		$this->hy = $hy;
	}

	/**
	 * @param mixed $street
	 */
	public function setStreet($street) {
		$this->street = $street;
	}

	/**
	 * @param mixed $district
	 */
	public function setDistrict($district) {
		$this->district = $district;
	}

	/**
	 * @param mixed $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @param mixed $checkByFF
	 */
	public function setCheckByFF($checkByFF) {
		$this->checkByFF = $checkByFF;
	}

	/**
	 * @param mixed $operating
	 */
	public function setOperating($operating) {
		$this->operating = $operating;
	}

	/**
	 * @param mixed $lat
	 */
	public function setLat($lat) {
		$this->lat = $lat;
	}

	/**
	 * @param mixed $lng
	 */
	public function setLng($lng) {
		$this->lng = $lng;
	}

	/**
	 * @param mixed $engine
	 */
	public function setEngine($engine) {
		$this->engine = $engine;
	}

	/**
	 * @param mixed $map
	 */
	public function setMap($map) {
		$this->map = $map;
	}

	/**
	 * @param mixed $cycle
	 */
	public function setCycle($cycle) {
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
	 * Custom Methods
	 */
	
	public function setHydrantData($hy, $fid, $lat, $lng, $street, $district, $type, $engine, $checkbyff, $operating) {
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