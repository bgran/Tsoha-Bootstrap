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
$routes->get('/menu/crud_template', function() use ($routes) {
	PizzaController::pizza_crud_template($routes);
});
$routes->post('/menu/crud_hook', function() use ($routes) {
	PizzaController::pizza_crud($routes);
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

/*
 * Orders
 */
$routes->get('/order', function() {
	OrderController::index();
});
$routes->get('/order/place/:userid/:pizzaid', function($userid, $pizzaid) use ($routes) {
	OrderController::place_new_order($userid, $pizzaid, $routes);
});
$routes->post('/order/del', function() {
	OrderController::del_order();
});
$routes->get('/order/checkout_templ', function() {
	OrderController::checkout_templ();
});
$routes->post('/order/checkout', function() use ($routes) {
	OrderController::checkout($routes);
});

$routes->get('/order/tracking', function() {
	OrderController::tracking();
});
$routes->get('/order/cancel/:id', function($id) use ($routes) {
	OrderController::cancel($id, $routes);
});
?>
