<?php

 /* $g_user = new User(array());
  $bauth = $g_user->is_valid();
  if ($bauth ) {
	print "BAUTH TRUE";
  } else {
	print "BAUTH FALSE";
  }*/


  $routes->get('/', function() {
    PizzaController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/tietokantayhteys', function () {
        DB::test_connection();
  });

//$routes->get('/koosta', function () use ($routes) {
//	tPizzaController::koosta();
//});
//$routes->post('/op_uusipizza', function () use ($routes) {
//	tPizzaController::op_uusipizza($routes);
//});
//$routes->get('/uusilisuke', function () use ($routes) {
//	tPizzaController::uusilisuke($routes);
//});
//$routes->post('/op_uusilisuke', function () use ($routes) {
//        tPizzaController::op_uusilisuke($routes);
//});
//$routes->get('/uusmenu', function () use ($routes) {
//	PizzaController::pizzas();
//});


/*
 * Pizzoja
 */
$routes->get('/menu', function() use ($routes) {
	PizzaController::pizzas();
});
$routes->get('/menu/add_templ', function() {
	PizzaController::add_templ();
});
$routes->get('/menu/add_static_templ', function() {
	PizzaController::add_static_templ();
});
$routes->post('/menu/add', function() use ($routes) {
	PizzaController::add($routes);
});
$routes->post('/menu/add_anon', function() use ($routes) {
	PizzaController::add_anon($routes);
});
$routes->post('/menu/del_anon', function() use ($routes) {
	PizzaController::del_anon($routes);
});
$routes->get('/menu/tietty/:numero', function($numero) use ($routes) {
        PizzaController::get_id($numero, $routes);
});


/*
 * Lisukkeita
 */
$routes->get('/lisuke/add_templ', function () {
	LisukeController::add_templ();
});
$routes->post('/lisuke/add', function() use ($routes) {
	LisukeController::add($routes);
});


/*
 * Login stuff.
 */
$routes->get('/login', function () {
	LoginController::login_page();
});
$routes->post('/login/doit', function() use ($routes) {
	LoginController::login($routes);
});
$routes->get('/login/logout', function() use ($routes) {
	LoginController::logout($routes);
});

?>
