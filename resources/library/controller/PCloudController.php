<?php

require_once 'BaseController.php';
require_once LIBRARY_PATH . "/pCloud/autoload.php";

//use Doctrine\ORM\EntityManager;

class PCloudController extends BaseController{
	
	protected $pCloudApp;
	
	function __construct() {
		parent::__construct();
		$this->pCloudApp = new pCloud\App();
	}
	
	function uploadBackup($filepath){
		global $config;
		
		try{
			if($this->login($config['pcloud']['username'], $config['pcloud']['password'])){
				
				$folderId = $this->getBackupFolder();
				
				$this->uploadFile($filepath, $folderId);
				
				return true;
			}
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		return false;
	}
	
	private function login($username, $password){
		$host = "https://api.pcloud.com";
		$url = $host . "/userinfo?getauth=1&logout=1&username=" 
				. urlencode($username) . "&password=" . $password;

        //$url = $host . "/login?logout=1&username="
        //    . urlencode($username) . "&password=" . $password;
        
        $result = $this->curl($url);
        
        if($result){
        	$this->pCloudApp->setAuth($result->auth);
        	return true;
        }
        return false;
	}
	
	private function getBackupFolder(){
		$folder = new pCloud\Folder($this->pCloudApp);
		$id = $folder->create("Feuerwehr Landshut");
		$id = $folder->create("Intranet_Backup", $id);
		return $id;
	}
	
	private function uploadFile($filePath, $folderId = 0){
		
		$pcloudFile = new pCloud\File($this->pCloudApp);

		$fileMetadata = $pcloudFile->upload($filePath, $folderId);
		
		return $fileMetadata;
	}
	
	private function curl($url){
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($curl);
		curl_close($curl);
		
		if($result){
			return json_decode($result);
		}
		return false;
	}
}