<?php

$root = realpath($_SERVER['DOCUMENT_ROOT']);
require_once "$root/server/config.php";

class DateFormats {
	const MM_DD_YYYY = 1; // Delimeter expected is a hyphen (-)
}

class TimeFormats {
	const HH_MM_PERIOD = 1; // Delimeters expected are a colon (:) and a space ( )
}

class ComparerResults {
	const LESS = 0b001;
	const EQUAL = 0b010;
	const GREATER = 0b100;
}

class DateTimeUtilities {

	public static function extractDateParts($dateString, $format = DateFormats::MM_DD_YYYY) {
		if (!isset($dateString) || !DateTimeUtilities::isValidDateString($dateString, $format)) throw new Exception('Invalid date string given', MY_EXCEPTION);
	
		if ($format === DateFormats::MM_DD_YYYY) {
			$split = preg_split('/-/', $dateString);
			return array(
				'day' => $split[1],
				'month' => $split[0],
				'year' => $split[2]
			);
		}

		throw new Exception('Unknown date format', MY_EXCEPTION);
	}
	
	public static function extractTimeParts($timeString, $format = TimeFormats::HH_MM_PERIOD) {
		if (!isset($timeString) || !DateTimeUtilities::isValidTimeString($timeString, $format)) throw new Exception('Invalid time string given', MY_EXCEPTION);
	
		if ($format === TimeFormats::HH_MM_PERIOD) {
			$split = preg_split('/(:| )/', $timeString);
			$period = $split[2];
		
			// Change hour to 24-hour format
			$hour = (int)$split[0];
			if ($period === 'AM' && $hour === 12) $hour = 0;
			if ($period === 'PM' && $hour !== 12) $hour += 12;
			$hourString = substr('0' . strval($hour), -2);
		
			return array(
				'hours' => $hourString,
				'minutes' => $split[1],
				'seconds' => '00'
			);
		}

		throw new Exception('Unknown time format', MY_EXCEPTION);
	}
	
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

	public static function compareTimeParts($timeParts1, $timeParts2) {
		if ($timeParts1['hours'] === $timeParts2['hours']
			&& $timeParts1['minutes'] === $timeParts2['minutes']
			&& $timeParts1['seconds'] === $timeParts2['seconds']) {
			return ComparerResults::EQUAL;
		} 
		
		if ((int)$timeParts1['hours'] > (int)$timeParts2['hours']
			&& (int)$timeParts1['minutes'] > (int)$timeParts2['minutes']
			&& (int)$timeParts1['seconds'] > (int)$timeParts2['seconds']) {
			return ComparerResults::GREATER;
		} 

		return ComparerResults::LESS;
	}
	
}
