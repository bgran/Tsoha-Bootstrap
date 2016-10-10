<?php

class Lisuke extends BaseModel {
	private $params = null;
	private $res = null;


	public $name, $price;
	public $id;
	public $checked = false;


	public function __construct($attributes) {
                parent::__construct($attributes);
		//$this->params = $attributes;
		//$this->res = $_res;
        }       	

	public static function get_id($id) {
		$id = intval($id);
		$db = DB::connection();
		$sql = "SELECT id,lisuke_nimi,lisuke_hinta FROM lisukkeet WHERE id=:id ORDER BY id";
		$st = $db->prepare($sql);
		$st->execute(array(
			':id' => $id));
		$ar = $st->fetch();
		$obj = new Lisuke(array());
		$obj->id = $id;
		$obj->name = $ar['lisuke_nimi'];
		$obj->price = $ar['lisuke_hinta'];

		return ($obj);
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
