<?php
class LisukeController extends BaseController {

	public static function add($res) {
		$params = array();
		$params['lisukename'] = $res->request->post('a_lisukename');
		$params['lisukehinta'] = $res->request->post('a_lisukehinta');
		$lisuke = new Lisuke($params, $res);
		$lisuke->add();
		//$res->redirect('/tsoha/menu');

		$pizzas = Pizza::get_pizzas_id();
		$lisuke = Lisuke::all();
		View::make('uusmenu.html', array(
			"pizza_data" => $pizzas,
			"lisuke" => $lisuke,
			"user"=>LisukeController::s_auth()));
	}

	public static function add_templ() {
		$data = Lisuke::get();
		View::make('lisaalisuke.html', array(
			'lisukkeet' => $data,
			"user"=>LisukeController::s_auth()));
	}


};


?>
