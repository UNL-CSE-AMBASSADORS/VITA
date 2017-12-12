<?php

function getUtcDateAdjustedForTimezoneOffset($date, $timezoneOffset, $dateFormat = 'Y-m-d') {
	// Take the date in UTC, and then change it to reflect the user's timezone offset
	// i.e. 11/24/2017 -> 11-24-2017 06:00:00 for CST
	$utcDate = DateTimeImmutable::createFromFormat($dateFormat, $date, new DateTimeZone('UTC'));
	$utcDate = $utcDate->setTime(0, 0, 0);
	$utcDateAdjustedForUserTimezone = $utcDate->add(new DateInterval('PT'.$timezoneOffset.'H'));
	$utcDateAdjustedForUserTimezonePlusOneDay = $utcDate->add(new DateInterval('P1D'))->add(new DateInterval('PT'.$timezoneOffset.'H'));
	return array(
		'date' => $utcDateAdjustedForUserTimezone,
		'datePlusOneDay' => $utcDateAdjustedForUserTimezonePlusOneDay
	);
}

function getTimezoneDateFromUtc($utcDate, $timezoneOffset, $datetimeFormat = 'Y-m-d H:i:s') {
	$dateInUtc = DateTimeImmutable::createFromFormat($datetimeFormat, $utcDate);
	$dateInTimezone = $dateInUtc->sub(new DateInterval('PT' . $timezoneOffset . 'H'));
	return $dateInTimezone;
}
