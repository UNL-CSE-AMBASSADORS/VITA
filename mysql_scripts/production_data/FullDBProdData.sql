USE vita;

SET @userId = 1;

-- Sites
INSERT INTO Site (title, address, phoneNumber, appointmentOnly, createdBy, lastModifiedBy)
	VALUES ("Nebraska East Union", "Holdrege and 35th Streets", "402-472-6150", true, @userId, @userId);
SET @site_nebraskaEastUnion = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, appointmentOnly, createdBy, lastModifiedBy)
	VALUES ("Anderson Library", "3635 Touzalin Ave", "TODO", true, @userId, @userId);
SET @site_andersonLibrary = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, appointmentOnly, createdBy, lastModifiedBy)
	VALUES ("Center for People in Need", "3901 N 27th, Unit 1", "TODO", false, @userId, @userId);
SET @site_centerForPeopleInNeed = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, appointmentOnly, createdBy, lastModifiedBy)
	VALUES ("Asian Community and Cultural Center", "144 North 44 Suite A", "402-477-3446", true, @userId, @userId);
SET @site_asianCommunityAndCulturalCenter = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, appointmentOnly, createdBy, lastModifiedBy)
	VALUES ("Eiseley Library", "1530 Superior Street", "TODO", false, @userId, @userId);
SET @site_eiseleyLibrary = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, appointmentOnly, createdBy, lastModifiedBy)
	VALUES ("Bennett Martin Library", "14th and N Street", "TODO", false, @userId, @userId);
SET @site_bennettMartinLibrary = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, appointmentOnly, createdBy, lastModifiedBy)
	VALUES ("Good Neighbor Center", "2617 Y Street", "TODO", false, @userId, @userId);
SET @site_goodNeighborCenter = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, appointmentOnly, createdBy, lastModifiedBy)
	VALUES ("Jackie Gaughan Multicultural Center", "1505 'S' Street", "TODO", true, @userId, @userId);
SET @site_jackieGaughanMulticulturalCenter = LAST_INSERT_ID();
-- End Sites



-- Shifts
SET @shiftStartTime = CONVERT_TZ("2018-01-21 13:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-21 15:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-01-21 14:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-21 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-01-22 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-22 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-01-22 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-22 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-01-23 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-23 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-01-23 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-23 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-01-24 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-24 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-01-24 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-24 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-01-27 09:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-27 13:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-01-27 12:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-27 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-01-28 13:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-28 15:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-01-28 14:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-28 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-01-29 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-29 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-01-29 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-29 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-01-30 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-30 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-01-30 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-30 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-01-31 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-31 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-01-31 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-01-31 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-03 09:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-03 13:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-03 12:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-03 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-04 13:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-04 15:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-04 14:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-04 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-05 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-05 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-05 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-05 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-06 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-06 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-06 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-06 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-07 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-07 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-07 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-07 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-10 09:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-10 13:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-10 12:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-10 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-11 13:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-11 15:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-11 14:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-11 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-12 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-12 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-12 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-12 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-13 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-13 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-13 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-13 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-14 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-14 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-14 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-14 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-17 09:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-17 13:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-17 12:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-17 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-18 13:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-18 15:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-18 14:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-18 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-19 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-19 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-19 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-19 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-20 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-20 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-20 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-20 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-21 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-21 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-21 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-21 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-24 09:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-24 13:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-24 12:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-24 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-25 13:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-25 15:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-25 14:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-25 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-26 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-26 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-26 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-26 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-27 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-27 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-27 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-27 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-02-28 16:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-28 18:30:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-02-28 18:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-02-28 20:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-03-03 09:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-03-03 13:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-03-03 12:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-03-03 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-03-04 13:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-03-04 15:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-03-04 14:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-03-04 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-03-10 09:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-03-10 13:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-03-10 12:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-03-10 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-03-11 13:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-03-11 15:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-03-11 14:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-03-11 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-04-01 13:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-04-01 15:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-04-01 12:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-04-01 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);


SET @shiftStartTime = CONVERT_TZ("2018-04-08 13:00:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-04-08 15:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);

SET @shiftStartTime = CONVERT_TZ("2018-04-08 12:30:00", "-06:00", "+00:00");
SET @shiftEndTime = CONVERT_TZ("2018-04-08 16:00:00", "-06:00", "+00:00");
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @userId, @userId);
-- End Shifts





