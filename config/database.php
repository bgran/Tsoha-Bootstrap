<?php

  class DatabaseConfig{

    // Valitse käyttämäsi tietokantapalvelin - PostgreSQL (psql) tai MySQL (mysql)
    private static $use_database = 'psql';

    // Muuta users-ympäristöä asettamalle oikeat arvot KAYTTAJATUNNUS-kohtaan (käyttäjätunnuksesi)
    // ja SALASANA-kohtaan (tietokantasi pääkäyttäjän salasana)
    private static $connection_config = array(
      'psql' => array(
	// tosta pois :
        'resource' => 'pgsql:',
	'hostname' => 'localhost',
	'username' => '',
	'password' => '',
	'dbname' => 'testi',
	'port' => 5432
      ),
      'mysql' => array(
        'resource' => 'mysql:unix_socket=/home/KAYTTAJATUNNUS/mysql/socket;dbname=mysql',
        'username' => 'root',
        'password' => 'SALASANA'
      )
    );

    public static function connection_config(){
      $config = array(
        'db' => self::$use_database,
        'config' => self::$connection_config[self::$use_database]
      );

      return $config;
    }

  }
