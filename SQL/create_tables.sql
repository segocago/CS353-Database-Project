CREATE TABLE user(
	state_ID char(11) PRIMARY KEY,
	first_name varchar(20),
	middle_name varchar(20),
	last_name varchar(20),
	sex varchar(20),
	phone varchar(100),
	password varchar(40) NOT NULL)ENGINE=InnoDB;

CREATE TABLE pharmacist(
	state_ID char(11) PRIMARY KEY,
	FOREIGN KEY (state_ID) references user(state_ID))ENGINE=InnoDB;

CREATE TABLE patient(
	state_ID char(11) PRIMARY KEY,
	patient_adress varchar(100),
	patient_date_of_birth date NOT NULL,
	patient_allergies varchar(100),
	patient_chronic_diseases varchar(100),
	patient_height numeric(3,2),
	patient_weight numeric(3,2),
	patient_bloodtype varchar(20),
	FOREIGN KEY (state_ID) references user(state_ID))ENGINE=InnoDB;

CREATE TABLE doctor(
	state_ID 		char(11) PRIMARY KEY,
	doctor_department	varchar(40) NOT NULL,
	doctor_title		varchar(40) NOT NULL,
	doctor_schedule	varchar(400) NOT NULL,
	FOREIGN KEY (state_ID) references user(state_ID))ENGINE=InnoDB;

CREATE TABLE examination(
	examination_ID		int PRIMARY KEY AUTO_INCREMENT,
	examination_cause		varchar(400) NOT NULL,
	examination_date		timestamp NOT NULL,
	examination_diagnose		varchar(400) NOT NULL)ENGINE=InnoDB;

CREATE TABLE rating(
	rating_ID	int PRIMARY KEY AUTO_INCREMENT,
	score		int,
	comment 	varchar(400),
	check (score between 0 and 5))ENGINE=InnoDB;

CREATE TABLE test(
	test_ID 	int PRIMARY KEY AUTO_INCREMENT,
	test_result	varchar(400),
	test_name	varchar(100))ENGINE=InnoDB; 

CREATE TABLE treatment(
	treatment_ID 			int PRIMARY KEY AUTO_INCREMENT,
	treatment_description		varchar(400)
	)ENGINE=InnoDB;

CREATE TABLE prescription(
	prescription_ID	int PRIMARY KEY AUTO_INCREMENT
	)ENGINE=InnoDB;

CREATE TABLE drug(
	drug_ID 	int PRIMARY KEY AUTO_INCREMENT,
	drug_name	varchar(200))ENGINE=InnoDB;

CREATE TABLE pharmacy(
	pharmacy_ID		int PRIMARY KEY AUTO_INCREMENT,
	pharmacy_name	varchar(100),
	pharmacy_address	varchar(100),
	pharmacy_phone	varchar(100))ENGINE=InnoDB;

CREATE TABLE hospital(
	hospital_ID 				int PRIMARY KEY AUTO_INCREMENT,
	hospital_name				varchar(200),
	hospital_capacity			int,
	hospital_telephone			varchar(100),
	hospital_address			varchar(200))ENGINE=InnoDB;

CREATE TABLE vaccine(
	vaccine_ID 		int PRIMARY KEY AUTO_INCREMENT,
	vaccine_name		varchar(100))ENGINE=InnoDB;

CREATE TABLE emergency_contact(
	state_ID 				char(11),
	emergency_contact_name		varchar(100),
	emergency_contact_telephone	varchar(100),
	emergency_contact_relationship	varchar(100),
	PRIMARY KEY (state_ID, emergency_contact_name, emergency_contact_telephone, emergency_contact_relationship),
	FOREIGN KEY (state_ID) references patient(state_ID))ENGINE=InnoDB;
 
CREATE TABLE hospital_department(
	hospital_ID		int,
	hospital_department	varchar(40),
	PRIMARY KEY (hospital_ID, hospital_department),
	FOREIGN KEY (hospital_ID) references hospital(hospital_ID))ENGINE=InnoDB;

CREATE TABLE patient_allergies(
	state_ID 	char(11),
	allergy_name	varchar(100),
	PRIMARY KEY (state_ID,allergy_name),
	FOREIGN KEY (state_ID) references patient(state_ID))ENGINE=InnoDB;

CREATE TABLE patient_chronic_diseases(
	state_ID 		char(11),
	chronic_disease	varchar(100),
	PRIMARY KEY (state_ID,chronic_disease),
	FOREIGN KEY (state_ID) references patient(state_ID))ENGINE=InnoDB;

CREATE TABLE examination_done(
	patient_state_ID	char(11),
	doctor_state_ID	char(11),
	examination_ID	char(11),
	PRIMARY KEY (examination_ID),
	FOREIGN KEY (patient_state_ID) references patient(state_ID),
	FOREIGN KEY (doctor_state_ID) references doctor(state_ID))ENGINE=InnoDB;

CREATE TABLE books(
	state_ID		char(11),
	examination_ID	int,
	doctor_ID		char(11),
	PRIMARY KEY (examination_ID),
	FOREIGN KEY (state_ID) references patient(state_ID),
	FOREIGN KEY (examination_ID) references examination(examination_ID),
    FOREIGN KEY (doctor_ID) references doctor(state_ID))ENGINE=InnoDB;

CREATE TABLE vaccinates(
	vaccine_ID		int,
	patient_state_ID	char(11),
	doctor_state_ID	char(11),
	date			date,
	PRIMARY KEY (vaccine_ID, patient_state_ID, doctor_state_ID),
	FOREIGN KEY (vaccine_ID) references vaccine(vaccine_ID),
	FOREIGN KEY (patient_state_ID) references patient(state_ID),
	FOREIGN KEY (doctor_state_ID) references doctor(state_ID))ENGINE=InnoDB;

CREATE TABLE works_as_pharmacist(
	state_ID		char(11),
	pharmacy_ID		int,
	PRIMARY KEY (state_ID),
	FOREIGN KEY (state_ID) references pharmacist(state_ID),
	FOREIGN KEY (pharmacy_ID) references pharmacy(pharmacy_ID))ENGINE=InnoDB;
    
 CREATE TABLE rate_for(
	rating_ID		int,
	patient_state_ID	char(11),
	doctor_state_ID	char(11),
	examination_ID	int,
	PRIMARY KEY (rating_ID),
	FOREIGN KEY (patient_state_ID) references patient(state_ID),
	FOREIGN KEY (doctor_state_ID) references doctor(state_ID),
	FOREIGN KEY (examination_ID) references examination(examination_ID))ENGINE=InnoDB;
   
CREATE TABLE stores(
	pharmacy_ID		int,
	drug_ID		int,
	number_in_stock	int,
	PRIMARY KEY (pharmacy_ID, drug_ID),
	FOREIGN KEY (pharmacy_ID) references pharmacy(pharmacy_ID),
	FOREIGN KEY (drug_ID) references drug(drug_ID))ENGINE=InnoDB;

CREATE TABLE works_as_doctor(
	state_ID	char(11),
	hospital_ID	int,
    role 	varchar(20),
	PRIMARY KEY (state_ID),
	FOREIGN KEY (state_ID) references doctor(state_ID),
	FOREIGN KEY (hospital_ID) references hospital(hospital_ID))ENGINE=InnoDB;

CREATE TABLE test_executed_in(
	test_ID		int,
	hospital_ID	int,
	PRIMARY KEY (test_ID, hospital_ID),
	FOREIGN KEY (test_ID) references test(test_ID),
	FOREIGN KEY (hospital_ID) references hospital(hospital_ID))ENGINE=InnoDB;

CREATE TABLE examination_result(
	examination_ID	int,
	test_ID			int,
	treatment_ID	int,
	prescription_ID	int,
	PRIMARY KEY (examination_ID),
	FOREIGN KEY (examination_ID) references examination(examination_ID),
	FOREIGN KEY (test_ID) references test(test_ID),
	FOREIGN KEY (treatment_ID) references treatment(treatment_ID),
	FOREIGN KEY (prescription_ID) references prescription(prescription_ID))ENGINE=InnoDB;

CREATE TABLE prescribed(
	prescription_ID	int,
	drug_ID		int,
	PRIMARY KEY (prescription_ID, drug_ID),
	FOREIGN KEY (prescription_ID) references prescription(prescription_ID),
	FOREIGN KEY (drug_ID) references drug(drug_ID))ENGINE=InnoDB;

CREATE TABLE alternative_to(
	drug_ID		int,
	alternative_drug_ID	int,
	PRIMARY KEY (drug_ID, alternative_drug_ID),
	FOREIGN KEY (drug_ID) references drug(drug_ID),
	FOREIGN KEY (alternative_drug_ID) references drug(drug_ID))ENGINE=InnoDB;
    
CREATE TABLE buy_drug (
	state_ID   char(11),
	drug_ID	   int,
    pharmacy_ID int,
    PRIMARY KEY (state_ID,drug_ID,pharmacy_ID),
    FOREIGN KEY (state_ID) references patient(state_ID) ,
    FOREIGN KEY (drug_ID) references drug(drug_ID),
    FOREIGN KEY (pharmacy_ID) references pharmacy(pharmacy_ID)
    )ENGINE=InnoDB;
    
CREATE VIEW patient_age as
SELECT state_ID, TIMESTAMPDIFF (YEAR, patient_date_of_birth,CURDATE()) AS age
FROM patient;
