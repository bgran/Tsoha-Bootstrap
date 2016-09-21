<?php
	class PizzaController extends BaseController {
		private static function get_pizzas($conn) {
			$rv = array();
			$sql = "SELECT pizza_name FROM staattiset_pizzat ORDER BY pizza_name";
			foreach ($conn->query($sql) as $row) {
				$rv[$row['pizza_name']] = $row['pizza_name'];
			}
			return ($rv);
		}
		private static function get_koosta_lisuke($conn) {
			$rv = array();
			$sql = "SELECT id,lisuke_nimi,lisuke_hinta FROM lisukkeet ORDER BY id";
			$sth = $conn->prepare($sql);
			$sth->execute();
			$result = $sth->fetchAll();
			return ($result);
			foreach($conn->query($sql) as $row) {
				$rv[$row['id']] = $row;

			}
			return ($rv);
		}

		private static function get_now($conn) {
			$rv = "";
			$sql = "SELECT now()";
			foreach ($conn->query($sql) as $row) {
				$rv = $row[0];
				//$rv = implode($row);
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

		private static function get_pizza_data($conn) {
			$sql ="SELECT staattiset_pizzat.id AS sid,lisukkeet.id AS lid,lisuke_nimi,lisuke_hinta,pizza_name FROM staattiset_pizzat,s_ll,lisukkeet WHERE lisukkeet.id=lisukkeen_id AND staattiset_pizzat.id=pizza_id ORDER BY sid";

			$sth = $conn->prepare($sql);
			$sth->execute();
			$result = $sth->fetchAll();
			return ($result);
		}
		private static function get_pizzas_id($conn) {
			$sql = "SELECT DISTINCT staattiset_pizzat.id from staattiset_pizzat,s_ll,lisukkeet WHERE staattiset_pizzat.id=s_ll.pizza_id AND lisukkeet.id=lisukkeen_id ORDER BY id";
			$result = array();
			$r = null;
			$l_id = 0;
			foreach ($conn->query($sql) as $row) {
				$id = $row[0];
				$result[$id] = array();
				$s1 = "SELECT lisukkeet.id AS lid,staattiset_pizzat.id AS sid,lisuke_nimi,lisuke_hinta,pizza_name FROM staattiset_pizzat,s_ll,lisukkeet WHERE lisukkeet.id=lisukkeen_id AND staattiset_pizzat.id=pizza_id and staattiset_pizzat.id=$id ORDER BY lisukkeet.id";
				///$s1 = "SELECT lisukkeet.id AS lid,lisuke_nimi,lisuke_hinta,pizza_name FROM stataattiset_pizzat,s_ll,lisukkeet WHERE lisukkeet.id=lisukkeen_id AND staattiset_pizzat.id=pizza_id ORDER BY lisukkeet.id";

				foreach ($conn->query($s1) as $row) {
					$result[$id][] = $row;
					
				}
			}
			return ($result);
		}


		public static function menu() {
			$db = DB::connection();
			$p = PizzaController::get_pizzas($db);
			$now = PizzaController::get_now($db);
			$pizza_data = PizzaController::get_pizzas_id($db);
			//////////////////////////////var_dump($pizza_data);
			View::make('menu.html', array('pizzas'=>$p, 'now'=>$now, 'pizza_data'=>$pizza_data));
		}
		public static function koosta() {
			$db = DB::connection();
			$lisukkeet = PizzaController::get_koosta_lisuke($db);
			//var_dump($lisukkeet);
			View::make('koosta.html', array('lisukkeet'=>$lisukkeet));
		}
		public static function ala_sivu(ServerRequestInterface $request, ResponseInterface $response) {
			print "haukea";


		}

		public static function op_uusipizza($res) {
			//print_r($res);
			$db = DB::connection();
			$p = PizzaController::get_pizzas($db);
			$now = PizzaController::get_now($db);

			$db->beginTransaction();

			$s_id = "SELECT nextval('staattiset_pizzat_id_seq')";
			$new_pizza = -1;
			foreach($db->query($s_id) as $row) {
				$new_pizza = $row[0];
				break;
			}
			print "<h1>$new_pizza</h1><br><br>";
			//$new_pizza = $s_v[0];

			$a_pizzaname = $res->request->post('a_pizzaname');
			print "a_pizzaname: " . $a_pizzaname;

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
			print "num_id: " . $num_id . "<br>";

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

			$res->redirect('/tsoha/');

			View::make('menu.html', array('pizzas'=>$p, 'now'=>$now));
		}

		public static function op_uusilisuke($res) {
			$db = DB::connection();
			$nimi = $res->request->post('a_lisukename');
			if ($nimi == null) {
				$res->redirect('/tsoha/');
			}
			$hinta = $res->request->post('a_lisukehinta');
			if ($hinta == null) {
				$res->redirect('/tsoha/');
			}
			$sql = "INSERT INTO lisukkeet(lisuke_nimi, lisuke_hinta) VALUES(:lisuke_nimi, :lisuke_hinta)";
			$db->beginTransaction();
			$st = $db->prepare($sql);
			$st->execute(array(
				"lisuke_nimi" => $nimi,
				"lisuke_hinta" => $hinta));
			$db->commit();
			$res->redirect('/tsoha');
		}
			
		public static function uusilisuke($res) {
			$db = DB::connection();
			$lisukkeet = PizzaController::get_koosta_lisuke($db);
			View::make('lisaalisuke.html', array('lisukkeet'=>$lisukkeet));


		}

	}
?>
