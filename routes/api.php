<?php

use App\Http\Controllers\Api\AuthController;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post("/login", [AuthController::class, 'login']);
Route::middleware("auth:sanctum")->post("/logout", [AuthController::class, 'logout']);
Route::post("/register", [AuthController::class, 'register']);
Route::get('tickets', function () {
    return Ticket::all();
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
