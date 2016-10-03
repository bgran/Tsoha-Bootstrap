<?php
class PizzaController extends BaseController {
	
	//
	// /menu stuff in pizzas()
	//
	public static function pizzas() {
		$pizzas = array();
		$pizzas = Pizza::get_pizzas_id();
		$lisuke = Lisuke::all();
		$tmp = new TempPizza(array());
		$tmp->populate_from_db(session_id());
		View::make('uusmenu.html', array('pizza_data'=>$pizzas,
			'lisuke'=>$lisuke,
			"user"=>BaseController::s_auth(),
			"tmp" => $tmp->lisukkeet));
	}

	public static function add($res) {
		if (!PizzaController::s_is_valid()) {
			View::make('noauth.html');
		} else {
			//$a_pizzaname = $res->request->post('a_pizzaname');
			//$a_pizzaname = \
			//	PizzaController::strip_unwanted($a_pizzaname);
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

	public static function pizza($numero) {
		$pizza_data = Pizza::pizza_numero($numero);
		View::make('.naytaid.html',
			array('pizza_data'=>$pizza_data,
				"user"=>BaseController::s_auth()));

	}
	public static function get_id($numero) {
		$pizza_data = Pizza::get_id($numero);
		View::make('naytaid.html',
			array('pizza_data' => $pizza_data,
			"user"=>BaseController::s_auth()));
	}

	public static function add_templ() {
		//if (!PizzaController::s_is_valid()) {
		//	View::make('noauth.html');
		//} else {
			$data = Lisuke::get();
			View::make('koosta.html', array('lisukkeet' => $data,
				"user"=>PizzaController::s_auth()));
		//}
	}

        public static function add_static_templ() {
		$user = new User(array());
		$user->populate_user_from_db(PizzaController::s_ex_auth());
		if (!$user->is_admin) {
                //if (!PizzaController::s_is_valid()) {
                        View::make('noauth.html');
                } else {
                        $data = Lisuke::get();
                        View::make('koosta_staattinen.html', array('lisukkeet' => $data,
                                "user"=>PizzaController::s_auth()));
                }
        }


	public static function index() {
		//$bauth = $g_user->is_valid();
		//print "<br>$bauth</br>";
		$db = DB::connection();
		$now = Pizza::now();
		$authcreds = PizzaController::auth_creds();
		$user = new User(array());
		$user->populate_user_from_db(PizzaController::s_ex_auth());
		View::make('pizza.html', array('now' => $now,
			"auth_creds" => $authcreds,
			"user"=>BaseController::s_auth()));
	}

	public static function del_anon($res) {
		$pizzas = Pizza::get_pizzas_id();
		$lisuke = Lisuke::all();
		
		//print "dfela_anon static";
		$tmp = new TempPizza(array());
		$tmp->del_anon_pizza();
		$res->redirect('/tsoha/menu');
		//View::make('uusmenu.html', array('pizza_data'=>$pizzas,
	         //       'lisuke'=>$lisuke,
                  //      "user"=>BaseController::s_auth(),
                    //    "tmp" => $tmp->lisukkeet));
	}
};


?>
