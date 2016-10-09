<?php

class Orders extends BaseModel {
	private $params = null;
	private $res = null;

	public $pizzas = array();
	public $price = array();
	

	public function __construct($attributes) {
                parent::__construct($attributes);
		//$this->params = $attributes;
		//$this->res = $_res;
        }

	public static function orders_report() {
		$orders = Order::get_id();
		$numtbl = array();
		
		$price_compund = 0;

		$arr = array_count_values($orders);
		
		foreach($arr as $key => $val) {
			
		}

		foreach($orders as $obj) {
			

		}
		
		$state = 1;
		$price = 0;
		$prev = null;
		foreach ($orders as $order) {
			$numtbl[$order->order] += 1;			
		}

		foreach ($numtbl as $key => $value) {
			$key->compound_price = $key->price * $value;	
		}

		
	}















	public static function get_by_pizza_id($id) {
		$id = intval($id);
		$rv = array();
		$db = DB::connection();
			$sql = "SELECT lisukkeet.id AS id FROM lisukkeet,s_ll,staattiset_pizzat WHERE lisukkeet.id=s_ll.lisukkeen_id AND s_ll.pizza_id=staattiset_pizzat.id AND pizza_id=:id";
		$st = $db->prepare($sql);
		$st->execute(array(
			':id' => $id));
		$arr = $st->fetchAll();
		foreach ($arr as $row) {
			$obj = Lisuke::get_id($row['id']);
			$rv[$row['id']] = $obj;
			//array_push($rv, $obj);
		}
		return $rv;
	}


	public static function all() {
		$db = DB::connection();
		$sql = "SELECT id,lisuke_nimi,lisuke_hinta FROM lisukkeet ORDER BY id";
		$result = array();
		foreach($db->query($sql) as $row) {
			$_id = $row['id'];
			$result[] = Lisuke::get_id($_id);
		}
		return $result;
	}


	public static function get() {
		$conn = DB::connection();
        	$rv = array();
        	$sql = "SELECT id,lisuke_nimi,lisuke_hinta FROM lisukkeet ORDER BY id";
       		$sth = $conn->prepare($sql);
        	$sth->execute();
        	$result = $sth->fetchAll();
        	return ($result);
	}

	/*
	 * Return stuff about errors ...
	 */
	private function validate_add() {
		if ($this->params['lisukename'] == "virhe") {
			$this->res->redirect('/tsoha/');
			exit();
		}
		return true;

	}

	public function ng_add() {
		$db = DB::connection();
		$sql = "INSERT INTO lisukkeet(lisuke_nimi, lisuke_hinta) VALUES(:nimi, :hinta)";
		$st = $db->prepare($sql);
		$st->execute(array(
			":nimi" => $this->name, ":hinta" => $this->price));
	}

	public function old_add() {
        	$db = DB::connection();
		$nimi = $this->params["lisukename"];
		$hinta = $this->params["lisukehinta"];

		$this->validate_add();

        	$sql = "INSERT INTO lisukkeet(lisuke_nimi, lisuke_hinta) VALUES(:lisuke_nimi, :lisuke_hinta)";
        	$db->beginTransaction();
        	$st = $db->prepare($sql);
        	$st->execute(array(
                	"lisuke_nimi" => $nimi,
                	"lisuke_hinta" => $hinta));
        	$db->commit();
        	//$res->redirect('/tsoha');
        }
                        

	public static function now() {
		$db = DB::connection();
		$sql = "SELECT now()";
		$rv = '';
		foreach ($conn->query($sql) as $row) {	
			$rv = $row[0];
			break;
		}
		return ($rv);
	}
	public static function num_lisukkeet() {
		$db = DB::connection();
		$sql = "SELECT MAX(id)+1 FROM lisukkeet";
	 	$st = $db->prepare($sql);
		$st->execute();
		$rv = $st->fetch();
		print "kalaa: " . $rv[0];
		return $rv[0];
	}
}

?>
