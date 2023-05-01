<?php declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Relay\Relay;

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
$serverRequest = $creator->fromGlobals();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/users', "Http\Middleware\FilterMiddleware");
});

$queue = [
    new Http\Middleware\RouteMiddleware($dispatcher),
    new Http\Middleware\Dispatcher(),
];

$relay = new Relay($queue);
$response = $relay->handle($serverRequest);