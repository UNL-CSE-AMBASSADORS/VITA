-- VITA Project
-- UNL CSE Ambassadors 2017

USE vita;

DROP TABLE IF EXISTS Answer;
DROP TABLE IF EXISTS Appointment;
DROP TABLE IF EXISTS Client;
DROP TABLE IF EXISTS Location;
DROP TABLE IF EXISTS PossibleAnswer;
DROP TABLE IF EXISTS LitmusQuestion;

CREATE TABLE LitmusQuestion (
	litmusQuestionId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    string VARCHAR(255) NOT NULL,
	orderIndex INTEGER NOT NULL,
    tag VARCHAR(255) NOT NULL,
    required BOOLEAN DEFAULT TRUE,
    archived BOOLEAN DEFAULT FALSE,
	CONSTRAINT uniqueTag UNIQUE INDEX(tag)
);

CREATE TABLE PossibleAnswer (
	possibleAnswerId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    string VARCHAR(255) NOT NULL,
    orderIndex INTEGER NOT NULL,
    archived BOOLEAN DEFAULT FALSE,
    litmusQuestionId INTEGER NOT NULL,
    FOREIGN KEY (litmusQuestionId) REFERENCES LitmusQuestion(litmusQuestionId)
);

CREATE TABLE Location (
	locationId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    title VARCHAR(255),
    address VARCHAR(255)
);

CREATE TABLE Client (
	clientId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    firstName VARCHAR(255) NOT NULL,
    lastName VARCHAR(255) NOT NULL,
    emailAddress VARCHAR(255) NOT NULL,
    languages VARCHAR(255)
);

CREATE TABLE Appointment (
	appointmentId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
    scheduledTime DATETIME NOT NULL,
    clientId INTEGER NOT NULL,
    FOREIGN KEY (clientId) REFERENCES Client(clientId),
    locationId INTEGER NOT NULL,
    FOREIGN KEY (locationId) REFERENCES Location(locationId),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    archived BOOLEAN DEFAULT FALSE
);

CREATE TABLE Answer (
	answerId INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT,
	possibleAnswerId INTEGER NOT NULL,
    FOREIGN KEY (possibleAnswerId) REFERENCES PossibleAnswer(possibleAnswerId),
    appointmentId INTEGER NOT NULL,
    FOREIGN KEY (appointmentId) REFERENCES Appointment(appointmentId),
    litmusQuestionId INTEGER NOT NULL,
    FOREIGN KEY (litmusQuestionId) REFERENCES LitmusQuestion(litmusQuestionId),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);