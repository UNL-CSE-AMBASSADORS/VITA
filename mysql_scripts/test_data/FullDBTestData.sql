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
TRUNCATE VirtualAppointmentConsent;
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
	VALUES (0, @passwordHash, @lockoutTime, @user_preparer2Id);

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
	VALUES ("Clienty", "McClientFace", "402-555-1234", "clientymcclientface@test.test");
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



-- virtualAppointmentConsent 
INSERT INTO VirtualAppointmentConsent (reviewConsent, virtualConsent, signature, appointmentId)
	VALUES (0, 1, "test_signature_one", @appointment_appointment1Id);
INSERT INTO VirtualAppointmentConsent (reviewConsent, virtualConsent, signature, appointmentId)
	VALUES (1, 1, "test_signature test_last", @appointment_appointment2Id);
INSERT INTO VirtualAppointmentConsent (reviewConsent, virtualConsent, signature, appointmentId)
	VALUES (1, 0, "Barry Test", @appointment_appointment3Id);
INSERT INTO VirtualAppointmentConsent (reviewConsent, virtualConsent, signature, appointmentId)
	VALUES (0, 0, "signature fo\' testing", @appointment_appointment4Id);
INSERT INTO VirtualAppointmentConsent (reviewConsent, virtualConsent, signature, appointmentId)
	VALUES (0, 1, "", @appointment_appointment5Id);
-- end virtualAppointmentConsent



-- selfserviceappointmentrescheduletoken
INSERT INTO SelfServiceAppointmentRescheduleToken (token, appointmentId)
	VALUES ("c4ca4238a0b923820dcc509a6f75849b", @appointment_appointment1Id);
INSERT INTO SelfServiceAppointmentRescheduleToken (token, appointmentId)
	VALUES ("c81e728d9d4c2f636f067f89cc14862c", @appointment_appointment2Id);
INSERT INTO SelfServiceAppointmentRescheduleToken (token, appointmentId)
	VALUES ("eccbc87e4b5ce2fe28308fd9f2a7baf3", @appointment_appointment3Id);
INSERT INTO SelfServiceAppointmentRescheduleToken (token, appointmentId)
	VALUES ("a87ff679a2f3e71d9181a67b7542122c", @appointment_appointment4Id);
INSERT INTO SelfServiceAppointmentRescheduleToken (token, appointmentId)
	VALUES ("e4da3b7fbbce2345d7772b0674a318d5", @appointment_appointment5Id);
-- end selfserviceappointmentrescheduletoken


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


-- Queue
insert into progressionType (progressionTypeId, progressionTypeName)
values
(1, 'Legacy'),
(2, 'In-Person Residential'), 
(3, 'Virtual'),
(4, 'International Student');

insert into progressionStep (progressionStepId, progressionTypeId, progressionStepOrdinal, progressionStepName)
values
# Legacy
(1, 1, 1, 'Checked-In'),
(2, 1, 2, 'Paperwork Done'),
(3, 1, 3, 'Preparing'),
(4, 1, 4, 'Complete'),
# In-Person Residential
(5, 2, 1, 'Checked-In'),
(6, 2, 2, 'Paperwork Done'),
(7, 2, 3, 'Preparing'),
(8, 2, 4, 'Complete'), #has sub-steps
# Virtual
(9, 3, 1, 'Received Information'), #has sub-steps
(10, 3, 2, 'Ready'),
(11, 3, 3, 'Preparing'),
(12, 3, 4, 'Complete'), #has sub-steps
# International Student
(13, 4, 1, 'Checked-In'),
(14, 4, 2, 'Paperwork Done'),
(15, 4, 3, 'Preparing'),
(16, 4, 4, 'Complete'); #has sub-steps

insert into progressionSubStep (progressionStepId, progressionSubStepName)
values
# 8: In-Person Residential Complete
(8, 'N/A'),
(8, 'Ready to E-File'),
(8, 'Transmitted'),
(8, 'Accepted'),
(8, 'Rejected'),
(8, 'Paper'),
(8, 'Deleted'),
(8, 'Amended'),
(8, 'Prior Years'),
# 9: Virtual Received Information
(9, 'Sufficient Information'),
(9, 'Need More Information'),
# 12: Virtual Complete
(12, 'N/A'),
(12, 'Ready to E-File'),
(12, 'Transmitted'),
(12, 'Accepted'),
(12, 'Rejected'),
(12, 'Paper'),
(12, 'Deleted'),
(12, 'Amended'),
(12, 'Prior Years'),
#16: International Student Complete
(16, 'N/A'),
(16, 'FSA'),
(16, 'Paper'),
(16, 'Resident');

# 2. Migrate old appointments onto new system
insert into progressionTimestamp (appointmentId, progressionStepId, progressionSubStepId, timestamp)
select
	appointmentId,
    c.progressionStepId,
    Null as progressionSubStepId,
    timeIn as timestamp
from servicedappointment a
left join progressionType b
	on b.progressionTypeName = 'Legacy'
left join progressionStep c
	on c.progressionTypeId = b.progressionTypeId and c.progressionStepName = 'Checked-In'
UNION
select
	appointmentId,
    c.progressionStepId,
    Null as progressionSubStepId,
    timeReturnedPapers as timestamp
from servicedappointment a
left join progressionType b
	on b.progressionTypeName = 'Legacy'
left join progressionStep c
	on c.progressionTypeId = b.progressionTypeId and c.progressionStepName = 'Paperwork Done'
UNION
select
	appointmentId,
    c.progressionStepId,
    Null as progressionSubStepId,
    timeAppointmentStarted as timestamp
from servicedappointment a
left join progressionType b
	on b.progressionTypeName = 'Legacy'
left join progressionStep c
	on c.progressionTypeId = b.progressionTypeId and c.progressionStepName = 'Preparing'
UNION
select
	appointmentId,
    c.progressionStepId,
    Null as progressionSubStepId,
    timeAppointmentEnded as timestamp
from servicedappointment a
left join progressionType b
	on b.progressionTypeName = 'Legacy'
left join progressionStep c
	on c.progressionTypeId = b.progressionTypeId and c.progressionStepName = 'Complete';

# 3. Insert dummy data
# Queue (Site)
INSERT INTO Site (title, address, phoneNumber, createdBy, lastModifiedBy)
	VALUES ("Queue Test Site", "Queue testing address", "555-202-2062", @user_siteAdmin1Id, @user_siteAdmin1Id);
SET @site_QueueId = LAST_INSERT_ID();

# Queue (Client)
INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("ResQueue", "Awaiting", "402-555-3422", "ResQueueAwaiting@test.test");
SET @client_ResQueueAwaitingId = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("ResQueue", "CompleteRejected", "402-555-3422", "ResQueueCompleteRejected@test.test");
SET @client_ResQueueCompleteRejectedId = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("ResQueue", "CheckedIn", "402-555-3422", "ResQueueCheckedIn@test.test");
SET @client_ResQueueCheckedInId = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("VirtualQueue", "Awaiting", "402-545-3422", "VirtualQueueAwaiting@test.test");
SET @client_VirtualQueueAwaitingId = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("VirtualQueue", "SuffInformation", "431-555-2222", "VirtualQueueSuffInformation@test.test");
SET @client_VirtualQueueSuffInfoId = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("InternationalQueue", "Awaiting", "492-545-3422", "InternationalQueueAwaiting@test.test");
SET @client_InternationalQueueAwaitingId = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("InternationalQueue", "CompleteFSA", "402-555-2299", "InternationalQueueCompleteFSA@test.test");
SET @client_InternationalQueueCompleteFSAId = LAST_INSERT_ID();

INSERT INTO Client (firstName, lastName, phoneNumber, emailAddress)
	VALUES ("InPersonResQueue", "NoShow", "772-555-2299", "InPersonResQueueNoShow@test.test");
SET @client_ResNoShowId = LAST_INSERT_ID();

# Queue (AppointmentTime)
SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 0 DAY); # can move this to future for long-term development.
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 30, @site_QueueId, @appointmentType_residentialId);
SET @appointmentTime_site2ResQueueId = LAST_INSERT_ID();

# SET @appointmentTime = @appointmentTime; # Let these be the same time/site so we can see all queues on one page
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 20, @site_QueueId, @appointmentType_chinaId);
SET @appointmentTime_site2InternationalQueueId = LAST_INSERT_ID();

INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@appointmentTime, 20, @site_QueueId, @appointmentType_virtualResidentialId);
SET @appointmentTime_siteVirtualandVirtualQueueId = LAST_INSERT_ID();

SET @noShowAppointmentTime = DATE_ADD(NOW(), INTERVAL -31 MINUTE);
INSERT INTO AppointmentTime (scheduledTime, numberOfAppointments, siteId, appointmentTypeId)
	VALUES (@noShowAppointmentTime, 20, @site_QueueId, @appointmentType_residentialId);
SET @appointmentTime_ResQueueForNoShowId = LAST_INSERT_ID();


# Queue (Appointment)
INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site2ResQueueId, @client_ResQueueAwaitingId, "eng", "localhost");
SET @appointment_ResQueueAwaitingId = LAST_INSERT_ID();

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site2ResQueueId, @client_ResQueueCompleteRejectedId, "eng", "localhost");
SET @appointment_ResQueueCompleteRejectedId = LAST_INSERT_ID();
    
INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site2ResQueueId, @client_ResQueueCheckedInId, "eng", "localhost");
SET @appointment_ResQueueCheckedInId = LAST_INSERT_ID();
    
INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_siteVirtualandVirtualQueueId, @client_VirtualQueueAwaitingId, "eng", "localhost");
SET @appointment_VirtualQueueAwaitingId = LAST_INSERT_ID();

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_siteVirtualandVirtualQueueId, @client_VirtualQueueSuffInfoId, "eng", "localhost");
SET @appointment_VirtualQueueSuffInfoId = LAST_INSERT_ID();

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site2InternationalQueueId, @client_InternationalQueueAwaitingId, "eng", "localhost");
SET @appointment_InternationalQueueAwaitingId = LAST_INSERT_ID();

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_site2InternationalQueueId, @client_InternationalQueueCompleteFSAId, "eng", "localhost");
SET @appointment_InternationalQueueCompleteFSAId = LAST_INSERT_ID();

INSERT INTO Appointment (appointmentTimeId, clientId, language, ipAddress)
	VALUES (@appointmentTime_ResQueueForNoShowId, @client_ResNoShowId, "eng", "localhost");
SET @appointment_ResNoShowId = LAST_INSERT_ID();

# Queue (Add progression steps to progressionTimeStamp)
# @client_ResQueueAwaitingId will be handled by script
# appointment_ResQueueAwaitingId
INSERT INTO progressionTimeStamp
	(appointmentId, progressionStepId, progressionSubStepId, timestamp)
select @appointment_ResQueueAwaitingId, a.progressionStepId, null, null
from progressionStep a
left join progressionType b
	on a.progressionTypeId = b.progressionTypeId
left join progressionSubStep c
on a.progressionStepId = c.progressionStepId
where progressionStepName = 'Checked-In' and progressionTypeName = 'In-Person Residential'; #for awaiting, take first ordinal step and make timestamp null

# @client_ResQueueCompleteRejectedId
# appointment_ResQueueCompleteRejectedId
# First add preceding steps
INSERT INTO progressionTimeStamp
	(appointmentId, progressionStepId, progressionSubStepId, timestamp)
VALUES
 (@appointment_ResQueueCompleteRejectedId, 5, NULL, DATE_ADD(@appointmentTime, INTERVAL 15 MINUTE)),
 (@appointment_ResQueueCompleteRejectedId, 6, NULL, DATE_ADD(@appointmentTime, INTERVAL 30 MINUTE)),
 (@appointment_ResQueueCompleteRejectedId, 7, NULL, DATE_ADD(@appointmentTime, INTERVAL 45 MINUTE));
# Then add final step
INSERT INTO progressionTimeStamp
	(appointmentId, progressionStepId, progressionSubStepId, timestamp)
select @appointment_ResQueueCompleteRejectedId, a.progressionStepId, c.progressionSubStepid, DATE_ADD(@appointmentTime, INTERVAL 45 MINUTE)
from progressionStep a
left join progressionType b
	on a.progressionTypeId = b.progressionTypeId
left join progressionSubStep c
on a.progressionStepId = c.progressionStepId
where progressionStepName = 'Complete' and progressionTypeName = 'In-Person Residential' and progressionSubStepName = 'Rejected';

# @client_ResQueueCheckedInId
# appointment_ResQueueCheckedInId
INSERT INTO progressionTimeStamp
	(appointmentId, progressionStepId, progressionSubStepId, timestamp)
select @appointment_ResQueueCheckedInId, a.progressionStepId, null, DATE_ADD(@appointmentTime, INTERVAL 10 MINUTE)
from progressionStep a
left join progressionType b
	on a.progressionTypeId = b.progressionTypeId
left join progressionSubStep c
on a.progressionStepId = c.progressionStepId
where progressionStepName = 'Checked-In' and progressionTypeName = 'In-Person Residential';

# @client_VirtualQueueAwaitingId
# appointment_VirtualQueueAwaitingId
INSERT INTO progressionTimeStamp
	(appointmentId, progressionStepId, progressionSubStepId, timestamp)
select @appointment_VirtualQueueAwaitingId, a.progressionStepId, null, null # null so it's awaiting.
from progressionStep a
left join progressionType b
	on a.progressionTypeId = b.progressionTypeId
left join progressionSubStep c
on a.progressionStepId = c.progressionStepId
where progressionStepName = 'Received Information' and progressionTypeName = 'Virtual'
limit 1;

# @client_VirtualQueueSuffInformationId
# appointment_VirtualQueueSuffInfoId
INSERT INTO progressionTimeStamp
	(appointmentId, progressionStepId, progressionSubStepId, timestamp)
select @appointment_VirtualQueueSuffInfoId, a.progressionStepId, c.progressionSubStepId, DATE_ADD(@appointmentTime, INTERVAL 20 MINUTE)
from progressionStep a
left join progressionType b
	on a.progressionTypeId = b.progressionTypeId
left join progressionSubStep c
on a.progressionStepId = c.progressionStepId
where progressionSubStepName = 'Sufficient Information' and progressionTypeName = 'Virtual';

# @client_InternationalQueueAwaitingId
# appointment_InternationalQueueAwaitingId
INSERT INTO progressionTimeStamp
	(appointmentId, progressionStepId, progressionSubStepId, timestamp)
select @appointment_VirtualQueueAwaitingId, a.progressionStepId, c.progressionSubStepId, null
from progressionStep a
left join progressionType b
	on a.progressionTypeId = b.progressionTypeId
left join progressionSubStep c
on a.progressionStepId = c.progressionStepId
where progressionStepName = 'Checked-In' and progressionTypeName = 'International Student';

# @client_InternationalQueueCompleteFSAId
# appointment_InternationalQueueCompleteFSAId
# First add preceding steps
INSERT INTO progressionTimeStamp
	(appointmentId, progressionStepId, progressionSubStepId, timestamp)
VALUES
 (@appointment_InternationalQueueCompleteFSAId, 13, NULL, DATE_ADD(@appointmentTime, INTERVAL 10 MINUTE)),
 (@appointment_InternationalQueueCompleteFSAId, 14, NULL, DATE_ADD(@appointmentTime, INTERVAL 25 MINUTE)),
 (@appointment_InternationalQueueCompleteFSAId, 15, NULL, DATE_ADD(@appointmentTime, INTERVAL 45 MINUTE));
# Then add final step
INSERT INTO progressionTimeStamp
	(appointmentId, progressionStepId, progressionSubStepId, timestamp)
select @appointment_InternationalQueueCompleteFSAId, a.progressionStepId, c.progressionSubStepId, DATE_ADD(@appointmentTime, INTERVAL 55 MINUTE)
from progressionStep a
left join progressionType b
	on a.progressionTypeId = b.progressionTypeId
left join progressionSubStep c
on a.progressionStepId = c.progressionStepId
where progressionSubStepName = 'FSA' and progressionTypeName = 'International Student';

# @appointment_ResNoShowId
INSERT INTO progressionTimeStamp
	(appointmentId, progressionStepId, progressionSubStepId, timestamp)
select @appointment_ResNoShowId, a.progressionStepId, null, null
from progressionStep a
left join progressionType b
	on a.progressionTypeId = b.progressionTypeId
left join progressionSubStep c
on a.progressionStepId = c.progressionStepId
where progressionStepName = 'Checked-In' and progressionTypeName = 'In-Person Residential'; 
