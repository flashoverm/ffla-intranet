<?php
class StringUtil {
	
	static function convertToWindowsCharset($string) {
		$charset =  mb_detect_encoding(
				$string,
				"UTF-8, ISO-8859-1, ISO-8859-15",
				true
				);
		
		$string =  mb_convert_encoding($string, "Windows-1252", $charset);
		return $string;
	}
	
	static function startsWith( $haystack, $needle ) {
		$length = strlen( $needle );
		return substr( $haystack, 0, $length ) === $needle;
	}
	
	static function endsWith($haystack, $needle) {
		$length = strlen($needle);
		if ($length == 0) {
			return true;
		}
		
		return (substr($haystack, -$length) === $needle);
	}
}
