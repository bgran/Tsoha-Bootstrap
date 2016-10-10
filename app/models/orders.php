<?php
class tracking_s {
	public $name;
	public $address;
	public $po_id;
	public $pizzas = array();
};
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
	public static function cancel($id) {
		$user = BaseController::s_auth();
		if (!$user->is_admin) {
			return false;
		} else {
			$sql = "DELETE FROM pending_orders WHERE po_id=?";
			$db = DB::connection();
			$st = $db->prepare($sql);
			$st->execute(array($id));
		}
	}

	public static function all_orders_report() {
		$sql = "select po_id,user_id,name,address,pizza_id FROM pending_orders,pending_pizza WHERE po_id=pending_order ORDER by po_id";

		$rv = array();
		$db = DB::connection();
		$st = $db->prepare($sql);
		$st->execute();
		$state = true;
		$prev = -1;
		$p = null;
		foreach($st->fetchAll() as $row) {
			if ($row[0] != $prev) {
				$prev = $row[0];
				if ($state) {
					$state = false;
				} else {
					$rv[] = $p;
				}
				$p = new tracking_s();
				$p->name = $row[2];
				$p->address = $row[3];
				$p->po_id = $row[0];
				//$p->pizzas[] = $row[4];
				$p->pizzas[] = Pizza::ng_get_id($row[4]);
			} else {
				//$p->pizzas[] = $row[4];
				$p->pizzas[] = Pizza::ng_get_id($row[4]);
			}
		}
		$rv[] = $p;
		return $rv;
	}

	
}

?>
