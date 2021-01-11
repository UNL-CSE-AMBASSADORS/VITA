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
}
