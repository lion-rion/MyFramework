<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';
require_once '../Http/Middleware/RouteMiddleware.php';
require_once '../Http/Middleware/FilterMiddleware.php';

use Http\Middleware\FilterMiddleware;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Relay\Relay;
use Http\Middleware\RouteMiddleware;

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
$serverRequest = $creator->fromGlobals();

$queue = [
    new RouteMiddleware,
    new FilterMiddleware,
];

$relay = new Relay($queue);
$response = $relay->handle($serverRequest);
