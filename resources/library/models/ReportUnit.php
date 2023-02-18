<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="report_unit")
 */
class ReportUnit extends BaseModel {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="string")
	 */
	protected ?string $uuid;

	protected $date;
	
	protected $startTime;
	
	protected $endTime;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $unitName;
	
	/**
	 * @ORM\Column(type="int")
	 */
	protected int $km;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $reportUuid;
	
	protected array $staff;
	
	
	/**
	 * @return mixed
	 */
	public function getUuid() : ?string {
		return $this->uuid;
	}

	/**
	 * @return mixed
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @return mixed
	 */
	public function getStartTime() {
		return $this->startTime;
	}

	/**
	 * @return mixed
	 */
	public function getEndTime() {
		return $this->endTime;
	}

	/**
	 * @return mixed
	 */
	public function getUnitName() : ?string  {
		return $this->unitName;
	}

	/**
	 * @return mixed
	 */
	public function getKm() : int {
		return $this->km;
	}
	
	/**
	 * @return mixed
	 */
	public function getReportUuid() : ?string  {
		return $this->reportUuid;
	}

	/**
	 * @return mixed
	 */
	public function getStaff() : array {
		return $this->staff;
	}

	/**
	 * @param mixed $uuid
	 */
	public function setUuid(?string $uuid) {
		$this->uuid = $uuid;
	}

	/**
	 * @param mixed $date
	 */
	public function setDate($date) {
		$this->date = $date;
	}

	/**
	 * @param mixed $startTime
	 */
	public function setStartTime($startTime) {
		$this->startTime = $startTime;
	}

	/**
	 * @param mixed $endTime
	 */
	public function setEndTime($endTime) {
		$this->endTime = $endTime;
	}

	/**
	 * @param mixed $unit
	 */
	public function setUnitName(?string $unitName) {
		$this->unitName = $unitName;
	}

	/**
	 * @param mixed $km
	 */
	public function setKm(int $km) {
		$this->km = $km;
	}
	
	/**
	 * @param mixed $uuid
	 */
	public function setReportUuid(?string $reportUuid) {
		$this->reportUuid = $reportUuid;
	}

	/**
	 * @param mixed $staff
	 */
	public function setStaff($staff) {
		$this->staff = $staff;
	}

	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct($unitName, $date, $startTime, $endTime) {
		parent::__construct();
		$this->date = $date;
		$this->endTime = $endTime;
		$this->startTime = $startTime;
		$this->km = 0;
		$this->staff = array();
		$this->unitName = $unitName;
		$this->uuid = NULL;
		$this->reportUuid = NULL;
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function jsonSerialize() {
		return [
				'date' => $this->date,
				'endTime' => $this->endTime,
				'startTime' => $this->startTime,
				'km' => $this->km,
				'staff' => $this->staff,
				'unitName' => $this->unitName,
				'uuid' => $this->uuid,
				'reportUuid' => $this->reportUuid,
		];
	}
	
	public function addStaff(ReportStaff $reportStaff){
		$this->staff[] = $reportStaff;
	}
	
	public function getUnitOperatingMinutes(){
	    $endSeconds =  (strtotime($this->getEndTime()) - strtotime('TODAY'));
	    $startSeconds = (strtotime($this->getStartTime()) - strtotime('TODAY'));
	    if($endSeconds < $startSeconds){
	        $endSeconds += 24*60*60;
	    }
	    return ($endSeconds - $startSeconds)/60;
	}
}