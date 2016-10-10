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
		$db = DB::connection();
		$pizza = Pizza::ng_get_id($pizza_id, $db);
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
			$obj->order = Pizza::ng_get_id($pizza_id, $db);
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


}

?>
