CREATE SEQUENCE lisukkeet_id_seq;
CREATE TABLE lisukkeet (
	id		INT PRIMARY KEY DEFAULT nextval('lisukkeet_id_seq'),
	lisuke_nimi	VARCHAR(255) UNIQUE NOT NULL,
	lisuke_hinta	INT
);
CREATE TABLE s_ll (
	pizza_id 	INT,
	lisukkeen_id	INT
);
CREATE SEQUENCE staattiset_pizzat_id_seq;
CREATE TABLE staattiset_pizzat (
	id		INT PRIMARY KEY DEFAULT nextval('staattiset_pizzat_id_seq'),
	pizza_name	VARCHAR(255) UNIQUE NOT NULL
);

CREATE SEQUENCE user_id_seq;
CREATE TABLE users (
	id		INT PRIMARY KEY DEFAULT nextval('user_id_seq'),
	username	VARCHAR(25) UNIQUE NOT NULL,
	phash		VARCHAR(66) NOT NULL -- SALT(2) + sha256 of the (salt CONCAT password) 
);

--CREATE TABLE user_tokens (
--	hashval		VARCHAR(64) NOT NULL, -- 
--	creation_time	TIMESTAMP NOT NULL DEFAULT now()
--);

--CREATE SEQUENCE temp_pizza_id_seq;
CREATE TABLE temp_pizza (
	id		VARCHAR(50) PRIMARY KEY
);
CREATE TABLE a_ll (
        temp_id        	VARCHAR(50) NOT NULL,
        lisukkeen_id    INT
);

