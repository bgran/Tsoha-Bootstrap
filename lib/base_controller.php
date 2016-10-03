<?php

  class BaseController{

	public static  function s_is_valid() {
		if (isset($_SESSION["auth"])) {
			 return true;
		} else {
			return false;
		}
	}
	public function is_valid() {
		if (isset($_SESSION["auth"])) {
			return true;
		} else {
			return false;
		}
	}
    public static function get_user_logged_in(){
      // Toteuta kirjautuneen käyttäjän haku tähän
      return null;
    }

    public static function check_logged_in(){
      // Toteuta kirjautumisen tarkistus tähän.
      // Jos käyttäjä ei ole kirjautunut sisään, ohjaa hänet toiselle sivulle (esim. kirjautumissivulle).
    }



	public static function auth_creds () {
		$rv = array();
		if (!isset($_SESSION['auth'])) {
			$rv['auth'] = false;
			return $rv;
		}
		if ($_SESSION['auth'] == "admin") {
			$rv['auth'] = true;
		} else {
			$rv['auth'] = false;
		}
		return $rv;	
	}
	public function o_auth_creds() {
		return BaseController::auth_creds();
	}

	public function ex_auth () {
		if (!isset($_SESSION["auth"])) {
			return "anon";
		} else {
			return $_SESSION["auth"];
		}
	}
 	public static function s_ex_auth () {
                if (!isset($_SESSION["auth"])) {
                        return "anon";
                } else {
                        return $_SESSION["auth"];
                }
        }
	public function auth() {
		$user = new User(array());
		$user->populate_user_from_db($this->ex_auth());
		return $user;
	}
        public static function s_auth() {
                $user = new User(array());
                $user->populate_user_from_db(BaseController::s_ex_auth());
                return $user;
        }


	public static function coerce_num($val) {
		$rv = intval($val);
		return $rv;
	}
	public static function strip_unwanted($val) {
		$val = strip_tags($val);
		$val = preg_replace("/[^[:alnum:][:space:]]/u", '', $val);
		return ($val);
	}

  }
