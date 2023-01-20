USE vita;

DROP TABLE IF EXISTS Answer;
-- TODO: FilingStatus tables can be removed once this script has been run by all dev members
DROP TABLE IF EXISTS AppointmentFilingStatus;
DROP TABLE IF EXISTS FilingStatus;
DROP TABLE IF EXISTS ServicedAppointment;
DROP TABLE IF EXISTS SelfServiceAppointmentRescheduleToken;
DROP TABLE IF EXISTS Note;
DROP TABLE IF EXISTS VirtualAppointmentConsent;
DROP TABLE IF EXISTS Appointment;
DROP TABLE IF EXISTS AppointmentTime;
DROP TABLE IF EXISTS AppointmentType;
DROP TABLE IF EXISTS Client;
-- TODO: RoleLimit, UserShift, Shift, and Role can be removed once this script has been run by each dev member
DROP TABLE IF EXISTS RoleLimit;
DROP TABLE IF EXISTS UserShift;
DROP TABLE IF EXISTS Shift;
DROP TABLE IF EXISTS Role;
DROP TABLE IF EXISTS Site;
DROP TABLE IF EXISTS PossibleAnswer;
DROP TABLE IF EXISTS Question;

DROP TABLE IF EXISTS UserPermission;
DROP TABLE IF EXISTS Permission;
-- TODO: Ability tables can be removed after this script has been run by each dev member
DROP TABLE IF EXISTS UserAbility;
DROP TABLE IF EXISTS Ability;
DROP TABLE IF EXISTS Login;
DROP TABLE IF EXISTS PasswordReset;
DROP TABLE IF EXISTS LoginHistory;
DROP TABLE IF EXISTS User;

drop table if exists ProgressionTimestamp;
drop table if exists ProgressionSubstep;
drop table if exists ProgressionStep;
drop table if exists ProgressionType;


CREATE TABLE User (
	userId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	firstName VARCHAR(65) NULL,
	lastName VARCHAR(65) NULL,
	email VARCHAR(355) NOT NULL,
	phoneNumber VARCHAR(20) NULL,
	archived BOOLEAN NOT NULL DEFAULT FALSE,
	CONSTRAINT uniqueEmail UNIQUE INDEX(email(255))
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
	createdAt DATETIME NOT NULL DEFAULT NOW(),
	lastModifiedDate DATETIME,
	archived BOOLEAN NOT NULL DEFAULT FALSE,
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
	emailAddress VARCHAR(255) NULL,
	bestTimeToCall VARCHAR(255) NULL
);

CREATE TABLE AppointmentType (
	appointmentTypeId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	name VARCHAR(255) NOT NULL,
	lookupName VARCHAR(255) NOT NULL,
	archived BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE AppointmentTime (
	appointmentTimeId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	scheduledTime DATETIME NOT NULL,
	numberOfAppointments INTEGER UNSIGNED DEFAULT 0,
	appointmentTypeId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(appointmentTypeId) REFERENCES AppointmentType(appointmentTypeId),
	siteId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(siteId) REFERENCES Site(siteId)
);

CREATE TABLE Appointment (
	appointmentId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	createdAt DATETIME NOT NULL DEFAULT NOW(),
	language VARCHAR(5) NOT NULL,
	ipAddress VARCHAR(95) NOT NULL,
	archived BOOLEAN NOT NULL DEFAULT FALSE,
	clientId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(clientId) REFERENCES Client(clientId),
	appointmentTimeId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY(appointmentTimeId) REFERENCES AppointmentTime(appointmentTimeId)
);

CREATE TABLE SelfServiceAppointmentRescheduleToken (
	token VARCHAR(255) NOT NULL,
	createdAt DATETIME NOT NULL DEFAULT NOW(),
	appointmentId INTEGER UNSIGNED NOT NULL,
	FOREIGN KEY (appointmentId) REFERENCES Appointment(appointmentId)
);

CREATE TABLE Note (
	noteId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	createdAt DATETIME NOT NULL DEFAULT NOW(),
	note VARCHAR(1000) NOT NULL,
    appointmentId INTEGER UNSIGNED NOT NULL,
    FOREIGN KEY(appointmentId) REFERENCES Appointment(appointmentId),
	createdBy INTEGER UNSIGNED NULL,
	FOREIGN KEY(createdBy) REFERENCES User(userId)
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
    cancelled BOOLEAN NOT NULL DEFAULT FALSE,
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

CREATE TABLE VirtualAppointmentConsent (
	virtualAppointmentConsentId INTEGER UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
	createdAt DATETIME NOT NULL DEFAULT NOW(),
	reviewConsent BOOLEAN NOT NULL,
	virtualConsent BOOLEAN NOT NULL, /* virtual is a keyword */
    signature VARCHAR(255) NOT NULL,
    appointmentId INTEGER UNSIGNED NOT NULL,
    CONSTRAINT UNIQUE unique_appointment_id (appointmentId),
    FOREIGN KEY(appointmentId) REFERENCES Appointment(appointmentId)
);

create table progressionType (
	progressionTypeId int(10) unsigned, #want this to be UN AI PK like other tables
    progressionTypeName varchar(255) not null,
	PRIMARY KEY (progressionTypeId)
);

create table progressionStep (
	progressionStepId int(10) unsigned,
    progressionTypeId int(10) unsigned not null,
    progressionStepOrdinal int(10) not null, # want the steps' original values to follow a logical order/sequence
    progressionStepName varchar(255) not null,
	PRIMARY KEY (progressionStepId),
	FOREIGN KEY (progressionTypeId) REFERENCES progressionType(progressionTypeId),
	unique key (progressionTypeId, progressionStepOrdinal)
);

create table progressionSubStep (
	progressionSubStepId int(10) unsigned auto_increment,
    progressionStepId int(10) unsigned not null,
    # progressionStepOrdinal int(10) not null, # substeps don't have a logical sequence--if they do, they should be abstracted to their own step
    progressionSubStepName varchar(255) null,
	PRIMARY KEY (progressionSubStepId),
	FOREIGN KEY (progressionStepId) REFERENCES progressionStep(progressionStepId)
);

create table progressionTimestamp (
	progressionTimeStampId int(10) unsigned auto_increment,
    appointmentId int(10) unsigned not null,
    progressionStepId int(10) unsigned not null,
	progressionSubStepId int(10),
    timestamp datetime,
	PRIMARY KEY (progressionTimeStampId),
	FOREIGN KEY (appointmentId) REFERENCES appointment(appointmentId),
	FOREIGN KEY (progressionStepId) REFERENCES progressionStep(progressionStepId),
    UNIQUE KEY (appointmentId, progressionStepId)#,
);