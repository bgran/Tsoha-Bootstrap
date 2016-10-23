<?php
class OrderController extends BaseController {


	public static function index() {
		$orders = Order::get_id();
		$objs = Orders::orders_report();
		View::make('order/index.html', array(
			"orders" => $orders,
			'objs' => $objs));
	}
	public static function place_new_order($userid, $pizzaid, $res) {
		$obj = Order::get_new($pizzaid);
		$obj->add();
		$res->redirect('/tsoha/menu');
	}
	public static function del_order() {
		Orders::del();
		Redirect::to("/menu");
	}

	public static function checkout_templ() {
		$objs = Orders::orders_report();
		View::make('order/checkout_templ.html', array(
			"objs" => $objs));

	}

	public static function checkout($res) {
		$name = $res->request->post('u_name');
		$address = $res->request->post('u_address');
		$obj = Checkout::new_checkout($name, $address);
		//$errs = $obj->errors();
		$errs = array();

		if (empty($name)) {
			$errs[] = "Nimi-kentta on tyhja";
		}
		if (strlen($name) > 49) {
			$errs[] = "Nimi-kentta liian pitka";
		}
		if (empty($address)) {
			$errs[] = "Osoite-kentta on tyhja";
		}
		if (strlen($address) > 254) {
			$errs[] = "Osoite-kentta on liian pitka";
		}
		
		if (!empty($errs)) {
			View::make('order/error.html', array(
				'errors' => $errs));
			exit();
		}
		
		$obj->save();
		Redirect::to('/');
	}

	public static function tracking() {
		$user = OrderController::s_auth();
		if (!$user->is_admin) {
			View::make('errauth.html');
			exit();
		}
		$vals = Orders::all_orders_report();
		View::make('order/tracking.html',  array(
			'vals' => $vals));

	}
	public static function cancel($id, $res) {
		$user = OrderController::s_auth();
		if (!$user->is_admin) {
			View::make('errauth.html');
			exit();
		}

		Orders::cancel($id);
		$res->redirect('/tsoha/order/tracking');
		//Redirect::to('/order/tracking');
	}

};


?>
