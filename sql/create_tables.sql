CREATE SEQUENCE lisukkeet_id_seq;
CREATE TABLE lisukkeet (
	id		INT PRIMARY KEY DEFAULT nextval('lisukkeet_id_seq'),
	lisuke_nimi	VARCHAR(255) UNIQUE,
	lisuke_hinta	INT
);
CREATE TABLE s_ll (
	pizza_id 	INT,
	lisukkeen_id	INT
);
CREATE SEQUENCE staattiset_pizzat_id_seq;
CREATE TABLE staattiset_pizzat (
	id		INT PRIMARY KEY DEFAULT nextval('staattiset_pizzat_id_seq'),
	pizza_name	VARCHAR(255) UNIQUE
);


