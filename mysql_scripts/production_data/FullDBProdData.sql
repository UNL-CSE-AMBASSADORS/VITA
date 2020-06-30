USE vita;

-- User
INSERT INTO User (firstName, lastName, email, phoneNumber) 
	VALUES ('Matthew', 'Meacham', 'mmeacham6@gmail.com', '');
SET @userId = LAST_INSERT_ID();

INSERT INTO User (firstName, lastName, email, phoneNumber)
	VALUES ('Austin', 'Schmidt', 'schmidtwithad@hotmail.com', '');

INSERT INTO User (firstName, lastName, email, phoneNumber)
	VALUES ('Spencer', 'Collins', 'collinsspencer97@gmail.com', '');
-- End User





-- Sites
INSERT INTO Site (title, address, phoneNumber, createdBy, lastModifiedBy)
	VALUES ("Nebraska East Union", "Holdrege and 35th Streets", "402-472-6150", @userId, @userId);
SET @site_nebraskaEastUnion = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, createdBy, lastModifiedBy)
	VALUES ("Anderson Library", "3635 Touzalin Ave", "402-472-9638", @userId, @userId);
SET @site_andersonLibrary = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, doesMultilingual, createdBy, lastModifiedBy)
	VALUES ("Jackie Gaughan Multicultural Center", "1505 'S' Street", "402-472-9638", TRUE, @userId, @userId);
SET @site_jackieGaughanMulticulturalCenter = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, doesInternational, createdBy, lastModifiedBy)
	VALUES ("International Student Scholar", "Nebraska Union -- Colonial Room", "402-472-9638", TRUE, @userId, @userId);
SET @site_internationalStudentScholar = LAST_INSERT_ID();
-- End Sites





-- Shifts and AppointmentTimes
-- Sunday
SET @shiftStartTime = "2018-01-21 13:00:00";
SET @shiftEndTime = "2018-01-21 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-01-21 14:30:00";
SET @shiftEndTime = "2018-01-21 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-01-21 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-21 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-21 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);


-- Monday
SET @shiftStartTime = "2018-01-22 16:30:00";
SET @shiftEndTime = "2018-01-22 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-01-22 18:00:00";
SET @shiftEndTime = "2018-01-22 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-01-22 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-22 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-22 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);


-- Tuesday
SET @shiftStartTime = "2018-01-23 16:30:00";
SET @shiftEndTime = "2018-01-23 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-01-23 18:00:00";
SET @shiftEndTime = "2018-01-23 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-01-23 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-23 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-23 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);


-- Wednesday, NEU
SET @shiftStartTime = "2018-01-24 16:30:00";
SET @shiftEndTime = "2018-01-24 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-01-24 18:00:00";
SET @shiftEndTime = "2018-01-24 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-01-24 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-24 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-24 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);


-- Wednesday, AL
SET @shiftStartTime = "2018-01-24 16:30:00";
SET @shiftEndTime = "2018-01-24 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-01-24 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-01-24 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-01-24 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-01-24 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-01-24 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Saturday
SET @shiftStartTime = "2018-01-27 09:30:00";
SET @shiftEndTime = "2018-01-27 13:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-01-27 12:30:00";
SET @shiftEndTime = "2018-01-27 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-01-27 10:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-27 11:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-27 12:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-27 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-27 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-27 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);


-- Sunday
SET @shiftStartTime = "2018-01-28 13:00:00";
SET @shiftEndTime = "2018-01-28 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-01-28 14:30:00";
SET @shiftEndTime = "2018-01-28 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-01-28 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-28 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-28 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);


-- Monday
SET @shiftStartTime = "2018-01-29 16:30:00";
SET @shiftEndTime = "2018-01-29 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-01-29 18:00:00";
SET @shiftEndTime = "2018-01-29 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-01-29 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-29 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-29 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);


-- Tuesday, NEU
SET @shiftStartTime = "2018-01-30 16:30:00";
SET @shiftEndTime = "2018-01-30 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-01-30 18:00:00";
SET @shiftEndTime = "2018-01-30 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-01-30 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-30 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-30 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);


-- Tuesday, AL
SET @shiftStartTime = "2018-01-30 16:30:00";
SET @shiftEndTime = "2018-01-30 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-01-30 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-01-30 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-01-30 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-01-30 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-01-30 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Wednesday, NEU
SET @shiftStartTime = "2018-01-31 16:30:00";
SET @shiftEndTime = "2018-01-31 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-01-31 18:00:00";
SET @shiftEndTime = "2018-01-31 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-01-31 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-31 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-01-31 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);


-- Wednesday, AL
SET @shiftStartTime = "2018-01-31 16:30:00";
SET @shiftEndTime = "2018-01-31 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-01-31 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-01-31 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-01-31 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-01-31 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-01-31 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Saturday
SET @shiftStartTime = "2018-02-03 09:30:00";
SET @shiftEndTime = "2018-02-03 13:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-03 12:30:00";
SET @shiftEndTime = "2018-02-03 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-03 10:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-03 11:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-03 12:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-03 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-03 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-03 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);


-- Sunday 
SET @shiftStartTime = "2018-02-04 13:00:00";
SET @shiftEndTime = "2018-02-04 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = "2018-02-04 14:30:00";
SET @shiftEndTime = "2018-02-04 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @scheduledTime = "2018-02-04 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

SET @scheduledTime = "2018-02-04 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

SET @scheduledTime = "2018-02-04 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);


-- Monday
SET @shiftStartTime = "2018-02-05 16:30:00";
SET @shiftEndTime = "2018-02-05 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-05 18:00:00";
SET @shiftEndTime = "2018-02-05 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-05 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-05 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-05 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);


-- Tuesday, NEU
SET @shiftStartTime = "2018-02-06 16:30:00";
SET @shiftEndTime = "2018-02-06 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-06 18:00:00";
SET @shiftEndTime = "2018-02-06 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-06 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-06 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-06 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

-- Tuesday, AL
SET @shiftStartTime = "2018-02-06 16:30:00";
SET @shiftEndTime = "2018-02-06 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-02-06 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-06 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-06 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-06 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-06 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Wednesday, NEU
SET @shiftStartTime = "2018-02-07 16:30:00";
SET @shiftEndTime = "2018-02-07 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-07 18:00:00";
SET @shiftEndTime = "2018-02-07 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-07 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-07 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-07 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

-- Wednesday, AL
SET @shiftStartTime = "2018-02-07 16:30:00";
SET @shiftEndTime = "2018-02-07 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-02-07 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-07 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-07 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-07 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-07 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Saturday
SET @shiftStartTime = "2018-02-10 09:30:00";
SET @shiftEndTime = "2018-02-10 13:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-10 12:30:00";
SET @shiftEndTime = "2018-02-10 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-10 10:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-10 11:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-10 12:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-10 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-10 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-10 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);


-- Sunday
SET @shiftStartTime = "2018-02-11 13:00:00";
SET @shiftEndTime = "2018-02-11 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = "2018-02-11 14:30:00";
SET @shiftEndTime = "2018-02-11 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @scheduledTime = "2018-02-11 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

SET @scheduledTime = "2018-02-11 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

SET @scheduledTime = "2018-02-11 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);


-- Monday
SET @shiftStartTime = "2018-02-12 16:30:00";
SET @shiftEndTime = "2018-02-12 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-12 18:00:00";
SET @shiftEndTime = "2018-02-12 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-12 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-12 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-12 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);


-- Tuesday, NEU
SET @shiftStartTime = "2018-02-13 16:30:00";
SET @shiftEndTime = "2018-02-13 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-13 18:00:00";
SET @shiftEndTime = "2018-02-13 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-13 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-13 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-13 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

-- Tuesday, AL
SET @shiftStartTime = "2018-02-13 16:30:00";
SET @shiftEndTime = "2018-02-13 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-02-13 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-13 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-13 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-13 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-13 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Wednesday, NEU
SET @shiftStartTime = "2018-02-14 16:30:00";
SET @shiftEndTime = "2018-02-14 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-14 18:00:00";
SET @shiftEndTime = "2018-02-14 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-14 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-14 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-14 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

-- Wednesday, AL
SET @shiftStartTime = "2018-02-14 16:30:00";
SET @shiftEndTime = "2018-02-14 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-02-14 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-14 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-14 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-14 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-14 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Saturday
SET @shiftStartTime = "2018-02-17 09:30:00";
SET @shiftEndTime = "2018-02-17 13:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-17 12:30:00";
SET @shiftEndTime = "2018-02-17 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-17 10:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-17 11:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-17 12:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-17 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-17 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-17 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);


-- Sunday
SET @shiftStartTime = "2018-02-18 13:00:00";
SET @shiftEndTime = "2018-02-18 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = "2018-02-18 14:30:00";
SET @shiftEndTime = "2018-02-18 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @scheduledTime = "2018-02-18 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

SET @scheduledTime = "2018-02-18 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

SET @scheduledTime = "2018-02-18 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);


-- Monday
SET @shiftStartTime = "2018-02-19 16:30:00";
SET @shiftEndTime = "2018-02-19 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-19 18:00:00";
SET @shiftEndTime = "2018-02-19 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-19 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-19 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-19 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);


-- Tuesday, NEU
SET @shiftStartTime = "2018-02-20 16:30:00";
SET @shiftEndTime = "2018-02-20 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-20 18:00:00";
SET @shiftEndTime = "2018-02-20 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-20 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-20 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-20 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);


-- Tuesday, AL
SET @shiftStartTime = "2018-02-20 16:30:00";
SET @shiftEndTime = "2018-02-20 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-02-20 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-20 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-20 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-20 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-20 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Wednesday, NEU
SET @shiftStartTime = "2018-02-21 16:30:00";
SET @shiftEndTime = "2018-02-21 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-21 18:00:00";
SET @shiftEndTime = "2018-02-21 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-21 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-21 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-21 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

-- Wednesday, AL
SET @shiftStartTime = "2018-02-21 16:30:00";
SET @shiftEndTime = "2018-02-21 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-02-21 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-21 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-21 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-21 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-21 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Saturday
SET @shiftStartTime = "2018-02-24 09:30:00";
SET @shiftEndTime = "2018-02-24 13:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-24 12:30:00";
SET @shiftEndTime = "2018-02-24 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-24 10:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-24 11:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-24 12:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-24 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-24 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-24 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);


-- Sunday
SET @shiftStartTime = "2018-02-25 13:00:00";
SET @shiftEndTime = "2018-02-25 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = "2018-02-25 14:30:00";
SET @shiftEndTime = "2018-02-25 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @scheduledTime = "2018-02-25 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

SET @scheduledTime = "2018-02-25 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

SET @scheduledTime = "2018-02-25 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);


-- Monday
SET @shiftStartTime = "2018-02-26 16:30:00";
SET @shiftEndTime = "2018-02-26 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-26 18:00:00";
SET @shiftEndTime = "2018-02-26 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-26 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-26 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-26 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);


-- Tuesday, NEU
SET @shiftStartTime = "2018-02-27 16:30:00";
SET @shiftEndTime = "2018-02-27 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-27 18:00:00";
SET @shiftEndTime = "2018-02-27 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-27 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-27 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-27 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

-- Tuesday, AL
SET @shiftStartTime = "2018-02-27 16:30:00";
SET @shiftEndTime = "2018-02-27 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-02-27 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-27 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-27 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-27 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-27 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Wednesday, NEU
SET @shiftStartTime = "2018-02-28 16:30:00";
SET @shiftEndTime = "2018-02-28 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-02-28 18:00:00";
SET @shiftEndTime = "2018-02-28 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-02-28 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-28 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-02-28 19:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_nebraskaEastUnion);

-- Wednesday, AL
SET @shiftStartTime = "2018-02-28 16:30:00";
SET @shiftEndTime = "2018-02-28 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-02-28 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-28 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-28 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-28 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-02-28 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Saturday
SET @shiftStartTime = "2018-03-03 09:30:00";
SET @shiftEndTime = "2018-03-03 13:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-03-03 12:30:00";
SET @shiftEndTime = "2018-03-03 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-03-03 10:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-03-03 11:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-03-03 12:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-03-03 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-03-03 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-03-03 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);


-- Sunday
SET @shiftStartTime = "2018-03-04 13:00:00";
SET @shiftEndTime = "2018-03-04 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = "2018-03-04 14:30:00";
SET @shiftEndTime = "2018-03-04 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @scheduledTime = "2018-03-04 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

SET @scheduledTime = "2018-03-04 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

SET @scheduledTime = "2018-03-04 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);


-- Tuesday, AL
SET @shiftStartTime = "2018-03-06 16:30:00";
SET @shiftEndTime = "2018-03-06 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-03-06 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-06 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-06 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-06 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-06 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

-- Tuesday, ISS
SET @shiftStartTime = "2018-03-06 13:00:00";
SET @shiftEndTime = "2018-03-06 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_internationalStudentScholar, @userId, @userId);

SET @shiftStartTime = "2018-03-06 15:30:00";
SET @shiftEndTime = "2018-03-06 18:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_internationalStudentScholar, @userId, @userId);

SET @scheduledTime = "2018-03-06 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-03-06 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-03-06 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-03-06 16:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 15, @site_internationalStudentScholar);

SET @scheduledTime = "2018-03-06 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 10, @site_internationalStudentScholar);


-- Wednesday
SET @shiftStartTime = "2018-03-07 16:30:00";
SET @shiftEndTime = "2018-03-07 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-03-07 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-07 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-07 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-07 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-07 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Saturday
SET @shiftStartTime = "2018-03-10 09:30:00";
SET @shiftEndTime = "2018-03-10 13:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = "2018-03-10 12:30:00";
SET @shiftEndTime = "2018-03-10 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @scheduledTime = "2018-03-10 10:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-03-10 11:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-03-10 12:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-03-10 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-03-10 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);

SET @scheduledTime = "2018-03-10 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId)
	VALUES (@scheduledTime, 75, @site_nebraskaEastUnion);


-- Sunday
SET @shiftStartTime = "2018-03-11 13:00:00";
SET @shiftEndTime = "2018-03-11 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = "2018-03-11 14:30:00";
SET @shiftEndTime = "2018-03-11 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @scheduledTime = "2018-03-11 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

SET @scheduledTime = "2018-03-11 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

SET @scheduledTime = "2018-03-11 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);


-- Tuesday, AL
SET @shiftStartTime = "2018-03-13 16:30:00";
SET @shiftEndTime = "2018-03-13 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-03-13 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-13 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-13 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-13 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-13 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

-- Tuesday, ISS
SET @shiftStartTime = "2018-03-13 13:00:00";
SET @shiftEndTime = "2018-03-13 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_internationalStudentScholar, @userId, @userId);

SET @shiftStartTime = "2018-03-13 15:30:00";
SET @shiftEndTime = "2018-03-13 18:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_internationalStudentScholar, @userId, @userId);

SET @scheduledTime = "2018-03-13 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-03-13 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-03-13 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-03-13 16:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 15, @site_internationalStudentScholar);

SET @scheduledTime = "2018-03-13 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 10, @site_internationalStudentScholar);


-- Wednesday
SET @shiftStartTime = "2018-03-14 16:30:00";
SET @shiftEndTime = "2018-03-14 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-03-14 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-14 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-14 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-14 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-14 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Tuesday, AL
SET @shiftStartTime = "2018-03-20 16:30:00";
SET @shiftEndTime = "2018-03-20 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-03-20 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-20 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-20 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-20 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-20 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

-- Tuesday, ISS
SET @shiftStartTime = "2018-03-20 13:00:00";
SET @shiftEndTime = "2018-03-20 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_internationalStudentScholar, @userId, @userId);

SET @scheduledTime = "2018-03-20 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 20, @site_internationalStudentScholar);

SET @scheduledTime = "2018-03-20 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 20, @site_internationalStudentScholar);

SET @scheduledTime = "2018-03-20 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 10, @site_internationalStudentScholar);


-- Wednesday
SET @shiftStartTime = "2018-03-21 16:30:00";
SET @shiftEndTime = "2018-03-21 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-03-21 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-21 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-21 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-21 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-21 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Tuesday, AL
SET @shiftStartTime = "2018-03-27 16:30:00";
SET @shiftEndTime = "2018-03-27 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-03-27 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-27 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-27 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-27 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-27 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

-- Tuesday, ISS
SET @shiftStartTime = "2018-03-27 13:00:00";
SET @shiftEndTime = "2018-03-27 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_internationalStudentScholar, @userId, @userId);

SET @shiftStartTime = "2018-03-27 15:30:00";
SET @shiftEndTime = "2018-03-27 18:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_internationalStudentScholar, @userId, @userId);

SET @scheduledTime = "2018-03-27 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-03-27 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-03-27 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-03-27 16:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 15, @site_internationalStudentScholar);

SET @scheduledTime = "2018-03-27 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 10, @site_internationalStudentScholar);


-- Wednesday
SET @shiftStartTime = "2018-03-28 16:30:00";
SET @shiftEndTime = "2018-03-28 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-03-28 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-28 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-28 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-28 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-03-28 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Sunday
-- Easter Sunday, no appointments/shifts

-- Tuesday, AL
SET @shiftStartTime = "2018-04-03 16:30:00";
SET @shiftEndTime = "2018-04-03 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-04-03 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-04-03 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-04-03 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-04-03 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-04-03 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

-- Tuesday, ISS
SET @shiftStartTime = "2018-04-03 13:00:00";
SET @shiftEndTime = "2018-04-03 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_internationalStudentScholar, @userId, @userId);

SET @shiftStartTime = "2018-04-03 15:30:00";
SET @shiftEndTime = "2018-04-03 18:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_internationalStudentScholar, @userId, @userId);

SET @scheduledTime = "2018-04-03 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-04-03 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-04-03 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-04-03 16:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 15, @site_internationalStudentScholar);

SET @scheduledTime = "2018-04-03 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 10, @site_internationalStudentScholar);


-- Wednesday
SET @shiftStartTime = "2018-04-04 16:30:00";
SET @shiftEndTime = "2018-04-04 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @userId, @userId);

SET @scheduledTime = "2018-04-04 16:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-04-04 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-04-04 17:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-04-04 18:00:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);

SET @scheduledTime = "2018-04-04 18:30:00";
INSERT INTO AppointmentTime (scheduledTime, minimumNumberOfAppointments, maximumNumberOfAppointments, siteId, approximateLengthInMinutes)
	VALUES (@scheduledTime, 3, 3, @site_andersonLibrary, 30);


-- Sunday
SET @shiftStartTime = "2018-04-08 13:00:00";
SET @shiftEndTime = "2018-04-08 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = "2018-04-08 14:30:00";
SET @shiftEndTime = "2018-04-08 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @scheduledTime = "2018-04-08 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

SET @scheduledTime = "2018-04-08 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

SET @scheduledTime = "2018-04-08 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, siteId)
	VALUES (@scheduledTime, @site_jackieGaughanMulticulturalCenter);

-- Tuesday
SET @shiftStartTime = "2018-04-10 13:00:00";
SET @shiftEndTime = "2018-04-10 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_internationalStudentScholar, @userId, @userId);

SET @shiftStartTime = "2018-04-10 15:30:00";
SET @shiftEndTime = "2018-04-10 18:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_internationalStudentScholar, @userId, @userId);

SET @scheduledTime = "2018-04-10 13:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-04-10 14:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-04-10 15:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 30, @site_internationalStudentScholar);

SET @scheduledTime = "2018-04-10 16:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 15, @site_internationalStudentScholar);

SET @scheduledTime = "2018-04-10 17:00:00";
INSERT INTO AppointmentTime (scheduledTime, maximumNumberOfAppointments, siteId)
	VALUES (@scheduledTime, 10, @site_internationalStudentScholar);
-- End Shifts and AppointmentTimes





-- Abilities
INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Basic Certification", "basic_certification", "Has completed the basic certification requirements", TRUE);

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Advanced Certification", "advanced_certification", "Has completed the advanced certification requirements", TRUE);

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("International Certification", "international_certification", "Has completed the international certification requirements", TRUE);

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Military Certification", "military_certification", "Has completed the military certification requirements", TRUE);

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Health Savings (HSA) Certification", "health_savings_certification", "Has completed the health savings (HSA) certification requirements", TRUE);

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Spanish-Speaking", "spanish_speaking", "Can speak fluent Spanish", FALSE);

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Vietnamese-Speaking", "viatnamese_speaking", "Can speak fluent vietnamese", FALSE);

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Arabic-Speaking", "arabic_speaking", "Can speak fluent Arabic", FALSE);
-- End Abilities





-- Permissions
INSERT INTO Permission (name, description, lookupName)
	VALUES ("Edit Permissions", "Can edit user permissions", "edit_user_permissions");
SET @permission_editUserPermissionId = LAST_INSERT_ID();

INSERT INTO Permission (name, description, lookupName)
	VALUES ("Use Admin Tools", "Can use administrative tools", "use_admin_tools");
SET @permission_useAdminToolsId = LAST_INSERT_ID();

INSERT INTO Permission (name, description, lookupName)
	VALUES ("View All Client Information", "Can view all client information (full last name, email, phone number)", "view_client_information");
SET @permission_viewClientInformationId = LAST_INSERT_ID();
-- End Permissions





-- Questions
INSERT INTO Question (text, lookupName)
	VALUES ("Are you a University of Nebraska - Lincoln student?", "unl_student");
SET @question_question1Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Are you an International Student Scholar?", "international_student_scholar");
SET @question_question2Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("What sort of visa are you on?", "visa");
SET @question_question3Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("How long have you been in the United States?", "duration_in_united_states");
SET @question_question4Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Have you been on this visa for less than 183 days and in the United States for less than five years (after 2012)?", "visa_less_than_183_days");
SET @question_question5Id = LAST_INSERT_ID();
-- End Questions





-- PossibleAnswer
INSERT INTO PossibleAnswer (text)
	VALUES ("Yes");
SET @possibleAnswer_yesId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("No");
SET @possibleAnswer_noId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("Unsure");
SET @possibleAnswer_unsureId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("F1");
SET @possibleAnswer_f1Id = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("J1");
SET @possibleAnswer_j1Id = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("H1B");
SET @possibleAnswer_h1bId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("2011 or earlier");
SET @possibleAnswer_2011OrEarlierId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("2012 or later");
SET @possibleAnswer_2012OrLaterId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("2014 or earlier");
SET @possibleAnswer_2014OrEarlierId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("2015 or later");
SET @possibleAnswer_2015OrLaterId = LAST_INSERT_ID();
-- End PossibleAnswer





-- Role
INSERT INTO Role (name, lookupName)
	VALUES ("Site Administrator", "site_administrator");
SET @role_siteAdministrator = LAST_INSERT_ID();

INSERT INTO Role (name, lookupName)
	VALUES ("Greeter", "greeter");
SET @role_greeter = LAST_INSERT_ID();

INSERT INTO Role (name, lookupName)
	VALUES ("Preparer", "preparer");
SET @role_preparer = LAST_INSERT_ID();

INSERT INTO Role (name, lookupName)
	VALUES ("Reviewer", "reviewer");
SET @role_reviewer = LAST_INSERT_ID();
-- End Role