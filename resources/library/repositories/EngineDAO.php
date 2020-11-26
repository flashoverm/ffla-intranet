<?php 

require_once "BaseDAO.php";

use Doctrine\ORM\EntityManager;

class EngineDAO extends BaseDAO{
	
	protected $engineRepository;
	
	function __construct(EntityManager $entityManager) {
		parent::__construct($entityManager);
		$this->engineRepository = $entityManager->getRepository('Engine');
	}
	
	static function initializeEngines(){
		//TODO change to objects -> persist
		insert_engine ("9BEECEFA-56CF-A009-0059-99DAA5FA0D4E", "Verwaltung", true );
		insert_engine ("2BAA144B-F946-1524-E60E-7DD485FE1881", "Löschzug 1/2", false );
		insert_engine ("9704558C-9A89-A5B0-7CDE-0321A518DCB1", "Löschzug 3", false );
		insert_engine ("B0C263B5-6416-B8F5-B7A2-4ED57E2123BE", "Löschzug 4", false );
		insert_engine ("A67C8A08-3BCD-6FA0-9BF4-491A5121EA7B", "Löschzug 5", false );
		insert_engine ("6D9D8344-BE44-BFD3-1B0F-72BE5E56571E", "Löschzug 6", false );
		insert_engine ("C440BB6A-D8BF-3FAB-FC57-FAE475A1DBED", "Löschzug 7", false );
		insert_engine ("1311075E-1260-2685-0822-8102BE480F32", "Löschzug 8", false );
		insert_engine ("67CF2ADD-F5ED-3D43-FFF1-C504B8F39743", "Löschzug 9", false );
		insert_engine ("ACCEC110-290E-6A65-A750-6AA93625D784", "Brandschutzzug", false );
		insert_engine ("57D2CB43-F3CE-3837-4181-2FE60FDB9277", "Keine Zuordnung", false );
		
	}
	
	function getEngine(String $uuid){
		return $this->engineRepository->findByID($uuid);
	}
	
	function getEngineByName(String $name){
		$criteria = array('name' => $name);
		return $this->engineRepository->findBy($criteria);
	}
	
	function getAdministration(){
		$criteria = array('isAdministration' => true);
		return $this->engineRepository->findBy($criteria);
	}
	
	function getEngines(){
		return $this->engineRepository->findAll();
	}
	
	function getEnginesWithoutAdministration(){
		$criteria = array('isAdministration' => false);
		return $this->engineRepository->findBy($criteria);
	}
	
	
	
}