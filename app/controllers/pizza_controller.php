<?php
	class PizzaController extends BaseController {
		private static function get_pizzas($conn) {
			$rv = array();
			$sql = "SELECT pizza_name FROM pizzas ORDER BY pizza_name";
			foreach ($conn->query($sql) as $row) {
				$rv[$row['pizza_name']] = $row['pizza_name'];
			}
			return ($rv);
		}
		private static function get_now($conn) {
			$rv = "";
			$sql = "SELECT now()";
			foreach ($conn->query($sql) as $row) {
				$rv = implode($row);
				break;
			}
			return $rv;
		}
		public static function index() {
			$db = DB::connection();
			//$twig = View::get_twig();
			$p = PizzaController::get_pizzas($db);
			$now = PizzaController::get_now($db);
				//echo $twig->render('pizza.html', array('pizzas' => $p));
			View::make('pizza.html', array('pizzas' => $p, 'now' => $now));
		}
	}
?>
