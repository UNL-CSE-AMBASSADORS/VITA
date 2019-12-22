<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/config.php";

class DateFormats {
	const MM_DD_YYYY = 1; // i.e. 08-27-2018
}

class TimeFormats {
	const HH_MM_PERIOD = 1; // i.e. 08:30 AM
}

class DateTimeUtilities {
	
	public static function isValidDateString($dateString, $format = DateFormats::MM_DD_YYYY) {
		if ($format === DateFormats::MM_DD_YYYY) {
			return preg_match('/(^(0[1-9]|1[012])[-](0[1-9]|[12][0-9]|3[01])[-](20)\d\d$)/', $dateString);
		}
		throw new Exception('Unknown date format', MY_EXCEPTION);
	}
	
	public static function isValidTimeString($timeString, $format = TimeFormats::HH_MM_PERIOD) {
		if ($format === TimeFormats::HH_MM_PERIOD) {
			return preg_match('/(^(0?[1-9]|1[012])[:]([0-5][0-9])[ ](AM|PM)$)/', $timeString);
		}
		throw new Exception('Unknown time format', MY_EXCEPTION);
	}
}
