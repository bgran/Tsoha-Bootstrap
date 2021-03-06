<?php
class LoginController extends BaseController {
	public static function add_templ() {
		$data = Lisuke::get();
		View::make('lisaalisuke.html', array('lisukkeet' => $data));
	}

	public static function login_page() {
		View::make('login_page.html');
	}
	public static function login($res) {
		$a_username = $res->request->post('a_username');
		$a_password = $res->request->post('a_password');
		$err = array();

		if ($a_username == null) {
			$err[] = "Tyhja kayttajanimi";
		}
		if (strlen($a_username) > 15) {
			$err[] = "Liian pitka kayttajanimi";
		}
		if ($a_password == null) {
			$err[] = "Virheellinen (tyhja) salasana";
		}
		if (!empty($err)) {
			View::make('pizza_err.html', array(
				'errors' => $err));
			exit();
		}
	
		$a_username = BaseController::strip_unwanted($a_username);
		$a_password = BaseController::strip_unwanted($a_password);
		

		$attr = array();
		$user = new User($attr);
		$user->populate_user_from_db($a_username);
		if ($user->password == $a_password) {
			$_SESSION["auth"] = $user->username;
		} else {
			View::make('errauth.html');
		}

		$res->redirect('/tsoha/');
	}

	public function _is_valid() {
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
