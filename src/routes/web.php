<?php

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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('/login', 'UserController@login');

$router->group(['middleware' => 'auth'], function () use ($router) {
	$router->post('/transaction', 'TransactionController@transaction');
	$router->get('/transaction/{id}', 'TransactionController@view');
	$router->delete('/transaction/{id}', 'TransactionController@delete');


	$router->post('person', 'PersonController@store');
	$router->get('person/{id}', 'PersonController@view');
	$router->get('people', 'PersonController@list');
	$router->put('person/{id}', 'PersonController@update');
	$router->delete('person/{id}', 'PersonController@delete');
});
