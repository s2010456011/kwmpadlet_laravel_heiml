<?php

use App\Http\Controllers\EntrieController;
use App\Models\Padlet;
use App\Models\Entrie;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PadletController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//default Route --> home, ruft die index methode im PadletController auf
Route::get('/', [PadletController::class, 'index']);
//padlet --> gibt Übersicht über alle Padlets
Route::get('/padlets', [PadletController::class, 'index']);
//entries --> gibt Übersicht über alle Entries
Route::get('/entries', [EntrieController::class, 'index']);
//detail padlet --> gibt Übersicht über die Details von Padlets
Route::get('/padlets/{padlet}', [PadletController::class, 'show']);


/*Route::get('/', function () {
    $padlets = Padlet::all();
    $entries = Entrie::all();
    return view('welcome', compact('padlets', 'entries'));
});*/

