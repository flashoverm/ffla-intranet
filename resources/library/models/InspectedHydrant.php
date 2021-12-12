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
	protected ?Hydrant $hydrant;
	
	/**
	 * @ORM\Column(type="int")
	 */
	protected int $index;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected ?string $type;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected array $criteria;
	
	/**
	 * @return mixed
	 */
	public function getHydrant() : ?Hydrant {
		return $this->hydrant;
	}

	/**
	 * @return mixed
	 */
	public function getIndex() : int {
		return $this->index;
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
	public function getCriteria() : array {
		return $this->criteria;
	}

	/**
	 * @param mixed $hydrant
	 */
	public function setHydrant(?Hydrant $hydrant) {
		$this->hydrant = $hydrant;
	}

	/**
	 * @param mixed $index
	 */
	public function setIndex(int $index) {
		$this->index = $index;
	}

	/**
	 * @param mixed $type
	 */
	public function setType(?string $type) {
		$this->type = $type;
	}

	/**
	 * @param mixed $criteria
	 */
	public function setCriteria(array $criteria) {
		$this->criteria = $criteria;
	}
	
	/*
	 **************************************************
	 * Constructor
	 */
	
	function __construct(){
		parent::__construct();
		$this->hydrant = NULL;
		$this->index = 0;
		$this->type = NULL;
		$this->criteria = array();
	}
	
	/*
	 **************************************************
	 * Custom Methods
	 */
	
	public function jsonSerialize() {
		return [
				'hydrant' => $this->hydrant,
				'index' => $this->index,
				'type' => $this->type,
				'criteria' => $this->criteria,
		];
	}
	
	public function addCriterion(int $hydrantIndex, int $criterionIndex, bool $value){
		$this->criteria[] = array(
				"hy_idx" => $hydrantIndex,
				"idx" => $criterionIndex,
				"value" => $value
		);
	}
}