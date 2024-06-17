<?php

use App\Http\Controllers\CreateController;
use App\Http\Controllers\FakerController;
use App\Http\Controllers\MainController;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/', [MainController::class, 'index'])->name('index')->middleware('auth');

Route::get('/faker', [FakerController::class, 'generate']);

Route::post('/tasks', [CreateController::class, 'create'])->name('tasks.store');

Route::get('/task', [CreateController::class, 'show']);

Route::get('/filter', [CreateController::class, 'filter']);

Route::get('/task/{id}', [CreateController::class, 'detal']);

Route::get('/delete/{id}', [CreateController::class, 'delete']);


Route::post('/update/{id}', [CreateController::class, 'update']);
