<?php
class LisukeController extends BaseController {

	public static function add($res) {
		$user = LisukeController::s_auth();
		if (!$user->is_admin) {
			View::make('errauth.html');
			exit();
		}

		$a_lisukename = $res->request->post('a_lisukename');
		$a_lisukehinta = intval($res->request->post('a_lisukehinta'));

		$err = array();

		if ($a_lisukename == null) {
			$err[] = "Lisukkeen nimi on tyhja";
		}
		if (strlen($a_lisukename) > 254) {
			$err[] = "Lisukkeen nimen pituus liian pitka";
		}
		if ($a_lisukehinta < 1) {
			$err[] = "Lisukkeen hinta on alle 1";
		}

		if (!empty($err)) {
			View::make('lisuke_err.html', array(
				'errors' => $err));
			exit();
		}

		$params = array();
		$lisuri = BaseController::strip_unwanted(
			$a_lisukename);
		$lishinta = BaseController::strip_unwanted(
			$a_lisukehinta);

		$lisuke = new Lisuke(array());
		$lisuke->name = $lisuri;
		$lisuke->price = $lishinta;

		$lisuke->ng_add();
		$res->redirect("/tsoha");
	}

	public static function add_templ() {
		$user = LisukeController::s_auth();
		if (!$user->is_admin) {
			View::make('errauth.html');
			exit();
		}
		$data = Lisuke::get();
		View::make('lisaalisuke.html', array(
			'lisukkeet' => $data));
	}


};


?>
