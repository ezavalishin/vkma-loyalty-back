<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/colors', 'ColorController@index');
$router->get('/categories', 'CategoryController@index');

$router->group([
    'prefix' => 'me'
], static function () use ($router) {
    $router->get('/', 'UserController@me');
    $router->get('/cards', 'UserController@indexCards');
});

$router->group([
    'prefix' => 'groups'
], static function () use ($router) {
    $router->post('/', 'GroupController@store');
    $router->post('/{id:[0-9]+}/goals', 'GroupController@storeGoal');
    $router->get('/{id:[0-9]+}/goals', 'GroupController@indexGoals');
    $router->get('/{id:[0-9]+}/cards', 'GroupController@indexCardsForUser');
});

$router->group([
    'prefix' => 'cards'
], static function () use ($router) {
    $router->post('/{id:[0-9]+}/checkin', 'CardController@checkin');
});
