USE vita;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE Answer;
TRUNCATE ServicedAppointment;
TRUNCATE SelfServiceAppointmentRescheduleToken;
TRUNCATE Note;
TRUNCATE Appointment;
TRUNCATE AppointmentTime;
TRUNCATE AppointmentType;
TRUNCATE Client;
TRUNCATE Site;
TRUNCATE PossibleAnswer;
TRUNCATE Question;

TRUNCATE UserPermission;
TRUNCATE Permission;
TRUNCATE Login;
TRUNCATE PasswordReset;
TRUNCATE LoginHistory;
TRUNCATE User;
SET FOREIGN_KEY_CHECKS = 1;

-- users
INSERT INTO User (firstName, lastName, email, phoneNumber)
	VALUES ("Preparer", "McPreparer", "preparer@test.test", "555-123-4567");
SET @user_preparer1Id = LAST_INSERT_ID();

INSERT INTO User (firstName, lastName, email, phoneNumber)
	VALUES ("Preparer2", "MacPreparer2", "preparer2@test.test", "555-902-7563");
SET @user_preparer2Id = LAST_INSERT_ID();

INSERT INTO User (firstName, lastName, email, phoneNumber)
	VALUES ("Reviewer", "McReviewer", "reviewer@test.test", "555-952-7319");
SET @user_reviewer1Id = LAST_INSERT_ID();

INSERT INTO User (firstName, lastName, email, phoneNumber)
	VALUES ("Receptionist", "McReceptionist", "receptionist@test.test", "555-987-6543");
SET @user_receptionist1Id = LAST_INSERT_ID();

INSERT INTO User (firstName, lastName, email, phoneNumber)
	VALUES ("SiteAdmin", "McSiteAdmin", "siteadmin@test.test", "555-019-2837");
SET @user_siteAdmin1Id = LAST_INSERT_ID();
-- end users



-- login
SET @passwordHash = "$2y$10$g0OGzs2N5akgizkj0odajON8.Nr8PqpHYLhCps7lMM4YCUyJDNKUS"; -- This is the hash of "test"
SET @lockoutTime = NOW() - INTERVAL 1 HOUR;
INSERT INTO Login (failedLoginCount, password, lockoutTime, userId)
	VALUES (0, @passwordHash, @lockoutTime, @user_preparer1Id);

INSERT INTO Login (failedLoginCount, password, lockoutTime, userId)
	VALUES (0, @passwordHash, @lockoutTime, @user_reviewer1Id);

INSERT INTO Login (failedLoginCount, password, lockoutTime, userId)
	VALUES (0, @passwordHash, @lockoutTime, @user_receptionist1Id);

INSERT INTO Login (failedLoginCount, password, lockoutTime, userId)
	VALUES (0, @passwordHash, @lockoutTime, @user_siteAdmin1Id);
-- end login




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
	VALUES ("International Student Scholar", "1400 R St, Lincoln, NE 68588", "402-472-9638", @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @site_internationalStudentScholar = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, createdBy, lastModifiedBy)
	VALUES ("Virtual Site", "", "402-472-9638", @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @site_virtualId = LAST_INSERT_ID();
-- End Sites




-- client
INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("Clienty", "McClientFace", "402-555-1234", "clientmcclientface@test.test");
SET @client_client1Id = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("Barry", "Tester", "402-555-4321", "barrytester@test.test");
SET @client_client2Id = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("Frank", "UnderTest", "402-555-2323", "frankundertest@test.test");
SET @client_client3Id = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("Laura", "McTest", "402-555-0101", "lauramctest@test.test");
SET @client_client4Id = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("DoneBoy", "DoneTest", "402-555-2222", "doneboydonetest@test.test");
SET @client_client5Id = LAST_INSERT_ID();

-- International clients
INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("ChinaInternational", "InternationalTest", "402-555-3212", "chinainternationaltest@test.test");
SET @client_chinaInternationalId = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("IndiaInternational", "InternationalTest", "402-555-6323", "indiainternationaltest@test.test");
SET @client_indiaInternationalId = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("TreatyInternational", "InternationalTest", "402-555-8273", "treatyinternationaltest@test.test");
SET @client_treatyInternationalId = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("NonTreatyInternational", "InternationalTest", "402-555-4329", "nontreatyinternationaltest@test.test");
SET @client_nonTreatyInternationalId = LAST_INSERT_ID();
-- end client



-- appointmentType
INSERT INTO AppointmentType (name, lookupName)
	VALUES 	('Residential', 'residential');
SET @appointmentType_residentialId = LAST_INSERT_ID();

INSERT INTO AppointmentType (name, lookupName)
	VALUES 	('China', 'china');
SET @appointmentType_chinaId = LAST_INSERT_ID();

INSERT INTO AppointmentType (name, lookupName)
	VALUES 	('India', 'india');
SET @appointmentType_indiaId = LAST_INSERT_ID();

INSERT INTO AppointmentType (name, lookupName)
	VALUES 	('Treaty', 'treaty');
SET @appointmentType_treatyId = LAST_INSERT_ID();

INSERT INTO AppointmentType (name, lookupName)
	VALUES 	('Non-Treaty', 'non-treaty');
SET @appointmentType_nonTreatyId = LAST_INSERT_ID();

INSERT INTO AppointmentType (name, lookupName)
	VALUES 	('Virtual Residential', 'virtual-residential');
SET @appointmentType_virtualResidentialId = LAST_INSERT_ID();

INSERT INTO AppointmentType (name, lookupName)
	VALUES 	('Virtual China', 'virtual-china');
SET @appointmentType_virtualChinaId = LAST_INSERT_ID();

INSERT INTO AppointmentType (name, lookupName)
	VALUES 	('Virtual India', 'virtual-india');
SET @appointmentType_virtualIndiaId = LAST_INSERT_ID();

INSERT INTO AppointmentType (name, lookupName)
	VALUES 	('Virtual Treaty', 'virtual-treaty');
SET @appointmentType_virtualTreatyId = LAST_INSERT_ID();

INSERT INTO AppointmentType (name, lookupName)
	VALUES 	('Virtual Non-Treaty', 'virtual-non-treaty');
SET @appointmentType_virtualNonTreatyId = LAST_INSERT_ID();
-- end appointmentType



-- appointmentTime
SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 1 HOUR);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 5, @site_site1Id, @appointmentType_residentialId);
SET @appointmentTime_site1AppointmentTime0 = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(DATE_ADD(@appointmentTime, INTERVAL 1 DAY), INTERVAL 60 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 5, @site_site1Id, @appointmentType_residentialId);
SET @appointmentTime_site1AppointmentTime1 = LAST_INSERT_ID();
   
SET @appointmentTime = DATE_ADD(@appointmentTime, INTERVAL 120 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 5, @site_site1Id, @appointmentType_residentialId);
SET @appointmentTime_site1AppointmentTime2 = LAST_INSERT_ID();


SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 1 MONTH);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 30, @site_site2Id, @appointmentType_residentialId);
SET @appointmentTime_site2AppointmentTime0 = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(@appointmentTime, INTERVAL 60 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 30, @site_site2Id, @appointmentType_residentialId);
SET @appointmentTime_site2AppointmentTime1 = LAST_INSERT_ID();
   
SET @appointmentTime = DATE_ADD(@appointmentTime, INTERVAL 120 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 30, @site_site2Id, @appointmentType_residentialId);
SET @appointmentTime_site2AppointmentTime2 = LAST_INSERT_ID();



SET @appointmentTime = DATE_ADD(@appointmentTime, INTERVAL 3 DAY);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 30, @site_site2Id, @appointmentType_residentialId);
SET @appointmentTime_site2AppointmentTime0 = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(@appointmentTime, INTERVAL 60 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 30, @site_site2Id, @appointmentType_residentialId);
SET @appointmentTime_site2AppointmentTime1 = LAST_INSERT_ID();


SET @appointmentTime = DATE_ADD(@appointmentTime, INTERVAL 1 DAY);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 30, @site_site2Id, @appointmentType_residentialId);
SET @appointmentTime_site2AppointmentTime0 = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(@appointmentTime, INTERVAL 60 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 30, @site_site2Id, @appointmentType_residentialId);
SET @appointmentTime_site2AppointmentTime1 = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(@appointmentTime, INTERVAL 120 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 30, @site_site2Id, @appointmentType_residentialId);
SET @appointmentTime_site2AppointmentTime2 = LAST_INSERT_ID();


-- Already serviced appointment
SET @appointmentTime = DATE_ADD(NOW(), INTERVAL -1 DAY);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 30, @site_site2Id, @appointmentType_residentialId);
SET @appointmentTime_site2AppointmentTime5Id = LAST_INSERT_ID();

-- Appointments for today (note that this is just for testing queue functionality)
SET @appointmentTime = DATE_ADD(NOW(), INTERVAL -5 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 5, @site_site1Id, @appointmentType_residentialId);
SET @appointmentTime_site1AppointmentTime6Id = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 5 HOUR);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 5, @site_site1Id, @appointmentType_residentialId);
SET @appointmentTime_site1AppointmentTime7Id = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 30 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 5, @site_site1Id, @appointmentType_residentialId);
SET @appointmentTime_site1AppointmentTime8Id = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 45 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 30, @site_site2Id, @appointmentType_residentialId);
SET @appointmentTime_site2AppointmentTime9Id = LAST_INSERT_ID();

-- Appointment times for international site
SET @appointmentTime = DATE_ADD(@appointmentTime, INTERVAL 1 DAY);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 10, @site_internationalStudentScholar, @appointmentType_chinaId);
SET @appointmentTime_internationalSiteTime0Id = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(@appointmentTime, INTERVAL 60 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 10, @site_internationalStudentScholar, @appointmentType_indiaId);
SET @appointmentTime_internationalSiteTime1Id = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(@appointmentTime, INTERVAL 120 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 5, @site_internationalStudentScholar, @appointmentType_treatyId);
SET @appointmentTime_internationalSiteTime2Id = LAST_INSERT_ID();

-- Appointment times for virtual site
SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 1 DAY);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 10, @site_virtualId, @appointmentType_virtualResidentialId);

SET @appointmentTime = DATE_ADD(@appointmentTime, INTERVAL 1 WEEK);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 10, @site_virtualId, @appointmentType_virtualResidentialId);
-- end appointmentTime



-- appointment
INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site1AppointmentTime0, @client_client1Id, "eng", "localhost");
SET @appointment_appointment1Id = LAST_INSERT_ID();

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site1AppointmentTime1, @client_client2Id, "eng", "localhost");

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site1AppointmentTime2, @client_client2Id, "spa", "localhost");
SET @appointment_appointment2Id = LAST_INSERT_ID();

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site1AppointmentTime2, @client_client3Id, "eng", "localhost");
SET @appointment_appointment3Id = LAST_INSERT_ID();

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site1AppointmentTime1, @client_client4Id, "vie", "localhost");
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

-- International appointments
INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_internationalSiteTime0Id, @client_chinaInternationalId, "eng", "localhost");
SET @appointment_chinaId = LAST_INSERT_ID();

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_internationalSiteTime1Id, @client_indiaInternationalId, "eng", "localhost");
SET @appointment_indiaId = LAST_INSERT_ID();

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_internationalSiteTime2Id, @client_treatyInternationalId, "eng", "localhost");
SET @appointment_treatyId = LAST_INSERT_ID();

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_internationalSiteTime2Id, @client_nonTreatyInternationalId, "eng", "localhost");
SET @appointment_nonTreatyId = LAST_INSERT_ID();
-- end appointment



-- serviced appointment
SET @timeIn = DATE_ADD((SELECT scheduledTime FROM AppointmentTime WHERE appointmentTimeId = (SELECT appointmentTimeId FROM Appointment WHERE appointmentId = @appointment_appointment5Id)), INTERVAL 5 MINUTE);
INSERT INTO ServicedAppointment (timeIn, appointmentId)
	VALUES (@timeIn, @appointment_appointment5Id);
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

INSERT INTO Question (text, lookupName)
	VALUES ("What country are you a citizen or resident of?", "treaty_type");
SET @question_question6Id = LAST_INSERT_ID();
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
SET @possibleAnswer_LessThan2FullYearId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("china");
SET @possibleAnswer_chinaId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("india");
SET @possibleAnswer_indiaId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("treaty");
SET @possibleAnswer_treatyId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text)
	VALUES ("non-treaty");
SET @possibleAnswer_nonTreatyId = LAST_INSERT_ID();
-- end possible answer



-- answer
-- Client 1
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_appointment1Id, @question_question1Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment1Id, @question_question2Id);

-- Client 2
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment2Id, @question_question1Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment2Id, @question_question2Id);

-- Client 3
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_appointment3Id, @question_question1Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment3Id, @question_question2Id);

-- Client 4
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_appointment4Id, @question_question1Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment4Id, @question_question2Id);

-- China client
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_chinaId, @question_question1Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_chinaId, @question_question2Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_f1Id, @appointment_chinaId, @question_question3Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_LessThan5FullYearsId, @appointment_chinaId, @question_question4Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_chinaId, @appointment_chinaId, @question_question6Id);

-- India client
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_indiaId, @question_question1Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_indiaId, @question_question2Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_f1Id, @appointment_indiaId, @question_question3Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_LessThan5FullYearsId, @appointment_indiaId, @question_question4Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_indiaId, @appointment_indiaId, @question_question6Id);

-- Treaty client
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_treatyId, @question_question1Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_treatyId, @question_question2Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_f1Id, @appointment_treatyId, @question_question3Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_LessThan5FullYearsId, @appointment_treatyId, @question_question4Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_treatyId, @appointment_treatyId, @question_question6Id);

-- Non-treaty client
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_nonTreatyId, @question_question1Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_nonTreatyId, @question_question2Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_f1Id, @appointment_nonTreatyId, @question_question3Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_LessThan5FullYearsId, @appointment_nonTreatyId, @question_question4Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_nonTreatyId, @appointment_nonTreatyId, @question_question6Id);
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
