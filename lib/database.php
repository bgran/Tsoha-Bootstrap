<?php

  class DB{

      public static function connection() {
        // Haetaan tietokantakonfiguraatio
        $connection_config = DatabaseConfig::connection_config();
        $config = $connection_config['config'];
	//$config['resource'] = "pgsql:";
	//$config['database'] = "testi";
	$config['username'] = "USER";
	$config['password'] = "PASSWRD";
	//foreach ($config as $key => $value) {
	//	print "$key: $value";
	///	print "<br>";
	//}
	//$dsn = "pgsql:host=localhost;dbname=testi";
	$dsn = "pgsql:host=localhost;dbname=testi;user=;password=";

        try {
            // Alustetaan PDO
            if(isset($config['username'])){
              //$connection = new PDO($config['resource'], $config['username'], $config['password']);
		$connection = new PDO($dsn, 'USER', 'PASSWORD');
            }else{
              $connection = new PDO($config['resource']);
            }
             // Asetetaan tietokannan kenttien koodaukseksi utf8
            //$connection->exec('SET NAMES UTF8');

            // Näytetään virheilmoitukset
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        } catch (PDOException $e) {
            die('Virhe tietokantayhteydessä tai tietokantakyselyssä: ' . $e->getMessage());
        }

        return $connection;
      }

      public static function test_connection(){
        require 'vendor/ConnectionTest/connection_test.php';

        exit();
      }

  }
