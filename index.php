<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require 'vendor/autoload.php';

$app = new \Slim\App;
// Get container
$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
  $view = new \Slim\Views\Twig('twig', [
    'cache' => FALSE
  ]);
  $view->addExtension(new \Slim\Views\TwigExtension(
    $container['router'],
    $container['request']->getUri()
  ));

  return $view;
};

// Render Twig template in route
$app->get('/', function ($request, $response, $args) {
  return $this->view->render($response, 'home.html', [
    'title' => 'T-rimmer'
  ]);
})->setName('home');


$app->post('/trim', function (Request $request, Response $response) {
  $newResponse = $response->withHeader('Content-type', 'application/json');
  $data = $request->getParsedBody();
  $data['string'] = trim($data['string']);
  $newResponse->getBody()->write(json_encode($data));
  return $newResponse;
});

$app->post('/trim/right', function (Request $request, Response $response) {
  $newResponse = $response->withHeader('Content-type', 'application/json');
  $data = $request->getParsedBody();
  $data['string'] = rtrim($data['string']);
  $newResponse->getBody()->write(json_encode($data));
  return $newResponse;
});

$app->post('/trim/left', function (Request $request, Response $response) {
  $newResponse = $response->withHeader('Content-type', 'application/json');
  $data = $request->getParsedBody();
  $data['string'] = ltrim($data['string']);
  $newResponse->getBody()->write(json_encode($data));
  return $newResponse;
});
$app->run();