USE vita;

-- sample questions with choices, if applicable
INSERT INTO QuestionAccessories (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("text", "First Name", "Contact Information", NULL, NULL, NULL);
    
INSERT INTO Question (string, tag, required, questionAccessoriesId) 
	VALUES ("First Name", "first_name", TRUE, 
    (SELECT questionAccessoriesId FROM QuestionAccessories
		WHERE placeholder="First Name"));

INSERT INTO QuestionAccessories (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("text", "Last Name", "Contact Information", NULL, NULL, NULL);
    
INSERT INTO Question (string, tag, required, questionAccessoriesId) 
	VALUES ("Last Name", "last_name", TRUE,
	(SELECT questionAccessoriesId FROM QuestionAccessories
		WHERE placeholder="Last Name"));
    
INSERT INTO QuestionAccessories (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("email", "example@example.com", "Contact Information", "email", NULL, NULL);
    
INSERT INTO Question (string, tag, required, questionAccessoriesId) 
	VALUES ("Email Address", "email", TRUE,
	(SELECT questionAccessoriesId FROM QuestionAccessories
		WHERE placeholder="example@example.com"));
    
INSERT INTO QuestionAccessories (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("text", "402-555-1234", "Contact Information", "phoneNumberUS", NULL, NULL);
    
INSERT INTO Question (string, tag, required, questionAccessoriesId) 
	VALUES ("Phone Number", "phone_number", FALSE,
    (SELECT questionAccessoriesId FROM QuestionAccessories
		WHERE placeholder="402-555-1234"));
	
INSERT INTO QuestionAccessories (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("select", "No", "Background Information", NULL, NULL, NULL);
    
INSERT INTO Question (string, tag, required, questionAccessoriesId) 
	VALUES ("Are you a pharmacist?", "pharmacist", FALSE,
    (SELECT questionAccessoriesId FROM QuestionAccessories
		WHERE inputType="select" AND placeholder="No" AND subheading="Background Information"));
	
INSERT INTO QuestionAccessories (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("select", "Never", "Background Information", NULL, NULL, NULL);
    
INSERT INTO Question (string, tag, required, questionAccessoriesId) 
	VALUES ("How often do you gamble?", "gamble", FALSE, 
    (SELECT questionAccessoriesId FROM QuestionAccessories
		WHERE inputType="select" AND placeholder="Never" AND subheading="Background Information"));
    
INSERT INTO QuestionAccessories (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("select", "None", "Background Information", NULL, NULL, NULL);
    
INSERT INTO Question (string, tag, required, questionAccessoriesId) 
	VALUES ("Indicate your military status", "military_status", FALSE,
    (SELECT questionAccessoriesId FROM QuestionAccessories
		WHERE inputType="select" AND placeholder="None" AND subheading="Background Information"));
        
INSERT INTO QuestionAccessories (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("select", "Yes", "Language Information", NULL, NULL, NULL);

INSERT INTO Question (string, tag, required, questionAccessoriesId) 
	VALUES ("Can you speak fluent English?", "fluent_english", TRUE,
    (SELECT questionAccessoriesId FROM QuestionAccessories
		WHERE inputType="select" AND placeholder="Yes" AND subheading="Language Information"));

INSERT INTO QuestionAccessories (inputType, placeholder, subheading, validationType, hint, errorMessage)
	VALUES ("text", "Spanish, German, French, etc.", "Language Information", NULL, NULL, NULL);

INSERT INTO Question (string, tag, required, questionAccessoriesId) 
	VALUES ("If no, what is your strongest language?", "strongest_language", FALSE,
    (SELECT questionAccessoriesId FROM QuestionAccessories
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

-- Sample Appointments With Answers

-- Start Sample Appointment 1
INSERT INTO Appointment (scheduledTime, locationId) 
	VALUES ("2017-05-28 16:30:00", 1);
	
INSERT INTO UserAnswer (string) 
	VALUES ("Ralph");
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

INSERT INTO UserAnswer (string)
	VALUES ("Schmidt");
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId)
	VALUES(
    (SELECT appointmentId
		FROM Appointment
        WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
	(SELECT questionId
		FROM Question
        WHERE string="Last Name"),
	@lastUserAnswerId);

INSERT INTO UserAnswer (string) 
	VALUES ("ralphman@gmail.com");
SET @lastUserAnswerId = LAST_INSERT_ID();
	
INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId = 1),
    (SELECT questionId 
		FROM Question 
		WHERE string = "Email Address"),
    @lastUserAnswerId);
	
INSERT INTO UserAnswer (string) 
	VALUES ("(753) 875-3165");
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    (SELECT questionId 
		FROM Question 
		WHERE string="Phone Number"),
    @lastUserAnswerId);

INSERT INTO UserAnswer (string)
	VALUES ("No");
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES (
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    (SELECT questionId 
		FROM Question 
		WHERE string="Are you a pharmacist?"),
    @lastUserAnswerId);

INSERT INTO UserAnswer (string)
	VALUES ("Occasionally");
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    (SELECT questionId 
		FROM Question 
		WHERE string="How often do you gamble?"),
    @lastUserAnswerId);

INSERT INTO UserAnswer (string)
	VALUES ("None");
SET @lastUserAnswerId = LAST_INSERT_ID();

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, userAnswerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    (SELECT questionId 
		FROM Question 
		WHERE string="Indicate your military status"),
    @lastUserAnswerId);
            
INSERT INTO UserAnswer (string)
	VALUES ("Yes");
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
-- End Sample Appointment 1

-- Start Sample Appointment 2
INSERT INTO Appointment (scheduledTime, locationId) 
	VALUES ("2017-06-01 12:45", 5);

INSERT INTO UserAnswer (string) 
	VALUES ("Kathy");
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

INSERT INTO UserAnswer (string)
	VALUES ("Stevens");
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

INSERT INTO UserAnswer (string) 
	VALUES ("kstev89@gmail.com");
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
    
INSERT INTO UserAnswer (string)
	VALUES ("Yes");
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

INSERT INTO UserAnswer (string)
	VALUES ("Active Reserve");
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
    
INSERT INTO UserAnswer (string)
	VALUES ("No");
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

INSERT INTO UserAnswer (string) 
	VALUES ("Spanish");
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
-- End Sample Appointment 2    


-- -- test queries -- --
SELECT * FROM Question;
SELECT * FROM QuestionAccessories;
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
