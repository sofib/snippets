<?php

namespace SofiB\Api;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class Router implements  RequestHandlerInterface
{
    private static $handlers = [];

    public static function registerHandler(string $routePattern, callable $handler, $method = 'GET')
    {
        unset(static::$handlers[$routePattern][$method]);
        static::$handlers[$routePattern][$method] = $handler;
    }
    
    public function handle (ServerRequestInterface $request) : ResponseInterface {
        #parse_str($request->getUri()->getQuery(), $qs);
        $handler = null;
        foreach(static::$handlers as $routePattern => $configuredHandler) {
            if (preg_match($routePattern, $request->getUri()->getPath())) {
                $method = strtoupper($request->getMethod());
                if (isset($configuredHandler[$method])) {
                    $handler = $configuredHandler[$method];
                    break;
                }
            }
        }

        if ($handler === null) {
            return (new Response())->withStatus(404);
        }
        
        $response = call_user_func($handler, $request);
        return $response;
    }
}
