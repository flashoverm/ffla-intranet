<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="pageralert")
 */
class PagerAlert extends BaseModel {
    
    protected int $fid;
    
    /**
     * @ORM\Column(type="decimal")
     */
    protected float $lat;
    
    /**
     * @ORM\Column(type="decimal")
     */
    protected float $lng;
    
    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $alerted;
    
    /**
     * @ORM\Column(type="string")
     */
    protected ?string $manufacturer;

    
    /**
     * @return mixed
     */
    public function getFid() : int {
        return $this->fid;
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
    public function getAlerted() : bool {
        return $this->alerted;
    }
    
    /**
     * @return mixed
     */
    public function getManufacturer() : ?string {
        return $this->manufacturer;
    }
    
    /**
     * @param mixed $uuid
     */
    public function setFid(int $fid) {
        $this->fid = $fid;
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
     * @param mixed $alerted
     */
    public function setAlerted(bool $alerted) {
        $this->alerted = $alerted;
    }
    
    /**
     * @param mixed $manufacturer
     */
    public function setManufacturer(?string $manufacturer) {
        $this->manufacturer = $manufacturer;
    }
    
    
    /*
     **************************************************
     * Constructor
     */
    
    function __construct() {
        parent::__construct();
        $this->fid = -1;
        $this->lat = 0.0;
        $this->lng = 0.0;
        $this->operating = false;
        $this->manufacturer = null;
    }
    
    /*
     **************************************************
     * Custom Methods
     */
    
    public function jsonSerialize() {
        return [
            'fid' => $this->fid,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'alerted' => $this->alerted,
            'manufacturer' => $this->manufacturer
        ];
    }

}
    