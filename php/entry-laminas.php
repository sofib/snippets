<?php

require(__DIR__ . '/../vendor/autoload.php');

use Laminas\Diactoros\Response;
use Laminas\Diactoros\ServerRequest;
use Laminas\Stratigility\Middleware\ErrorResponseGenerator;

use Laminas\HttpHandlerRunner\RequestHandlerRunner;
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

use Laminas\Diactoros\ServerRequestFactory;


$serverRequestFactory = [ServerRequestFactory::class, 'fromGlobals'];
$emitter = new SapiEmitter();

$errorResponseGenerator = function (Throwable $e) {
    $generator = new ErrorResponseGenerator();
    return $generator($e, new ServerRequest(), new Response());
};

$handler = require(__DIR__ . '/../config/routing.php');

$runner = new RequestHandlerRunner(
    $handler,
    $emitter,
    $serverRequestFactory,
    $errorResponseGenerator
);

$runner->run();
