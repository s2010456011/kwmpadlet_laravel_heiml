<?php

use App\Http\Controllers\EntrieController;
use App\Http\Controllers\PadletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Controller Methoden definieren
Route::get('/', [PadletController::class, 'index']);
Route::get('/padlets', [PadletController::class, 'index']);
Route::get('/padlets/{id}', [PadletController::class, 'findByID']);
Route::get('/padlets/checkid/{id}', [PadletController::class, 'checkByID']);
Route::get('/padlets/public/{is_public}', [PadletController::class, 'getPublic']);
Route::get('/padlets/search/{term}', [PadletController::class, 'findBySearchTerm']);

Route::post('/padlets', [PadletController::class, 'save']);
Route::put('/padlets/{id}', [PadletController::class, 'update']);
Route::delete('/padlets/{id}', [PadletController::class, 'delete']);



Route::get('/entries', [EntrieController::class, 'index']);
Route::get('/entries/{id}', [PadletController::class, 'findByID']);
Route::get('/entries/checkid/{id}', [PadletController::class, 'checkByID']);

Route::post('/entries', [EntrieController::class, 'save']);
Route::put('/entries/{id}', [EntrieController::class, 'update']);
Route::delete('/entries/{id}', [EntrieController::class, 'delete']);










