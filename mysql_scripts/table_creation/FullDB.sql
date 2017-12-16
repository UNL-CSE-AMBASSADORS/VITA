USE vita;

DROP TABLE IF EXISTS Answer;
DROP TABLE IF EXISTS ServicedAppointment;
DROP TABLE IF EXISTS Appointment;
DROP TABLE IF EXISTS Client;
DROP TABLE IF EXISTS UserShift;
DROP TABLE IF EXISTS Shift;
DROP TABLE IF EXISTS Site;
DROP TABLE IF EXISTS PossibleAnswer;
DROP TABLE IF EXISTS Question;

DROP TABLE IF EXISTS UserPermission;
DROP TABLE IF EXISTS Permission;
DROP TABLE IF EXISTS UserAbility;
DROP TABLE IF EXISTS Ability;
DROP TABLE IF EXISTS Login;
DROP TABLE IF EXISTS PasswordReset;
DROP TABLE IF EXISTS LoginHistory;
DROP TABLE IF EXISTS User;

CREATE TABLE User (
	userId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	firstName VARCHAR(65) NULL,
	lastName VARCHAR(65) NULL,
	email VARCHAR(355) NOT NULL,
	phoneNumber VARCHAR(20) NULL,
	preparesTaxes BOOLEAN NOT NULL DEFAULT FALSE,
	archived BOOLEAN NOT NULL DEFAULT FALSE,
	CONSTRAINT uniqueEmail UNIQUE INDEX(email)
);

CREATE TABLE Question (
	questionId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	text VARCHAR(255) NOT NULL,
	lookupName VARCHAR(255) NOT NULL,
	archived BOOLEAN NOT NULL DEFAULT FALSE,
	CONSTRAINT uniqueLookupName UNIQUE INDEX(lookupName)
);

CREATE TABLE PossibleAnswer (
	possibleAnswerId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	text VARCHAR(255) NOT NULL,
	archived BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE Site (
	siteId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	title VARCHAR(255) NOT NULL,
	address VARCHAR(255) NOT NULL,
	phoneNumber VARCHAR(20) NOT NULL,
	appointmentOnly BOOLEAN NOT NULL DEFAULT FALSE,
	createdAt DATETIME NOT NULL DEFAULT NOW(),
	lastModifiedDate DATETIME,
	createdBy INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(createdBy) REFERENCES User(userId),
	lastModifiedBy INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(lastModifiedBy) REFERENCES User(userId)
);

CREATE TABLE Client (
	clientId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	firstName VARCHAR(255) NOT NULL,
	lastName VARCHAR(255) NOT NULL,
	phoneNumber VARCHAR(255) NULL,
	emailAddress VARCHAR(255) NULL
);

CREATE TABLE Appointment (
	appointmentId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    scheduledTime DATETIME NOT NULL,
	createdAt DATETIME NOT NULL DEFAULT NOW(),
    language VARCHAR(255) NOT NULL,
	ipAddress VARCHAR(95) NOT NULL,
	archived BOOLEAN NOT NULL DEFAULT FALSE,
	clientId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(clientId) REFERENCES Client(clientId),
	siteId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(siteId) REFERENCES Site(siteId)
);

CREATE TABLE Answer (
	answerId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	possibleAnswerId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(possibleAnswerId) REFERENCES PossibleAnswer(possibleAnswerId),
	appointmentId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(appointmentId) REFERENCES Appointment(appointmentId),
	questionId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(questionId) REFERENCES Question(questionId)
);

CREATE TABLE ServicedAppointment (
	servicedAppointmentId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	timeIn DATETIME NULL DEFAULT NULL,
    timeReturnedPapers DATETIME NULL DEFAULT NULL,
    timeAppointmentStarted DATETIME NULL DEFAULT NULL,
    timeAppointmentEnded DATETIME NULL DEFAULT NULL,
    completed BOOLEAN NULL DEFAULT NULL,
    notCompletedDescription VARCHAR(255) NULL DEFAULT NULL,
	userId INTEGER UNSIGNED NULL,
	FOREIGN KEY(userId) REFERENCES User(userId),
	appointmentId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(appointmentId) REFERENCES Appointment(appointmentId)
);

CREATE TABLE Login (
	failedLoginCount INT UNSIGNED ZEROFILL NOT NULL DEFAULT 0,
	password VARCHAR(255) NOT NULL,
	lockoutTime TIMESTAMP NOT NULL,
	userId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(userId) REFERENCES User(userId)
);

CREATE TABLE LoginHistory (
	loginHistoryId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	ipAddress VARCHAR(95) NOT NULL,
	userId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(userId) REFERENCES User(userId)
);

CREATE TABLE PasswordReset (
	token VARCHAR(255) NOT NULL,
	ipAddress VARCHAR(95) NOT NULL,
	timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	archived BOOLEAN NOT NULL DEFAULT FALSE,
	userId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY (userId) REFERENCES User(userId)
);

CREATE TABLE Permission (
	permissionId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	description VARCHAR(500) NOT NULL,
	lookupName VARCHAR(255) NOT NULL,
	CONSTRAINT uniqueLookupName UNIQUE INDEX(lookupName)
);

CREATE TABLE UserPermission (
	userPermissionId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	createdAt DATETIME NOT NULL DEFAULT NOW(),
	createdBy INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(createdBy) REFERENCES User(userId),
	userId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(userId) REFERENCES User(userId),
	permissionId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(permissionId) REFERENCES Permission(permissionId),
	CONSTRAINT UNIQUE unique_permission (userId, permissionId)
);

CREATE TABLE Ability (
	abilityId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	lookupName VARCHAR(255) NOT NULL,
	verificationRequired BOOLEAN NOT NULL,
	description VARCHAR(500) NOT NULL,
	CONSTRAINT uniqueLookupName UNIQUE INDEX(lookupName)
);

CREATE TABLE UserAbility (
	userAbilityId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	createdAt DATETIME NOT NULL DEFAULT NOW(),
	createdBy INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(createdBy) REFERENCES User(userId),
	userId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(userId) REFERENCES User(userId),
	abilityId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(abilityId) REFERENCES Ability(abilityId)
);

CREATE TABLE Shift (
	shiftId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	startTime DATETIME NOT NULL,
	endTime DATETIME NOT NULL,
	archived BOOLEAN NOT NULL DEFAULT FALSE,
	createdAt DATETIME NOT NULL DEFAULT NOW(),
	lastModifiedDate DATETIME NULL,
	siteId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(siteId) REFERENCES Site(siteId),
	createdBy INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(createdBy) REFERENCES User(userId),
	lastModifiedBy INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(lastModifiedBy) REFERENCES User(userId)
);

CREATE TABLE UserShift (
	userShiftId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	createdAt DATETIME NOT NULL DEFAULT NOW(),
	userId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(userId) REFERENCES User(userId),
	shiftId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(shiftId) REFERENCES Shift(shiftId)
);
