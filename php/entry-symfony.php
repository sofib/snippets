<?php

require(__DIR__ . '/../vendor/autoload.php');

use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\HttpKernel\EventListener\RouterListener;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$routes = new RouteCollection();
$routes->add('root', new Route('/', [
    '_controller' => function () {
        $response = new Response();
        $response->setStatusCode(403);
        return $response;
    }]
));
$routes->add('html', new Route('/index.html', [
    '_controller' => function () {
        $response = new Response();
        $response->setContent(file_get_contents(__DIR__ . '/index.html'));
        return $response;
    }]
));
$routes->add('favicon', new Route('/favicon.ico', [
    '_controller' => function () {
        $response = new Response();
        $response->setStatusCode(404);
        return $response;
    }]
));

$routes->add('entities', new Route('/entity', [
    '_controller' => function (Request $request) {
        return new JsonResponse((object)['test' => 2]);
    }]
));
$routes->add('entity', new Route('/entity/{id}', [
    '_controller' => function (Request $request) {

        return new JsonResponse((object)['test' => 1]);
    }]
));

$request = Request::createFromGlobals();

$matcher = new UrlMatcher($routes, new RequestContext());

$dispatcher = new EventDispatcher();
$dispatcher->addSubscriber(new RouterListener($matcher, new RequestStack()));

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$kernel = new HttpKernel($dispatcher, $controllerResolver, new RequestStack(), $argumentResolver);

$response = $kernel->handle($request);
$response->send();

$kernel->terminate($request, $response);