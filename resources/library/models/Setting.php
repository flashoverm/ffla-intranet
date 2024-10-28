<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="setting")
 */
class Setting extends BaseModel {
    
    const CAT_EVENTS = "Wachverwaltung";
    const CAT_CONFIRMATION = "Arbeitgeberbestätigungen";
    
    
    protected string $key;
        
    protected string $description;
    
    protected string $category;
    
    protected ?string $privilege;
    
    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getPrivilege()
    {
        return $this->privilege;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @param string $privilege
     */
    public function setPrivilege($privilege)
    {
        $this->privilege = $privilege;
    }

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
    
    function __construct($key, $description, $category, $privilege) {
        parent::__construct();
        $this->key = $key;
        $this->description = $description;
        $this->category = $category;
        $this->privilege = $privilege;
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