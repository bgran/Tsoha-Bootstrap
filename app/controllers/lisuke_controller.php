<?php
class LisukeController extends BaseController {
	public static function dfdfpizzas() {
		$pizzas = array();
		$pizzas = Pizza::get_pizzas_id();
		$lisuke = Lisuke::all();
		View::make('uusmenu.html', array('pizza_data'=>$pizzas,
			'lisuke'=>$lisuke));
	}

	public static function add($res) {
		$a_pizzaname = $res->request->post('a_pizzaname');
		Lisuke::add($res);
		$res->redirect('/tsoha/menu');
	}
	public static function pizzasdsd($numero) {
		$pizza_data = Pizza::pizza_numero($numero);
		View::make('naytaid.html',
			array('pizza_data'=>$pizza_data));

	}

	public static function add_templ() {
		$data = Lisuke::get();
		View::make('lisaalisuke.html', array('lisukkeet' => $data));
	}


};


?>
