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

$router->get('/upload', 'UploadController@index');
$router->get('/upload/{id}', 'UploadController@show');
$router->post('/upload', 'UploadController@store');
$router->post('/upload/{id}/edit', 'UploadController@update');
