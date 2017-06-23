USE vita;

-- users
INSERT INTO User (firstName, lastName, email, phoneNumber, preparesTaxes, archived)
	VALUES ("Preparer", "McPreparer", "preparer@test.test", "555-123-4567", true, false);
SET @user_preparer1Id = LAST_INSERT_ID();

INSERT INTO User (firstName, lastName, email, phoneNumber, preparesTaxes, arhcived)
	VALUES ("Preparer2", "MacPreparer2", "preparer2@test.test", "555-902-7563", true, false);
SET @user_preparer2Id = LAST_INSERT_ID();

INSERT INTO User (firstName, lastName, email, phoneNumber, preparesTaxes, archived)
	VALUES ("Reviewer", "McReviewer", "reviewer@test.test", "555-952-7319", false, false);
SET @user_reviewer1Id = LAST_INSERT_ID();

INSERT INTO User (firstName, lastName, email, phoneNumber, preparesTaxes, archived)
	VALUES ("Receptionist", "McReceptionist", "receptionist@test.test", "555-987-6543", false, false);
SET @user_receptionist1Id = LAST_INSERT_ID();

INSERT INTO User (firstName, lastName, email, phoneNumber, preparesTaxes, archived)
	VALUES ("SiteAdmin", "McSiteAdmin", "siteadmin@test.test", "555-019-2837", false, false);
SET @user_siteAdmin1Id = LAST_INSERT_ID();
-- end users



-- abilities
INSERT INTO Ability (abilityName, tag)
	VALUES ("Basic Certification", "basic_certification");
SET @ability_basicCertificationId = LAST_INSERT_ID();

INSERT INTO Ability (abilityName, tag)
	VALUES ("International Certification", "international_certification");
SET @ability_internationalCertificationId = LAST_INSERT_ID();

INSERT INTO Ability (abilityName, tag)
	VALUES ("Military Certification", "military_certification");
SET @ability_militaryCertificationId = LAST_INSERT_ID();

INSERT INTO Ability (abilityName, tag)
	VALUES ("Spanish-Speaking", "spanish_speaking");
SET @ability_spanishSpeakingId = LAST_INSERT_ID();
-- end abilities



-- user abilities
INSERT INTO UserAbility (userId, abilityId)
	VALUES (@user_volunteer1Id, @ability_basicCertificationId);

INSERT INTO UserAbility (userId, abilityId)
	VALUES (@user_volunteer1Id, @ability_internationalCertificationId);
    
INSERT INTO UserAbility (userId, abilityId)
	VALUES (@user_volunteer2Id, @ability_basicCertificationId);
    
INSERT INTO UserAbility (userId, abilityId)
	VALUES (@user_volunteer2Id, @ability_spanishSpeakingId);
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
INSERT INTO UserPrivilege (userId, privilegeId)
	VALUES (@user_volunteer1Id, @privilege_popClientOffQueueId);
    
INSERT INTO UserPrivilege (userId, privilegeId)
	VALUES (@user_volunteer2Id, @privilege_popClientOffQueueId);
    
INSERT INTO UserPrivilege (userId, privilegeId)
	VALUES (@user_siteAdmin1Id, @privilege_addSiteId);
    
INSERT INTO UserPrivilege (userId, privilegeId)
	VALUES (@user_siteAdmin1Id, @privilege_editSiteInformationId);
    
INSERT INTO UserPrivilege (userId, privilegeId)
	VALUES (@user_receptionist1Id, @privilege_popClientOffQueueId);
    
INSERT INTO UserPrivilege (userId, privilegeId)
	VALUES (@user_reviewer1Id, @privilege_popClientOffQueueId);
-- end user privileges



-- site

-- end site




-- sample questions with choices, if applicable
INSERT INTO LitmusQuestion (string, orderIndex, tag)
	VALUES ("Are you a phamacist?", 1, "pharmacist");
    
INSERT INTO LitmusQuestion (string, orderIndex, tag)
	VALUES ("How often do you gamble?", 2, "gamble");
    
INSERT INTO LitmusQuestion (string, orderIndex, tag)
	VALUES ("Indicate your military status", 3, "military_status");
    
INSERT INTO LitmusQuestion (string, orderIndex, tag)
	VALUES ("Can you speak fluent English?", 4, "fluent_english");
    
-- Sample Possible Answer data
INSERT INTO PossibleAnswer (string, orderIndex, litmusQuestionId) 
	VALUES ("Yes", 1,
	(SELECT litmusQuestionId 
		FROM LitmusQuestion 
		WHERE tag="pharmacist"));
		
INSERT INTO PossibleAnswer (string, orderIndex, litmusQuestionId) 
	VALUES ("No", 2,
	(SELECT litmusQuestionId 
		FROM LitmusQuestion 
		WHERE tag="pharmacist"));
	
INSERT INTO PossibleAnswer (string, orderIndex, litmusQuestionId) 
	VALUES ("Frequently", 1,
	(SELECT litmusQuestionId 
		FROM LitmusQuestion 
		WHERE tag="gamble"));
		
INSERT INTO PossibleAnswer (string, orderIndex, litmusQuestionId) 
	VALUES ("Occasionally", 2,
	(SELECT litmusQuestionId 
		FROM LitmusQuestion 
		WHERE tag="gamble"));
		
INSERT INTO PossibleAnswer (string, orderIndex, litmusQuestionId) 
	VALUES ("Rarely", 3,
	(SELECT litmusQuestionId 
		FROM LitmusQuestion 
		WHERE tag="gamble"));
		
INSERT INTO PossibleAnswer (string, orderIndex, litmusQuestionId) 
	VALUES ("Never", 4,
	(SELECT litmusQuestionId 
		FROM LitmusQuestion 
		WHERE tag="gamble"));
	
INSERT INTO PossibleAnswer (string, orderIndex, litmusQuestionId) 
	VALUES ("Active Duty", 1,
	(SELECT litmusQuestionId 
		FROM LitmusQuestion 
		WHERE tag="military_status"));
		
INSERT INTO PossibleAnswer (string, orderIndex, litmusQuestionId) 
	VALUES ("Active Reserve", 2,
	(SELECT litmusQuestionId 
		FROM LitmusQuestion 
		WHERE tag="military_status"));
		
INSERT INTO PossibleAnswer (string, orderIndex, litmusQuestionId) 
	VALUES ("Disabled Veterans", 3,
	(SELECT litmusQuestionId 
		FROM LitmusQuestion 
		WHERE tag="military_status"));
		
INSERT INTO PossibleAnswer (string, orderIndex, litmusQuestionId) 
	VALUES ("Inactive Reserve", 4,
	(SELECT litmusQuestionId 
		FROM LitmusQuestion 
		WHERE tag="military_status"));
		
INSERT INTO PossibleAnswer (string, orderIndex, litmusQuestionId) 
	VALUES ("None", 5,
	(SELECT litmusQuestionId 
		FROM LitmusQuestion 
		WHERE tag="military_status"));
	
INSERT INTO PossibleAnswer (string, orderIndex, litmusQuestionId) 
	VALUES ("Yes", 1,
	(SELECT litmusQuestionId 
		FROM LitmusQuestion 
		WHERE tag="fluent_english"));
		
INSERT INTO PossibleAnswer (string, orderIndex, litmusQuestionId) 
	VALUES ("No", 2,
	(SELECT litmusQuestionId 
		FROM LitmusQuestion 
		WHERE tag="fluent_english"));

-- sample locations
INSERT INTO Location (title, address) 
	VALUES ("Teal", "5696 Lotheville Court");
	
INSERT INTO Location (title, address) 
	VALUES ("Red", "9 Utah Court");
	
INSERT INTO Location (title, address) 
	VALUES ("Turquoise", "71499 Buhler Trail");
	
INSERT INTO Location (title, address) 
	VALUES ("Green", "02122 Prairieview Place");
	
INSERT INTO Location (title, address) 
	VALUES ("Orange", "8 Scofield Road");
	
INSERT INTO Location (title, address) 
	VALUES ("Yellow", "591 Oak Avenue");

-- sample appointment with answers

INSERT INTO Client (firstName, lastName, emailAddress)
	VALUES ("Test", "McTesterson", "test_mctesterson@test.test");
SET @clientId = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 2 HOUR);

INSERT INTO Appointment (scheduledTime, clientId, locationId)
	VALUES (@appointmentTime, @clientId, 1);


INSERT INTO Client (firstName, lastName, emailAddress) 
	VALUES ("Big Tony", "Constanza", "big.tony.constanza@test.test");
SET @clientId = LAST_INSERT_ID();

SET @appointmentTime = DATE_ADD(NOW(), INTERVAL 3 HOUR);

INSERT INTO Appointment (scheduledTime, clientId, locationId)
	VALUES (@appointmentTime, @clientId, 1);
        
INSERT INTO Client (firstName, lastName, emailAddress)
	VALUES ("Ralph", "Schmidt", "ralphman@test.test");
SET @clientId = LAST_INSERT_ID();
    
INSERT INTO Appointment (scheduledTime, clientId, locationId) 
	VALUES ("2017-05-28 16:30:00", @clientId, 1);

SET @pharmacistLitmusQuestionId = (SELECT litmusQuestionId FROM LitmusQuestion WHERE tag="pharmacist");
SET @gambleLitmusQuestionId = (SELECT litmusQuestionId FROM LitmusQuestion WHERE tag="gamble");
SET @militaryStatusLitmusQuestionId = (SELECT litmusQuestionId FROM LitmusQuestion WHERE tag="military_status");
SET @fluentEnglishLitmusQuestionId = (SELECT litmusQuestionId FROM LitmusQuestion WHERE tag="fluent_english");

INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId) 
	VALUES (
    (SELECT possibleAnswerId
		FROM PossibleAnswer
        WHERE litmusQuestionId=@pharmacistLitmusQuestionId AND string="Yes"),
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    @pharmacistLitmusQuestionId);

INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId) 
	VALUES(
    (SELECT possibleAnswerId
		FROM PossibleAnswer
		WHERE litmusQuestionId=@gambleLitmusQuestionId AND string="Never"),
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    @gambleLitmusQuestionId);

INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId) 
	VALUES(
    (SELECT possibleAnswerId
		FROM PossibleAnswer
        WHERE litmusQuestionId=@militaryStatusLitmusQuestionId AND string="None"),
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    @militaryStatusLitmusQuestionId);

INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId) 
	VALUES(
    (SELECT possibleAnswerId
		FROM PossibleAnswer
        WHERE litmusQuestionId=@fluentEnglishLitmusQuestionId AND string="Yes"),
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    @fluentEnglishLitmusQuestionId);

INSERT INTO Client (firstName, lastName, emailAddress)
	VALUES ("Kathy", "Stevens", "kstev89@test.test");
SET @clientId = LAST_INSERT_ID();

INSERT INTO Appointment (scheduledTime, clientId, locationId) 
	VALUES ("2017-06-01 12:45", @clientId, 5);

INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId) 
	VALUES(
    (SELECT possibleAnswerId
		FROM PossibleAnswer
        WHERE litmusQuestionId=@pharmacistLitmusQuestionId AND string="Yes"),
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    @pharmacistLitmusQuestionId);

INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId) 
	VALUES(
    (SELECT possibleAnswerId
		FROM PossibleAnswer
        WHERE litmusQuestionId=@militaryStatusLitmusQuestionId AND string="Active Reserve"),
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    @militaryStatusLitmusQuestionId);

INSERT INTO Answer (possibleAnswerId, appointmentId, litmusQuestionId) 
	VALUES (
    (SELECT possibleAnswerId
		FROM PossibleAnswer
        WHERE litmusQuestionId=@fluentEnglishLitmusQuestionId AND string="No"),
    (SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    @fluentEnglishLitmusQuestionId);