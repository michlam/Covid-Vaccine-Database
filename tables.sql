drop table if exists Update_Accounts cascade;
drop table if exists Account_PHN cascade;
drop table if exists Appt_info cascade;
drop table if exists Location_info cascade;
drop table if exists Accounts_HasClients cascade;
drop table if exists Shift_id_and_Location cascade;
drop table if exists Vaccine_Sets cascade;
drop table if exists Appt_info_with_shift cascade;
drop table if exists Clients_LiveAt cascade;
drop table if exists Officials_ReportingTo_Sources cascade;
drop table if exists Workers_ReportingTo_Officials cascade;
drop table if exists Officials cascade;
drop table if exists News_Sources cascade;
drop table if exists Assign cascade;
drop table if exists Workers cascade;
drop table if exists Shifts_Has_Vaccine_At_Location cascade;
drop table if exists VaccineLocations_AtAddress cascade;
drop table if exists Addresses cascade;
drop table if exists Neighbourhood cascade;
CREATE TABLE Officials (
  eid VARCHAR(30),
  official_name VARCHAR(30),
  PRIMARY KEY (eid)
);
CREATE TABLE Neighbourhood (
  neighbourhood VARCHAR(30),
  city VARCHAR(30) NOT NULL,
  PRIMARY KEY (neighbourhood)
);
CREATE TABLE Workers (
  eid VARCHAR(30),
  worker_name VARCHAR(30),
  PRIMARY KEY (eid)
);
CREATE TABLE News_Sources (
  sname VARCHAR(30),
  city VARCHAR(30),
  PRIMARY KEY(sname)
);
CREATE TABLE Addresses (
  postal_code VARCHAR(30),
  street_num int(10),
  street_name VARCHAR(30) NOT NULL,
  neighbourhood VARCHAR(30),
  PRIMARY KEY (postal_code, street_num),
  FOREIGN KEY (neighbourhood) REFERENCES Neighbourhood(neighbourhood) ON DELETE NO ACTION ON UPDATE NO ACTION
);
CREATE TABLE Clients_LiveAt (
  phn int(10),
  priority_group VARCHAR(30),
  age int(10) NOT NULL,
  postal_code VARCHAR(30) NOT NULL,
  street_num int(10) NOT NULL,
  PRIMARY KEY (phn),
  FOREIGN KEY (postal_code, street_num) REFERENCES Addresses(postal_code, street_num) ON DELETE NO ACTION ON UPDATE CASCADE
);
CREATE TABLE Accounts_HasClients (
  account_id int(10),
  vaccination_status VARCHAR(30) NOT NULL,
  vaccine_type_received VARCHAR(30) NOT NULL,
  phn int(10) UNIQUE NOT NULL,
  PRIMARY KEY (account_id),
  FOREIGN KEY (phn) REFERENCES Clients_LiveAt(phn) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE VaccineLocations_AtAddress (
  building_name VARCHAR(30),
  room_num int(10),
  street_num int(10),
  postal_code VARCHAR(30),
  PRIMARY KEY (building_name, room_num, street_num, postal_code),
  FOREIGN KEY (postal_code, street_num) REFERENCES Addresses(postal_code, street_num) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE Account_PHN (
  account_id int(10),
  phn int(10) NOT NULL,
  PRIMARY KEY (account_id),
  UNIQUE (phn),
  FOREIGN KEY (account_id) REFERENCES Accounts_HasClients(account_id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (phn) REFERENCES Clients_LiveAt(phn) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE Location_info (
  postal_code VARCHAR(30),
  street_num int(10),
  building_name VARCHAR(30) NOT NULL,
  PRIMARY KEY (postal_code, street_num),
  FOREIGN KEY (postal_code, street_num, building_name) REFERENCES VaccineLocations_AtAddress(postal_code, street_num, building_name) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE Officials_ReportingTo_Sources (
  sname VARCHAR(30),
  vaccinated_total int(10) NOT NULL,
  eid VARCHAR(30),
  PRIMARY KEY (sname, eid),
  FOREIGN KEY (sname) REFERENCES News_Sources(sname) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (eid) REFERENCES Officials(eid) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE Workers_ReportingTo_Officials (
  eid1 VARCHAR(30),
  eid2 VARCHAR(30),
  vaccinated_name VARCHAR(30),
  PRIMARY KEY (eid1, eid2),
  FOREIGN KEY (eid1) REFERENCES Workers(eid) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (eid2) REFERENCES Officials(eid) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE Shifts_Has_Vaccine_At_Location (
  shift_id int(10),
  appt_time TIME NOT NULL,
  appt_date DATE NOT NULL,
  building_name VARCHAR(30) NOT NULL,
  room_num int(10) NOT NULL,
  PRIMARY KEY (shift_id),
  FOREIGN KEY (building_name, room_num) REFERENCES VaccineLocations_AtAddress(building_name, room_num) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE Appt_info_with_shift (
  appt_id int(10),
  appt_time TIME NOT NULL,
  phn int(10) NOT NULL,
  shift_id int(10),
  PRIMARY KEY (appt_id),
  FOREIGN KEY (phn) REFERENCES Clients_LiveAt(phn) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (shift_id) REFERENCES Shifts_Has_Vaccine_At_Location(shift_id) ON UPDATE CASCADE
);
CREATE TABLE Appt_info (
  appt_date DATE NOT NULL,
  appt_time TIME NOT NULL,
  dose_num int(10) NOT NULL,
  account_id int(10),
  postal_code VARCHAR(30),
  street_num int(10),
  appt_id int(10),
  UNIQUE (appt_id),
  PRIMARY KEY (appt_date, appt_time, account_id),
  FOREIGN KEY (account_id) REFERENCES Accounts_HasClients(account_id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (postal_code, street_num) REFERENCES Addresses(postal_code, street_num) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (appt_id) REFERENCES Appt_info_with_shift(appt_id) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE Vaccine_Sets (
  vaccine_name VARCHAR(30),
  shift_id int(10),
  quantity int(10) NOT NULL,
  PRIMARY KEY (vaccine_name, shift_id),
  FOREIGN KEY (shift_id) REFERENCES Shifts_Has_Vaccine_At_Location(shift_id) ON DELETE NO ACTION ON UPDATE CASCADE
);
CREATE TABLE Assign (
  eid VARCHAR(30),
  shift_id int(10),
  PRIMARY KEY (eid, shift_id),
  FOREIGN KEY (eid) REFERENCES Workers(eid) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (shift_id) REFERENCES Shifts_Has_Vaccine_At_Location(shift_id) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE Shift_id_and_Location (
  shift_id int(10),
  appt_date DATE NOT NULL,
  building_name VARCHAR(30) NOT NULL,
  room_num int(10) NOT NULL,
  PRIMARY KEY(shift_id),
  FOREIGN KEY (shift_id) REFERENCES Shifts_Has_Vaccine_At_Location(shift_id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (building_name, room_num) REFERENCES VaccineLocations_AtAddress(building_name, room_num) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE Update_Accounts (
  account_id int(10),
  appt_id int(10),
  eid VARCHAR(30) NOT NULL DEFAULT 'N0000',
  PRIMARY KEY (account_id, appt_id, eid),
  FOREIGN KEY (account_id) REFERENCES Accounts_HasClients(account_id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (appt_id) REFERENCES Appt_info_with_shift(appt_id) ON DELETE NO ACTION ON UPDATE CASCADE,
  FOREIGN KEY (eid) REFERENCES Workers(eid) ON UPDATE CASCADE
);
INSERT INTO
  Neighbourhood
VALUES
  ('Kerrisdale', 'Vancouver'),
  ('Downtown', 'Vancouver'),
  ('Steveston', 'Richmond'),
  ('Burnaby Heights', 'Burnaby'),
  ('Mount Pleasant', 'Vancouver');
INSERT INTO
  Addresses
VALUES
  ('V6M 5V4', 1111, 'Macdonald St', 'Kerrisdale'),
  ('A0B 1C2', 0001, 'Macdonald St', 'Downtown'),
  ('B1C 2D3', 1234, 'Cambie St', 'Downtown'),
  ('V6T 7R2', 1234, 'Pender St', 'Burnaby Heights'),
  ('V6T 7R5', 1235, 'Vine St', 'Steveston');
INSERT INTO
  Workers
VALUES
  ('E1122', 'Person1'),
  ('E2233', 'Lily'),
  ('E3344', 'Michelle'),
  ('E5566', 'Henry'),
  ('E6677', 'Samuel');
INSERT INTO
  News_Sources
VALUES
  ('Vancouver Sun', 'Vancouver'),
  ('Richmond News', 'Richmond'),
  ('The Ubyssey', null),
  ('Surrey News', 'Surrey'),
  ('Burnaby News', 'Burnaby');
INSERT INTO
  Clients_LiveAt
VALUES
  (4541, 'Frontline', 34, 'V6M 5V4', 1111),
  (6512, 'Health Condition', 56, 'A0B 1C2', 0001),
  (8496, null, 20, 'V6T 7R2', 1234),
  (6548, 'Health Condition', 78, 'B1C 2D3', 1234),
  (4658, null, 20, 'V6T 7R2', 1234),
  (1314, null, 23, 'A0B 1C2', 0001);
INSERT INTO
  Accounts_HasClients
VALUES
  (123, 'none', '', 4541),
  (321, 'half', 'Pfizer', 6512),
  (126, 'full', 'Pfizer', 8496),
  (432, 'full', 'J&J', 6548),
  (562, 'half', 'Moderna', 4658);
INSERT INTO
  Account_PHN
VALUES
  (562, 4658),
  (321, 6512),
  (126, 8496),
  (432, 6548),
  (123, 4541);
INSERT INTO
  VaccineLocations_AtAddress
VALUES
  ('Canada Place', 0000, 1235, 'V6T 7R5'),
  ('BC Health', 001, 0001, 'A0B 1C2'),
  ('BC Health', 001, 1234, 'B1C 2D3'),
  ('Burnaby Health', 0000, 1234, 'V6T 7R2'),
  ('Richmond Health', 0000, 1111, 'V6M 5V4');
INSERT INTO
  Location_info
VALUES
  ('A0B 1C2', 0001, 'BC Health'),
  ('B1C 2D3', 1234, 'BC Health'),
  ('V6T 7R5', 1235, 'Canada Place'),
  ('V6M 5V4', 1111, 'Richmond Health'),
  ('V6T 7R2', 1234, 'Burnaby Health');
INSERT INTO
  Shifts_Has_Vaccine_At_Location
VALUES
  (2, '07:00', '2021-01-31', 'Canada Place', 0000),
  (3, '11:00', '2021-06-11', 'BC Health', 0001),
  (
    4,
    '09:00',
    '2021-06-04',
    'Richmond Health',
    0000
  ),
  (
    6,
    '08:00',
    '2021-03-13',
    'Richmond Health',
    0000
  ),
  (
    8,
    '19:00',
    '2021-01-31',
    'Richmond Health',
    0000
  );
INSERT INTO
  Vaccine_Sets
VALUES
  ('Moderna', 2, 2000),
  ('Pfizer', 3, 3500),
  ('AstraZeneca', 4, 1000),
  ('J&J', 6, 2800),
  ('Moderna', 8, 3941);
INSERT INTO
  Assign
VALUES
  ('E1122', 2),
  ('E1122', 3),
  ('E1122', 4),
  ('E1122', 6),
  ('E2233', 3),
  ('E3344', 4),
  ('E5566', 6),
  ('E1122', 8);
INSERT INTO
  Appt_info_with_shift
VALUES
  (1, '08:00', 6512, 2),
  (2, '12:30', 8496, 3),
  (3, '10:00', 6548, 4),
  (4, '09:15', 4658, 6),
  (5, '21:00', 4541, 8);
INSERT INTO
  Appt_info
VALUES
  (
    '2021-01-31',
    '08:00',
    1,
    123,
    'V6M 5V4',
    1111,
    1
  ),
  (
    '2021-06-11',
    '12:30',
    2,
    321,
    'V6T 7R2',
    1234,
    2
  ),
  (
    '2021-06-04',
    '10:00',
    1,
    126,
    'V6T 7R5',
    1235,
    3
  ),
  (
    '2021-03-13',
    '09:15',
    2,
    432,
    'V6T 7R5',
    1235,
    4
  ),
  (
    '2021-01-31',
    '21:00',
    1,
    562,
    'V6T 7R5',
    1235,
    5
  );
INSERT INTO
  Update_Accounts
VALUES
  (123, 1, 'E1122'),
  (321, 2, 'E2233'),
  (126, 3, 'E3344'),
  (432, 4, 'E5566'),
  (562, 5, 'E1122');
INSERT INTO
  Officials
VALUES
  ('E1234', 'Sally'),
  ('E2345', 'Johnson'),
  ('E3456', 'Frank'),
  ('E4567', 'Lisa'),
  ('E5678', 'Margaret');
INSERT INTO
  Workers_ReportingTo_Officials
VALUES
  ('E1122', 'E1234', 'Pfizer'),
  ('E2233', 'E2345', 'Moderna'),
  ('E3344', 'E3456', 'AZ'),
  ('E5566', 'E4567', 'J&J'),
  ('E1122', 'E5678', 'Pfizer');
INSERT INTO
  Officials_ReportingTo_Sources
VALUES
  ('Vancouver Sun', 3000, 'E1234'),
  ('Richmond News', 1000, 'E1234'),
  ('Vancouver Sun', 2550, 'E2345'),
  ('Burnaby News', 5000, 'E3456'),
  ('Surrey News', 3000, 'E3456');
INSERT INTO
  Shift_id_and_Location
VALUES
  (2, '2021-01-31', 'Canada Place', 0000),
  (3, '2021-06-11', 'BC Health', 0001),
  (4, '2021-06-04', 'Richmond Health', 0000),
  (6, '2021-03-13', 'Richmond Health', 0000),
  (8, '2021-01-31', 'Richmond Health', 0000);