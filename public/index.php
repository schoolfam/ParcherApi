<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../src/config/db.php';

$config = [
    'settings' =>[
        'displayErrorDetails' => true,
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
        ],
    ],
];

$app = new \Slim\App;
$app->get('/', function(Request $request, Response $response, array $args){
    echo '{"message":{"body":"path not found"}}';
    return $response;
});

// other routes

require '../src/routes/routes.php';

$app->run();