<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\GameSessionController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/register', [UserController::class, 'register']);


// Rutas protegidas solo para ADMIN o USER

    Route::middleware(['auth:sanctum'])->group(function () {

    //rutas usuarios
    Route::get('/usuarios', [UserController::class, 'index']);
    Route::post('/usuarios', [UserController::class, 'store']);
    Route::get('/usuarios/{id}', [UserController::class, 'show']);
    Route::put('/usuarios/{id}', [UserController::class, 'update']);
    Route::delete('/usuarios/{id}', [UserController::class, 'destroy']);
    Route::get('/usuarios/puntos/{id}', [UserController::class, 'getUserPoints']);
    Route::put('/usuarios/puntos/{id}', [UserController::class, 'actualizarPuntos']);

    //rutas juegos
    Route::post('/juegos', [GameController::class, 'store']);
    Route::put('/juegos/{id}', [GameController::class, 'update']);
    Route::delete('/juegos/{id}', [GameController::class, 'destroy']);
    Route::get('/juegos/{id}', [GameController::class, 'show']);

    //rutas sesiones
    Route::get('/sesiones',[GameSessionController::class, 'index']);
    Route::get('/sesiones/{id}', [GameSessionController::class, 'show']);
    Route::post('/sesiones',[GameSessionController::class, 'store']);
    Route::put('/sesiones/{id}', [GameSessionController::class, 'update']);
    Route::delete('/sesiones/{id}', [GameSessionController::class, 'destroy']);

    Route::get('/mis-sesiones', [GameSessionController::class, 'mySessions']);
});
    //Rutas sin roles
    Route::get('/juegos', [GameController::class, 'index']);


    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    // Redirige al login del frontend (Angular)
    return redirect(config('app.frontend_url') . '/login?verified=1');
    })->middleware(['auth:sanctum', 'signed'])->name('verification.verify');



