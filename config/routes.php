<?php
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
$routes->post('/menu/add', function() use ($routes) {
	PizzaController::add($routes);
});
$routes->get('/menu/:numero', function($numero) use ($routes) {
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

?>
