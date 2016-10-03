<?php
class LoginController extends BaseController {
	/*
	 * Tata ei kayteta!
	 */
	public static function dfdfpizzas() {
		$pizzas = array();
		$pizzas = Pizza::get_pizzas_id();
		$lisuke = Lisuke::all();
		View::make('uusmenu.html', array('pizza_data'=>$pizzas,
			'lisuke'=>$lisuke,
			"user"=>BaseController::s_auth()));
	}

	public static function add($res) {
		$params = array();
		$params['lisukename'] = $res->request->post('a_lisukename');
		$params['lisukehinta'] = $res->request->post('a_lisukehinta');
		$lisuke = new Lisuke($params, $res);
		$lisuke->add();
		$res->redirect('/tsoha/menu');
	}
	/*
	 * Tata ei kayteta
	 */
	public static function pizzasdsd($numero) {
		$pizza_data = Pizza::pizza_numero($numero);
		View::make('naytaid.html',
			array('pizza_data'=>$pizza_data,
				"user"=>BaseController::s_auth()));

	}

	public static function add_templ() {
		$data = Lisuke::get();
		View::make('lisaalisuke.html', array('lisukkeet' => $data,
			"user"=>BaseController::s_auth()));
	}

	public static function login_page() {
		View::make('login_page.html', array(
			"user"=>BaseController::s_auth()));
	}
	public static function login($res) {
		$a_username = $res->request->post('a_username');
		$a_password = $res->request->post('a_password');

		$a_username = BaseController::strip_unwanted($a_username);
		$a_username = BaseController::strip_unwanted($a_password);
		

		$attr = array();
		$user = new User($attr);
		$user->populate_user_from_db($a_username);

		if ($user->password == $a_password) {
			$_SESSION["auth"] = $user->username;
		} else {
			View::make('errauth.html', array(
				"user"=>BaseController::s_auth()));
		}

		$res->redirect('/tsoha/');
	}

	public function _is_valid() {
		print "$_SESSION";
		if ($_SESSION) {
			return true;
		} else {
			return false;
		}
	}

	public static function logout($res) {
		session_unset();
		session_destroy();
		$res->redirect('/tsoha/');

	}

};


?>
