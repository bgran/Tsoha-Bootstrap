<?php
class LisukeController extends BaseController {

	public static function add($res) {
		$user = LisukeController::s_auth();
		if (!$user->is_admin) {
			View::make('errauth.html');
			exit();
		}
		$params = array();
		$lisuri = BaseController::strip_unwanted(
			$res->request->post('a_lisukename'));
		$lishinta = BaseController::strip_unwanted(
			$res->request->post('a_lisukehinta'));

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
		$data = Lisuke::get();
		View::make('lisaalisuke.html', array(
			'lisukkeet' => $data));
	}


};


?>
