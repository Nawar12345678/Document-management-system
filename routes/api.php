<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CommentController;



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
Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout')->middleware('auth.api');
    Route::post('refresh', 'refresh')->middleware('auth.api');
});
Route::middleware('auth:api')->group(function() {
    Route::apiResource('documents', DocumentController::class);
});
Route::middleware('auth:api')->group(function() {
    Route::post('documents/{document}/comments', [CommentController::class, 'storeDocumentComment']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('comments', [CommentController::class, 'store']);
    Route::delete('comments/{id}', [CommentController::class, 'destroy']);
});
