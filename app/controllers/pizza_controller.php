<?php
class PizzaController extends BaseController {
	
	//
	// /menu stuff in pizzas()
	//
	public static function pizzas() {
		$pizzas = Pizza::ng_get_all();
		//$pizzas = array();
		//$pizzas = Pizza::get_pizzas_id();
		//$lisuke = Lisuke::all();
		//$tmp = new TempPizza(array());
		//$tmp->populate_from_db(session_id());
		View::make('uusmenu.html', array('pizza_data'=>$pizzas));
			//'lisuke'=>$lisuke,
			//"tmp" => $tmp->lisukkeet));
	}

	public static function pizza_crud_template($res) {
		$user = PizzaController::s_auth();
		$pizza_id = $res->request->get('a_pizzaid');

		$db = DB::connection();
		$pizza = Pizza::ng_get_id($pizza_id, $db);
		$lis = Lisuke::all($db);
		
		if (!$user->is_admin) {
			View::make('crud_err.html');
			exit();
		} else {
			View::make('crud_template.html', array(
				'pizza' => $pizza,
				'lisukkeet' => $lis
			));
		}
	}
	public static function pizza_crud($res) {
		$user = PizzaContoller::s_auth();
		if (!$user->is_admin) {
			View::make('autherr.html');
			exit();
		}


		$db = DB::connection();

		$op = null;
		$op_del = $res->request->post('op_delete');
		$op_save = $res->request->post('op_save');
		if ($op_del != null) {
			$op = "delete";
		} elseif ($op_save) { 
			$op = "save";
		} else {
		}

		if ($op == "delete") {
			$pizza_id = $res->request->post('pizza_id');
			$pizza_id = intval($pizza_id);
			$pizza = Pizza::ng_get_id($pizza_id, $db);
			$pizza->delete();
		} else {
			/*
			 * First edit name...
			 */
			$pizza_id = $res->request->post('pizza_id');
			$pizza_name = $res->request->post('a_pizzaname');
          	    	$pizza_id = intval($pizza_id);
			$pizza = Pizza::ng_get_id($pizza_id, $db);
			$pizza->name = $pizza_name;
			$pizza->save();

	
			/*
		 	 * Then handle lisukeparams.
		 	 */
		 	$arr = array();
			$i = Lisuke::num_lisukkeet();		
			for ($j = 0; $j < $i; $j++) {
				$temper = $res->request->post("a_lisuke_$j");
				if ($temper == null) {
					$pizza->drop_lisuke_id($j);
				} else {
					$pizza->add_lisuke_id($j);
				}
			}
		}

		$res->redirect('/tsoha/menu');
	}

	public static function add($res) {
		$user = PizzaController::s_auth();
		if (!$user->is_admin) {
			View::make('errauth.html');
			exit();
		} else {
			//$a_pizzaname = $res->request->post('a_pizzaname');
			//$a_pizzaname = \
			//	PizzaController::strip_unwanted($a_pizzaname);


			//
			// XXX no res to models!
			//
			Pizza::add($res);
			$res->redirect('/tsoha/menu');
		}
	}
        public static function add_anon($res) {
		$tmp = new TempPizza(array());
		$tmp->add($res);

                //$a_pizzaname = $res->request->post('a_pizzaname');
                //TempPizza::add($res);
                $res->redirect('/tsoha/menu');
        }

	public static function add_templ() {
		$user = PizzaController::s_auth();
                //if (!$user->is_admin) {
                //        View::make('errauth.html');
                // else {
			$data = Lisuke::get();
			View::make('koosta.html', array('lisukkeet' => $data));
		///}
	}

        public static function add_static_templ() {
		$user = PizzaController::s_auth();
		if (!$user->is_admin) {
                //if (!PizzaController::s_is_valid()) {
                        View::make('noauth.html');
			exit();
                } else {
                        $data = Lisuke::get();
                        View::make('koosta_staattinen.html', array('lisukkeet' => $data));
                }
        }


	public static function index() {
		//$user = PizzaController::s_auth();
		//$bauth = $g_user->is_valid();
		//$db = DB::connection();
		$now = Pizza::now();
		$authcreds = PizzaController::auth_creds();
		View::make('pizza.html', array('now' => $now));
			//"auth_creds" => $authcreds,
			//"user"=>$user));
	}

	public static function del_anon($res) {
		$pizzas = Pizza::get_pizzas_id();
		$lisuke = Lisuke::all();
		
		$tmp = new TempPizza(array());
		$tmp->del_anon_pizza();
		$res->redirect('/tsoha/menu');
		//View::make('uusmenu.html', array('pizza_data'=>$pizzas,
	         //       'lisuke'=>$lisuke,
                  //      "user"=>BaseController::s_auth(),
                    //    "tmp" => $tmp->lisukkeet));
	}

        public static function get_id($numero) {
                $numero = intval($numero);
		$db = DB::connection();
                $pizza_data = Pizza::ng_get_id($numero, $db);
                View::make('naytaid.html',
                        array('pizza_data' => $pizza_data));
        }

};


?>
