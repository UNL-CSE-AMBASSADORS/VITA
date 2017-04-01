-- VITA Project
-- UNL CSE Ambassadors 2017

drop table if exists appointment_question_answer;
drop table if exists appointment;
drop table if exists location;
drop table if exists answer;
drop table if exists question;

create table location (
	id integer primary key not null auto_increment,
    title varchar(255),
    address varchar(255)
);

create table question (
	id integer primary key not null auto_increment,
    str varchar(255) not null,
    required boolean default true,
    archived boolean default false
);

create table answer (
	id integer primary key not null auto_increment,
    str varchar(255) not null,
    archived boolean default false,
    question_id integer not null,
    foreign key (question_id) references question(id)
);

create table appointment (
	id integer primary key not null auto_increment,
    date varchar(255) not null,
    time varchar(255) not null,
    location_id int not null,
    foreign key (location_id) references location(id),
    timestamp varchar(255) not null,
    archived boolean default false
);

create table appointment_question_answer (
	id integer primary key not null auto_increment,
    appointment_id int not null,
    foreign key (appointment_id) references appointment(id),
    question_id int not null,
    foreign key (question_id) references question(id),
    answer_id int not null,
    foreign key(answer_id) references answer(id)
);

-- -- seed data -- --

-- sample questions with choices, if applicable
insert into question (str, required) values ("Name", true);
insert into question (str, required) values ("Email Address", true);
insert into question (str, required) values ("Phone Number", false);

insert into question (str, required) values ("Are you a pharmacist?", false);
insert into answer (str, question_id) values ("Yes", (select id from question where str="Are you a pharmacist?"));
insert into answer (str, question_id) values ("No", (select id from question where str="Are you a pharmacist?"));

insert into question (str, required) values ("How often do you gamble?", false);
insert into answer (str, question_id) values ("Frequently", (select id from question where str="How often do you gamble?"));
insert into answer (str, question_id) values ("Occasionally", (select id from question where str="How often do you gamble?"));
insert into answer (str, question_id) values ("Rarely", (select id from question where str="How often do you gamble?"));
insert into answer (str, question_id) values ("Never", (select id from question where str="How often do you gamble?"));

insert into question (str, required) values ("Indicate your military status", false);
insert into answer (str, question_id) values ("Active Duty", (select id from question where str="Indicate your military status"));
insert into answer (str, question_id) values ("Active Reserve", (select id from question where str="Indicate your military status"));
insert into answer (str, question_id) values ("Disabled Veterans", (select id from question where str="Indicate your military status"));
insert into answer (str, question_id) values ("Inactive Reserve", (select id from question where str="Indicate your military status"));
insert into answer (str, question_id) values ("None", (select id from question where str="Indicate your military status"));

insert into question (str, required) values ("Can you speak fluent English?", true);
insert into answer (str, question_id) values ("Yes", (select id from question where str="Can you speak fluent English?"));
insert into answer (str, question_id) values ("No", (select id from question where str="Can you speak fluent English?"));

insert into question (str, required) values ("If no, what is your strongest language?", false);

-- sample locations
insert into location (title, address) values ("Teal", "5696 Lotheville Court");
insert into location (title, address) values ("Red", "9 Utah Court");
insert into location (title, address) values ("Turquoise", "71499 Buhler Trail");
insert into location (title, address) values ("Green", "02122 Prairieview Place");
insert into location (title, address) values ("Orange", "8 Scofield Road");
insert into location (title, address) values ("Yellow", "591 Oak Avenue");

-- sample appointment with answers
insert into appointment (date, time, location_id, timestamp) values ("05-28-2017", "16:30", 1, "05-22-2017 05:30:34");
insert into answer (str, question_id) values ("Ralph Schmidt", (select id from question where str="Name"));
insert into appointment_question_answer (appointment_id, question_id, answer_id) values
	((select id from appointment where timestamp="05-22-2017 05:30:34"),
    (select id from question where str="Name"),
    (select id from answer where str="Ralph Schmidt" and question_id=(select id from question where str="Name")));
insert into answer (str, question_id) values ("ralphman@gmail.com", (select id from question where str="Email Address"));
insert into appointment_question_answer (appointment_id, question_id, answer_id) values
	((select id from appointment where timestamp="05-22-2017 05:30:34"),
    (select id from question where str="Email Address"),
    (select id from answer where str="ralphman@gmail.com" and question_id=(select id from question where str="Email Address")));
insert into answer (str, question_id) values ("(753) 875-3165", (select id from question where str="Phone Number"));
insert into appointment_question_answer (appointment_id, question_id, answer_id) values
	((select id from appointment where timestamp="05-22-2017 05:30:34"),
    (select id from question where str="Phone Number"),
    (select id from answer where str="(753) 875-3165" and question_id=(select id from question where str="Phone Number")));
insert into appointment_question_answer (appointment_id, question_id, answer_id) values
	((select id from appointment where timestamp="05-22-2017 05:30:34"),
    (select id from question where str="Are you a pharmacist?"),
    (select id from answer where str="No" and question_id=(select id from question where str="Are you a pharmacist?")));
insert into appointment_question_answer (appointment_id, question_id, answer_id) values
	((select id from appointment where timestamp="05-22-2017 05:30:34"),
    (select id from question where str="How often do you gamble?"),
    (select id from answer where str="Occasionally" and question_id=(select id from question where str="How often do you gamble?")));
insert into appointment_question_answer (appointment_id, question_id, answer_id) values
	((select id from appointment where timestamp="05-22-2017 05:30:34"),
    (select id from question where str="Indicate your military status"),
    (select id from answer where str="None" and question_id=(select id from question where str="Indicate your military status")));
insert into appointment_question_answer (appointment_id, question_id, answer_id) values
	((select id from appointment where timestamp="05-22-2017 05:30:34"),
    (select id from question where str="Can you speak fluent English?"),
    (select id from answer where str="Yes" and question_id=(select id from question where str="Can you speak fluent English?")));

insert into appointment (date, time, location_id, timestamp) values ("06-01-2017", "12:45", 5, "05-23-2017 11:09:14");
insert into answer (str, question_id) values ("Kathy Stevens", (select id from question where str="Name"));
insert into appointment_question_answer (appointment_id, question_id, answer_id) values
 	 ((select id from appointment where timestamp="05-23-2017 11:09:14"),
     (select id from question where str="Name"),
     (select id from answer where str="Kathy Stevens" and question_id=(select id from question where str="Name")));
insert into answer (str, question_id) values ("kstev89@gmail.com", (select id from question where str="Email Address"));
insert into appointment_question_answer (appointment_id, question_id, answer_id) values
	((select id from appointment where timestamp="05-23-2017 11:09:14"),
    (select id from question where str="Email Address"),
    (select id from answer where str="kstev89@gmail.com" and question_id=(select id from question where str="Email Address")));
insert into appointment_question_answer (appointment_id, question_id, answer_id) values
	((select id from appointment where timestamp="05-23-2017 11:09:14"),
    (select id from question where str="Are you a pharmacist?"),
    (select id from answer where str="Yes" and question_id=(select id from question where str="Are you a pharmacist?")));
insert into appointment_question_answer (appointment_id, question_id, answer_id) values
	((select id from appointment where timestamp="05-23-2017 11:09:14"),
    (select id from question where str="Indicate your military status"),
    (select id from answer where str="Active Reserve" and question_id=(select id from question where str="Indicate your military status")));
insert into appointment_question_answer (appointment_id, question_id, answer_id) values
	((select id from appointment where timestamp="05-23-2017 11:09:14"),
    (select id from question where str="Can you speak fluent English?"),
    (select id from answer where str="No" and question_id=(select id from question where str="Can you speak fluent English?")));
insert into answer (str, question_id) values ("Spanish", (select id from question where str="If no, what is your strongest language?"));
insert into appointment_question_answer (appointment_id, question_id, answer_id) values
	((select id from appointment where timestamp="05-23-2017 11:09:14"),
    (select id from question where str="If no, what is your strongest language?"),
    (select id from answer where str="Spanish" and question_id=(select id from question where str="If no, what is your strongest language?")));
    
-- -- test queries -- --
select * from question;
select * from answer;
select * from location;
select * from appointment;
select * from appointment_question_answer;

-- all answers for an appointment
select q.str as question, a.str as answer from appointment_question_answer aqa join answer a on aqa.answer_id=a.id 
	join question q on aqa.question_id = q.id where appointment_id = 1;
    
select q.str as question, a.str as answer from appointment_question_answer aqa join answer a on aqa.answer_id=a.id 
	join question q on aqa.question_id = q.id where appointment_id = 2;

-- active questions
select id, str from question where archived=false;

-- required questions
select id, str from question where required=true;
