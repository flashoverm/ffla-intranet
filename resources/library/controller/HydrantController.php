<?php

require_once 'BaseController.php';

//use Doctrine\ORM\EntityManager;

class HydrantController extends BaseController{
	
	protected $hydrantDAO;
	
	function __construct() {
		$this->hydrantDAO = new HydrantDAO();
	}
	
	function isHyExisting(int $hy){
		if( $this->hydrantDAO->getHydrantByHy($hy) ){
			return true;
		}
		return false;
	}
	
	function isMapExisting(int $hy){
		global $config;
		
		$hydrant = $this->hydrantDAO->getHydrantByHy($hy);
		if($hydrant != null && $hydrant->getMap() != NULL){
			if(file_exists($config["paths"]["maps"] . $hydrant->getMap())){
				return true;
			}
		}
		return false;
	}
		
}