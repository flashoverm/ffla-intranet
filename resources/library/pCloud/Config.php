<?php

namespace pCloud;

class Config {
    public static $usHost = "https://api.pcloud.com/";
    public static $euHost = "https://eapi.pcloud.com/";
    public static $curllib = "pCloud\Curl";
    public static $filePartSize = 10485760;

	static public function getApiHostByLocationId($locationid) {
		if ($locationid == 2) {
			return self::$euHost;
		} else {
			return self::$usHost;
		}
	}
}