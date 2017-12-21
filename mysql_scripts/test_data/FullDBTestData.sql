USE vita;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE Answer;
TRUNCATE ServicedAppointment;
TRUNCATE Appointment;
TRUNCATE AppointmentTime;
TRUNCATE Client;
TRUNCATE UserShift;
TRUNCATE Shift;
TRUNCATE Site;
TRUNCATE PossibleAnswer;
TRUNCATE Question;

TRUNCATE UserPermission;
TRUNCATE Permission;
TRUNCATE UserAbility;
TRUNCATE Ability;
TRUNCATE Login;
TRUNCATE PasswordReset;
TRUNCATE LoginHistory;
TRUNCATE User;
SET FOREIGN_KEY_CHECKS = 1;

-- users
INSERT INTO User (firstName, lastName, email, phoneNumber, preparesTaxes)
	VALUES ("Preparer", "McPreparer", "preparer@test.test", "555-123-4567", true);
SET @user_preparer1Id = LAST_INSERT_ID();

INSERT INTO User (firstName, lastName, email, phoneNumber, preparesTaxes)
	VALUES ("Preparer2", "MacPreparer2", "preparer2@test.test", "555-902-7563", true);
SET @user_preparer2Id = LAST_INSERT_ID();

INSERT INTO User (firstName, lastName, email, phoneNumber, preparesTaxes)
	VALUES ("Reviewer", "McReviewer", "reviewer@test.test", "555-952-7319", false);
SET @user_reviewer1Id = LAST_INSERT_ID();

INSERT INTO User (firstName, lastName, email, phoneNumber, preparesTaxes)
	VALUES ("Receptionist", "McReceptionist", "receptionist@test.test", "555-987-6543", false);
SET @user_receptionist1Id = LAST_INSERT_ID();

INSERT INTO User (firstName, lastName, email, phoneNumber, preparesTaxes)
	VALUES ("SiteAdmin", "McSiteAdmin", "siteadmin@test.test", "555-019-2837", false);
SET @user_siteAdmin1Id = LAST_INSERT_ID();
-- end users



-- login
SET @passwordHash = "$2y$10$g0OGzs2N5akgizkj0odajON8.Nr8PqpHYLhCps7lMM4YCUyJDNKUS"; -- This is the hash of "test"
INSERT INTO Login (failedLoginCount, password, lockoutTime, userId)
	VALUES (0, @passwordHash, TIMESTAMP(0), @user_preparer1Id);

INSERT INTO Login (failedLoginCount, password, lockoutTime, userId)
	VALUES (0, @passwordHash, TIMESTAMP(0), @user_reviewer1Id);

INSERT INTO Login (failedLoginCount, password, lockoutTime, userId)
	VALUES (0, @passwordHash, TIMESTAMP(0), @user_receptionist1Id);

INSERT INTO Login (failedLoginCount, password, lockoutTime, userId)
	VALUES (0, @passwordHash, TIMESTAMP(0), @user_siteAdmin1Id);
-- end login



-- Abilities
INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Basic Certification", "basic_certification", "Has completed the basic certification requirements", TRUE);
SET @ability_basicCertificationId = LAST_INSERT_ID();

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Advanced Certification", "advanced_certification", "Has completed the advanced certification requirements", TRUE);
SET @ability_advancedCertificationId = LAST_INSERT_ID();

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Worldwide Income Certification", "worldwide_income_certification", "Has completed the worldwide income certification requirements", TRUE);
SET @ability_worldwideIncomeCertificationId = LAST_INSERT_ID();

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Military Certification", "military_certification", "Has completed the military certification requirements", TRUE);
SET @ability_militaryCertificationId = LAST_INSERT_ID();

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Health Savings (HSA) Certification", "health_savings_certification", "Has completed the health savings (HSA) certification requirements", TRUE);
SET @ability_healthSavingsCertificationId = LAST_INSERT_ID();

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Spanish-Speaking", "spanish_speaking", "Can speak fluent Spanish", FALSE);
SET @ability_spanishSpeakingId = LAST_INSERT_ID();

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Vietnamese-Speaking", "viatnamese_speaking", "Can speak fluent vietnamese", FALSE);
SET @ability_vietnameseSpeakingId = LAST_INSERT_ID();

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Arabic-Speaking", "arabic_speaking", "Can speak fluent Arabic", FALSE);
SET @ability_arabicSpeakingId = LAST_INSERT_ID();

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Foreign Student Scholar Certification", "foreign_student_scholar_certification", "Has complete the foreign student scholar certification requirements", TRUE);
-- End Abilities



-- user abilities
INSERT INTO UserAbility (userId, abilityId, createdBy)
	VALUES (@user_preparer1Id, @ability_basicCertificationId, @user_siteAdmin1Id);

INSERT INTO UserAbility (userId, abilityId, createdBy)
	VALUES (@user_preparer1Id, @ability_worldwideIncomeCertificationId, @user_siteAdmin1Id);

INSERT INTO UserAbility (userId, abilityId, createdBy)
	VALUES (@user_preparer2Id, @ability_basicCertificationId, @user_siteAdmin1Id);

INSERT INTO UserAbility (userId, abilityId, createdBy)
	VALUES (@user_preparer2Id, @ability_spanishSpeakingId, @user_siteAdmin1Id);
-- end user abilities



-- permissions
INSERT INTO Permission (name, description, lookupName)
	VALUES ("Edit Permissions", "Can edit user permissions", "edit_user_permissions");
SET @permission_editUserPermissionId = LAST_INSERT_ID();

INSERT INTO Permission (name, description, lookupName)
	VALUES ("Use Admin Tools", "Can use administrative tools", "use_admin_tools");
SET @permission_useAdminToolsId = LAST_INSERT_ID();

INSERT INTO Permission (name, description, lookupName)
	VALUES ("View All Client Information", "Can view all client information (full last name, email, phone number)", "view_client_information");
SET @permission_viewClientInformationId = LAST_INSERT_ID();
-- end permissions



-- user permissions
INSERT INTO UserPermission (userId, permissionId, createdBy)
	VALUES (@user_siteAdmin1Id, @permission_editUserPermissionId, @user_siteAdmin1Id);

INSERT INTO UserPermission (userId, permissionId, createdBy)
	VALUES (@user_siteAdmin1Id, @permission_useAdminToolsId, @user_siteAdmin1Id);

INSERT INTO UserPermission (userId, permissionId, createdBy)
	VALUES (@user_siteAdmin1Id, @permission_viewClientInformationId, @user_siteAdmin1Id);
-- end user permissions



-- site
INSERT INTO Site (title, address, phoneNumber, createdBy, lastModifiedBy)
	VALUES ("Test Site", "1234 Test Ave. Lincoln, NE 86722", "555-203-2032", @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @site_site1Id = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, createdBy, lastModifiedBy)
	VALUES ("My new new site", "5656 Test test. Lincoln, NE 83747", "555-111-2345", @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @site_site2Id = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, createdBy, lastModifiedBy)
	VALUES ("No walkins site", "9876 Test St. Lincoln, NE 29384", "555-999-8888", @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @site_site3Id = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, createdBy, lastModifiedBy)
	VALUES ("Nebraska East Union", "Holdrege and 35th Streets", "402-472-6150", @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @site_nebraskaEastUnion = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, createdBy, lastModifiedBy)
	VALUES ("Anderson Library", "3635 Touzalin Ave", "402-472-9638", @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @site_andersonLibrary = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, createdBy, lastModifiedBy)
	VALUES ("Jackie Gaughan Multicultural Center", "1505 'S' Street", "402-472-9638", @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @site_jackieGaughanMulticulturalCenter = LAST_INSERT_ID();
-- End Sites

-- Shifts
-- Sunday
SET @shiftStartTime = "2018-01-21 13:00:00";
SET @shiftEndTime = "2018-01-21 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-01-21 14:30:00";
SET @shiftEndTime = "2018-01-21 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Monday
SET @shiftStartTime = "2018-01-22 16:30:00";
SET @shiftEndTime = "2018-01-22 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-01-22 18:00:00";
SET @shiftEndTime = "2018-01-22 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Tuesday
SET @shiftStartTime = "2018-01-23 16:30:00";
SET @shiftEndTime = "2018-01-23 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-01-23 18:00:00";
SET @shiftEndTime = "2018-01-23 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Wednesday, NEU
SET @shiftStartTime = "2018-01-24 16:30:00";
SET @shiftEndTime = "2018-01-24 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-01-24 18:00:00";
SET @shiftEndTime = "2018-01-24 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

-- Wednesday, AL
SET @shiftStartTime = "2018-01-24 16:30:00";
SET @shiftEndTime = "2018-01-24 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Saturday
SET @shiftStartTime = "2018-01-27 09:30:00";
SET @shiftEndTime = "2018-01-27 13:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-01-27 12:30:00";
SET @shiftEndTime = "2018-01-27 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

-- Sunday
SET @shiftStartTime = "2018-01-28 13:00:00";
SET @shiftEndTime = "2018-01-28 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-01-28 14:30:00";
SET @shiftEndTime = "2018-01-28 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Monday
SET @shiftStartTime = "2018-01-29 16:30:00";
SET @shiftEndTime = "2018-01-29 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-01-29 18:00:00";
SET @shiftEndTime = "2018-01-29 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Tuesday, NEU
SET @shiftStartTime = "2018-01-30 16:30:00";
SET @shiftEndTime = "2018-01-30 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-01-30 18:00:00";
SET @shiftEndTime = "2018-01-30 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

-- Tuesday, AL
SET @shiftStartTime = "2018-01-30 16:30:00";
SET @shiftEndTime = "2018-01-30 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Wednesday, NEU
SET @shiftStartTime = "2018-01-31 16:30:00";
SET @shiftEndTime = "2018-01-31 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-01-31 18:00:00";
SET @shiftEndTime = "2018-01-31 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

-- Wednesday, AL
SET @shiftStartTime = "2018-01-31 16:30:00";
SET @shiftEndTime = "2018-01-31 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Saturday
SET @shiftStartTime = "2018-02-03 09:30:00";
SET @shiftEndTime = "2018-02-03 13:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-03 12:30:00";
SET @shiftEndTime = "2018-02-03 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Sunday 
SET @shiftStartTime = "2018-02-04 13:00:00";
SET @shiftEndTime = "2018-02-04 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-04 14:30:00";
SET @shiftEndTime = "2018-02-04 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Monday
SET @shiftStartTime = "2018-02-05 16:30:00";
SET @shiftEndTime = "2018-02-05 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-05 18:00:00";
SET @shiftEndTime = "2018-02-05 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Tuesday, NEU
SET @shiftStartTime = "2018-02-06 16:30:00";
SET @shiftEndTime = "2018-02-06 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-06 18:00:00";
SET @shiftEndTime = "2018-02-06 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

-- Tuesday, AL
SET @shiftStartTime = "2018-02-06 16:30:00";
SET @shiftEndTime = "2018-02-06 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Wednesday, NEU
SET @shiftStartTime = "2018-02-07 16:30:00";
SET @shiftEndTime = "2018-02-07 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-07 18:00:00";
SET @shiftEndTime = "2018-02-07 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

-- Wednesday, AL
SET @shiftStartTime = "2018-02-07 16:30:00";
SET @shiftEndTime = "2018-02-07 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Saturday
SET @shiftStartTime = "2018-02-10 09:30:00";
SET @shiftEndTime = "2018-02-10 13:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-10 12:30:00";
SET @shiftEndTime = "2018-02-10 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Sunday
SET @shiftStartTime = "2018-02-11 13:00:00";
SET @shiftEndTime = "2018-02-11 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-11 14:30:00";
SET @shiftEndTime = "2018-02-11 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Monday
SET @shiftStartTime = "2018-02-12 16:30:00";
SET @shiftEndTime = "2018-02-12 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-12 18:00:00";
SET @shiftEndTime = "2018-02-12 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Tuesday, NEU
SET @shiftStartTime = "2018-02-13 16:30:00";
SET @shiftEndTime = "2018-02-13 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-13 18:00:00";
SET @shiftEndTime = "2018-02-13 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

-- Tuesday, AL
SET @shiftStartTime = "2018-02-13 16:30:00";
SET @shiftEndTime = "2018-02-13 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Wednesday, NEU
SET @shiftStartTime = "2018-02-14 16:30:00";
SET @shiftEndTime = "2018-02-14 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-14 18:00:00";
SET @shiftEndTime = "2018-02-14 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

-- Wednesday, AL
SET @shiftStartTime = "2018-02-14 16:30:00";
SET @shiftEndTime = "2018-02-14 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Saturday
SET @shiftStartTime = "2018-02-17 09:30:00";
SET @shiftEndTime = "2018-02-17 13:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-17 12:30:00";
SET @shiftEndTime = "2018-02-17 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Sunday
SET @shiftStartTime = "2018-02-18 13:00:00";
SET @shiftEndTime = "2018-02-18 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-18 14:30:00";
SET @shiftEndTime = "2018-02-18 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Monday
SET @shiftStartTime = "2018-02-19 16:30:00";
SET @shiftEndTime = "2018-02-19 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-19 18:00:00";
SET @shiftEndTime = "2018-02-19 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Tuesday, NEU
SET @shiftStartTime = "2018-02-20 16:30:00";
SET @shiftEndTime = "2018-02-20 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-20 18:00:00";
SET @shiftEndTime = "2018-02-20 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

-- Tuesday, AL
SET @shiftStartTime = "2018-02-20 16:30:00";
SET @shiftEndTime = "2018-02-20 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Wednesday, NEU
SET @shiftStartTime = "2018-02-21 16:30:00";
SET @shiftEndTime = "2018-02-21 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-21 18:00:00";
SET @shiftEndTime = "2018-02-21 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

-- Wednesday, AL
SET @shiftStartTime = "2018-02-21 16:30:00";
SET @shiftEndTime = "2018-02-21 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Saturday
SET @shiftStartTime = "2018-02-24 09:30:00";
SET @shiftEndTime = "2018-02-24 13:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-24 12:30:00";
SET @shiftEndTime = "2018-02-24 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Sunday
SET @shiftStartTime = "2018-02-25 13:00:00";
SET @shiftEndTime = "2018-02-25 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-25 14:30:00";
SET @shiftEndTime = "2018-02-25 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Monday
SET @shiftStartTime = "2018-02-26 16:30:00";
SET @shiftEndTime = "2018-02-26 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-26 18:00:00";
SET @shiftEndTime = "2018-02-26 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Tuesday, NEU
SET @shiftStartTime = "2018-02-27 16:30:00";
SET @shiftEndTime = "2018-02-27 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-27 18:00:00";
SET @shiftEndTime = "2018-02-27 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

-- Tuesday, AL
SET @shiftStartTime = "2018-02-27 16:30:00";
SET @shiftEndTime = "2018-02-27 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Wednesday, NEU
SET @shiftStartTime = "2018-02-28 16:30:00";
SET @shiftEndTime = "2018-02-28 18:30:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-02-28 18:00:00";
SET @shiftEndTime = "2018-02-28 20:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

-- Wednesday, AL
SET @shiftStartTime = "2018-02-28 16:30:00";
SET @shiftEndTime = "2018-02-28 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Saturday
SET @shiftStartTime = "2018-03-03 09:30:00";
SET @shiftEndTime = "2018-03-03 13:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-03-03 12:30:00";
SET @shiftEndTime = "2018-03-03 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Sunday
SET @shiftStartTime = "2018-03-04 13:00:00";
SET @shiftEndTime = "2018-03-04 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-03-04 14:30:00";
SET @shiftEndTime = "2018-03-04 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Tuesday
SET @shiftStartTime = "2018-03-06 16:30:00";
SET @shiftEndTime = "2018-03-06 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Wednesday
SET @shiftStartTime = "2018-03-07 16:30:00";
SET @shiftEndTime = "2018-03-07 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Saturday
SET @shiftStartTime = "2018-03-10 09:30:00";
SET @shiftEndTime = "2018-03-10 13:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-03-10 12:30:00";
SET @shiftEndTime = "2018-03-10 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_nebraskaEastUnion, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Sunday
SET @shiftStartTime = "2018-03-11 13:00:00";
SET @shiftEndTime = "2018-03-11 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-03-11 14:30:00";
SET @shiftEndTime = "2018-03-11 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Tuesday
SET @shiftStartTime = "2018-03-13 16:30:00";
SET @shiftEndTime = "2018-03-13 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Wednesday
SET @shiftStartTime = "2018-03-14 16:30:00";
SET @shiftEndTime = "2018-03-14 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Tuesday
SET @shiftStartTime = "2018-03-20 16:30:00";
SET @shiftEndTime = "2018-03-20 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Wednesday
SET @shiftStartTime = "2018-03-21 16:30:00";
SET @shiftEndTime = "2018-03-21 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Tuesday
SET @shiftStartTime = "2018-03-27 16:30:00";
SET @shiftEndTime = "2018-03-27 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Wednesday
SET @shiftStartTime = "2018-03-28 16:30:00";
SET @shiftEndTime = "2018-03-28 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Sunday
SET @shiftStartTime = "2018-04-01 13:00:00";
SET @shiftEndTime = "2018-04-01 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-04-01 14:30:00";
SET @shiftEndTime = "2018-04-01 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Tuesday
SET @shiftStartTime = "2018-04-03 16:30:00";
SET @shiftEndTime = "2018-04-03 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Wednesday
SET @shiftStartTime = "2018-04-04 16:30:00";
SET @shiftEndTime = "2018-04-04 19:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_andersonLibrary, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Sunday
SET @shiftStartTime = "2018-04-08 13:00:00";
SET @shiftEndTime = "2018-04-08 15:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);

SET @shiftStartTime = "2018-04-08 14:30:00";
SET @shiftEndTime = "2018-04-08 16:00:00";
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_jackieGaughanMulticulturalCenter, @user_siteAdmin1Id, @user_siteAdmin1Id);


-- Other test data shifts
SET @shiftStartTime = DATE_ADD(NOW(), INTERVAL 1 HOUR);
SET @shiftEndTime = DATE_ADD(@shiftStartTime, INTERVAL 1 HOUR);
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_site1Id, @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @shift_site1Shift1Id = LAST_INSERT_ID();

SET @shiftStartTime = DATE_ADD(@shiftStartTime, INTERVAL 1 DAY);
SET @shiftEndTime = DATE_ADD(@shiftStartTime, INTERVAL 3 HOUR);
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_site1Id, @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @shift_site1Shift2Id = LAST_INSERT_ID();

SET @shiftStartTime = DATE_ADD(NOW(), INTERVAL 1 MONTH);
SET @shiftEndTime = DATE_ADD(@shiftStartTime, INTERVAL 4 HOUR);
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_site2Id, @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @shift_site2Shift1Id = LAST_INSERT_ID();

SET @shiftStartTime = DATE_ADD(@shiftStartTime, INTERVAL 3 DAY);
SET @shiftEndTime = DATE_ADD(@shiftStartTime, INTERVAL 2 HOUR);
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_site2Id, @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @shift_site2Shift2Id = LAST_INSERT_ID();

SET @shiftStartTime = DATE_ADD(@shiftStartTime, INTERVAL 1 DAY);
SET @shiftEndTime = DATE_ADD(@shiftStartTime, INTERVAL 3 HOUR);
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy)
	VALUES (@shiftStartTime, @shiftEndTime, @site_site2Id, @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @shift_site2Shift3Id = LAST_INSERT_ID();
-- end shift



-- user shift
INSERT INTO UserShift (userId, shiftId)
	VALUES (@user_preparer1Id, @shift_site1Shift1Id);

INSERT INTO UserShift (userId, shiftId)
	VALUES (@user_preparer1Id, @shift_site1Shift2Id);

INSERT INTO UserShift (userId, shiftId)
	VALUES (@user_preparer2Id, @shift_site1Shift2Id);

INSERT INTO UserShift (userId, shiftId)
	VALUES (@user_preparer2Id, @shift_site2Shift3Id);

INSERT INTO UserShift (userId, shiftId)
	VALUES (@user_receptionist1Id, @shift_site1Shift1Id);

INSERT INTO UserShift (userId, shiftId)
	VALUES (@user_receptionist1Id, @shift_site1Shift2Id);

INSERT INTO UserShift (userId, shiftId)
	VALUES (@user_reviewer1Id, @shift_site2Shift1Id);

INSERT INTO UserShift (userId, shiftId)
	VALUES (@user_reviewer1Id, @shift_site2Shift2Id);

INSERT INTO UserShift (userId, shiftId)
	VALUES (@user_reviewer1Id, @shift_site2Shift3Id);
-- end user shift



-- client
INSERT INTO Client (firstName, lastName, emailAddress)
	VALUES ("Clienty", "McClientFace", "clientmcclientface@test.test");
SET @client_client1Id = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, emailAddress)
	VALUES ("Barry", "Tester", "barrytester@test.test");
SET @client_client2Id = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, emailAddress)
	VALUES ("Frank", "UnderTest", "frankundertest@test.test");
SET @client_client3Id = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, emailAddress)
	VALUES ("Laura", "McTest", "lauramctest@test.test");
SET @client_client4Id = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, emailAddress)
	VALUES ("DoneBoy", "DoneTest", "doneboydonetest@test.test");
SET @client_client5Id = LAST_INSERT_ID();
-- end client



-- appointmentTime
SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site1Shift1Id), INTERVAL 0 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, minimumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site1Id, 5);
SET @appointmentTime_site1Shift1Time0 = LAST_INSERT_ID();


SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site1Shift2Id), INTERVAL 0 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, minimumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site1Id, 5);
SET @appointmentTime_site1Shift2Time0 = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site1Shift2Id), INTERVAL 60 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, minimumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site1Id, 5);
SET @appointmentTime_site1Shift2Time1 = LAST_INSERT_ID();
   
SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site1Shift2Id), INTERVAL 120 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, minimumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site1Id, 5);
SET @appointmentTime_site1Shift2Time2 = LAST_INSERT_ID();


SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site2Shift1Id), INTERVAL 0 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, maximumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site2Id, 30);
SET @appointmentTime_site2Shift1Time0 = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site2Shift1Id), INTERVAL 60 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, maximumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site2Id, 30);
SET @appointmentTime_site2Shift1Time1 = LAST_INSERT_ID();
   
SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site2Shift1Id), INTERVAL 120 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, maximumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site2Id, 30);
SET @appointmentTime_site2Shift1Time2 = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site2Shift1Id), INTERVAL 180 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, maximumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site2Id, 30);
SET @appointmentTime_site2Shift1Time3 = LAST_INSERT_ID();


SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site2Shift2Id), INTERVAL 0 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, maximumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site2Id, 30);
SET @appointmentTime_site2Shift2Time0 = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site2Shift2Id), INTERVAL 60 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, maximumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site2Id, 30);
SET @appointmentTime_site2Shift2Time1 = LAST_INSERT_ID();


SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site2Shift3Id), INTERVAL 0 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, maximumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site2Id, 30);
SET @appointmentTime_site2Shift3Time0 = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site2Shift3Id), INTERVAL 60 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, maximumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site2Id, 30);
SET @appointmentTime_site2Shift3Time1 = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site2Shift3Id), INTERVAL 120 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, maximumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site2Id, 30);
SET @appointmentTime_site2Shift3Time2 = LAST_INSERT_ID();


-- Already serviced appointment
SET @appointmentTime = DATE_ADD(NOW(), INTERVAL -1 DAY);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, maximumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site2Id, 30);
SET @appointmentTime_site2AppointmentTime5Id = LAST_INSERT_ID();

-- Appointments for today (note that this is just for testing queue functionality)
SET @appointmentTime = DATE_ADD(NOW(), INTERVAL -5 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, minimumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site1Id, 5);
SET @appointmentTime_site1AppointmentTime6Id = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 5 HOUR);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, minimumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site1Id, 5);
SET @appointmentTime_site1AppointmentTime7Id = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 30 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, minimumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site1Id, 5);
SET @appointmentTime_site1AppointmentTime8Id = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 45 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, percentageAppointments, siteId, maximumNumberOfAppointments)
	VALUES (@appointmentTime, 100, @site_site2Id, 30);
SET @appointmentTime_site2AppointmentTime9Id = LAST_INSERT_ID();
-- end appointmentTime



-- appointment
INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site1Shift1Time0, @client_client1Id, "eng", "localhost");
SET @appointment_appointment1Id = LAST_INSERT_ID();

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site1Shift2Time0, @client_client2Id, "eng", "localhost");

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site1Shift2Time2, @client_client2Id, "spa", "localhost");
SET @appointment_appointment2Id = LAST_INSERT_ID();

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site2Shift1Time3, @client_client3Id, "eng", "localhost");
SET @appointment_appointment3Id = LAST_INSERT_ID();

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site2Shift2Time1, @client_client4Id, "vie", "localhost");
SET @appointment_appointment4Id = LAST_INSERT_ID();

-- Already serviced appointment
INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site2AppointmentTime5Id, @client_client5Id, "eng", "localhost");
SET @appointment_appointment5Id = LAST_INSERT_ID();

-- Appointments for today (note that this is just for testing queue functionality)
INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site1AppointmentTime6Id, @client_client1Id, "eng", "localhost");

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site1AppointmentTime7Id, @client_client1Id, "eng", "localhost");

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site1AppointmentTime8Id, @client_client2Id, "eng", "localhost");

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site2AppointmentTime9Id, @client_client3Id, "eng", "localhost");
-- end appointment



-- serviced appointment
SET @timeIn = DATE_ADD((SELECT scheduledTime FROM AppointmentTime WHERE appointmentTimeId = (SELECT appointmentTimeId FROM Appointment WHERE appointmentId = @appointment_appointment5Id)), INTERVAL 5 MINUTE);
INSERT INTO ServicedAppointment (timeIn, servicedBy, appointmentId)
	VALUES (@timeIn, @user_preparer1Id, @appointment_appointment5Id);
-- end serviced appointment



-- questions
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
	VALUES ("Have you been on this visa for less than 183 days and in the United States for less than five years?", "visa_less_than_183_days");
SET @question_question5Id = LAST_INSERT_ID();
-- end question



-- possible answer
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
	VALUES ("5 full years or more");
SET @possibleAnswer_5FullYearsOrMoreId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("Less than 5 full years");
SET @possibleAnswer_LessThan5FullYearsId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("2 full years or more");
SET @possibleAnswer_2FullYearsOrMoreId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("Less than 2 full years");
SET @possibleAnswer_LessThan2FullYearsd = LAST_INSERT_ID();
-- end possible answer



-- answer
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_appointment1Id, @question_question1Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment1Id, @question_question2Id);


INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment2Id, @question_question1Id);


INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_appointment3Id, @question_question1Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_appointment3Id, @question_question2Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_f1Id, @appointment_appointment3Id, @question_question3Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_5FullYearsOrMoreId, @appointment_appointment3Id, @question_question4Id);
	

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_appointment4Id, @question_question1Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_appointment4Id, @question_question2Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_j1Id, @appointment_appointment4Id, @question_question3Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_LessThan2FullYearsd, @appointment_appointment4Id, @question_question4Id);
-- end answer


-- load testing for Appointments
DROP PROCEDURE IF EXISTS sp_CreateAppointments;
DELIMITER $$
CREATE PROCEDURE sp_CreateAppointments(IN numAppointments INT, IN startingSiteId INT, IN endingSiteId INT)
BEGIN
	DECLARE minIntervalValue INT DEFAULT 1;
	DECLARE maxIntervalValue INT DEFAULT 300; # 5 hours

	DECLARE i INT DEFAULT 0;

	SET @min = (SELECT MIN(appointmentTimeid) FROM AppointmentTime);
	SET @max = (SELECT MAX(appointmentTimeid) FROM AppointmentTime);

	START TRANSACTION;
	WHILE i < numAppointments DO
		-- create random client
		SET @firstName = LEFT(UUID(), 20);
		SET @lastName = LEFT(UUID(), 20);
		SET @emailAddress = CONCAT(LEFT(UUID(), 20), "@test.test");
		INSERT INTO Client (firstName, lastName, emailAddress)
			VALUES (@firstName, @lastName, @emailAddress);
		SET @clientIdForThisAppointment = LAST_INSERT_ID();

		-- create appointment
		SET @appointmentTimeIdForThisAppointment = @min + ROUND(RAND() * (@max - @min));
		INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
			VALUES (@appointmentTimeIdForThisAppointment, @clientIdForThisAppointment, "eng", "localhost");
		SET i = i + 1;
	END WHILE;
	COMMIT;
END$$
DELIMITER ;

CALL sp_CreateAppointments(200, @site_site1Id, @site_site2Id);
-- end load testing for Appointments
