-- VITA Project
-- UNL CSE Ambassadors 2017

DROP TABLE IF EXISTS AppointmentQuestionAnswer;
DROP TABLE IF EXISTS Appointment;
DROP TABLE IF EXISTS Location;
DROP TABLE IF EXISTS Answer;
DROP TABLE IF EXISTS Question;

CREATE TABLE Location (
	locationId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(255),
    address VARCHAR(255)
);

CREATE TABLE Question (
	questionId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    string VARCHAR(255) NOT NULL,
    inputType VARCHAR(255) NOT NULL,
    placeholder VARCHAR(255) NOT NULL,
    tag VARCHAR(255) NOT NULL,
    required BOOLEAN DEFAULT TRUE,
    archived BOOLEAN DEFAULT FALSE
);

CREATE TABLE Answer (
	answerId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    string VARCHAR(255) NOT NULL,
    archived BOOLEAN DEFAULT FALSE,
    questionId INTEGER NOT NULL,
    FOREIGN KEY (questionId) REFERENCES Question(questionId)
);

CREATE TABLE Appointment (
	appointmentId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    scheduledTime DATETIME NOT NULL,
    locationId INTEGER NOT NULL,
    FOREIGN KEY (locationId) REFERENCES Location(locationId),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    archived BOOLEAN DEFAULT FALSE
);

CREATE TABLE AppointmentQuestionAnswer (
	appointmentQuestionAnswerId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    appointmentId INTEGER NOT NULL,
    FOREIGN KEY (appointmentId) REFERENCES Appointment(appointmentId),
    questionId INTEGER NOT NULL,
    FOREIGN KEY (questionId) REFERENCES Question(questionId),
    answerId INTEGER NOT NULL,
    FOREIGN KEY(answerId) REFERENCES Answer(answerId),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- -- seed data -- --

-- sample questions with choices, if applicable
INSERT INTO Question (string, inputType, placeholder, tag, required) 
	VALUES ("First Name", "text", "First Name", "first_name", TRUE);

INSERT INTO Question (string, inputType, placeholder, tag, required)
	VALUES ("Last Name", "text", "Last Name", "last_name", TRUE);
	
INSERT INTO Question (string, inputType, placeholder, tag, required) 
	VALUES ("Email Address", "email", "example@example.com", "email", TRUE);
	
INSERT INTO Question (string, inputType, placeholder, tag, required) 
	VALUES ("Phone Number", "text", "402-555-1234", "phone_number", FALSE);

INSERT INTO Question (string, inputType, placeholder, tag, required) 
	VALUES ("Are you a pharmacist?", "select", "No", "pharmacist", FALSE);
	
INSERT INTO Question (string, inputType, placeholder, tag, required) 
	VALUES ("How often do you gamble?", "select", "Never", "gamble", FALSE);
    
INSERT INTO Question (string, inputType, placeholder, tag, required) 
	VALUES ("Indicate your military status", "select", "None", "military_status", FALSE);

INSERT INTO Question (string, inputType, placeholder, tag, required) 
	VALUES ("Can you speak fluent English?", "select", "Yes", "fluent_english", TRUE);

INSERT INTO Question (string, inputType, placeholder, tag, required) 
	VALUES ("If no, what is your strongest language?", "text", "Spanish, German, French, etc.", "strongest_language", FALSE);
    
-- Sample answer data
    
INSERT INTO Answer (string, questionId) 
	VALUES ("Yes", 
	(SELECT questionId 
		FROM Question 
		WHERE string="Are you a pharmacist?"));
		
INSERT INTO Answer (string, questionId) 
	VALUES ("No", 
	(SELECT questionId 
		FROM Question 
		WHERE string="Are you a pharmacist?"));
	
INSERT INTO Answer (string, questionId) 
	VALUES ("Frequently", 
	(SELECT questionId 
		FROM Question 
		WHERE string="How often do you gamble?"));
		
INSERT INTO Answer (string, questionId) 
	VALUES ("Occasionally", 
	(SELECT questionId 
		FROM Question 
		WHERE string="How often do you gamble?"));
		
INSERT INTO Answer (string, questionId) 
	VALUES ("Rarely", 
	(SELECT questionId 
		FROM Question 
		WHERE string="How often do you gamble?"));
		
INSERT INTO Answer (string, questionId) 
	VALUES ("Never", 
	(SELECT questionId 
		FROM Question 
		WHERE string="How often do you gamble?"));
	
INSERT INTO Answer (string, questionId) 
	VALUES ("Active Duty", 
	(SELECT questionId 
		FROM Question 
		WHERE string="Indicate your military status"));
		
INSERT INTO Answer (string, questionId) 
	VALUES ("Active Reserve", 
	(SELECT questionId 
		FROM Question 
		WHERE string="Indicate your military status"));
		
INSERT INTO Answer (string, questionId) 
	VALUES ("Disabled Veterans", 
	(SELECT questionId 
		FROM Question 
		WHERE string="Indicate your military status"));
		
INSERT INTO Answer (string, questionId) 
	VALUES ("Inactive Reserve", 
	(SELECT questionId 
		FROM Question 
		WHERE string="Indicate your military status"));
		
INSERT INTO Answer (string, questionId) 
	VALUES ("None", 
	(SELECT questionId 
		FROM Question 
		WHERE string="Indicate your military status"));
	
INSERT INTO Answer (string, questionId) 
	VALUES ("Yes", 
	(SELECT questionId 
		FROM Question 
		WHERE string="Can you speak fluent English?"));
		
INSERT INTO Answer (string, questionId) 
	VALUES ("No", 
	(SELECT questionId 
		FROM Question 
		WHERE string="Can you speak fluent English?"));

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
	VALUES ("2017-05-28 16:30:00", 1);
	
INSERT INTO Answer (string, questionId) 
	VALUES ("Ralph", 
	(SELECT questionId 
		FROM Question 
		WHERE string="First Name"));
        
INSERT INTO Answer (string, questionId)
	VALUES ("Schmidt",
    (SELECT questionId
		FROM Question
        WHERE string="Last Name"));
		
INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
	(SELECT questionId 
		FROM Question 
		WHERE string="First Name"),
	(SELECT answerId 
		FROM Answer 
		WHERE ((string="Ralph" AND questionId=
		(SELECT questionId 
			FROM Question 
			WHERE string="First Name")))));

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId)
	VALUES(
    (SELECT appointmentId
		FROM Appointment
        WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
	(SELECT questionId
		FROM Question
        WHERE string="Last Name"),
	(SELECT answerId
		FROM Answer
        WHERE (string="Schmidt" AND questionId=
        (SELECT questionId
			FROM Question
			WHERE string="Last Name"))));

INSERT INTO Answer (string, questionId) 
	VALUES ("ralphman@gmail.com", 
	(SELECT questionId 
		FROM Question 
		WHERE string="Email Address"));
	
INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId = 1),
    (SELECT questionId 
		FROM Question 
		WHERE string = "Email Address"),
    (SELECT answerId 
		FROM Answer 
		WHERE string = "ralphman@gmail.com" AND questionId = 
		(SELECT questionId 
			FROM Question 
			WHERE string = "Email Address")));
	
INSERT INTO Answer (string, questionId) 
	VALUES ("(753) 875-3165", 
	(SELECT questionId 
		FROM Question 
		WHERE string="Phone Number"));

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    (SELECT questionId 
		FROM Question 
		WHERE string="Phone Number"),
    (SELECT answerId 
		FROM Answer 
		WHERE string="(753) 875-3165" AND questionId=
		(SELECT questionId 
			FROM Question 
			WHERE string="Phone Number")));

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId) 
	VALUES (
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    (SELECT questionId 
		FROM Question 
		WHERE string="Are you a pharmacist?"),
    (SELECT answerId 
		FROM Answer 
		WHERE string="No" AND questionId=
		(SELECT questionId 
			FROM Question 
			WHERE string="Are you a pharmacist?")));

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    (SELECT questionId 
		FROM Question 
		WHERE string="How often do you gamble?"),
    (SELECT answerId 
		FROM Answer 
		WHERE string="Occasionally" AND questionId=
		(SELECT questionId 
			FROM Question 
			WHERE string="How often do you gamble?")));

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    (SELECT questionId 
		FROM Question 
		WHERE string="Indicate your military status"),
    (SELECT answerId 
		FROM Answer 
		WHERE string="None" AND questionId=
		(SELECT questionId 
			FROM Question 
			WHERE string="Indicate your military status")));

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-05-28 16:30:00" AND locationId=1),
    (SELECT questionId 
		FROM Question 
		WHERE string="Can you speak fluent English?"),
    (SELECT answerId 
		FROM Answer 
		WHERE string="Yes" AND questionId=
		(SELECT questionId 
			FROM Question 
			WHERE string="Can you speak fluent English?")));

INSERT INTO Appointment (scheduledTime, locationId) 
	VALUES ("2017-06-01 12:45", 5);

INSERT INTO Answer (string, questionId) 
	VALUES ("Kathy", 
	(SELECT questionId 
		FROM Question 
		WHERE string="First Name"));
	
INSERT INTO Answer (string, questionId)
	VALUES ("Stevens",
    (SELECT questionId
		FROM Question
        WHERE string="Last Name"));

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    (SELECT questionId 
		FROM Question 
		WHERE string="First Name"),
    (SELECT answerId 
		FROM Answer 
		WHERE string="Kathy" AND questionId=
		(SELECT questionId 
			FROM Question 
			WHERE string="First Name")));
                
INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId)
	VALUES(
    (SELECT appointmentId
		FROM Appointment
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
	(SELECT questionId
		FROM Question
        WHERE string="Last Name"),
	(SELECT answerId
		FROM Answer
        WHERE string="Stevens" AND questionId=
        (SELECT questionId
			FROM Question
            WHERE string="Last Name")));

INSERT INTO Answer (string, questionId) 
	VALUES ("kstev89@gmail.com", 
	(SELECT questionId 
		FROM Question 
		WHERE string="Email Address"));

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    (SELECT questionId 
		FROM Question 
		WHERE string="Email Address"),
    (SELECT answerId 
		FROM Answer 
		WHERE string="kstev89@gmail.com" AND questionId=
		(SELECT questionId 
			FROM Question 
			WHERE string="Email Address")));

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    (SELECT questionId 
		FROM Question 
		WHERE string="Are you a pharmacist?"),
    (SELECT answerId 
		FROM Answer 
		WHERE string="Yes" AND questionId=
		(SELECT questionId 
			FROM Question 
			WHERE string="Are you a pharmacist?")));

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId) 
	VALUES(
	(SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    (SELECT questionId 
		FROM Question 
		WHERE string="Indicate your military status"),
    (SELECT answerId 
		FROM Answer 
		WHERE string="Active Reserve" AND questionId=
		(SELECT questionId 
			FROM Question 
			WHERE string="Indicate your military status")));

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId) VALUES
	((SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    (SELECT questionId 
		FROM Question 
		WHERE string="Can you speak fluent English?"),
    (SELECT answerId 
		FROM Answer 
		WHERE string="No" AND questionId=
			(SELECT questionId 
				FROM Question 
				WHERE string="Can you speak fluent English?")));

INSERT INTO Answer (string, questionId) 
	VALUES ("Spanish", 
	(SELECT questionId 
		FROM Question 
		WHERE string="If no, what is your strongest language?"));

INSERT INTO AppointmentQuestionAnswer (appointmentId, questionId, answerId) VALUES
	((SELECT appointmentId 
		FROM Appointment 
		WHERE scheduledTime="2017-06-01 12:45" AND locationId=5),
    (SELECT questionId 
		FROM Question 
		WHERE string="If no, what is your strongest language?"),
    (SELECT answerId 
		FROM Answer 
		WHERE string="Spanish" AND questionId=
			(SELECT questionId 
				FROM Question 
				WHERE string="If no, what is your strongest language?")));

-- -- test queries -- --
SELECT * FROM Question;
SELECT * FROM Answer;
SELECT * FROM Location;
SELECT * FROM Appointment;
SELECT * FROM AppointmentQuestionAnswer;

-- all answers for an appointment
SELECT Question.string AS question, Answer.string AS answer 
	FROM AppointmentQuestionAnswer AQA 
	JOIN Answer ON AQA.answerId=Answer.answerId 
	JOIN Question ON AQA.questionId = Question.questionId 
	WHERE appointmentId = 1;
    
SELECT Question.string AS question, Answer.string AS answer 
	FROM AppointmentQuestionAnswer AQA 
	JOIN Answer ON AQA.answerId=Answer.answerId 
	JOIN Question ON AQA.questionId = Question.questionId 
	WHERE appointmentId = 2;

-- active questions
SELECT questionId, string FROM Question WHERE archived=FALSE;

-- required questions
SELECT questionId, string FROM Question WHERE required=TRUE;
