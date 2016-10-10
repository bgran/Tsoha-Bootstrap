<?php

class Report extends BaseModel {
	private $params = null;
	private $res = null;

	public $pizza = null;
	public $count = 0;
	public $price = 0;
	

	public function __construct($attributes) {
                parent::__construct($attributes);
		//$this->params = $attributes;
		//$this->res = $_res;
        }

	public static function gen($pizza_id, $c, $price_) {
		$pizza = Pizza::ng_get_id($pizza_id);
		$obj = new Report(array());
		$obj->count = $c;
		$obj->price = $price_;
		$obj->pizza = $pizza;

		return $obj;
	}

	public static function get_id() {
		$rv = array();
		$id = session_id();
		$db = DB::connection();
		$sql = "SELECT user_id,pizza_id FROM orders WHERE user_id=:user_id ORDER by pizza_id";
		$st = $db->prepare($sql);
		$st->execute(array(
			':user_id' => $id));
		foreach ($st->fetchAll() as $row) {
			$user_id = $row['user_id'];
			$pizza_id = $row['pizza_id'];
			$obj = new Order(array());
			$obj->user = $user_id;
			$obj->order = Pizza::ng_get_id($pizza_id);
			//$obj->price += $obj->order->price;
			$rv[] = $obj;
		}
		return $rv;
	}

	public static function get_new($pizza_id) {
		$obj = new Order(array());
		$obj->user = session_id();
		$obj->order = $pizza_id;
		return $obj;
	}

	public function add() {
		$db = DB::connection();
		$sql = "INSERT INTO orders(user_id, pizza_id) VALUES(:userid, :pizzaid)";
		$st = $db->prepare($sql);
		$st->execute(array(
			':userid' => $this->user,
			':pizzaid' => $this->order));
	}

	public static function raportti() {

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
		return $rv[0];
	}
}

?>
