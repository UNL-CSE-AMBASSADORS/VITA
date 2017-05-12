USE vita;

-- sample questions with choices, if applicable
INSERT INTO QuestionInformation (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("text", "First Name", "Contact Information", NULL, NULL, NULL);
    
INSERT INTO Question (string, tag, required, questionInformationId) 
	VALUES ("First Name", "first_name", TRUE, 
    (SELECT questionInformationId FROM QuestionInformation
		WHERE placeholder="First Name"));

INSERT INTO QuestionInformation (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("text", "Last Name", "Contact Information", NULL, NULL, NULL);
    
INSERT INTO Question (string, tag, required, questionInformationId) 
	VALUES ("Last Name", "last_name", TRUE,
	(SELECT questionInformationId FROM QuestionInformation
		WHERE placeholder="Last Name"));
    
INSERT INTO QuestionInformation (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("email", "example@example.com", "Contact Information", "email", NULL, NULL);
    
INSERT INTO Question (string, tag, required, questionInformationId) 
	VALUES ("Email Address", "email", TRUE,
	(SELECT questionInformationId FROM QuestionInformation
		WHERE placeholder="example@example.com"));
    
INSERT INTO QuestionInformation (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("text", "402-555-1234", "Contact Information", "phoneNumberUS", NULL, NULL);
    
INSERT INTO Question (string, tag, required, questionInformationId) 
	VALUES ("Phone Number", "phone_number", FALSE,
    (SELECT questionInformationId FROM QuestionInformation
		WHERE placeholder="402-555-1234"));
	
INSERT INTO QuestionInformation (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("select", "No", "Background Information", NULL, NULL, NULL);
    
INSERT INTO Question (string, tag, required, questionInformationId) 
	VALUES ("Are you a pharmacist?", "pharmacist", FALSE,
    (SELECT questionInformationId FROM QuestionInformation
		WHERE inputType="select" AND placeholder="No" AND subheading="Background Information"));
	
INSERT INTO QuestionInformation (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("select", "Never", "Background Information", NULL, NULL, NULL);
    
INSERT INTO Question (string, tag, required, questionInformationId) 
	VALUES ("How often do you gamble?", "gamble", FALSE, 
    (SELECT questionInformationId FROM QuestionInformation
		WHERE inputType="select" AND placeholder="Never" AND subheading="Background Information"));
    
INSERT INTO QuestionInformation (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("select", "None", "Background Information", NULL, NULL, NULL);
    
INSERT INTO Question (string, tag, required, questionInformationId) 
	VALUES ("Indicate your military status", "military_status", FALSE,
    (SELECT questionInformationId FROM QuestionInformation
		WHERE inputType="select" AND placeholder="None" AND subheading="Background Information"));
        
INSERT INTO QuestionInformation (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("select", "Yes", "Language Information", NULL, NULL, NULL);

INSERT INTO Question (string, tag, required, questionInformationId) 
	VALUES ("Can you speak fluent English?", "fluent_english", TRUE,
    (SELECT questionInformationId FROM QuestionInformation
		WHERE inputType="select" AND placeholder="Yes" AND subheading="Language Information"));

INSERT INTO QuestionInformation (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("text", "Spanish, German, French, etc.", "Language Information", NULL, NULL, NULL);

INSERT INTO Question (string, tag, required, questionInformationId) 
	VALUES ("If no, what is your strongest language?", "strongest_language", FALSE,
    (SELECT questionInformationId FROM QuestionInformation
		WHERE inputType="text" AND placeholder="Spanish, German, French, etc." AND subheading="Language Information"));
    
-- Sample Possible Answer data
INSERT INTO PossibleAnswer (string, questionId) 
	VALUES ("Yes", 
	(SELECT questionId 
		FROM Question 
		WHERE tag="pharmacist"));
		
INSERT INTO PossibleAnswer (string, questionId) 
	VALUES ("No", 
	(SELECT questionId 
		FROM Question 
		WHERE tag="pharmacist"));
	
INSERT INTO PossibleAnswer (string, questionId) 
	VALUES ("Frequently", 
	(SELECT questionId 
		FROM Question 
		WHERE tag="gamble"));
		
INSERT INTO PossibleAnswer (string, questionId) 
	VALUES ("Occasionally", 
	(SELECT questionId 
		FROM Question 
		WHERE tag="gamble"));
		
INSERT INTO PossibleAnswer (string, questionId) 
	VALUES ("Rarely", 
	(SELECT questionId 
		FROM Question 
		WHERE tag="gamble"));
		
INSERT INTO PossibleAnswer (string, questionId) 
	VALUES ("Never", 
	(SELECT questionId 
		FROM Question 
		WHERE tag="gamble"));
	
INSERT INTO PossibleAnswer (string, questionId) 
	VALUES ("Active Duty", 
	(SELECT questionId 
		FROM Question 
		WHERE tag="military_status"));
		
INSERT INTO PossibleAnswer (string, questionId) 
	VALUES ("Active Reserve", 
	(SELECT questionId 
		FROM Question 
		WHERE tag="military_status"));
		
INSERT INTO PossibleAnswer (string, questionId) 
	VALUES ("Disabled Veterans", 
	(SELECT questionId 
		FROM Question 
		WHERE tag="military_status"));
		
INSERT INTO PossibleAnswer (string, questionId) 
	VALUES ("Inactive Reserve", 
	(SELECT questionId 
		FROM Question 
		WHERE tag="military_status"));
		
INSERT INTO PossibleAnswer (string, questionId) 
	VALUES ("None", 
	(SELECT questionId 
		FROM Question 
		WHERE tag="military_status"));
	
INSERT INTO PossibleAnswer (string, questionId) 
	VALUES ("Yes", 
	(SELECT questionId 
		FROM Question 
		WHERE tag="fluent_english"));
		
INSERT INTO PossibleAnswer (string, questionId) 
	VALUES ("No", 
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

INSERT INTO Appointment (scheduledTime, locationId)
	VALUES ("2017-05-07 23:59:59", 1);
    
INSERT INTO UserAnswer (string, questionId)
	VALUES ("Test",
    (SELECT questionId
		FROM question
        WHERE tag="first_name"));
SET @lastUserAnswerId = LAST_INSERT_ID();
        
INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId)
	VALUES(
    (SELECT appointmentId
		FROM Appointment
        WHERE scheduledTime="2017-05-07 23:59:59" AND locationId=1),
	(SELECT questionId
		FROM Question
        WHERE tag="first_name"),
	@lastUserAnswerId);
            
INSERT INTO UserAnswer (string, questionId)
	VALUES ("McTesterson",
    (SELECT questionId
		FROM question
        WHERE tag="last_name"));
SET @lastUserAnswerId = LAST_INSERT_ID();
            
INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId)
	VALUES(
    (SELECT appointmentId
		FROM Appointment
        WHERE scheduledTime="2017-05-07 23:59:59" AND locationId=1),
	(SELECT questionId
		FROM Question
        WHERE tag="last_name"),
	@lastUserAnswerId);

INSERT INTO Appointment (scheduledTime, locationId)
	VALUES ("2017-05-07 19:30:00", 1);
    
INSERT INTO UserAnswer (string, questionId)
	VALUES ("Tony",
    (SELECT questionId
		FROM question
        WHERE tag="first_name"));
SET @lastUserAnswerId = LAST_INSERT_ID();
        
INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId)
	VALUES(
    (SELECT appointmentId
		FROM Appointment
        WHERE scheduledTime="2017-05-07 19:30:00" AND locationId=1),
	(SELECT questionId
		FROM Question
        WHERE tag="first_name"),
	@lastUserAnswerId);
            
INSERT INTO UserAnswer (string, questionId)
	VALUES ("Constanza",
    (SELECT questionId
		FROM question
        WHERE tag="last_name"));
SET @lastUserAnswerId = LAST_INSERT_ID();
            
INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId)
	VALUES(
    (SELECT appointmentId
		FROM Appointment
        WHERE scheduledTime="2017-05-07 19:30:00" AND locationId=1),
	(SELECT questionId
		FROM Question
        WHERE tag="last_name"),
	@lastUserAnswerId);

INSERT INTO Appointment (scheduledTime, locationId) 
	VALUES ("2017-05-28 16:30:00", 1);
	
INSERT INTO UserAnswer (string, questionId) 
	VALUES ("Ralph",
    (SELECT questionId
		FROM question
        WHERE tag="first_name"));
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
	(SELECT questionId 
		FROM Question 
		WHERE tag="first_name"),
	@lastUserAnswerId);

INSERT INTO UserAnswer (string, questionId)
	VALUES ("Schmidt",
    (SELECT questionId
		FROM question
        WHERE tag="last_name"));
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId)
	VALUES(
    (SELECT appointmentId
		FROM Appointment
        WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
	(SELECT questionId
		FROM Question
        WHERE tag="last_name"),
	@lastUserAnswerId);

INSERT INTO UserAnswer (string, questionId) 
	VALUES ("ralphman@gmail.com",
    (SELECT questionId
		FROM question
        WHERE tag="email"));
SET @lastUserAnswerId = LAST_INSERT_ID();
	
INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId = 1),
    (SELECT questionId 
		FROM Question 
		WHERE tag="email"),
    @lastUserAnswerId);
	
INSERT INTO UserAnswer (string, questionId) 
	VALUES ("(753) 875-3165",
    (SELECT questionId
		FROM question
        WHERE tag="phone_number"));
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    (SELECT questionId 
		FROM Question 
		WHERE tag="phone_number"),
    @lastUserAnswerId);

INSERT INTO UserAnswer (string, questionId)
	VALUES ("No",
    (SELECT questionId
		FROM question
        WHERE tag="pharmacist"));
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES (
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    (SELECT questionId 
		FROM Question 
		WHERE tag="pharmacist"),
    @lastUserAnswerId);

INSERT INTO UserAnswer (string, questionId)
	VALUES ("Occasionally",
    (SELECT questionId
		FROM question
        WHERE tag="gamble"));
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    (SELECT questionId 
		FROM Question 
		WHERE tag="gamble"),
    @lastUserAnswerId);

INSERT INTO UserAnswer (string, questionId)
	VALUES ("None",
    (SELECT questionId
		FROM question
        WHERE tag="military_status"));
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    (SELECT questionId 
		FROM Question 
		WHERE tag="military_status"),
    @lastUserAnswerId);
            
INSERT INTO UserAnswer (string, questionId)
	VALUES ("Yes",
    (SELECT questionId
		FROM question
        WHERE tag="fluent_english"));
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    (SELECT questionId 
		FROM Question 
		WHERE tag="fluent_english"),
    @lastUserAnswerId);

INSERT INTO Appointment (scheduledTime, locationId) 
	VALUES ("2017-06-01 12:45", 5);

INSERT INTO UserAnswer (string, questionId) 
	VALUES ("Kathy",
    (SELECT questionId
		FROM question
        WHERE tag="first_name"));
SET @lastUserAnswerId = LAST_INSERT_ID();
	
INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    (SELECT questionId 
		FROM Question 
		WHERE tag="first_name"),
    @lastUserAnswerId);

INSERT INTO UserAnswer (string, questionId)
	VALUES ("Stevens",
    (SELECT questionId
		FROM question
        WHERE tag="last_name"));
SET @lastUserAnswerId = LAST_INSERT_ID();
      
INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId)
	VALUES(
    (SELECT appointmentId
		FROM Appointment
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
	(SELECT questionId
		FROM Question
        WHERE tag="last_name"),
	@lastUserAnswerId);

INSERT INTO UserAnswer (string, questionId) 
	VALUES ("kstev89@gmail.com",
    (SELECT questionId
		FROM question
        WHERE tag="email"));
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    (SELECT questionId 
		FROM Question 
		WHERE tag="email"),
    @lastUserAnswerId);
    
INSERT INTO UserAnswer (string, questionId)
	VALUES ("Yes",
    (SELECT questionId
		FROM question
        WHERE tag="pharmacist"));
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    (SELECT questionId 
		FROM Question 
		WHERE tag="pharmacist"),
    @lastUserAnswerId);

INSERT INTO UserAnswer (string, questionId)
	VALUES ("Active Reserve",
    (SELECT questionId
		FROM question
        WHERE tag="military_status"));
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    (SELECT questionId 
		FROM Question 
		WHERE tag="military_status"),
    @lastUserAnswerId);
    
INSERT INTO UserAnswer (string, questionId)
	VALUES ("No",
    (SELECT questionId
		FROM question
        WHERE tag="fluent_english"));
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES (
    (SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    (SELECT questionId 
		FROM Question 
		WHERE tag="fluent_english"),
    @lastUserAnswerId);

INSERT INTO UserAnswer (string, questionId) 
	VALUES ("Spanish",
    (SELECT questionId
		FROM question
        WHERE tag="strongest_language"));
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES (
    (SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    (SELECT questionId 
		FROM Question 
		WHERE tag="strongest_language"),
    @lastUserAnswerId);

-- -- test queries -- --
SELECT * FROM Question;
SELECT * FROM QuestionInformation;
SELECT * FROM UserAnswer;
SELECT * FROM PossibleAnswer;
SELECT * FROM Location;
SELECT * FROM Appointment;
SELECT * FROM AppointmentQuestionAnswer;

-- all answers for an appointment
SELECT Question.string AS question, UserAnswer.string AS answer 
	FROM AppointmentQuestionAnswer AQA 
	JOIN UserAnswer ON AQA.userAnswerId=UserAnswer.userAnswerId 
	JOIN Question ON AQA.questionId = Question.questionId 
	WHERE appointmentId = 1;
    
SELECT Question.string AS question, UserAnswer.string AS answer 
	FROM AppointmentQuestionAnswer AQA 
	JOIN UserAnswer ON AQA.userAnswerId=UserAnswer.userAnswerId 
	JOIN Question ON AQA.questionId = Question.questionId 
	WHERE appointmentId = 2;

-- active questions
SELECT questionId, string FROM Question WHERE archived=FALSE;

-- required questions
SELECT questionId, string FROM Question WHERE required=TRUE;
