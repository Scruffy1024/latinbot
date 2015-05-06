<?php
	function startsWith($haystack, $needle) {
	    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}
	
	function endsWith($haystack, $needle) {
	    return $needle === "" || strpos($haystack, $needle, strlen($haystack) - strlen($needle)) !== FALSE;
	}
	
	function countSyllables($word) {
		return preg_split("[aeiouy]+?\w*?[^e]", mb_strtolower($word));
	}
	
	function isVowel($c) {
		return ($c == "a" || $c == "e" || $c == "i" || $c == "o" || $c == "u" || $c == "y");
	}
?>
