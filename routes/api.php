<?php

use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\ItensPedidosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('produtos')->group(function () {
    Route::get('/', [ProdutosController::class, 'index']);
    Route::post('/', [ProdutosController::class, 'store']);
    Route::get('/{id}', [ProdutosController::class, 'show']);
    Route::put('/{id}', [ProdutosController::class, 'update']);
    Route::delete('/{id}', [ProdutosController::class, 'destroy']);
});

Route::prefix('pedidos')->group(function () {
    Route::get('/', [ProdutosController::class, 'index']);
    Route::post('/', [ProdutosController::class, 'store']);
    Route::get('/{id}', [ProdutosController::class, 'show']);
    Route::put('/{id}', [ProdutosController::class, 'update']);
    Route::delete('/{id}', [ProdutosController::class, 'destroy']);
});

Route::prefix('itensPedidos')->group(function () {
    Route::get('/', [ItensPedidosController::class, 'index']);
    Route::post('/', [ItensPedidosController::class, 'store']);
    Route::get('/{id}', [ItensPedidosController::class, 'show']);
    Route::put('/{id}', [ItensPedidosController::class, 'update']);
    Route::delete('/{id}', [ItensPedidosController::class, 'destroy']);
});