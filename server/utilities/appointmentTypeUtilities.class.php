<?php

class AppointmentTypeUtilities {
	public static function isResidentialAppointmentType($appointmentTypeString) {
		return strpos($appointmentTypeString, 'residential') !== false;
	}
	
	public static function isInternationalAppointmentType($appointmentTypeString) {
		return strpos($appointmentTypeString, 'china') !== false
			|| strpos($appointmentTypeString, 'india') !== false
			|| strpos($appointmentTypeString, 'treaty') !== false
			|| strpos($appointmentTypeString, 'non-treaty') !== false;
	}
	
	public static function isVirtualAppointmentType($appointmentTypeString) {
		return strpos($appointmentTypeString, 'virtual') !== false;
	}

	// TODO we need to make a better system for the following functions. I'm keeping them simple for now to reduce potential operational impact
	public static function isFsaAppointment($appointmentTitleString) {
		return strpos($appointmentTitleString, 'Lincoln VITA FSA') !== false;
	}

	public static function isInternationalFsaAppointment($appointmentTitleString, $appointmentTypeString) {
		return strpos($appointmentTitleString, 'International Student Scholar') !== false && strpos($appointmentTypeString, 'virtual') !== false;
	}

	public static function isDomesticFsaAppointment($appointmentTitleString) {
		return strpos($appointmentTitleString, 'Lincoln VITA FSA') !== false;
	}

	public static function isDropoffAppointment($appointmentTitleString) {
		return strpos($appointmentTitleString, 'Ashland Public Library') !== false || strpos($appointmentTitleString, 'Wahoo Public Library') !== false;
	}
}
