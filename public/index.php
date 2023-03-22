<?php

require __DIR__ . '/../vendor/autoload.php';
require_once '../Http/Middleware/RouteMiddleware.php';

use Http\Middleware\RouteMiddleware;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Relay\Relay;

$psr17Factory = new Psr17Factory();
$creator = new ServerRequestCreator($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
$serverRequest = $creator->fromGlobals();

//$queue = [
//    new RouteMiddleware,
//];

$queue[] = new RouteMiddleware();

$relay = new Relay($queue);
$response = $relay->handle($serverRequest);
