<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="setting")
 */
class Setting extends BaseModel {
    
    protected string $key;
        
    protected string $description;
    
    public function getKey() : string {
        return $this->key;
    }
    
    public function getDescription() : string {
        return $this->description;
    }
        
    /*
     **************************************************
     * Constructor
     */
    
    function __construct($key, $description) {
        parent::__construct();
        $this->key = $key;
        $this->description = $description;
    }
    
    /*
     **************************************************
     * Custom Methods
     */
    
    public function jsonSerialize() {
        return [
            'key' => $this->key,
            'description' => $this->description,
        ];
    }
    
    
}