<?php
class UserController extends BaseController {
	public static function auth($token) {
		$params = array('token' => $token);
		$user = new User($params);
		if ($user->is_valid()) {
			return true;
		} else {
			return false;
		}
	}

	public static function pizzas() {
		$pizzas = array();
		$pizzas = Pizza::get_pizzas_id();
		$lisuke = Lisuke::all();
		View::make('uusmenu.html', array('pizza_data'=>$pizzas,
			'lisuke'=>$lisuke));
	}

	public static function add($res) {
		$a_pizzaname = $res->request->post('a_pizzaname');
		Pizza::add($res);
		$res->redirect('/tsoha/menu');
	}
	public static function pizza($numero) {
		$pizza_data = Pizza::pizza_numero($numero);
		View::make('naytaid.html',
			array('pizza_data'=>$pizza_data));

	}
	public static function get_id($numero) {
		$pizza_data = Pizza::get_id($numero);
		View::make('naytaid.html',
			array('pizza_data' => $pizza_data));
	}

	public static function add_templ() {
		$data = Lisuke::get();
		View::make('koosta.html', array('lisukkeet' => $data));
	}

	public static function index() {
		$db = DB::connection();
		$now = Pizza::now();
		View::make('pizza.html', array('now' => $now));
	}
};


?>
