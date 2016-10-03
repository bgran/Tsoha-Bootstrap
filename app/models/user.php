<?php

class User extends BaseModel {

	public $username;
	public $password;
	public $is_admin;

	private $token = null;
	public function __construct($attributes = null) {
                parent::__construct($attributes);
		//$this->token = $attributes['token'];
        }       	

	public function is_valid() {
		/*$db = DB::connection();
		$sql = "SELECT hashval,creation_time+INTERVAL '15 minutes' FROM user_tokens";
		foreach($db->query($sql) as $row) {
			$db_hash = $row[0];
			$db_timer = $row[1];
			
		}*/
		if ($_SESSION["auth"] = "admin") {
			//print "ADMIN LOGIN";
			$this->is_admin = true;
			return true;
		} else {
			//print "EI ADMIN LOGIN";
			return false;
		}
		$sessid = session_id();
		//print "sessid: $sessid<br><br>";
		if ($sessid == "") {
			return false;
		} else {
			return true;
		}


		if (!defined("_SESSION")) {
			//print "ei sessiota";
			return false;
		}

		
		
		//print "$_SESSION";
		if ($_SESSION) {
			return true;
		} else {
			return false;
		}
	}

	public function feed($params) {
	}

	public static function all() {
		$db = DB::connection();
		$sql = "SELECT id,pizza_name FROM staattiset_pizzat ORDER BY id";
		$reesult = array();
		foreach($db->query($sql) as $row) {

			$result[] = $row;
		}
		return $result;
	}

	public static function get_pizzas_id() {
		$conn = DB::connection();
		$sql = "SELECT DISTINCT staattiset_pizzat.id from staattiset_pizzat,s_ll,lisukkeet WHERE staattiset_pizzat.id=s_ll.pizza_id AND lisukkeet.id=lisukkeen_id ORDER BY id";
		$result = array();
		foreach ($conn->query($sql) as $row) {
			$id = $row[0];
			$result[$id] = array();
			$s1 = "SELECT lisukkeet.id AS lid,staattiset_pizzat.id AS sid,lisuke_nimi,lisuke_hinta,pizza_name FROM staattiset_pizzat,s_ll,lisukkeet WHERE lisukkeet.id=lisukkeen_id AND staattiset_pizzat.id=pizza_id and staattiset_pizzat.id=$id ORDER BY lisukkeet.id";
			foreach ($conn->query($s1) as $row) {
				$result[$id][] = $row;
			}

		}
		return ($result);
			
	}

	public function populate_user_from_db($username) {
		$db = DB::connection();
		
		$sql = "SELECT id,username,phash FROM users ORDER BY id";
		foreach ($db->query ($sql) as $row) {
			if ($username == $row['username']) {
			
				$this->userid = $row['id'];
				$this->username = $row['username'];
				$this->password = $row['phash'];
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
	}

	public static function add($res) {
        	$db = DB::connection();
                //$p = tPizzaController::get_pizzas($db);
                //$now = tPizzaController::get_now($db);

                $db->beginTransaction();

                $s_id = "SELECT nextval('staattiset_pizzat_id_seq')";
                $new_pizza = -1;
                foreach($db->query($s_id) as $row) {
                        $new_pizza = $row[0];
                        break;
                }
                //print "<h1>$new_pizza</h1><br><br>";
                //$new_pizza = $s_v[0];

                $a_pizzaname = $res->request->post('a_pizzaname');
                //print "a_pizzaname: " . $a_pizzaname;

                $s_pizza = "INSERT INTO staattiset_pizzat (id, pizza_name) VALUES(:id, :pizza_name)"; //, $a_pizzaname)";
                $st = $db->prepare($s_pizza);
                $st->execute(array(
 	               "id" => $new_pizza,
                       "pizza_name" => $a_pizzaname));
                        

                $vals = array();

                $num_id = 0;
                $sql = "SELECT max(id) FROM lisukkeet";
                foreach ($db->query($sql) as $row) {
                       $num_id = $row[0];
                       break;
                 }
                 //print "num_id: " . $num_id . "<br>";

                 for ($i= 0; $i<$num_id ;$i++) {
                 	$tmp = $res->request->post('a_lisuke_'.$i);
                        if ($tmp == null) continue;
                        else {
                  	      // $i points to 
                              $s_pizza = "INSERT INTO s_ll (pizza_id, lisukkeen_id) VALUES(:pizza_id, :lisukkeen_id)";
                              $st = $db->prepare($s_pizza);   
                              $st->execute(array(
                              "pizza_id" => $new_pizza,
                              "lisukkeen_id" => $i));
                        }
		}

                $db->commit();

               // View::make('menu.html', array('pizzas'=>$p, 'now'=>$now));
	}

	private static function pizza_numero($id, $conn) {
                $id = intval($id);
                $sql = "SELECT DISTINCT staattiset_pizzat.id FROM staattiset_pizzat,s_ll,lisukkeet WHERE staattiset_pizzat.id=s_ll.pizza_id AND lisukkeet.id=s_ll.lisukkeen_id AND staattiset_pizzat.id=$id";
                $result = array();
                foreach ($conn->query($sql) as $row) {
                       $id = $row[0];
                       $result[$id] = array();
                       $s1 = "SELECT lisukkeet.id AS lid,staattiset_pizzat.id AS sid,lisuke_nimi,lisuke_hinta,pizza_name FROM staattiset_pizzat,s_ll,lisukkeet WHERE lisukkeet.id=lisukkeen_id AND staattiset_pizzat.id=pizza_id AND staattiset_pizzat.id=$id ORDER BY lisukkeet.id";
                                
                        foreach($conn->query($s1) as $row2) {
                                $result[$id][] = $row2;
                        }
                }
                return ($result);
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

	public static function get_id($numero) {
		$conn = DB::connection();
		$id = intval($numero);
		$sql = "SELECT DISTINCT staattiset_pizzat.id FROM staattiset_pizzat,s_ll,lisukkeet WHERE staattiset_pizzat.id=s_ll.pizza_id AND lisukkeet.id=s_ll.lisukkeen_id AND staattiset_pizzat.id=$id";
		$result = array();
		foreach ($conn->query($sql) as $row) {
			$id = $row[0];
			$result[$id] = array();
			$s1 = "SELECT lisukkeet.id AS lid,staattiset_pizzat.id AS sid,lisuke_nimi,lisuke_hinta,pizza_name FROM staattiset_pizzat,s_ll,lisukkeet WHERE lisukkeet.id=lisukkeen_id AND staattiset_pizzat.id=pizza_id AND staattiset_pizzat.id=$id ORDER BY lisukkeet.id";

			foreach($conn->query($s1) as $row2) {
				$result[$id][] = $row2;
			}
		}
		return ($result);


	}

}


?>
