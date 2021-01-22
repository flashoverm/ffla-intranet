<?php

namespace pCloud;

class Auth {

	public static function getAuth($credentialPath) {
		if (!file_exists($credentialPath)) {
			throw new Exception("Couldn't find credential file");			
		}

		$file = file_get_contents($credentialPath);
		$credential = json_decode($file, true);

		if (!isset($credential["auth"]) || empty($credential["auth"])) {
			throw new Exception("Couldn't find \"auth\"");			
		}

		return $credential;
	}
}