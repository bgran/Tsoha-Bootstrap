<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

  //$routes->get('/tietokantayhteys', function () {
	//print "AKALAAA";
	//DB::test_connection();
 // });

  $routes->get('/', function() {
    PizzaController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/tietokantayhteys', function () {
        DB::test_connection();
  });

	//$routes->get('/hello/{msg}', function (Request $request, Response $response, $args) {

	$routes->get('/hello/kala', function () use ($routes) {
		//print "KLFLLFLFL : " . $id;
		$routes->redirect("/index.php");
		//print "haukikala";
    		//$name = $request->getAttribute('name');
    		//$response->getBody()->write("Hello, $name");

   		 //return $response;
	});


$routes->get('/kkamyja/:kalavar', function () use ($routes) {
	$kalavar = $routes->request->get('kalavar');
	print $kalavar . " saatoa ";
    echo "Hello, " . $kalavar;
});
$routes->post('/haukikala/new', function() use ($routes) {
	echo "Hello world<br><br>";
	$kala = $routes->request->post('kalavar');
	print "jou: " . $kala;
});

$routes->get('/ticket/{id}', function (Request $request, Response $response, $args) {
    $ticket_id = (int)$args['id'];
	print "kaala: " . $ticket_id . "<br>";
});

$routes->post('/ticket/new', function (Request $request, Response $response) {
	print "tama antaa uuden postin settiin ";

});

$routes->post('/hamyja', function (Request $request, Response $response) {
	print "HAMYJA";
});

$routes->get('/menu', function () use ($routes) {
	PizzaController::menu();
});
$routes->get('/koosta', function () use ($routes) {
	PizzaController::koosta();
});
$routes->post('/op_uusipizza', function () use ($routes) {
	PizzaController::op_uusipizza($routes);
});
$routes->get('/uusilisuke', function () use ($routes) {
	PizzaController::uusilisuke($routes);
});
$routes->post('/op_uusilisuke', function () use ($routes) {
        PizzaController::op_uusilisuke($routes);
});



//print "HJAIKDDLDLDL";
?>
