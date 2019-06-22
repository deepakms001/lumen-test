<?php

$router->get('/', function () use ($router) {
    return response()->json(['api' => 'Agriculture management API', 'version' => "1.0.0"]);
});



$router->group(['prefix' => 'auth'], function() use ($router) {
    $router->post('/login', 'AuthController@authenticate');
});

$router->group(['middleware' => ['jwt.auth']], function() use ($router) {

    //Customer APIs
    $router->get('crops', 'CropsController@getAllCrops');
    $router->get('tractors', 'TractorsController@getAllTractors');
    $router->get('fields', 'FieldsController@getAllFields');

    $router->post('fields', 'FieldsController@addField');
    $router->post('fields/update', 'FieldsController@editField');
    $router->get('fields/{id}', 'FieldsController@getFieldById');
    $router->delete('fields/{id}', 'FieldsController@deleteField');

    $router->get('process-fields', 'ProcessFieldsController@getAllProcessFields');
    $router->post('process-fields', 'ProcessFieldsController@addProcessField');
    $router->post('process-fields/update', 'ProcessFieldsController@editProcessField');

    //Admin only APIs
    $router->group(['middleware' => ['isAdmin']], function() use ($router) {
        $router->get('users', 'UsersController@getAllUsers');

        $router->post('crops', 'CropsController@addCrop');
        $router->post('crops/update', 'CropsController@editCrop');
        $router->get('crops/{id}', 'CropsController@getCropById');
        $router->delete('crops/{id}', 'CropsController@deleteCrop');
        $router->post('tractors', 'TractorsController@addTractor');
        $router->post('tractors/update', 'TractorsController@editTractor');
        $router->get('tractors/{id}', 'TractorsController@getTractorById');
        $router->delete('tractors/{id}', 'TractorsController@deleteTractor');

        $router->post('reports', 'ReportsController@generateReport');
    });

    //Admin and Supervisor APIs
    $router->group(['middleware' => ['isSupervisor']], function() use ($router) {
        $router->put('process-fields/{id}/{status}', 'ProcessFieldsController@changeProcessFieldStatus');
        $router->get('process-fields/{id}', 'ProcessFieldsController@getProcessFieldById');
        $router->delete('process-fields/{id}', 'ProcessFieldsController@deleteProcessField');
    });
});


