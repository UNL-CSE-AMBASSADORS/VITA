USE vita;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE Answer;
TRUNCATE ServicedAppointment;
TRUNCATE Appointment;
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



-- abilities
INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Basic Certification", "basic_certification", "Has finished the basic certification requirements", TRUE);
SET @ability_basicCertificationId = LAST_INSERT_ID();

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("International Certification", "international_certification", "Has completed the international certification requirements", TRUE);
SET @ability_internationalCertificationId = LAST_INSERT_ID();

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Military Certification", "military_certification", "Has completed the military certification requirements", TRUE);
SET @ability_militaryCertificationId = LAST_INSERT_ID();

INSERT INTO Ability (name, lookupName, description, verificationRequired)
	VALUES ("Spanish-Speaking", "spanish_speaking", "Can speak fluent Spanish", FALSE);
SET @ability_spanishSpeakingId = LAST_INSERT_ID();
-- end abilities



-- user abilities
INSERT INTO UserAbility (userId, abilityId, createdBy)
	VALUES (@user_preparer1Id, @ability_basicCertificationId, @user_siteAdmin1Id);

INSERT INTO UserAbility (userId, abilityId, createdBy)
	VALUES (@user_preparer1Id, @ability_internationalCertificationId, @user_siteAdmin1Id);

INSERT INTO UserAbility (userId, abilityId, createdBy)
	VALUES (@user_preparer2Id, @ability_basicCertificationId, @user_siteAdmin1Id);

INSERT INTO UserAbility (userId, abilityId, createdBy)
	VALUES (@user_preparer2Id, @ability_spanishSpeakingId, @user_siteAdmin1Id);
-- end user abilities



-- permissions
INSERT INTO Permission (name, description, lookupName)
	VALUES ("Add Site", "Can create a new VITA site on the add site page", "add_site");
SET @permission_addSiteId = LAST_INSERT_ID();

INSERT INTO Permission (name, description, lookupName)
	VALUES ("Edit Site Information", "Can edit the information associated with sites", "edit_site_information");
SET @permission_editSiteInformationId = LAST_INSERT_ID();

INSERT INTO Permission (name, description, lookupName)
	VALUES ("Edit Permissions", "Can edit user permissions", "edit_user_permissions");
SET @permission_editUserPermissionId = LAST_INSERT_ID();

INSERT INTO Permission (name, description, lookupName)
	VALUES ("Use Admin Tools", "Can use administrative tools", "use_admin_tools");
SET @permission_useAdminToolsId = LAST_INSERT_ID();
-- end permissions



-- user permissions
INSERT INTO UserPermission (userId, permissionId, createdBy)
	VALUES (@user_siteAdmin1Id, @permission_addSiteId, @user_siteAdmin1Id);

INSERT INTO UserPermission (userId, permissionId, createdBy)
	VALUES (@user_siteAdmin1Id, @permission_editUserPermissionId, @user_siteAdmin1Id);

INSERT INTO UserPermission (userId, permissionId, createdBy)
	VALUES (@user_siteAdmin1Id, @permission_editSiteInformationId, @user_siteAdmin1Id);

INSERT INTO UserPermission (userId, permissionId, createdBy)
	VALUES (@user_siteAdmin1Id, @permission_useAdminToolsId, @user_siteAdmin1Id);
-- end user permissions



-- site
INSERT INTO Site (title, address, phoneNumber, appointmentOnly, createdBy, lastModifiedBy)
	VALUES ("Test Site", "1234 Test Ave. Lincoln, NE 86722", "555-203-2032", false, @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @site_site1Id = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, appointmentOnly, createdBy, lastModifiedBy)
	VALUES ("My new new site", "5656 Test test. Lincoln, NE 83747", "555-111-2345", false, @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @site_site2Id = LAST_INSERT_ID();

INSERT INTO Site (title, address, phoneNumber, appointmentOnly, createdBy, lastModifiedBy)
	VALUES ("No walkins site", "9876 Test St. Lincoln, NE 29384", "555-999-8888", true, @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @site_site3Id = LAST_INSERT_ID();
-- end site



-- shift
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



-- appointment
SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site1Shift1Id), INTERVAL 0 MINUTE);
INSERT INTO Appointment (scheduledTime, clientId, siteId, language, ipAddress)
	VALUES (@appointmentTime, @client_client1Id, @site_site1Id, "en", "localhost");
SET @appointment_appointment1Id = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site1Shift2Id), INTERVAL 30 MINUTE);
INSERT INTO Appointment (scheduledTime, clientId, siteId, language, ipAddress)
	VALUES (@appointmentTime, @client_client2Id, @site_site1Id, "sp", "localhost");
SET @appointment_appointment2Id = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site2Shift1Id), INTERVAL 0 MINUTE);
INSERT INTO Appointment (scheduledTime, clientId, siteId, language, ipAddress)
	VALUES (@appointmentTime, @client_client3Id, @site_site2Id, "en", "localhost");
SET @appointment_appointment3Id = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site2Shift2Id), INTERVAL 30 MINUTE);
INSERT INTO Appointment (scheduledTime, clientId, siteId, language, ipAddress)
	VALUES (@appointmentTime, @client_client4Id, @site_site2Id, "vi", "localhost");
SET @appointment_appointment4Id = LAST_INSERT_ID();

-- Already serviced appointment
SET @appointmentTime = DATE_ADD(NOW(), INTERVAL -1 DAY);
INSERT INTO Appointment (scheduledTime, clientId, siteId, language, ipAddress)
	VALUES (@appointmentTime, @client_client5Id, @site_site1Id, "en", "localhost");
SET @appointment_appointment5Id = LAST_INSERT_ID();

-- Appointments for today (note that this is just for testing queue functionality)
SET @appointmentTime = DATE_ADD(NOW(), INTERVAL -5 MINUTE);
INSERT INTO Appointment (scheduledTime, clientId, siteId, language, ipAddress)
	VALUES (@appointmentTime, @client_client1Id, @site_site1Id, "en", "localhost");

SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 5 HOUR);
INSERT INTO Appointment (scheduledTime, clientId, siteId, language, ipAddress)
	VALUES (@appointmentTime, @client_client1Id, @site_site1Id, "en", "localhost");

SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 30 MINUTE);
INSERT INTO Appointment (scheduledTime, clientId, siteId, language, ipAddress)
	VALUES (@appointmentTime, @client_client2Id, @site_site1Id, "en", "localhost");

SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 45 MINUTE);
INSERT INTO Appointment (scheduledTime, clientId, siteId, language, ipAddress)
	VALUES (@appointmentTime, @client_client3Id, @site_site2Id, "en", "localhost");
-- end appointment



-- serviced appointment
SET @timeIn = DATE_ADD((SELECT scheduledTime FROM Appointment WHERE appointmentId = @appointment_appointment5Id), INTERVAL 5 MINUTE);
INSERT INTO ServicedAppointment (timeIn, userId, appointmentId)
	VALUES (@timeIn, @user_preparer1Id, @appointment_appointment5Id);
-- end serviced appointment



-- questions
INSERT INTO Question (text, lookupName)
	VALUES ("Will you require a Depreciation Schedule?", "depreciation_schedule");
SET @question_question1Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Will you require a Schedule F (Farm)?", "schedule_f");
SET @question_question2Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Are you self-employed or own a home-based business?", "self_employed");
SET @question_question3Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Does your home-based business or self-employment have a net loss?", "net_loss");
SET @question_question4Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Does your home-based business or self-employment have more than $10,000 in expenses?", "more_than_10000_expenses");
SET @question_question5Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Does your home-based business or self-employment have self-employed, SEP, SIMPLE, or qualified retirement plans", "retirement_plans");
SET @question_question6Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Does your home-based business or self-employment have employees?", "any_employees");
SET @question_question7Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Will your return have casualty losses?", "casualty_losses");
SET @question_question8Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Will your return have theft losses?", "theft_losses");
SET @question_question9Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Will you require a Schedule E (rental income)?", "schedule_e");
SET @question_question10Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Will you require a Schedule K-1 (partnership or trust income)", "schedule_k-1");
SET @question_question11Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Do you have income from dividends, capital gains, or minimal brokerage transactions?", "dividends_income");
SET @question_question12Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Will your return involve a current bankruptcy?", "current_bankruptcy");
SET @question_question13Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Will your return involve income from more than one state?", "multiple_states");
SET @question_question14Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Are you a University of Nebraska - Lincoln student?", "unl_student");
SET @question_question15Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Are you an International Student Scholar?", "international_student_scholar");
SET @question_question16Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("What sort of visa are you on?", "visa");
SET @question_question17Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("How long have you been in the United States?", "duration_in_united_states");
SET @question_question18Id = LAST_INSERT_ID();

INSERT INTO Question (text, lookupName)
	VALUES ("Have you been on this visa for less than 183 days and in the United States for less than five years (after 2012)?", "visa_less_than_183_days");
SET @question_question19Id = LAST_INSERT_ID();
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
-- end possible answer



-- answer
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_appointment1Id, @question_question1Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment1Id, @question_question2Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment1Id, @question_question3Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment1Id, @question_question6Id);


INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment2Id, @question_question1Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_appointment2Id, @question_question2Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_appointment2Id, @question_question3Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment2Id, @question_question4Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment2Id, @question_question5Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment2Id, @question_question6Id);
	

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment3Id, @question_question1Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment3Id, @question_question2Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment3Id, @question_question3Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment3Id, @question_question6Id);
	

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_appointment4Id, @question_question1Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_appointment4Id, @question_question2Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_noId, @appointment_appointment4Id, @question_question3Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, questionId)
	VALUES (@possibleAnswer_yesId, @appointment_appointment4Id, @question_question6Id);
-- end answer


-- load testing for Appointments
DROP PROCEDURE IF EXISTS sp_CreateAppointments;
DELIMITER $$
CREATE PROCEDURE sp_CreateAppointments(IN numAppointments INT, IN startingSiteId INT, IN endingSiteId INT)
BEGIN
	DECLARE minIntervalValue INT DEFAULT 1;
	DECLARE maxIntervalValue INT DEFAULT 300; # 5 hours

	DECLARE i INT DEFAULT 0;
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
		SET @randomMinute = CEIL(RAND() * (maxIntervalValue - minIntervalValue));
		SET @scheduledTime = DATE_ADD(NOW(), INTERVAL @randomMinute MINUTE);
		SET @siteIdForThisAppointment = startingSiteId + ROUND(RAND() * (endingSiteId - startingSiteId));
		INSERT INTO Appointment (scheduledTime, clientId, siteId, language, ipAddress)
			VALUES (@scheduledTime, @clientIdForThisAppointment, @siteIdForThisAppointment, "en", "localhost");
		SET i = i + 1;
	END WHILE;
	COMMIT;
END$$
DELIMITER ;

CALL sp_CreateAppointments(200, @site_site1Id, @site_site2Id);
-- end load testing for Appointments
