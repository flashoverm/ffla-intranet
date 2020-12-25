<?php

//use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="hydrant_inspection")
 */
class InspectedHydrant extends BaseModel {
	
	const HYDRANTCRITERIA = array (
			array(0, "Adresse in Ordnung"),
			array(1,"Kein Mangel"),
			
			array(2,"Hinweisschild fehlt"),
			array(3,"Hinweisschild verdeckt<br> (Grund s. Hinweise)"),
			array(4,"Hinweisschild: Angaben stimmen nicht<br> (Richtige Angabe s. Hinweise)"),
			array(5,"Deckel lässt sich nicht öffnen"),
			array(6,"Deckel defekt"),
			array(7,"Deckelgelenk defekt"),
			array(8,"Schmutzsicherung Gummi defekt/fehlt"),
			array(9,"Hydrant innen erheblich verschmutzt<br> Reiningung erforderlich"),
			array(10,"Hydrant undicht (Wasser im Schacht)"),
			array(11,"Schutzkappte fehlt"),
			array(12,"Ventilspindel lässt sich nicht drehen"),
			array(13,"Klaue abgebrochen"),
			array(14,"Standrohr kann nicht aufgesetzt werden"),
			array(15,"Hydrant entleert nicht"),
			array(16,"Ventilspindel geht leer durch"),
			array(17,"Vierkant abgebrochen"),
			array(18,"Hydrant lässt sich nicht dicht schließen"),
			array(19,"Hydrant nicht gefunden"),
			array(20,"Hydrant überteert"),
	);
	
	/**
	 * @ORM\ManyToOne(targetEntity="Hydrant")
	 * @ORM\JoinColumn(name="hydrant", referencedColumnName="uuid")
	 */
	protected $hydrant;
	
	/**
	 * @ORM\Column(type="idx")
	 */
	protected $index;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $type;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected $criteria;
	
	
	
	function __construct(){
		$this->criteria = array();
	}
	
	
	
	/**
	 * @return mixed
	 */
	public function getHydrant() {
		return $this->hydrant;
	}

	/**
	 * @return mixed
	 */
	public function getIndex() {
		return $this->index;
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
	public function getCriteria() {
		return $this->criteria;
	}

	/**
	 * @param mixed $hydrant
	 */
	public function setHydrant($hydrant) {
		$this->hydrant = $hydrant;
	}

	/**
	 * @param mixed $index
	 */
	public function setIndex($index) {
		$this->index = $index;
	}

	/**
	 * @param mixed $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * @param mixed $criteria
	 */
	public function setCriteria($criteria) {
		$this->criteria = $criteria;
	}
	
	
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	
	public function addCriterion($hydrantIndex, $criterionIndex, $value){
		$this->criteria[] = array(
				"hy_idx" => $hydrantIndex,
				"idx" => $criterionIndex,
				"value" => $value
		);
	}
}