-- VITA Project
-- UNL CSE Ambassadors 2017

USE vita;

DROP TABLE IF EXISTS AppointmentQuestionAnswer;
DROP TABLE IF EXISTS Appointment;
DROP TABLE IF EXISTS Location;
DROP TABLE IF EXISTS UserAnswer;
DROP TABLE IF EXISTS PossibleAnswer;
DROP TABLE IF EXISTS Question;
DROP TABLE IF EXISTS QuestionInformation;

CREATE TABLE QuestionInformation (
	questionInformationId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    inputType VARCHAR(255) NOT NULL,
    placeholder VARCHAR(255) NOT NULL,
    subheading VARCHAR(255) NOT NULL,
    validationType VARCHAR(255),
    hint VARCHAR(255),
    errorMessage VARCHAR(255)
);

CREATE TABLE Question (
	questionId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    string VARCHAR(255) NOT NULL,
    tag VARCHAR(255) NOT NULL,
    required BOOLEAN DEFAULT TRUE,
    archived BOOLEAN DEFAULT FALSE,
    questionInformationId INTEGER NOT NULL,
    FOREIGN KEY (questionInformationId) REFERENCES QuestionInformation(questionInformationId),
	CONSTRAINT uniqueTag unique index(tag)
);

CREATE TABLE UserAnswer (
	userAnswerId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    string VARCHAR(255) NOT NULL,
    questionId INTEGER NOT NULL,
    FOREIGN KEY (questionId) REFERENCES Question(questionId)
);

CREATE TABLE PossibleAnswer (
	possibleAnswerId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    string VARCHAR(255) NOT NULL,
    archived BOOLEAN DEFAULT FALSE,
    questionId INTEGER NOT NULL,
    FOREIGN KEY (questionId) REFERENCES Question(questionId)
);

CREATE TABLE Location (
	locationId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(255),
    address VARCHAR(255)
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
    userAnswerId INTEGER NOT NULL,
    FOREIGN KEY(userAnswerId) REFERENCES UserAnswer(userAnswerId),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);