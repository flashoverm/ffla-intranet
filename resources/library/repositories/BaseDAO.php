<?php 

require_once __DIR__ . "/../../bootstrap.php";

use Doctrine\ORM\EntityManager;

abstract class BaseDAO {
	
	protected $entityManager;
	
	function __construct(EntityManager $entityManager) {
		$this->entityManager = $entityManager;
	}
	
	function save($entity){
		$this->entityManager->persist($entity);
		$this->entityManager->flush();
	}
	
	function delete($entity){
		$this->entityManager->remove($entity);
		$this->entityManager->flush();
	}
}
	