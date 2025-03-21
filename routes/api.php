<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('customers', [CustomerController::class, 'index']);
Route::post('customers', [CustomerController::class, 'store']); // New route for creating customers & orders
Route::post('get-connection-id', [AuthController::class, 'get_connection_id']);
Route::post('request-otp', [AuthController::class, 'request_otp']);
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register_customer']);
