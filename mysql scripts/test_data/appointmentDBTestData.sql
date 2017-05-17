USE vita;

-- sample questions with choices, if applicable
INSERT INTO Question (string, orderIndex, tag)
	VALUES ("Are you a phamacist?", 1, "pharmacist");
    
INSERT INTO Question (string, orderIndex, tag)
	VALUES ("How often do you gamble?", 2, "gamble");
    
INSERT INTO Question (string, orderIndex, tag)
	VALUES ("Indicate your military status", 3, "military_status");
    
INSERT INTO Question (string, orderIndex, tag)
	VALUES ("Can you speak fluent English?", 4, "fluent_english");
    
-- Sample Possible Answer data
INSERT INTO PossibleAnswer (string, orderIndex, questionId) 
	VALUES ("Yes", 1,
	(SELECT questionId 
		FROM Question 
		WHERE tag="pharmacist"));
		
INSERT INTO PossibleAnswer (string, orderIndex, questionId) 
	VALUES ("No", 2,
	(SELECT questionId 
		FROM Question 
		WHERE tag="pharmacist"));
	
INSERT INTO PossibleAnswer (string, orderIndex, questionId) 
	VALUES ("Frequently", 1,
	(SELECT questionId 
		FROM Question 
		WHERE tag="gamble"));
		
INSERT INTO PossibleAnswer (string, orderIndex, questionId) 
	VALUES ("Occasionally", 2,
	(SELECT questionId 
		FROM Question 
		WHERE tag="gamble"));
		
INSERT INTO PossibleAnswer (string, orderIndex, questionId) 
	VALUES ("Rarely", 3,
	(SELECT questionId 
		FROM Question 
		WHERE tag="gamble"));
		
INSERT INTO PossibleAnswer (string, orderIndex, questionId) 
	VALUES ("Never", 4,
	(SELECT questionId 
		FROM Question 
		WHERE tag="gamble"));
	
INSERT INTO PossibleAnswer (string, orderIndex, questionId) 
	VALUES ("Active Duty", 1,
	(SELECT questionId 
		FROM Question 
		WHERE tag="military_status"));
		
INSERT INTO PossibleAnswer (string, orderIndex, questionId) 
	VALUES ("Active Reserve", 2,
	(SELECT questionId 
		FROM Question 
		WHERE tag="military_status"));
		
INSERT INTO PossibleAnswer (string, orderIndex, questionId) 
	VALUES ("Disabled Veterans", 3,
	(SELECT questionId 
		FROM Question 
		WHERE tag="military_status"));
		
INSERT INTO PossibleAnswer (string, orderIndex, questionId) 
	VALUES ("Inactive Reserve", 4,
	(SELECT questionId 
		FROM Question 
		WHERE tag="military_status"));
		
INSERT INTO PossibleAnswer (string, orderIndex, questionId) 
	VALUES ("None", 5,
	(SELECT questionId 
		FROM Question 
		WHERE tag="military_status"));
	
INSERT INTO PossibleAnswer (string, orderIndex, questionId) 
	VALUES ("Yes", 1,
	(SELECT questionId 
		FROM Question 
		WHERE tag="fluent_english"));
		
INSERT INTO PossibleAnswer (string, orderIndex, questionId) 
	VALUES ("No", 2,
	(SELECT questionId 
		FROM Question 
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

SET @pharmacistQuestionId = (SELECT questionId FROM Question WHERE tag="pharmacist");
SET @gambleQuestionId = (SELECT questionId FROM Question WHERE tag="gamble");
SET @militaryStatusQuestionId = (SELECT questionId FROM Question WHERE tag="military_status");
SET @fluentEnglishQuestionId = (SELECT questionId FROM Question WHERE tag="fluent_english");

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId) 
	VALUES (
    (SELECT possibleAnswerId
		FROM PossibleAnswer
        WHERE questionId=@pharmacistQuestionId AND string="Yes"),
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    @pharmacistQuestionId);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId) 
	VALUES(
    (SELECT possibleAnswerId
		FROM PossibleAnswer
		WHERE questionId=@gambleQuestionId AND string="Never"),
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    @gambleQuestionId);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId) 
	VALUES(
    (SELECT possibleAnswerId
		FROM PossibleAnswer
        WHERE questionId=@militaryStatusQuestionId AND string="None"),
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    @militaryStatusQuestionId);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId) 
	VALUES(
    (SELECT possibleAnswerId
		FROM PossibleAnswer
        WHERE questionId=@fluentEnglishQuestionId AND string="Yes"),
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    @fluentEnglishQuestionId);

INSERT INTO Client (firstName, lastName, emailAddress)
	VALUES ("Kathy", "Stevens", "kstev89@test.test");
SET @clientId = LAST_INSERT_ID();

INSERT INTO Appointment (scheduledTime, clientId, locationId) 
	VALUES ("2017-06-01 12:45", @clientId, 5);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId) 
	VALUES(
    (SELECT possibleAnswerId
		FROM PossibleAnswer
        WHERE questionId=@pharmacistQuestionId AND string="Yes"),
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    @pharmacistQuestionId);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId) 
	VALUES(
    (SELECT possibleAnswerId
		FROM PossibleAnswer
        WHERE questionId=@militaryStatusQuestionId AND string="Active Reserve"),
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    @militaryStatusQuestionId);

INSERT INTO Answer (possibleAnswerId, appointmentId, questionId) 
	VALUES (
    (SELECT possibleAnswerId
		FROM PossibleAnswer
        WHERE questionId=@fluentEnglishQuestionId AND string="No"),
    (SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    @fluentEnglishQuestionId);

-- -- test queries -- --
SELECT * FROM Question;
SELECT * FROM PossibleAnswer;
SELECT * FROM Location;
SELECT * FROM Client;
SELECT * FROM Appointment;
SELECT * FROM Answer;

-- all answers for an appointment
SELECT Question.string AS question, PossibleAnswer.string as answer 
	FROM Answer
	JOIN Question ON Answer.questionId = Question.questionId
    JOIN PossibleAnswer ON Answer.possibleAnswerId = PossibleAnswer.possibleAnswerId
	WHERE appointmentId = 1;
    
SELECT Question.string AS question, PossibleAnswer.string as answer 
	FROM Answer 
	JOIN Question ON Answer.questionId = Question.questionId
    JOIN PossibleAnswer ON Answer.possibleAnswerId = PossibleAnswer.possibleAnswerId
	WHERE appointmentId = 3;

-- active questions
SELECT questionId, string FROM Question WHERE archived=FALSE;

-- required questions
SELECT questionId, string FROM Question WHERE required=TRUE;
