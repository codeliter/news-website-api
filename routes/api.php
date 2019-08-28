<?php

use Laravel\Lumen\Routing\Router;

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

$router->group(['prefix' => '/api/v1'], function (Router $router) {
    $router->group(['prefix' => 'oauth', 'namespace' => 'Oauth'], function () use ($router) {
        $router->post('/token', 'OauthController@generateToken');
        $router->get('/token/validate', 'OauthController@checkToken');
    });

    $router->group(['prefix' => 'news', 'namespace' => 'News', 'middleware' => 'user.auth'], function () use ($router) {
        $router->post('/add', 'NewsController@add');
        $router->delete('/delete/{id}', 'NewsController@delete');
    });

    $router->group(['prefix' => 'news', 'namespace' => 'News'], function () use ($router) {
        $router->get('/list', 'NewsController@list');
        $router->get('/search', 'NewsController@search');
        $router->get('/details/{id}', 'NewsController@details');
    });
});