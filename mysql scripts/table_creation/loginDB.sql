use `vita`;

DROP TABLE IF EXISTS Login;
DROP TABLE IF EXISTS PasswordReset;
DROP TABLE IF EXISTS LoginHistory;
DROP TABLE IF EXISTS User;

CREATE TABLE `User` (
	`userId` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`email` VARCHAR(355) NOT NULL,
	`firstName` VARCHAR(65) NULL,
	`lastName` VARCHAR(65) NULL,
	`archived` TINYINT NOT NULL DEFAULT 0,
	PRIMARY KEY (`userId`),
	UNIQUE INDEX `userId_UNIQUE` (`userId` ASC)
	);
	
CREATE TABLE `Login` (
	`userId` INT UNSIGNED NOT NULL,
	`failedLoginCount` INT UNSIGNED ZEROFILL NOT NULL DEFAULT 0,
	`password` VARCHAR(255) NOT NULL,
	`lockoutTime` TIMESTAMP NOT NULL DEFAULT 0,
	foreign key (userId) references User(userId)
	);

CREATE TABLE `LoginHistory` (
	`userId` INT UNSIGNED NOT NULL,
	`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`ipAddress` VARCHAR(95) NOT NULL,
	foreign key (userId) references User(userId)
	);
	
CREATE TABLE `PasswordReset` (
	`userId` INT UNSIGNED NOT NULL,
	`token` VARCHAR(255) NOT NULL,
	`ipAddress` VARCHAR(95) NOT NULL,
	`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`archived` TINYINT NOT NULL DEFAULT 0,
	foreign key (userId) references User(userId)
	);

INSERT INTO `User`
		(
			`email`,
			`firstName`,
			`lastName`
		)
	VALUES
		(
			'test@gmail.com',
			'John',
			'Smith'
		);

