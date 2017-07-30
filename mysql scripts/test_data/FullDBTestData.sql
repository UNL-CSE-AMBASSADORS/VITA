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
TRUNCATE LitmusQuestion;

TRUNCATE UserPrivilege;
TRUNCATE Privilege;
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



-- abilities
INSERT INTO Ability (name, tag, description, verificationRequired)
	VALUES ("Basic Certification", "basic_certification", "Has finished the basic certification requirements", TRUE);
SET @ability_basicCertificationId = LAST_INSERT_ID();

INSERT INTO Ability (name, tag, description, verificationRequired)
	VALUES ("International Certification", "international_certification", "Has completed the international certification requirements", TRUE);
SET @ability_internationalCertificationId = LAST_INSERT_ID();

INSERT INTO Ability (name, tag, description, verificationRequired)
	VALUES ("Military Certification", "military_certification", "Has completed the military certification requirements", TRUE);
SET @ability_militaryCertificationId = LAST_INSERT_ID();

INSERT INTO Ability (name, tag, description, verificationRequired)
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



-- privileges
INSERT INTO Privilege (name, description, tag)
	VALUES ("Add Site", "Has the privilege to create a new VITA site on the add site page", "add_site");
SET @privilege_addSiteId = LAST_INSERT_ID();
	
INSERT INTO Privilege (name, description, tag)
	VALUES ("Edit Site Information", "Has the privilege to edit the information associated with sites", "edit_site_information");
SET @privilege_editSiteInformationId = LAST_INSERT_ID();

INSERT INTO Privilege (name, description, tag)
	VALUES ("Can Take Client off of Queue", "Has the privilege to take clients off of the queue", "pop_client_off_queue");
SET @privilege_popClientOffQueueId = LAST_INSERT_ID();
-- end privileges



-- user privileges
INSERT INTO UserPrivilege (userId, privilegeId, createdBy)
	VALUES (@user_preparer1Id, @privilege_popClientOffQueueId, @user_siteAdmin1Id);
	
INSERT INTO UserPrivilege (userId, privilegeId, createdBy)
	VALUES (@user_preparer2Id, @privilege_popClientOffQueueId, @user_siteAdmin1Id);
	
INSERT INTO UserPrivilege (userId, privilegeId, createdBy)
	VALUES (@user_siteAdmin1Id, @privilege_addSiteId, @user_siteAdmin1Id);
	
INSERT INTO UserPrivilege (userId, privilegeId, createdBy)
	VALUES (@user_siteAdmin1Id, @privilege_editSiteInformationId, @user_siteAdmin1Id);
	
INSERT INTO UserPrivilege (userId, privilegeId, createdBy)
	VALUES (@user_receptionist1Id, @privilege_popClientOffQueueId, @user_siteAdmin1Id);
	
INSERT INTO UserPrivilege (userId, privilegeId, createdBy)
	VALUES (@user_reviewer1Id, @privilege_popClientOffQueueId, @user_siteAdmin1Id);
-- end user privileges



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
SET @shiftStartTime = DATE_ADD(NOW(), INTERVAL 1 MONTH);
SET @shiftEndTime = DATE_ADD(@shiftStartTime, INTERVAL 1 HOUR);
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy, handlesResidential, handlesInternational)
	VALUES (@shiftStartTime, @shiftEndTime, @site_site1Id, @user_siteAdmin1Id, @user_siteAdmin1Id, TRUE, FALSE);
SET @shift_site1Shift1Id = LAST_INSERT_ID();

SET @shiftStartTime = DATE_ADD(@shiftStartTime, INTERVAL 1 DAY);
SET @shiftEndTime = DATE_ADD(@shiftStartTime, INTERVAL 3 HOUR);
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy, handlesResidential, handlesInternational)
	VALUES (@shiftStartTime, @shiftEndTime, @site_site1Id, @user_siteAdmin1Id, @user_siteAdmin1Id, TRUE, TRUE);
SET @shift_site1Shift2Id = LAST_INSERT_ID();

SET @shiftStartTime = DATE_ADD(NOW(), INTERVAL 1 MONTH);
SET @shiftEndTime = DATE_ADD(@shiftStartTime, INTERVAL 4 HOUR);
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy, handlesResidential, handlesInternational)
	VALUES (@shiftStartTime, @shiftEndTime, @site_site2Id, @user_siteAdmin1Id, @user_siteAdmin1Id, TRUE, FALSE);
SET @shift_site2Shift1Id = LAST_INSERT_ID();

SET @shiftStartTime = DATE_ADD(@shiftStartTime, INTERVAL 3 DAY);
SET @shiftEndTime = DATE_ADD(@shiftStartTime, INTERVAL 2 HOUR);
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy, handlesResidential, handlesInternational)
	VALUES (@shiftStartTime, @shiftEndTime, @site_site2Id, @user_siteAdmin1Id, @user_siteAdmin1Id, TRUE, TRUE);
SET @shift_site2Shift2Id = LAST_INSERT_ID();

SET @shiftStartTime = DATE_ADD(@shiftStartTime, INTERVAL 1 DAY);
SET @shiftEndTime = DATE_ADD(@shiftStartTime, INTERVAL 3 HOUR);
INSERT INTO Shift (startTime, endTime, siteId, createdBy, lastModifiedBy, handlesResidential, handlesInternational)
	VALUES (@shiftStartTime, @shiftEndTime, @site_site2Id, @user_siteAdmin1Id, @user_siteAdmin1Id, FALSE, TRUE);
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
INSERT INTO Appointment (scheduledTime, clientId, siteId)
	VALUES (@appointmentTime, @client_client1Id, @site_site1Id);
SET @appointment_appointment1Id = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site1Shift2Id), INTERVAL 30 MINUTE);
INSERT INTO Appointment (scheduledTime, clientId, siteId)
	VALUES (@appointmentTime, @client_client2Id, @site_site1Id);
SET @appointment_appointment2Id = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site2Shift1Id), INTERVAL 0 MINUTE);
INSERT INTO Appointment (scheduledTime, clientId, siteId)
	VALUES (@appointmentTime, @client_client3Id, @site_site2Id);
SET @appointment_appointment3Id = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD((SELECT startTime FROM Shift WHERE shiftId = @shift_site2Shift2Id), INTERVAL 30 MINUTE);
INSERT INTO Appointment (scheduledTime, clientId, siteId)
	VALUES (@appointmentTime, @client_client4Id, @site_site2Id);
SET @appointment_appointment4Id = LAST_INSERT_ID();

-- Already serviced appointment
SET @appointmentTime = DATE_ADD(NOW(), INTERVAL -1 DAY);
INSERT INTO Appointment (scheduledTime, clientId, siteId)
	VALUES (@appointmentTime, @client_client5Id, @site_site1Id);
SET @appointment_appointment5Id = LAST_INSERT_ID();

-- Appointments for today (note that this is just for testing queue functionality)
SET @appointmentTime = DATE_ADD(NOW(), INTERVAL -5 MINUTE);
INSERT INTO Appointment (scheduledTime, arrivedAt, clientId, siteId)
	VALUES (@appointmentTime, NOW(), @client_client1Id, @site_site1Id);

SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 5 HOUR);
INSERT INTO Appointment (scheduledTime, clientId, siteId)
	VALUES (@appointmentTime, @client_client1Id, @site_site1Id);

SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 30 MINUTE);
INSERT INTO Appointment (scheduledTime, clientId, siteId)
	VALUES (@appointmentTime, @client_client2Id, @site_site1Id);

SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 45 MINUTE);
INSERT INTO Appointment (scheduledTime, clientId, siteId)
	VALUES (@appointmentTime, @client_client3Id, @site_site2Id);
-- end appointment



-- serviced appointment
SET @startTime = DATE_ADD((SELECT scheduledTime FROM Appointment WHERE appointmentId = @appointment_appointment5Id), INTERVAL 5 MINUTE);
SET @endTime = DATE_ADD(@startTime, INTERVAL 37 MINUTE);
INSERT INTO ServicedAppointment (startTime, endTime, userId, appointmentId)
	VALUES (@startTime, @endTime, @user_preparer1Id, @appointment_appointment5Id);
-- end serviced appointment



-- litmus question
INSERT INTO LitmusQuestion (text, orderIndex, tag, required, followUpTo)
	VALUES ("Will you require a Depreciation Schedule?", 1, "depreciation_schedule", true, NULL);
SET @litmusQuestion_litmusQuestion1Id = LAST_INSERT_ID();

INSERT INTO LitmusQuestion (text, orderIndex, tag, required, followUpTo)
	VALUES ("Will you require a Schedule F (Farm)?", 2, "schedule_f", true, NULL);
SET @litmusQuestion_litmusQuestion2Id = LAST_INSERT_ID();

INSERT INTO LitmusQuestion (text, orderIndex, tag, required, followUpTo)
	VALUES ("Are you self-employed or own a home-based business?", 3, "self_employed", true, NULL);
SET @litmusQuestion_litmusQuestion3Id = LAST_INSERT_ID();

INSERT INTO LitmusQuestion (text, orderIndex, tag, required, followUpTo)
	VALUES ("Does your home-based business or self-employment have a net loss?", 4, "net_loss", false, @litmusQuestion_litmusQuestion3Id);
SET @litmusQuestion_litmusQuestion4Id = LAST_INSERT_ID();

INSERT INTO LitmusQuestion (text, orderIndex, tag, required, followUpTo)
	VALUES ("Does your home-based business or self-employment have more than $10,000 in expenses?", 5, "more_than_10000_expenses", false, @litmusQuestion_litmusQuestion3Id);
SET @litmusQuestion_litmusQuestion5Id = LAST_INSERT_ID();

INSERT INTO LitmusQuestion (text, orderIndex, tag, required, followUpTo)
	VALUES ("Will your return have casualty losses?",  6, "casualty_losses", true, NULL);
SET @litmusQuestion_litmusQuestion6Id = LAST_INSERT_ID();
-- end litmus question



-- possible answer
INSERT INTO PossibleAnswer (text, orderIndex, litmusQuestionId)
	VALUES ("Yes", 1, @litmusQuestion_litmusQuestion1Id);
SET @possibleAnswer_question1YesId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text, orderIndex, litmusQuestionId)
	VALUES ("No", 2, @litmusQuestion_litmusQuestion1Id);
SET @possibleAnswer_question1NoId = LAST_INSERT_ID();


INSERT INTO PossibleAnswer (text, orderIndex, litmusQuestionId)
	VALUES ("Yes", 1, @litmusQuestion_litmusQuestion2Id);
SET @possibleAnswer_question2YesId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text, orderIndex, litmusQuestionId)
	VALUES ("No", 2, @litmusQuestion_litmusQuestion2Id);
SET @possibleAnswer_question2NoId = LAST_INSERT_ID();


INSERT INTO PossibleAnswer (text, orderIndex, litmusQuestionId)
	VALUES ("Yes", 1, @litmusQuestion_litmusQuestion3Id);
SET @possibleAnswer_question3YesId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text, orderIndex, litmusQuestionId)
	VALUES ("No", 2, @litmusQuestion_litmusQuestion3Id);
SET @possibleAnswer_question3NoId = LAST_INSERT_ID();


INSERT INTO PossibleAnswer (text, orderIndex, litmusQuestionId)
	VALUES ("Yes", 1, @litmusQuestion_litmusQuestion4Id);
SET @possibleAnswer_question4YesId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text, orderIndex, litmusQuestionId)
	VALUES ("No", 2, @litmusQuestion_litmusQuestion4Id);
SET @possibleAnswer_question4NoId = LAST_INSERT_ID();


INSERT INTO PossibleAnswer (text, orderIndex, litmusQuestionId)
	VALUES ("Yes", 1, @litmusQuestion_litmusQuestion5Id);
SET @possibleAnswer_question5YesId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text, orderIndex, litmusQuestionId)
	VALUES ("No", 2, @litmusQuestion_litmusQuestion5Id);
SET @possibleAnswer_question5NoId = LAST_INSERT_ID();


INSERT INTO PossibleAnswer (text, orderIndex, litmusQuestionId)
	VALUES ("Yes", 1, @litmusQuestion_litmusQuestion6Id);
SET @possibleAnswer_question6YesId = LAST_INSERT_ID();

INSERT INTO PossibleAnswer (text, orderIndex, litmusQuestionId)
	VALUES ("No", 2, @litmusQuestion_litmusQuestion6Id);
SET @possibleAnswer_question6NoId = LAST_INSERT_ID();
-- end possible answer



-- answer
INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question1YesId, @appointment_appointment1Id, @litmusQuestion_litmusQuestion1Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question2NoId, @appointment_appointment1Id, @litmusQuestion_litmusQuestion2Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question3NoId, @appointment_appointment1Id, @litmusQuestion_litmusQuestion3Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question6NoId, @appointment_appointment1Id, @litmusQuestion_litmusQuestion6Id);
	

INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question1NoId, @appointment_appointment2Id, @litmusQuestion_litmusQuestion1Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question2YesId, @appointment_appointment2Id, @litmusQuestion_litmusQuestion2Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question3YesId, @appointment_appointment2Id, @litmusQuestion_litmusQuestion3Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question4NoId, @appointment_appointment2Id, @litmusQuestion_litmusQuestion4Id);

INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question5NoId, @appointment_appointment2Id, @litmusQuestion_litmusQuestion5Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question6NoId, @appointment_appointment2Id, @litmusQuestion_litmusQuestion6Id);
	

INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question1NoId, @appointment_appointment3Id, @litmusQuestion_litmusQuestion1Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question2NoId, @appointment_appointment3Id, @litmusQuestion_litmusQuestion2Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question3NoId, @appointment_appointment3Id, @litmusQuestion_litmusQuestion3Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question6NoId, @appointment_appointment3Id, @litmusQuestion_litmusQuestion6Id);
	

INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question1YesId, @appointment_appointment4Id, @litmusQuestion_litmusQuestion1Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question2YesId, @appointment_appointment4Id, @litmusQuestion_litmusQuestion2Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question3NoId, @appointment_appointment4Id, @litmusQuestion_litmusQuestion3Id);
	
INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId)
	VALUES (@possibleAnswer_question6YesId, @appointment_appointment4Id, @litmusQuestion_litmusQuestion6Id);
-- end answer