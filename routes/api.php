<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::get('/auth/google/url', [AuthController::class, 'getGoogleAuthUrl']);


Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);


Route::middleware('auth:sanctum')->group(function () {

    // Retorna os dados do usuário logado (para o Front saber quem é)
    Route::get('/me', function (Request $request) {
        return $request->user();
    });

    // Logout (revoga o token)
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out']);
    });

    Route::post('/auth/complete-registration', [AuthController::class, 'completeRegistration']);


    Route::get('/users', [UserController::class, 'index']);
});
