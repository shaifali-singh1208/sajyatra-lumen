<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Http\Controllers\Api\BusBookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Test route

$router->group(['middleware' => 'auth:sanctum'], function () use ($router) {
    // $router->get('/user', function (Request $request) {
    //     return $request->user();
    // });

    $router->get('/test', 'BusBookingController@test');
});

// Bus routes group
// $router->group(['prefix' => 'bus'], function () use ($router) {
//     $router->get('/test', 'BusBookingController@test');

//     $router->post('bus-search', 'BusBookingController@BusSearchList');
//     $router->post('bus-seat-layout', 'BusBookingController@BusSeatLayout');
//     $router->post('bus-boarding-point', 'BusBookingController@BusBoradingPoint');
//     $router->post('bus-seat-block', 'BusBookingController@BusSeatBlock');
//     $router->post('bus-seat-book', 'BusBookingController@BusSeatBook');
//     $router->post('bus-seat-cancel', 'BusBookingController@BusSeatCancel');
// });