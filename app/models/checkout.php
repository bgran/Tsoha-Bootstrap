<?php

class pizza_s {
	public $id;
	public $price;
};

class Checkout extends BaseModel {
	private $params = null;
	private $res = null;

	public $name;
	public $address;
	public $pizzas = array();
	public $pending;

	public function __construct($attributes) {
                parent::__construct($attributes);
		//$this->params = $attributes;
		//$this->res = $_res;
        }

	public static function new_checkout($_name, $_address) {
		$obj = new Checkout(array());
		$obj->name = $_name;
		$obj->address = $_address;
		$obj->populate_from_db();
		return $obj;
	}
	public function save() {
		$db = DB::connection();

		$db->beginTransaction();

		$sql = "SELECT nextval('po_id_seq')";
		$st = $db->prepare($sql);
		$st->execute();
		$val = $st->fetch();
		$seq_val = $val[0];

		foreach($this->pizzas as $pizza) {
			$sql = "INSERT INTO pending_pizza(pending_order, pizza_id) VALUES(:po, :pizzaid)";
			$st = $db->prepare($sql);
			$st->execute(array(
				':po' => $seq_val, ':pizzaid' => $pizza->id));
		}

		$sql = "INSERT INTO pending_orders(po_id, user_id, name, address) VALUES(:po_id, :user_id, :name, :address)";
		$st = $db->prepare($sql);
		$st->execute(array(
				':po_id' => $seq_val, ':user_id' => session_id(), ':name' => $this->name, ':address' => $this->address));

		$sql = "DELETE FROM orders WHERE user_id=?";
		$st = $db->prepare($sql);
		$st->execute(array(session_id()));

		$db->commit();
	}

	public function populate_from_db() {

		$sql1 = "select orders.pizza_id,sum(lisukkeet.lisuke_hinta) from lisukkeet,s_ll,staattiset_pizzat,orders WHERE lisukkeet.id=s_ll.lisukkeen_id AND s_ll.pizza_id=staattiset_pizzat.id and orders.pizza_id=staattiset_pizzat.id AND orders.user_id= ? GROUP BY orders.pizza_id ORDER BY orders.pizza_id";
		$sql2 = "select pizza_id,count(pizza_id) from orders where user_id= ? group by pizza_id ORDER BY orders.pizza_id";

		$db = DB::connection();

		$st1 = $db->prepare($sql1);
		$st1->execute(array(session_id()));
                $arr1 = array();
                $k = 0;
                foreach($st1->fetchAll() as $row) {
                        $arr1[] = $row;
                        $k ++;
		}

                $st2 = $db->prepare($sql2);
                $st2->execute(array(
                        session_id()));
                $arr2 = array();
                $k = 0;
                foreach($st2->fetchAll() as $row) {

                        $arr2[] = $row;
                        $k ++;
                }

                $i = count($arr1);

		for ($j=0; $j < $i; $j++) {
                        $r1 = $arr1[$j];
                        $r2 = $arr2[$j];

                        $pizza_id = $r1[0];
                        $count_ = $r2[1];
                        $price = $r1[1];
			$obj = new pizza_s();
			$obj->id = $pizza_id;
			$obj->price = $price;
                        $this->pizzas[] = $obj;
                }
	}




	public static function orders_report() {
		$sql1 = "select orders.pizza_id,sum(lisukkeet.lisuke_hinta) from lisukkeet,s_ll,staattiset_pizzat,orders WHERE lisukkeet.id=s_ll.lisukkeen_id AND s_ll.pizza_id=staattiset_pizzat.id and orders.pizza_id=staattiset_pizzat.id AND orders.user_id= ? GROUP BY orders.pizza_id ORDER BY orders.pizza_id";
		$sql2 = "select pizza_id,count(pizza_id) from orders where user_id= ? group by pizza_id ORDER BY orders.pizza_id";

		$db = DB::connection();

		$st1 = $db->prepare($sql1);
		$st1->execute(array(
			/*':user_id' =>*/ session_id()));

		$arr1 = array();
		$k = 0;
		foreach($st1->fetchAll() as $row) {
			//print "KALAAAA<br><br>";
			$arr1[] = $row;
			$k ++;
		}
		
		$st2 = $db->prepare($sql2);
		$st2->execute(array(/*
			':user_id' => */session_id()));
		$arr2 = array();
		$k = 0;
		foreach($st2->fetchAll() as $row) {
			//print "HAUKEA<br><br>";

			$arr2[] = $row;
			$k ++;
		}

		$i = count($arr1);

		$objs = array();

		for ($j=0; $j < $i; $j++) {
		//	print "iter: <br><br>";
			$r1 = $arr1[$j];
			$r2 = $arr2[$j];

			//var_dump($r1);
			//var_dump($r2);
			$pizza_id = $r1[0];
			$count_ = $r2[1];
			$price = $r1[1];
			$obj = Report::gen($pizza_id, $count_, $price);
			//print "price: " . $obj->price . "<br><br>";
			$objs[] = $obj;
		}

		return $objs;
	}
	public static function del() {
		$db = DB::connection();
		$sql = "DELETE FROM orders WHERE user_id=?";
		$st = $db->prepare($sql);
		$st->execute(array(session_id()));
	}

}

?>
