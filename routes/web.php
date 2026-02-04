<?php

use Illuminate\Routing\Router;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PathfinderController;

$api = app('Illuminate\Routing\Router');

$api->group([], function ($api) {

    $api->group([
        'prefix' => '/home',
        'controller' => HomeController::class,
    ], function (Router $api) {
        $api->get('/', 'index')->name('home.index');
    });

    $api->group([
        'prefix' => '/pathfinder',
        'controller' => PathfinderController::class,
    ], function (Router $api) {
        $api->get('/', 'index')->name('pathfinder.index');
    });
});
