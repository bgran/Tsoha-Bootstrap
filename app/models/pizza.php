<?php

class Pizza extends BaseModel {

	public $name, $price = 0;
	public $id;
	public $lisukkeet = array();
	public function __construct($attributes = null) {
                parent::__construct($attributes);
        }

	public static function all() {
		$db = DB::connection();
		$sql = "SELECT id,pizza_name FROM staattiset_pizzat ORDER BY id";
		$result = array();
		foreach($db->query($sql) as $row) {
			//$pizza = new Pizza();
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

	public function save() {
		$sql = "UPDATE staattiset_pizzat SET pizza_name=:pizzaname WHERE
 id=:id";
		$db = DB::connection();
		$st = $db->prepare($sql);
		$st->execute(array(
			':pizzaname' => $this->name,
                        ':id' => $this->id));
	}
	public function delete() {
		$db = DB::connection();
		$sql = "DELETE FROM staattiset_pizzat WHERE id=:id";
		$st = $db->prepare($sql);
		$st->execute(array(":id" => $this->id));
	}

	public function drop_lisuke_id($i) {
		$db = DB::connection();
		$sql = "DELETE FROM s_ll WHERE lisukkeen_id=:id and pizza_id=:pid";
		$st = $db->prepare($sql);
		$st->execute(array(':id' => $i, ":pid" => $this->id));
	}
	private function lisuke_exists($i) {
		$db = DB::connection();
		$sql = "SELECT count(pizza_id) FROM s_ll WHERE pizza_id=:pid AND lisukkeen_id=:id";
                $st = $db->prepare($sql);
                $st->execute(array(":pid" => $this->id, ':id' => $i));
		$result = $st->fetch();
		return $result[0] > 0;
		return $result[0];
	}

	public function add_lisuke_id($i) {
		if ($this->lisuke_exists($i)) {

		} else {
			$db = DB::connection();
			$sql = "INSERT INTO s_ll(pizza_id, lisukkeen_id) VALUES(:pid, :lid)";
			$st = $db->prepare($sql);
			$st->execute(array(":pid" => $this->id, ':lid' => $i));
		}
	}


	private function populate_lisuke() {
		$db = DB::connection();
		$this->lisukkeet = Lisuke::get_by_pizza_id($this->id);
		foreach($this->lisukkeet as $obj) {
			$this->price += $obj->price;
			//print "<br>obj-price" . $obj->price;
			//print "<br>this-price" . $this->price;
			//print "<br><br>";
		}
	}
	public static function ng_get_id($id) {
		$db = DB::connection();
		$sql = "SELECT id,pizza_name FROM staattiset_pizzat WHERE id=:id";
		$st = $db->prepare($sql);
		//$st->bindParam(':id', $id, PDO::PARAM_INT);
		$st->execute(array(
			':id' => $id));
		$ar = $st->fetch();
		$obj = new Pizza(array());
		$obj->name = $ar['pizza_name'];
		$obj->id = $ar['id'];
		$obj->populate_lisuke();
		return $obj;
	}
	public static function ng_get_all() {
		$db = DB::connection();
		$sql = "SELECT id,pizza_name FROM staattiset_pizzat ORDER BY id";
		$res = array();
		$st = $db->prepare($sql);
		$st->execute();
		foreach ($st->fetchAll() as $row) {
			$_id = $row['id'];
			//print "kala: " . $_id;
			//$res[$_id] = Pizza::ng_get_id($_id);
			array_push($res, Pizza::ng_get_id($_id));
		}
		return $res;
	}

	public function pizza_has_lisuke($id) {
		$id = intval($id);
		foreach ($lisukkeet as $row) {
			if ($row->id == $id) {
				return true;
			}
		}
		return false;
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
		$a_pizzaname = BaseController::strip_unwanted(
			$a_pizzaname);
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
		$id = BaseController::coerce_num($numero);
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
