<?php

class User extends BaseModel {

	public $username;
	public $password;
	public $is_admin;
	public $session_id;

	private $token = null;
	public function __construct($attributes = null) {
                parent::__construct($attributes);
		//$this->token = $attributes['token'];
        }

	public static function get_obj($_username) {
		$db = DB::connection();
		$sql = "SELECT username,phash FROM users ORDER BY username";
		$st = $db->prepare($sql);
		foreach($st->fetchAll() as $row) {
			$_u = $row['username'];
			$_p = $row['phash'];
			if ($_username == $_u) {
				$obj = new User(array());
				$obj->username = $_username;
				$obj->password = $_p;
				if ($obj->username == "admin") {
					$obj->is_admin = true;
				}
				$obj->session_id = session_id();
			}
		}
		return(null);

	}

	/*
	 * Duplication of code...
	 */
	public function is_valid() {
		if (isset($_SESSION["auth"])) {
			return true;
		} else {
			return false;
		}
	}

	public function populate_user_from_db($username) {
		$db = DB::connection();
		
		$sql = "SELECT id,username,phash FROM users ORDER BY id";
		foreach ($db->query ($sql) as $row) {
			if ($username == $row['username']) {
			
				$this->userid = $row['id'];
				$this->username = $row['username'];
				$this->password = $row['phash'];
				$this->session_id = session_id();
				if ($row['username'] == "admin") {
					$this->is_admin = true;
				} else {
					$this->is_admin = false;
				}

				return;
			}

		}
		$this->userid = -1;
		$this->username = 'anon';
		$this->password = '';
		$this->is_admin = false;
		$this->session_id = session_id();
	}


        public static function now() {
                $conn = DB::connection();
                $sql = "SELECT now()";
                $rv = '';
                foreach ($conn->query($sql) as $row) {
                        $rv = $row[0];
                        break;
                }
                return ($rv);
        }


}


?>
