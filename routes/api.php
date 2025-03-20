<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('customers', [CustomerController::class, 'index']);
Route::post('customers', [CustomerController::class, 'store']); // New route for creating customers & orders
