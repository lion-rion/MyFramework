<?php

namespace Http\Middleware;

use FastRoute;
use FastRoute\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteMiddleware implements MiddlewareInterface
{
    private Dispatcher $dispatcher;

    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $httpMethod = $request->getMethod();
        $uri = $request->getUri()->getPath();

        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                // 404 Not Found
                return $handler->handle($request->withAttribute('error', '404 Not Found'));
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                // 405 Method Not Allowed
                return $handler->handle($request->withAttribute('error', '405 Method Not Allowed'));
            case FastRoute\Dispatcher::FOUND:
                $middlewareClass = $routeInfo[1];
                $vars = $routeInfo[2];
                // Instantiate the middleware class and call it as a handler
                $middleware = new $middlewareClass();
                return $middleware->process($request, $handler);
        }

        return $handler->handle($request);
    }
}