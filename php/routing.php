<?php

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response;
use SofiB\Api\Entity\Controller;
use SofiB\Api\Router;

$dependencyProvider = require(__DIR__ . '/dependencies.php');

$controllerFactory = function (string $controllerClass) use ($dependencyProvider) {
    return new $controllerClass($dependencyProvider);
};

$controller = $controllerFactory(Controller::class);

Router::registerHandler('/\/entity\/?$/', [$controller, 'create'], 'POST');
Router::registerHandler('/\/entity\/\d+$/', [$controller, 'update'], 'POST');

return new Router();