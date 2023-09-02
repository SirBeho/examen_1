<?php

use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DetalleVentaController;
use App\Http\Controllers\TerceroController;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\VentaController;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('articulo')->group(function () {
    Route::get('/', [ArticuloController::class, 'index']);
    Route::get('/{id}', [ArticuloController::class, 'show']);
    Route::post('/', [ArticuloController::class, 'create']);
    Route::put('/{id}', [ArticuloController::class, 'update']);
    Route::delete('/{id}', [ArticuloController::class, 'destroy']);
});
Route::prefix('categoria')->group(function () {
    Route::get('/', [CategoriaController::class, 'index']);
    Route::get('/{id}', [CategoriaController::class, 'show']);
    Route::post('/', [CategoriaController::class, 'create']);
    Route::put('/{id}', [CategoriaController::class, 'update']);
    Route::delete('/{id}', [CategoriaController::class, 'destroy']);
});

Route::prefix('tercero')->group(function () {
    Route::get('/', [TerceroController::class, 'index']);
    Route::get('/{id}', [TerceroController::class, 'show']);
    Route::post('/', [TerceroController::class, 'create']);
    Route::put('/{id}', [TerceroController::class, 'update']);
    Route::delete('/{id}', [TerceroController::class, 'destroy']);
});
Route::prefix('trabajador')->group(function () {
    Route::get('/', [TrabajadorController::class, 'index']);
    Route::get('/{id}', [TrabajadorController::class, 'show']);
    Route::post('/', [TrabajadorController::class, 'create']);
    Route::put('/{id}', [TrabajadorController::class, 'update']);
    Route::delete('/{id}', [TrabajadorController::class, 'destroy']);
});
Route::prefix('venta')->group(function () {
    Route::get('/', [VentaController::class, 'index']);
    Route::get('/{id}', [VentaController::class, 'show']);
    Route::post('/', [VentaController::class, 'create']);
    Route::put('/{id}', [VentaController::class, 'update']);
    Route::delete('/{id}', [VentaController::class, 'destroy']);
});


Route::prefix('detalle')->group(function () {
  
    Route::get('/', [DetalleVentaController::class, 'create'])->name('detallecrear');
    
});